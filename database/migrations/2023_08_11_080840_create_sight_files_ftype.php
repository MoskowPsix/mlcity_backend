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
        Schema::create('sight_file_types', function (Blueprint $table) {
            $table->id();
            $table->integer('type_id');
            $table->foreign('type_id')->references('id')->on('file_types')->onDelete('cascade');
            $table->integer('file_id');
            $table->foreign('file_id')->references('id')->on('sight_files')->onDelete('cascade');
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
        Schema::dropIfExists('sight_file_ftype');
    }
};
