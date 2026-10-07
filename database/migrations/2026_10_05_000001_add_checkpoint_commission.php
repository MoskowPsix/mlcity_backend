<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('checkpoint_access')->default(false);
            $table->boolean('checkpoint_publish_override')->default(false);
        });
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('checkpoint_enabled')->default(false);
        });
        Schema::create('checkpoint_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->string('name');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->unique(['event_id', 'name']);
            $table->unique(['id', 'event_id']);
        });
        Schema::create('checkpoint_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->unsignedBigInteger('group_id')->nullable();
            $table->foreign(['group_id', 'event_id'])->references(['id', 'event_id'])->on('checkpoint_groups')->restrictOnDelete();
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('city')->nullable();
            $table->string('start_number');
            $table->string('rfid')->nullable();
            $table->timestamps();
            $table->unique(['event_id', 'start_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checkpoint_participants');
        Schema::dropIfExists('checkpoint_groups');
        Schema::table('events', fn (Blueprint $table) => $table->dropColumn('checkpoint_enabled'));
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn(['checkpoint_access', 'checkpoint_publish_override']));
    }
};
