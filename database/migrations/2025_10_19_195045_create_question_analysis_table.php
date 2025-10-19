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
        Schema::create('question_analysis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('topic_id')->constrained('topics')->onDelete('cascade');
            $table->foreignId('subtopic_id')->nullable()->constrained('subtopics')->onDelete('cascade');
            $table->string('question_source')->nullable(); // Kaynak (ÖSYM, YKS, vs.)
            $table->integer('question_year')->nullable(); // Soru yılı
            $table->integer('question_number')->nullable(); // Soru numarası
            $table->enum('difficulty', ['kolay', 'orta', 'zor'])->default('orta');
            $table->enum('result', ['correct', 'incorrect', 'empty'])->default('empty');
            $table->integer('time_spent_seconds')->default(0); // Soruya harcanan süre
            $table->text('student_answer')->nullable(); // Öğrencinin cevabı
            $table->text('correct_answer')->nullable(); // Doğru cevap
            $table->text('explanation')->nullable(); // Açıklama
            $table->text('notes')->nullable(); // Notlar
            $table->date('solved_at'); // Çözüldüğü tarih
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_analysis');
    }
};
