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
        Schema::create('events_etypes', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('event_id');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');

            $table->integer('etype_id');
            $table->foreign('etype_id')->references('id')->on('etypes')->onDelete('cascade');

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
        Schema::dropIfExists('events_etypes');
    }
};
