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
        Schema::create('pcodes', function (Blueprint $table) {
            $table->id();
            $table->integer('phone_id');
            $table->foreign('phone_id')->references('id')->on('phones')->onDelete('cascade');
            $table->string('code');
            $table->boolean('last')->default(true);
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
        Schema::dropIfExists('pcodes');
    }
};