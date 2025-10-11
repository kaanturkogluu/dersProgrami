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
        Schema::create('student_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Program adı (örn: "TYT Haftalık Program 1")
            $table->enum('area', ['TYT', 'AYT', 'KPSS', 'DGS', 'ALES']); // Alan seçimi
            $table->date('start_date'); // Program başlangıç tarihi
            $table->date('end_date'); // Program bitiş tarihi
            $table->boolean('is_active')->default(true); // Aktif program mı?
            $table->text('description')->nullable(); // Program açıklaması
            $table->json('schedule_data')->nullable(); // Haftalık program verisi (JSON)
            $table->timestamps();
            
            // Index'ler
            $table->index(['student_id', 'area']);
            $table->index(['student_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_schedules');
    }
};
