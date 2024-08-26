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
        Schema::create('sight_invites', function (Blueprint $table) {
            $table->id();
            $table->string("email");
            $table->text("token");
            $table->string("url")->unique();
            $table->foreignId("user_id")->constrained("users");
            $table->foreignId("sight_id")->constrained("sights");
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
        Schema::dropIfExists('sight_invites');
    }
};
