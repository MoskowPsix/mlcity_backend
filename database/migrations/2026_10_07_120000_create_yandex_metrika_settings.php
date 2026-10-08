<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('yandex_metrika_settings', function (Blueprint $table) {
            $table->id();
            $table->string('counter_id')->nullable();
            $table->boolean('enabled')->default(false);
            $table->boolean('webvisor')->default(true);
            $table->boolean('clickmap')->default(true);
            $table->boolean('track_links')->default(true);
            $table->boolean('accurate_track_bounce')->default(true);
            $table->timestamps();
        });

        $enabled = env('YANDEX_METRIKA_ENABLED');
        DB::table('yandex_metrika_settings')->insert([
            'counter_id' => env('YANDEX_METRIKA_ID') ?: '96112606',
            'enabled' => $enabled === null ? true : filter_var($enabled, FILTER_VALIDATE_BOOLEAN),
            'webvisor' => filter_var(env('YANDEX_METRIKA_WEBVISOR', true), FILTER_VALIDATE_BOOLEAN),
            'clickmap' => filter_var(env('YANDEX_METRIKA_CLICKMAP', true), FILTER_VALIDATE_BOOLEAN),
            'track_links' => filter_var(env('YANDEX_METRIKA_TRACK_LINKS', true), FILTER_VALIDATE_BOOLEAN),
            'accurate_track_bounce' => filter_var(env('YANDEX_METRIKA_ACCURATE_TRACK_BOUNCE', true), FILTER_VALIDATE_BOOLEAN),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('yandex_metrika_settings');
    }
};
