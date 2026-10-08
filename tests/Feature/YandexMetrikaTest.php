<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\YandexMetrikaSetting;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class YandexMetrikaTest extends TestCase
{
    use DatabaseTransactions;

    public function test_public_config_hides_counter_when_disabled(): void
    {
        YandexMetrikaSetting::query()->delete();
        YandexMetrikaSetting::query()->create([
            'counter_id' => '96112606',
            'enabled' => false,
        ]);

        $this->getJson('/api/metrika')
            ->assertOk()
            ->assertJsonPath('enabled', false)
            ->assertJsonPath('id', null);
    }

    public function test_public_config_returns_counter_when_enabled(): void
    {
        YandexMetrikaSetting::query()->delete();
        YandexMetrikaSetting::query()->create([
            'counter_id' => '96112606',
            'enabled' => true,
            'webvisor' => true,
            'clickmap' => false,
            'track_links' => true,
            'accurate_track_bounce' => true,
        ]);

        $this->getJson('/api/metrika')
            ->assertOk()
            ->assertJsonPath('enabled', true)
            ->assertJsonPath('id', 96112606)
            ->assertJsonPath('webvisor', true)
            ->assertJsonPath('clickmap', false);
    }

    public function test_admin_form_shows_saved_counter(): void
    {
        YandexMetrikaSetting::query()->delete();
        $row = YandexMetrikaSetting::query()->create([
            'counter_id' => '96112606',
            'enabled' => true,
            'webvisor' => true,
            'clickmap' => true,
            'track_links' => true,
            'accurate_track_bounce' => true,
        ]);

        $admin = User::factory()->create();

        $this->actingAs($admin, 'moonshine')
            ->get('/moon/resource/yandex-metrika-setting-resource/index-page')
            ->assertOk()
            ->assertSee('96112606');

        $this->actingAs($admin, 'moonshine')
            ->get('/moon/resource/yandex-metrika-setting-resource/form-page?resourceItem=' . $row->id)
            ->assertOk()
            ->assertSee('Номер счётчика')
            ->assertSee('Вебвизор')
            ->assertSee('96112606');
    }
}
