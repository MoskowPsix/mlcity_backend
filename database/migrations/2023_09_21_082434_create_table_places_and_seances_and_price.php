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
        Schema::create('places', function (Blueprint $table) {
            $table->id();

            $table->integer('event_id');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');

            $table->integer('sight_id')->nullable();
            $table->foreign('sight_id')->references('id')->on('sights');

            $table->integer('location_id');
            $table->foreign('location_id')->references('id')->on('locations');

            $table->decimal('latitude', 17, 14);
            $table->decimal('longitude', 17, 14);

            

            $table->string('address')->nullable();

            $table->timestamps();
        });
        Schema::create('seances', function (Blueprint $table) {
            $table->id();

            $table->integer('place_id');
            $table->foreign('place_id')->references('id')->on('places')->onDelete('cascade');

            $table->dateTime('date_start')->nullable();
            $table->dateTime('date_end')->nullable();
            $table->timestamps();
        });
        Schema::create('price', function (Blueprint $table) {
            $table->id();

            $table->integer('event_id')->nullable();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');

            $table->integer('sight_id')->nullable();
            $table->foreign('sight_id')->references('id')->on('sights')->onDelete('cascade');

            $table->string('cost_rub')->nullable();
            $table->string('descriptions')->nullable();
            
            $table->timestamps();
        });
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'address']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seances');
        Schema::dropIfExists('places');
        Schema::dropIfExists('price');
        Schema::table('events', function (Blueprint $table) {
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('address');
        });
    }
};
