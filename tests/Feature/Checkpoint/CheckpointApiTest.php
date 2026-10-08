<?php

namespace Tests\Feature\Checkpoint;

use App\Mail\CommissionInvite;
use App\Models\Event;
use App\Models\EventCommissionMember;
use App\Models\Location;
use App\Models\Place;
use App\Models\Sight;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CheckpointApiTest extends TestCase
{
    use RefreshDatabase;

    private function user(bool $access = true, bool $publish = false): User
    {
        return User::factory()->create([
            'checkpoint_access' => $access,
            'checkpoint_publish_override' => $publish,
        ]);
    }

    private function event(User $owner, bool $enabled = true, array $place = []): Event
    {
        $event = Event::create([
            'name' => 'Кубок города',
            'date_start' => '2026-12-01 10:00:00',
            'date_end' => '2026-12-01 18:00:00',
            'user_id' => $owner->id,
            'checkpoint_enabled' => $enabled,
        ]);
        if ($place) {
            $location = Location::create(['name' => $place['city']]);
            $timezoneId = DB::table('timezones')->insertGetId(['name' => 'Москва', 'UTC' => '+03:00']);
            $sightId = null;
            if (!empty($place['venue'])) {
                $sightId = Sight::create([
                    'name' => $place['venue'],
                    'address' => $place['address'] ?? 'Адрес',
                    'user_id' => $owner->id,
                ])->id;
            }
            Place::create([
                'event_id' => $event->id,
                'location_id' => $location->id,
                'sight_id' => $sightId,
                'latitude' => 55.75,
                'longitude' => 37.61,
                'address' => $place['address'] ?? null,
                'timezone_id' => $timezoneId,
            ]);
        }
        return $event;
    }

    public function test_competitions_require_access_and_hide_foreign_events(): void
    {
        $owner = $this->user();
        $other = $this->user();
        $stranger = $this->user(false);
        $event = $this->event($owner, true, ['city' => 'Казань', 'venue' => 'Лыжная база', 'address' => 'База, 1']);
        $this->event($owner, false);
        $this->event($other, true);

        $this->actingAs($stranger, 'sanctum')->getJson('/api/checkpoint/competitions')->assertStatus(403);

        $list = $this->actingAs($owner, 'sanctum')->getJson('/api/checkpoint/competitions');
        $list->assertOk()->assertJsonCount(1)->assertJsonPath('0.id', $event->id)->assertJsonPath('0.city', 'Казань')->assertJsonPath('0.publish_allowed', false);

        $this->actingAs($other, 'sanctum')->getJson('/api/checkpoint/competitions/'.$event->id)->assertStatus(403);
        $this->actingAs($owner, 'sanctum')->getJson('/api/checkpoint/competitions/'.$this->event($owner, false)->id)->assertStatus(404);
    }

    public function test_competition_details_match_contract_and_place_name(): void
    {
        $owner = $this->user(true, true);
        $event = $this->event($owner, true, ['city' => 'Казань', 'venue' => 'Лыжная база', 'address' => 'База, 1']);
        $group = $event->checkpointGroups()->create(['name' => 'М18', 'sort_order' => 1]);
        $event->checkpointParticipants()->create([
            'last_name' => 'Иванов', 'first_name' => 'Иван', 'group_id' => $group->id, 'start_number' => '12',
        ]);

        $this->actingAs($owner, 'sanctum')->getJson('/api/checkpoint/competitions/'.$event->id)
            ->assertOk()
            ->assertJsonPath('competition.place_name', 'Лыжная база')
            ->assertJsonPath('competition.city', 'Казань')
            ->assertJsonPath('competition.publish_allowed', true)
            ->assertJsonPath('groups.0.name', 'М18')
            ->assertJsonPath('groups.0.participants_count', 1)
            ->assertJsonPath('participants.0.group_name', 'М18')
            ->assertJsonPath('participants.0.start_number', '12');

        $export = $this->actingAs($owner, 'sanctum')->getJson('/api/checkpoint/competitions/'.$event->id.'/export');
        $export->assertOk()
            ->assertJsonPath('version', 1)
            ->assertJsonPath('competition.id', $event->id)
            ->assertJsonPath('groups.0.name', 'М18')
            ->assertJsonPath('participants.0.start_number', '12');
        $body = $export->json();
        $this->assertArrayNotHasKey('members', $body);
        $this->assertArrayNotHasKey('can_manage_access', $body['competition']);
        $this->assertArrayNotHasKey('participants_count', $body['groups'][0]);
        $this->assertNotEmpty($body['exported_at']);
    }

    public function test_start_number_is_unique_inside_event(): void
    {
        $owner = $this->user();
        $event = $this->event($owner);
        $payload = ['last_name' => 'Иванов', 'first_name' => 'Иван', 'start_number' => '12'];
        $this->actingAs($owner, 'sanctum')->postJson('/api/checkpoint/events/'.$event->id.'/participants', $payload)->assertCreated();
        $this->actingAs($owner, 'sanctum')->postJson('/api/checkpoint/events/'.$event->id.'/participants', $payload)->assertStatus(422);
    }

    public function test_participant_update_keeps_its_start_number(): void
    {
        $owner = $this->user();
        $event = $this->event($owner);
        $created = $this->actingAs($owner, 'sanctum')->postJson('/api/checkpoint/events/'.$event->id.'/participants', [
            'last_name' => 'Иванов', 'first_name' => 'Иван', 'start_number' => '12',
        ])->assertCreated();

        $this->actingAs($owner, 'sanctum')->patchJson('/api/checkpoint/events/'.$event->id.'/participants/'.$created->json('id'), [
            'last_name' => 'Петров', 'first_name' => 'Иван', 'start_number' => '12', 'middle_name' => '', 'rfid' => '',
        ])->assertOk()->assertJsonPath('last_name', 'Петров')->assertJsonPath('start_number', '12');
    }

    public function test_excel_import_upserts_by_start_number_and_reports_row_errors(): void
    {
        $owner = $this->user();
        $event = $this->event($owner);
        $event->checkpointParticipants()->create(['last_name' => 'Старый', 'first_name' => 'Иван', 'start_number' => '12']);
        $csv = "last_name,first_name,middle_name,birth_date,city,group_name,start_number,rfid\nПетров,Пётр,,,Казань,Ж18,12,\n";
        $upload = fn () => UploadedFile::fake()->createWithContent('list.csv', $csv);

        $this->actingAs($owner, 'sanctum')->post('/api/checkpoint/events/'.$event->id.'/imports/preview', ['file' => $upload()])->assertOk()->assertJsonPath('rows.0.group_name', 'Ж18');
        $this->actingAs($owner, 'sanctum')->post('/api/checkpoint/events/'.$event->id.'/imports', ['file' => $upload()])->assertOk()->assertJsonPath('imported', 1);
        $this->assertDatabaseHas('checkpoint_participants', ['event_id' => $event->id, 'start_number' => '12', 'last_name' => 'Петров']);
        $this->assertDatabaseHas('checkpoint_groups', ['event_id' => $event->id, 'name' => 'Ж18']);
        $this->assertSame(1, $event->checkpointParticipants()->count());

        $duplicate = UploadedFile::fake()->createWithContent('bad.csv', "last_name,first_name,start_number\nИванов,Иван,1\nПетров,Пётр,1\n");
        $this->actingAs($owner, 'sanctum')->post('/api/checkpoint/events/'.$event->id.'/imports/preview', ['file' => $duplicate])
            ->assertStatus(422)
            ->assertJsonPath('errors.0.row', 3);
    }

    public function test_russian_template_headers_import_without_mapping(): void
    {
        $owner = $this->user();
        $event = $this->event($owner);
        $csv = "Фамилия,Имя,Отчество,Дата рождения,Город,Группа,Стартовый номер,RFID\nИванов,Иван,Петрович,1998-05-12,Казань,М18,12,E200001122334455\n";
        $this->actingAs($owner, 'sanctum')->post('/api/checkpoint/events/'.$event->id.'/imports/preview', [
            'file' => UploadedFile::fake()->createWithContent('list.csv', $csv),
        ])->assertOk()->assertJsonPath('mapping_required', false)->assertJsonPath('rows.0.last_name', 'Иванов');
    }

    public function test_foreign_headers_stay_unimported_until_mapped(): void
    {
        $owner = $this->user();
        $event = $this->event($owner);
        $csv = "Фамилия,Имя,Номер,Группа\nИванов,Иван,7,Ж21\n";
        $upload = fn () => UploadedFile::fake()->createWithContent('custom.csv', $csv);

        $this->actingAs($owner, 'sanctum')->post('/api/checkpoint/events/'.$event->id.'/imports/preview', ['file' => $upload()])
            ->assertOk()
            ->assertJsonPath('mapping_required', true);
        $this->actingAs($owner, 'sanctum')->post('/api/checkpoint/events/'.$event->id.'/imports', ['file' => $upload()])
            ->assertStatus(422)
            ->assertJsonPath('mapping_required', true);
        $this->assertSame(0, $event->checkpointParticipants()->count());

        $mapping = ['Фамилия' => 'last_name', 'Имя' => 'first_name', 'Номер' => 'start_number', 'Группа' => 'group_name'];
        $this->actingAs($owner, 'sanctum')->post('/api/checkpoint/events/'.$event->id.'/imports', ['file' => $upload(), 'mapping' => $mapping])
            ->assertOk()
            ->assertJsonPath('imported', 1);
        $this->assertDatabaseHas('checkpoint_groups', ['event_id' => $event->id, 'name' => 'Ж21']);
        $this->assertDatabaseHas('checkpoint_participants', ['event_id' => $event->id, 'start_number' => '7', 'last_name' => 'Иванов']);
    }

    public function test_unmapped_columns_are_skipped(): void
    {
        $owner = $this->user();
        $event = $this->event($owner);
        $file = UploadedFile::fake()->createWithContent('custom.csv', "Фамилия,Имя,Номер,Комментарий\nИванов,Иван,7,игнорировать\n");
        $this->actingAs($owner, 'sanctum')->post('/api/checkpoint/events/'.$event->id.'/imports/preview', [
            'file' => $file,
            'mapping' => ['Фамилия' => 'last_name', 'Имя' => 'first_name', 'Номер' => 'start_number', 'Комментарий' => ''],
        ])->assertOk()->assertJsonPath('mapping_required', false)->assertJsonPath('rows.0.start_number', '7');
    }

    public function test_publish_permission_is_checked_on_the_server(): void
    {
        $owner = $this->user(true, false);
        $event = $this->event($owner);
        $participant = $event->checkpointParticipants()->create(['last_name' => 'Иванов', 'first_name' => 'Иван', 'start_number' => '12']);
        $payload = [
            'event_id' => '2c594780-0084-4c31-a777-86884f5b5221',
            'type' => 'heat.open',
            'occurred_at' => '2026-10-06T12:30:00+05:00',
            'heat' => ['external_id' => 'heat-1', 'name' => 'Финал М18'],
            'results' => [['participant_id' => $participant->id, 'place' => 1, 'result' => '00:35:12.420', 'result_value_ms' => 2112420]],
        ];
        $this->actingAs($owner, 'sanctum')->postJson('/api/checkpoint/competitions/'.$event->id.'/results', $payload)->assertStatus(403);

        $owner->forceFill(['checkpoint_publish_override' => true])->save();
        $this->actingAs($owner->fresh(), 'sanctum')->postJson('/api/checkpoint/competitions/'.$event->id.'/results', $payload)
            ->assertOk()
            ->assertJsonPath('accepted', true)
            ->assertJsonPath('state.live.name', 'Финал М18');
        $this->getJson('/api/events/'.$event->id.'/checkpoint-results')->assertOk()->assertJsonPath('live.results.0.start_number', '12');
        $this->actingAs($owner->fresh(), 'sanctum')->postJson('/api/checkpoint/competitions/'.$event->id.'/results', $payload)
            ->assertOk()
            ->assertJsonPath('accepted', false);
    }

    public function test_author_can_share_commission_with_another_account(): void
    {
        Mail::fake();
        $owner = $this->user();
        $helper = $this->user(false, false);
        $event = $this->event($owner);
        $event->checkpointParticipants()->create(['last_name' => 'Иванов', 'first_name' => 'Иван', 'start_number' => '12']);

        $this->actingAs($owner, 'sanctum')->postJson('/api/checkpoint/events/'.$event->id.'/members', ['email' => $helper->email])
            ->assertCreated()
            ->assertJsonPath('status', 'added')
            ->assertJsonPath('member.accepted', true)
            ->assertJsonPath('member.email', mb_strtolower($helper->email));
        Mail::assertSent(CommissionInvite::class);

        $this->actingAs($helper, 'sanctum')->getJson('/api/checkpoint/competitions')
            ->assertOk()
            ->assertJsonPath('0.id', $event->id)
            ->assertJsonPath('0.shared', true)
            ->assertJsonPath('0.publish_allowed', true);
        $this->actingAs($helper, 'sanctum')->postJson('/api/checkpoint/events/'.$event->id.'/participants', [
            'last_name' => 'Петров', 'first_name' => 'Пётр', 'start_number' => '13',
        ])->assertCreated();
        $this->actingAs($helper, 'sanctum')->patchJson('/api/checkpoint/events/'.$event->id.'/settings', ['checkpoint_enabled' => false])->assertStatus(403);
        $this->actingAs($helper, 'sanctum')->postJson('/api/checkpoint/events/'.$event->id.'/members', ['email' => 'other@example.com'])->assertStatus(403);

        $payload = [
            'event_id' => '2c594780-0084-4c31-a777-86884f5b5222',
            'type' => 'heat.open',
            'occurred_at' => '2026-10-06T12:30:00+05:00',
            'heat' => ['external_id' => 'heat-2', 'name' => 'Финал'],
            'results' => [],
        ];
        $this->actingAs($helper, 'sanctum')->postJson('/api/checkpoint/competitions/'.$event->id.'/results', $payload)->assertOk();

        $this->actingAs($owner, 'sanctum')->postJson('/api/checkpoint/events/'.$event->id.'/members', ['email' => 'new-helper@example.com'])
            ->assertCreated()
            ->assertJsonPath('status', 'invited')
            ->assertJsonPath('member.accepted', false);
        $pending = EventCommissionMember::where('email', 'new-helper@example.com')->first();
        $this->assertNotNull($pending->token);
        $this->assertNull($pending->user_id);

        $registered = User::factory()->create(['email' => 'new-helper@example.com', 'checkpoint_access' => false]);
        $this->postJson('/api/login', ['name' => $registered->email, 'password' => 'password'])->assertOk();
        $pending->refresh();
        $this->assertSame($registered->id, $pending->user_id);
        $this->assertNotNull($pending->token);
        $this->actingAs($registered, 'sanctum')->postJson('/api/checkpoint/invites/'.$pending->token.'/accept')
            ->assertOk()
            ->assertJsonPath('event_id', $event->id);
        $this->actingAs($registered, 'sanctum')->getJson('/api/checkpoint/events/'.$event->id.'/commission')
            ->assertOk()
            ->assertJsonPath('competition.can_manage_access', false);
    }
}
