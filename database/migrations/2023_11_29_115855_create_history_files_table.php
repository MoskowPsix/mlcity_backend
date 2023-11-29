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
        Schema::create('history_files', function (Blueprint $table) {
            $table->id();
            $table->integer("file_id");
            $table->string("name");
            $table->text("link");
            $table->integer("local");
            $table->boolean("on_delete")->nullable();
            $table->foreignId("history_content_id")->constrained("history_contents")->nullOnDelete();
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
        Schema::dropIfExists('history_files');
    }
};
