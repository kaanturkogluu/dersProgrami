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
        Schema::table('student_schedules', function (Blueprint $table) {
            // Önce mevcut area kolonunu kaldır
            $table->dropColumn('area');
        });
        
        Schema::table('student_schedules', function (Blueprint $table) {
            // JSON olarak area kolonunu ekle
            $table->json('areas')->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_schedules', function (Blueprint $table) {
            $table->dropColumn('areas');
        });
        
        Schema::table('student_schedules', function (Blueprint $table) {
            $table->enum('area', ['TYT', 'AYT', 'KPSS', 'DGS', 'ALES'])->after('name');
        });
    }
};
