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
        Schema::table('etypes', function (Blueprint $table) {
            $table->integer('cult_id')->nullable();
            $table->integer('etypes_id')->nullable();
            $table->foreign('etypes_id')->references('id')->on('etypes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('etypes', function (Blueprint $table) {
            $table->dropColumn('etypes_id');
            $table->dropColumn('cult_id');
        });
    }
};
