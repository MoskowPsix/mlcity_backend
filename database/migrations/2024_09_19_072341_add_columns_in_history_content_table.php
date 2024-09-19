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
            $table->string("phone_number")->nullable();
            $table->string("email")->nullable();
            $table->string("site")->nullable();
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
            $table->dropColumn("phone_number");
            $table->dropColumn("email");
            $table->dropColumn("site");
        });
    }
};
