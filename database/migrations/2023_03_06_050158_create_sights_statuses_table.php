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
        Schema::create('sight_status', function (Blueprint $table) {
            $table->id('id');

            $table->integer('status_id');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');

            $table->integer('sight_id');
            $table->foreign('sight_id')->references('id')->on('sights')->onDelete('cascade');

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
        Schema::dropIfExists('sight_status');
    }
};
