<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('view_counts', function (Blueprint $table) {
            $table->foreignId('sight_id')->nullable()->references('id')->on('sights')->cascadeOnDelete()->nullable();
            $table->bigInteger('event_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('view_counts', function (Blueprint $table) {
            $table->dropColumn('sight_id');
        });
    }
};
