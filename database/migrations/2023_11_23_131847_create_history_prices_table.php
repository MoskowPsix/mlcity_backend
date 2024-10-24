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
        Schema::create('history_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId("history_content_id")->constrained("history_contents")->nullOnDelete();
            $table->foreignId("price_id")->nullable()->constrained("price")->nullOnDelete();
            $table->boolean("on_delete")->nullable();
            $table->string("cost_rub")->nullable();
            $table->text("descriptions")->nullable();
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
        Schema::dropIfExists('history_prices');
    }
};
