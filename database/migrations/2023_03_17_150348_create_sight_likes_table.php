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
        Schema::create('sight_likes', function (Blueprint $table) {
            $table->id();
            $table->integer('vk_count')->default(0);
            $table->integer('local_count')->default(0);
            $table->integer('sight_id');
            $table->foreign('sight_id')->references('id')->on('sights')->onDelete('cascade');
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
        Schema::dropIfExists('sight_likes');
    }
};
