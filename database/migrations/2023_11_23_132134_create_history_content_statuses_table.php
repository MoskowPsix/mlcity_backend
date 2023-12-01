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
        Schema::create('history_content_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId("status_id")->constrained("statuses")->nullOnDelete();
            $table->foreignId("history_content_id")->constrained("history_contents")->nullOnDelete();
            $table->text("descriptions")->nullable();
            $table->boolean("last")->nullable();
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
        Schema::dropIfExists('history_status_contents');
    }
};
