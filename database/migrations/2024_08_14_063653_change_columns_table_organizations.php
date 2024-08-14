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
            $table->string("inn", 10)->nullable(true)->unique()->change();
            $table->string("ogrn", 13)->nullable(true)->unique()->change();
            $table->string("kpp", 9)->nullable(true)->unique()->change();
            $table->dropColumn("address");
            $table->string("number", 10)->unique()->nullable(true)->change();
            $table->text("description")->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organisations', function (Blueprint $table) {
            $table->string("inn", 10)->nullable(false)->change();
            $table->string("ogrn", 13)->nullable(false)->change();
            $table->string("kpp", 9)->nullable(false)->change();
            $table->string("address");
            $table->string("number", 10)->nullable(false)->change();
            $table->text("description")->nullable(false)->change();
        });
    }
};
