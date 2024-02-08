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
        Schema::create('organization_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId("status_id")->constrained("statuses")->cascadeOnDelete();
            $table->foreignId("organization_id")->constrained("organizations")->cascadeOnDelete();
            $table->text("description");
            $table->boolean("last")->default(false);
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
        Schema::dropIfExists('organization_status');
    }
};
