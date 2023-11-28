<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_seances', function (Blueprint $table) {
            $table->id();
            $table->foreignId("history_place_id")->constrained("history_places");
            $table->foreignId("seance_id")->constrained("seances")->nullOnDelete();     
            $table->dateTime("date_start")->nullable();
            $table->dateTime("date_end")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_seances');
    }
};
