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
        Schema::create('sights', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->string('sponsor');
            $table->string('city')->nullable();
            $table->string('address');
            $table->decimal('latitude', 17, 14);
            $table->decimal('longitude', 17, 14);
            $table->longText('description');
            $table->integer('price')->nullable();
            $table->longText('materials')->nullable();
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('vk_group_id')->nullable();
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
        Schema::dropIfExists('sights');
    }
};
