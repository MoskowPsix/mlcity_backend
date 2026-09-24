<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rfid_tag_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('moto_tag_number')->unique();
            $table->text('sensor_value');
            $table->string('normalized_sensor_value')->unique();
            $table->string('source')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rfid_tag_mappings');
    }
};
