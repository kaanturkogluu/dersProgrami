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
        Schema::table('schedule_items', function (Blueprint $table) {
            $table->foreignId('subtopic_id')->nullable()->after('topic_id')->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedule_items', function (Blueprint $table) {
            $table->dropForeign(['subtopic_id']);
            $table->dropColumn('subtopic_id');
        });
    }
};
