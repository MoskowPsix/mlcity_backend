<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mototrack_device_place_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('device_id')->unique();
            $table->foreignId('place_id')->constrained('places')->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mototrack_device_place_mappings');
    }
};
