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
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn("inn");
            $table->dropColumn("ogrn");
            $table->dropColumn("kpp");
            $table->dropColumn("number");
            $table->text("avatar")->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn("avatar");
            $table->string("inn", 10)->nullable(true)->unique();
            $table->string("ogrn", 13)->nullable(true)->unique();
            $table->string("kpp", 9)->nullable(true)->unique();
            $table->string("number", 10)->unique()->nullable(true);
        });
    }
};
