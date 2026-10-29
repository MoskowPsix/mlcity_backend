<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('checkpoint_heats', function (Blueprint $table) {
            $table->string('type_slug')->nullable()->after('name');
            $table->unsignedInteger('laps_target')->nullable()->after('type_slug');
        });
        Schema::table('checkpoint_results', function (Blueprint $table) {
            $table->bigInteger('best_lap_time_ms')->nullable()->after('result_value_ms');
            $table->bigInteger('last_lap_time_ms')->nullable()->after('best_lap_time_ms');
            $table->json('lap_times')->nullable()->after('last_lap_time_ms');
        });
    }

    public function down(): void
    {
        Schema::table('checkpoint_results', function (Blueprint $table) {
            $table->dropColumn(['best_lap_time_ms', 'last_lap_time_ms', 'lap_times']);
        });
        Schema::table('checkpoint_heats', function (Blueprint $table) {
            $table->dropColumn(['type_slug', 'laps_target']);
        });
    }
};
