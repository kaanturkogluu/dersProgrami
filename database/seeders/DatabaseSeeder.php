<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Student;
use App\Models\Course;
use App\Models\Topic;
use App\Models\Subtopic;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create categories
        $kpss = Category::create([
            'name' => 'KPSS',
            'description' => 'Kamu Personeli Seçme Sınavı hazırlık dersleri',
            'color' => '#3B82F6',
            'is_active' => true
        ]);

        $tyt = Category::create([
            'name' => 'TYT',
            'description' => 'Temel Yeterlilik Testi hazırlık dersleri',
            'color' => '#10B981',
            'is_active' => true
        ]);

        $ayt = Category::create([
            'name' => 'AYT',
            'description' => 'Alan Yeterlilik Testi hazırlık dersleri',
            'color' => '#F59E0B',
            'is_active' => true
        ]);

        // Create students
        Student::create([
            'first_name' => 'Ahmet',
            'last_name' => 'Yılmaz',
            'email' => 'ahmet.yilmaz@example.com',
            'phone' => '0555 123 45 67',
            'birth_date' => '1995-03-15',
            'student_number' => '2024001',
            'address' => 'İstanbul, Türkiye',
            'is_active' => true
        ]);

        Student::create([
            'first_name' => 'Ayşe',
            'last_name' => 'Demir',
            'email' => 'ayse.demir@example.com',
            'phone' => '0555 234 56 78',
            'birth_date' => '1998-07-22',
            'student_number' => '2024002',
            'address' => 'Ankara, Türkiye',
            'is_active' => true
        ]);

        // Create courses
        $matematik = Course::create([
            'name' => 'Matematik',
            'description' => 'Temel matematik konuları ve problem çözme teknikleri',
            'category_id' => $tyt->id,
            'duration_hours' => 40,
            'price' => 500.00,
            'is_active' => true
        ]);

        $turkce = Course::create([
            'name' => 'Türkçe',
            'description' => 'Dil bilgisi, anlam bilgisi ve yazım kuralları',
            'category_id' => $tyt->id,
            'duration_hours' => 30,
            'price' => 400.00,
            'is_active' => true
        ]);

        $tarih = Course::create([
            'name' => 'Tarih',
            'description' => 'Türk ve dünya tarihi konuları',
            'category_id' => $kpss->id,
            'duration_hours' => 35,
            'price' => 450.00,
            'is_active' => true
        ]);

        // Create topics for Mathematics
        $sayilar = Topic::create([
            'name' => 'Sayılar',
            'description' => 'Doğal sayılar, tam sayılar, rasyonel sayılar',
            'course_id' => $matematik->id,
            'order_index' => 1,
            'duration_minutes' => 120,
            'is_active' => true
        ]);

        $cebir = Topic::create([
            'name' => 'Cebir',
            'description' => 'Denklemler, eşitsizlikler ve fonksiyonlar',
            'course_id' => $matematik->id,
            'order_index' => 2,
            'duration_minutes' => 150,
            'is_active' => true
        ]);

        // Create subtopics for Numbers
        Subtopic::create([
            'name' => 'Doğal Sayılar',
            'description' => 'Doğal sayıların özellikleri ve işlemler',
            'topic_id' => $sayilar->id,
            'order_index' => 1,
            'duration_minutes' => 60,
            'content' => 'Doğal sayılar 0, 1, 2, 3, ... şeklinde devam eden sayılardır.',
            'is_active' => true
        ]);

        Subtopic::create([
            'name' => 'Tam Sayılar',
            'description' => 'Pozitif ve negatif tam sayılar',
            'topic_id' => $sayilar->id,
            'order_index' => 2,
            'duration_minutes' => 60,
            'content' => 'Tam sayılar pozitif ve negatif sayıları içerir.',
            'is_active' => true
        ]);

        // Create topics for Turkish
        $dilbilgisi = Topic::create([
            'name' => 'Dil Bilgisi',
            'description' => 'İsim, sıfat, zamir, fiil gibi sözcük türleri',
            'course_id' => $turkce->id,
            'order_index' => 1,
            'duration_minutes' => 90,
            'is_active' => true
        ]);

        $anlambilgisi = Topic::create([
            'name' => 'Anlam Bilgisi',
            'description' => 'Sözcük anlamı, cümle anlamı, paragraf anlamı',
            'course_id' => $turkce->id,
            'order_index' => 2,
            'duration_minutes' => 90,
            'is_active' => true
        ]);

        // Create topics for History
        $osmanli = Topic::create([
            'name' => 'Osmanlı Tarihi',
            'description' => 'Osmanlı Devleti\'nin kuruluşu, yükselişi ve çöküşü',
            'course_id' => $tarih->id,
            'order_index' => 1,
            'duration_minutes' => 120,
            'is_active' => true
        ]);

        $cumhuriyet = Topic::create([
            'name' => 'Cumhuriyet Tarihi',
            'description' => 'Türkiye Cumhuriyeti\'nin kuruluşu ve gelişimi',
            'course_id' => $tarih->id,
            'order_index' => 2,
            'duration_minutes' => 90,
            'is_active' => true
        ]);
    }
}
