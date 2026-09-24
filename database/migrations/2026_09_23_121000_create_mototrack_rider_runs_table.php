<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mototrack_rider_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('place_id')->constrained('places')->cascadeOnDelete();
            $table->string('device_id');
            $table->string('rfid_tag_number')->nullable();
            $table->unsignedBigInteger('start_event_id')->nullable();
            $table->unsignedBigInteger('finish_event_id')->nullable();
            $table->timestampTz('started_at');
            $table->timestampTz('finished_at')->nullable();
            $table->unsignedBigInteger('duration_ms')->nullable();
            $table->json('start_payload')->nullable();
            $table->json('finish_payload')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'place_id', 'started_at']);
            $table->index(['place_id', 'started_at']);
            $table->index(['user_id', 'finished_at']);
            $table->unique('start_event_id');
            $table->unique('finish_event_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mototrack_rider_runs');
    }
};
