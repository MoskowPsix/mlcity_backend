<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sights', function (Blueprint $table) {
            $table->text('source_id')->nullable()->after('afisha7_id');
            $table->string('source_name')->nullable()->after('source_id');
            $table->index(['source_name', 'source_id']);
        });
    }

    public function down(): void
    {
        Schema::table('sights', function (Blueprint $table) {
            $table->dropIndex(['source_name', 'source_id']);
            $table->dropColumn(['source_id', 'source_name']);
        });
    }
};
