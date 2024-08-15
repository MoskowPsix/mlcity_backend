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
        Schema::table('history_contents', function (Blueprint $table) {
            $table->unsignedBigInteger("accepter_id")->nullable();
            $table->foreign("accepter_id")->references("id")->on("users")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('history_contents', function (Blueprint $table) {
            $table->dropColumn("accepter_id");
        });
    }
};
