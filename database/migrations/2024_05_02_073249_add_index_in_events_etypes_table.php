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
        Schema::table('events_etypes', function (Blueprint $table) {
            $table->index("event_id");
            $table->index("etype_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events_etypes', function (Blueprint $table) {
            $table->dropIndex(["event_id"]);
            $table->dropIndex(["etype_id"]);
        });
    }
};
