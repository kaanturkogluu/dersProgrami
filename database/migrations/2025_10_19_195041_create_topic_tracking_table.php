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
        Schema::create('topic_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('topic_id')->constrained('topics')->onDelete('cascade');
            $table->foreignId('subtopic_id')->nullable()->constrained('subtopics')->onDelete('cascade');
            $table->enum('status', ['not_started', 'in_progress', 'completed', 'approved'])->default('not_started');
            $table->date('started_at')->nullable();
            $table->date('completed_at')->nullable();
            $table->date('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->integer('difficulty_level')->default(1); // 1-5 arası zorluk seviyesi
            $table->integer('time_spent_minutes')->default(0); // Harcanan süre
            $table->timestamps();
            
            // Aynı öğrenci için aynı konu/alt konu kombinasyonu sadece bir kez olabilir
            $table->unique(['student_id', 'topic_id', 'subtopic_id'], 'unique_student_topic_subtopic');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topic_tracking');
    }
};
