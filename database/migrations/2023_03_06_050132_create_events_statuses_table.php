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
        Schema::create('event_status', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('status_id');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');

            $table->integer('event_id');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');

            $table->boolean('last')->default(false);

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
        Schema::dropIfExists('event_status');
    }
};
