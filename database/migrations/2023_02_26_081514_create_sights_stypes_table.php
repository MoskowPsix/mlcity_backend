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
        Schema::create('sights_stypes', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('sight_id');
            $table->foreign('sight_id')->references('id')->on('sights')->onDelete('cascade');

            $table->integer('stype_id');
            $table->foreign('stype_id')->references('id')->on('stypes')->onDelete('cascade');

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
        Schema::dropIfExists('sights_stypes');
    }
};
