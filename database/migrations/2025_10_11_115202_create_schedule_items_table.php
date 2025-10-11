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
        Schema::create('schedule_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained('student_schedules')->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('topic_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->time('start_time'); // Başlangıç saati
            $table->time('end_time'); // Bitiş saati
            $table->integer('duration_minutes'); // Süre (dakika)
            $table->text('notes')->nullable(); // Notlar
            $table->boolean('is_completed')->default(false); // Tamamlandı mı?
            $table->date('scheduled_date')->nullable(); // Belirli bir tarih (opsiyonel)
            $table->timestamps();
            
            // Index'ler
            $table->index(['schedule_id', 'day_of_week']);
            $table->index(['schedule_id', 'scheduled_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_items');
    }
};
