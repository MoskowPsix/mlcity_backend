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
        Schema::create('history_places', function (Blueprint $table) {
            $table->id();
            $table->foreignId("history_content_id")->constrained("history_contents")->cascadeOnDelete();
            $table->foreignId("sight_id")->constrained("sights")->nullable()->nullOnDelete();
            $table->integer("location_id")->nullable();
            $table->decimal('latitude', 17, 14)->nullable();
            $table->decimal('longitude', 17, 14)->nullable();
            $table->string("address")->nullable();
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
        Schema::dropIfExists('history_places');
    }
};
