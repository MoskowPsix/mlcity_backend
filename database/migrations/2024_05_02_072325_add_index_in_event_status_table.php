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
        Schema::table('event_status', function (Blueprint $table) {
            $table->index("event_id");
            $table->index("status_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_status', function (Blueprint $table) {
            $table->dropIndex(["event_id"]);
            $table->dropIndex(["status_id"]);
        });
    }
};
