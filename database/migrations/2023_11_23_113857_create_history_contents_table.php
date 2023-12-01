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
        Schema::create('history_contents', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->string("sponsor")->nullable();
            $table->text("description")->nullable();
            $table->text("materials")->nullable();
            $table->dateTime("date_start")->nullable();
            $table->dateTime("date_end")->nullable();
            $table->foreignId('user_id')->constrained('users')->nullable()->onDelete('cascade');
            $table->string("vk_group_id")->nullable();
            $table->string("vk_post_id")->nullable();
            $table->integer("cult_id")->nullable();
            $table->text("work_time")->nullable();
            $table->decimal('latitude', 17, 14);
            $table->decimal('longitude', 17, 14);
            $table->integer('location_id')->nullable();
            $table->foreign('location_id')->references('id')->on('locations')->nullOnDelete();
            $table->boolean("on_delete")->nullable();
            $table->unsignedBigInteger("history_contentable_id");
            $table->string("history_contentable_type");
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
        Schema::dropIfExists('history_contentable');
    }
};
