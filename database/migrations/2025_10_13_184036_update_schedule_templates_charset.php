<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Önce mevcut verileri temizle
        DB::table('schedule_templates')->truncate();
        
        Schema::table('schedule_templates', function (Blueprint $table) {
            // Mevcut tabloyu utf8mb4 charset'e çevir
            $table->string('name')->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->change();
            $table->text('description')->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedule_templates', function (Blueprint $table) {
            // Geri al
            $table->string('name')->charset('utf8')->collation('utf8_general_ci')->change();
            $table->text('description')->charset('utf8')->collation('utf8_general_ci')->change();
        });
    }
};
