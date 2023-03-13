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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sponsor');
            $table->string('city');
            $table->string('address');
            $table->decimal('latitude', 8, 6);
            $table->decimal('longitude', 8, 6);
            $table->longText('description');
            $table->string('price')->nullable();
            $table->longText('materials')->nullable();
            $table->dateTime('date_start');
            $table->dateTime('date_end');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('vk_post_id')->nullable();
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
        Schema::dropIfExists('events');
    }
};
