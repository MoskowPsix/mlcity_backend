<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('checkpoint_heats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->string('external_id');
            $table->string('name');
            $table->string('status')->default('draft');
            $table->timestampTz('started_at')->nullable();
            $table->timestampTz('finished_at')->nullable();
            $table->timestamps();
            $table->unique(['event_id', 'external_id']);
        });
        Schema::create('checkpoint_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('heat_id')->constrained('checkpoint_heats')->cascadeOnDelete();
            $table->foreignId('participant_id')->nullable()->constrained('checkpoint_participants')->nullOnDelete();
            $table->unsignedInteger('place')->nullable();
            $table->string('result')->nullable();
            $table->bigInteger('result_value_ms')->nullable();
            $table->timestamps();
            $table->unique(['heat_id', 'participant_id']);
        });
        Schema::create('checkpoint_result_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->uuid('external_event_id');
            $table->timestampTz('occurred_at');
            $table->timestamps();
            $table->unique(['event_id', 'external_event_id']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('checkpoint_result_events');
        Schema::dropIfExists('checkpoint_results');
        Schema::dropIfExists('checkpoint_heats');
    }
};
