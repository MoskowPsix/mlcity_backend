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
        Schema::create('history_file_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId("type_id")->constrained("file_types")->nullOnDelete();
            $table->foreignId("history_file_id")->constrained("history_files")->nullOnDelete();
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
        Schema::dropIfExists('history_file_types');
    }
};
