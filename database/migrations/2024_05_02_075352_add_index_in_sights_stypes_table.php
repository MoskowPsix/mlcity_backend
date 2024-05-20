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
        Schema::table('sights_stypes', function (Blueprint $table) {
            $table->index("sight_id");
            $table->index("stype_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sights_stypes', function (Blueprint $table) {
            $table->dropIndex(["sight_id"]);
            $table->dropIndex(["stype_id"]);
        });
    }
};
