<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mototrack_device_place_mappings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('place_id');
            $table->foreignId('sight_id')->after('device_id')->constrained('sights')->cascadeOnDelete();
        });

        Schema::table('mototrack_rider_runs', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'place_id', 'started_at']);
            $table->dropIndex(['place_id', 'started_at']);
            $table->dropConstrainedForeignId('place_id');
            $table->foreignId('sight_id')->after('user_id')->constrained('sights')->cascadeOnDelete();
            $table->index(['user_id', 'sight_id', 'started_at']);
            $table->index(['sight_id', 'started_at']);
        });
    }

    public function down(): void
    {
        Schema::table('mototrack_device_place_mappings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('sight_id');
            $table->foreignId('place_id')->after('device_id')->constrained('places')->cascadeOnDelete();
        });

        Schema::table('mototrack_rider_runs', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'sight_id', 'started_at']);
            $table->dropIndex(['sight_id', 'started_at']);
            $table->dropConstrainedForeignId('sight_id');
            $table->foreignId('place_id')->after('user_id')->constrained('places')->cascadeOnDelete();
            $table->index(['user_id', 'place_id', 'started_at']);
            $table->index(['place_id', 'started_at']);
        });
    }
};
