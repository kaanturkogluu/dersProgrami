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
        Schema::create('daily_lesson_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('schedule_item_id')->constrained()->onDelete('cascade');
            $table->date('tracking_date'); // Takip edilen tarih
            $table->boolean('is_completed')->default(false); // Ders tamamlandı mı?
            $table->integer('study_duration_minutes')->nullable(); // Çalışma süresi (dakika)
            $table->text('notes')->nullable(); // Öğrenci notları
            $table->enum('difficulty_level', ['kolay', 'orta', 'zor'])->nullable(); // Zorluk seviyesi
            $table->integer('understanding_score')->nullable(); // Anlama puanı (1-10)
            $table->timestamps();
            
            // Index'ler
            $table->index(['student_id', 'tracking_date']);
            $table->index(['schedule_item_id', 'tracking_date']);
            $table->unique(['student_id', 'schedule_item_id', 'tracking_date'], 'daily_tracking_unique'); // Aynı gün aynı ders için tek kayıt
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_lesson_tracking');
    }
};