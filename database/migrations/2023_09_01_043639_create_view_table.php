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
        Schema::create('view', function (Blueprint $table) {
            $table->id();
            
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('event_id')->nullable();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');

            $table->integer('sight_id')->nullable();
            $table->foreign('sight_id')->references('id')->on('sights')->onDelete('cascade');

            $table->decimal('time_view', 9, 3);

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
        Schema::dropIfExists('view');
    }
};
