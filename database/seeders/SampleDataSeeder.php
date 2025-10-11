<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Course;
use App\Models\Topic;
use App\Models\Subtopic;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kategoriler oluştur
        $categories = [
            [
                'name' => 'TYT',
                'description' => 'Temel Yeterlilik Testi',
                'color' => '#3B82F6',
                'is_active' => true
            ],
            [
                'name' => 'AYT',
                'description' => 'Alan Yeterlilik Testi',
                'color' => '#10B981',
                'is_active' => true
            ],
            [
                'name' => 'KPSS',
                'description' => 'Kamu Personeli Seçme Sınavı',
                'color' => '#F59E0B',
                'is_active' => true
            ],
            [
                'name' => 'DGS',
                'description' => 'Dikey Geçiş Sınavı',
                'color' => '#EF4444',
                'is_active' => true
            ],
            [
                'name' => 'ALES',
                'description' => 'Akademik Personel ve Lisansüstü Eğitimi Giriş Sınavı',
                'color' => '#8B5CF6',
                'is_active' => true
            ]
        ];

        foreach ($categories as $categoryData) {
            $category = Category::firstOrCreate(
                ['name' => $categoryData['name']],
                $categoryData
            );

            // Her kategori için 5 ders oluştur
            $courses = $this->getCoursesForCategory($category->name);
            foreach ($courses as $courseData) {
                $course = Course::firstOrCreate(
                    ['name' => $courseData['name'], 'category_id' => $category->id],
                    array_merge($courseData, ['category_id' => $category->id])
                );

                // Her ders için 5 konu oluştur
                $topics = $this->getTopicsForCourse($course->name);
                foreach ($topics as $topicData) {
                    $topic = Topic::firstOrCreate(
                        ['name' => $topicData['name'], 'course_id' => $course->id],
                        array_merge($topicData, ['course_id' => $course->id])
                    );

                    // Her konu için 5 alt konu oluştur
                    $subtopics = $this->getSubtopicsForTopic($topic->name);
                    foreach ($subtopics as $subtopicData) {
                        Subtopic::firstOrCreate(
                            ['name' => $subtopicData['name'], 'topic_id' => $topic->id],
                            array_merge($subtopicData, ['topic_id' => $topic->id])
                        );
                    }
                }
            }
        }

        $this->command->info('Sample data created successfully!');
    }

    private function getCoursesForCategory($categoryName)
    {
        $courses = [
            'TYT' => [
                ['name' => 'TYT Matematik', 'description' => 'Temel matematik konuları', 'is_active' => true],
                ['name' => 'TYT Türkçe', 'description' => 'Türkçe dil bilgisi ve anlam bilgisi', 'is_active' => true],
                ['name' => 'TYT Fizik', 'description' => 'Temel fizik konuları', 'is_active' => true],
                ['name' => 'TYT Kimya', 'description' => 'Temel kimya konuları', 'is_active' => true],
                ['name' => 'TYT Biyoloji', 'description' => 'Temel biyoloji konuları', 'is_active' => true]
            ],
            'AYT' => [
                ['name' => 'AYT Matematik', 'description' => 'İleri matematik konuları', 'is_active' => true],
                ['name' => 'AYT Fizik', 'description' => 'İleri fizik konuları', 'is_active' => true],
                ['name' => 'AYT Kimya', 'description' => 'İleri kimya konuları', 'is_active' => true],
                ['name' => 'AYT Biyoloji', 'description' => 'İleri biyoloji konuları', 'is_active' => true],
                ['name' => 'AYT Edebiyat', 'description' => 'Türk edebiyatı ve dil anlatım', 'is_active' => true]
            ],
            'KPSS' => [
                ['name' => 'KPSS Genel Kültür', 'description' => 'Tarih, coğrafya, vatandaşlık', 'is_active' => true],
                ['name' => 'KPSS Genel Yetenek', 'description' => 'Matematik, Türkçe, mantık', 'is_active' => true],
                ['name' => 'KPSS Eğitim Bilimleri', 'description' => 'Öğretim yöntemleri ve psikoloji', 'is_active' => true],
                ['name' => 'KPSS Hukuk', 'description' => 'Anayasa, idare, ceza hukuku', 'is_active' => true],
                ['name' => 'KPSS İktisat', 'description' => 'Mikro ve makro iktisat', 'is_active' => true]
            ],
            'DGS' => [
                ['name' => 'DGS Sayısal', 'description' => 'Matematik ve geometri', 'is_active' => true],
                ['name' => 'DGS Sözel', 'description' => 'Türkçe ve mantık', 'is_active' => true],
                ['name' => 'DGS Geometri', 'description' => 'Geometri konuları', 'is_active' => true],
                ['name' => 'DGS Mantık', 'description' => 'Mantık ve akıl yürütme', 'is_active' => true],
                ['name' => 'DGS Türkçe', 'description' => 'Dil bilgisi ve anlam bilgisi', 'is_active' => true]
            ],
            'ALES' => [
                ['name' => 'ALES Sayısal', 'description' => 'Matematik ve geometri', 'is_active' => true],
                ['name' => 'ALES Sözel', 'description' => 'Türkçe ve mantık', 'is_active' => true],
                ['name' => 'ALES Geometri', 'description' => 'Geometri konuları', 'is_active' => true],
                ['name' => 'ALES Mantık', 'description' => 'Mantık ve akıl yürütme', 'is_active' => true],
                ['name' => 'ALES Türkçe', 'description' => 'Dil bilgisi ve anlam bilgisi', 'is_active' => true]
            ]
        ];

        return $courses[$categoryName] ?? [];
    }

    private function getTopicsForCourse($courseName)
    {
        $topics = [
            'TYT Matematik' => [
                ['name' => 'Sayılar', 'description' => 'Doğal sayılar, tam sayılar, rasyonel sayılar', 'order_index' => 1, 'duration_minutes' => 120],
                ['name' => 'Cebir', 'description' => 'Denklemler, eşitsizlikler, fonksiyonlar', 'order_index' => 2, 'duration_minutes' => 150],
                ['name' => 'Geometri', 'description' => 'Temel geometrik şekiller ve özellikleri', 'order_index' => 3, 'duration_minutes' => 180],
                ['name' => 'Veri Analizi', 'description' => 'İstatistik ve olasılık', 'order_index' => 4, 'duration_minutes' => 90],
                ['name' => 'Problemler', 'description' => 'Sözel problemler ve çözüm teknikleri', 'order_index' => 5, 'duration_minutes' => 200]
            ],
            'TYT Türkçe' => [
                ['name' => 'Dil Bilgisi', 'description' => 'Kelime türleri, cümle bilgisi', 'order_index' => 1, 'duration_minutes' => 120],
                ['name' => 'Anlam Bilgisi', 'description' => 'Kelimede anlam, cümlede anlam', 'order_index' => 2, 'duration_minutes' => 150],
                ['name' => 'Paragraf', 'description' => 'Paragraf yapısı ve anlam', 'order_index' => 3, 'duration_minutes' => 180],
                ['name' => 'Yazım Kuralları', 'description' => 'Noktalama ve yazım kuralları', 'order_index' => 4, 'duration_minutes' => 90],
                ['name' => 'Anlatım Bozuklukları', 'description' => 'Anlatım bozukluğu türleri', 'order_index' => 5, 'duration_minutes' => 100]
            ],
            'TYT Fizik' => [
                ['name' => 'Fizik Bilimine Giriş', 'description' => 'Fizik bilimi ve ölçme', 'order_index' => 1, 'duration_minutes' => 60],
                ['name' => 'Madde ve Özellikleri', 'description' => 'Maddenin halleri ve özellikleri', 'order_index' => 2, 'duration_minutes' => 90],
                ['name' => 'Hareket', 'description' => 'Düzgün hareket, ivmeli hareket', 'order_index' => 3, 'duration_minutes' => 120],
                ['name' => 'Kuvvet', 'description' => 'Newton yasaları, sürtünme', 'order_index' => 4, 'duration_minutes' => 150],
                ['name' => 'Enerji', 'description' => 'İş, güç, enerji türleri', 'order_index' => 5, 'duration_minutes' => 120]
            ],
            'TYT Kimya' => [
                ['name' => 'Kimya Bilimine Giriş', 'description' => 'Kimya bilimi ve madde', 'order_index' => 1, 'duration_minutes' => 60],
                ['name' => 'Atom ve Periyodik Sistem', 'description' => 'Atom yapısı, periyodik tablo', 'order_index' => 2, 'duration_minutes' => 120],
                ['name' => 'Kimyasal Türler Arası Etkileşimler', 'description' => 'Bağ türleri ve özellikleri', 'order_index' => 3, 'duration_minutes' => 150],
                ['name' => 'Maddenin Halleri', 'description' => 'Katı, sıvı, gaz halleri', 'order_index' => 4, 'duration_minutes' => 120],
                ['name' => 'Kimyasal Tepkimeler', 'description' => 'Tepkime türleri ve denkleştirme', 'order_index' => 5, 'duration_minutes' => 90]
            ],
            'TYT Biyoloji' => [
                ['name' => 'Biyoloji Bilimine Giriş', 'description' => 'Biyoloji bilimi ve canlılık', 'order_index' => 1, 'duration_minutes' => 60],
                ['name' => 'Canlıların Ortak Özellikleri', 'description' => 'Canlılığın temel özellikleri', 'order_index' => 2, 'duration_minutes' => 90],
                ['name' => 'Canlıların Sınıflandırılması', 'description' => 'Taksonomi ve sınıflandırma', 'order_index' => 3, 'duration_minutes' => 120],
                ['name' => 'Hücre', 'description' => 'Hücre yapısı ve organelleri', 'order_index' => 4, 'duration_minutes' => 150],
                ['name' => 'Canlıların Çeşitliliği', 'description' => 'Canlı grupları ve özellikleri', 'order_index' => 5, 'duration_minutes' => 120]
            ]
        ];

        return $topics[$courseName] ?? [];
    }

    private function getSubtopicsForTopic($topicName)
    {
        $subtopics = [
            'Sayılar' => [
                ['name' => 'Doğal Sayılar', 'description' => 'Doğal sayılar ve işlemler', 'order_index' => 1, 'duration_minutes' => 30],
                ['name' => 'Tam Sayılar', 'description' => 'Tam sayılar ve işlemler', 'order_index' => 2, 'duration_minutes' => 30],
                ['name' => 'Rasyonel Sayılar', 'description' => 'Rasyonel sayılar ve işlemler', 'order_index' => 3, 'duration_minutes' => 30],
                ['name' => 'Ondalık Sayılar', 'description' => 'Ondalık sayılar ve işlemler', 'order_index' => 4, 'duration_minutes' => 30],
                ['name' => 'Sayı Problemleri', 'description' => 'Sayılarla ilgili problemler', 'order_index' => 5, 'duration_minutes' => 30]
            ],
            'Cebir' => [
                ['name' => 'Denklemler', 'description' => 'Birinci ve ikinci derece denklemler', 'order_index' => 1, 'duration_minutes' => 40],
                ['name' => 'Eşitsizlikler', 'description' => 'Birinci ve ikinci derece eşitsizlikler', 'order_index' => 2, 'duration_minutes' => 40],
                ['name' => 'Fonksiyonlar', 'description' => 'Fonksiyon kavramı ve türleri', 'order_index' => 3, 'duration_minutes' => 40],
                ['name' => 'Polinomlar', 'description' => 'Polinom kavramı ve işlemler', 'order_index' => 4, 'duration_minutes' => 40],
                ['name' => 'Cebir Problemleri', 'description' => 'Cebirsel problemler', 'order_index' => 5, 'duration_minutes' => 40]
            ],
            'Geometri' => [
                ['name' => 'Temel Kavramlar', 'description' => 'Nokta, doğru, düzlem', 'order_index' => 1, 'duration_minutes' => 40],
                ['name' => 'Üçgenler', 'description' => 'Üçgen türleri ve özellikleri', 'order_index' => 2, 'duration_minutes' => 40],
                ['name' => 'Dörtgenler', 'description' => 'Dörtgen türleri ve özellikleri', 'order_index' => 3, 'duration_minutes' => 40],
                ['name' => 'Çember', 'description' => 'Çember ve daire', 'order_index' => 4, 'duration_minutes' => 40],
                ['name' => 'Geometri Problemleri', 'description' => 'Geometrik problemler', 'order_index' => 5, 'duration_minutes' => 40]
            ],
            'Veri Analizi' => [
                ['name' => 'İstatistik', 'description' => 'Merkezi eğilim ölçüleri', 'order_index' => 1, 'duration_minutes' => 30],
                ['name' => 'Grafikler', 'description' => 'Veri grafikleri ve yorumlama', 'order_index' => 2, 'duration_minutes' => 30],
                ['name' => 'Olasılık', 'description' => 'Temel olasılık kavramları', 'order_index' => 3, 'duration_minutes' => 30],
                ['name' => 'Kombinasyon', 'description' => 'Kombinasyon ve permütasyon', 'order_index' => 4, 'duration_minutes' => 30],
                ['name' => 'Veri Problemleri', 'description' => 'Veri analizi problemleri', 'order_index' => 5, 'duration_minutes' => 30]
            ],
            'Problemler' => [
                ['name' => 'Sayı Problemleri', 'description' => 'Sayılarla ilgili sözel problemler', 'order_index' => 1, 'duration_minutes' => 40],
                ['name' => 'Yaş Problemleri', 'description' => 'Yaş hesaplama problemleri', 'order_index' => 2, 'duration_minutes' => 40],
                ['name' => 'İş Problemleri', 'description' => 'İş ve işçi problemleri', 'order_index' => 3, 'duration_minutes' => 40],
                ['name' => 'Hareket Problemleri', 'description' => 'Hız ve mesafe problemleri', 'order_index' => 4, 'duration_minutes' => 40],
                ['name' => 'Karışım Problemleri', 'description' => 'Karışım hesaplama problemleri', 'order_index' => 5, 'duration_minutes' => 40]
            ]
        ];

        return $subtopics[$topicName] ?? [];
    }
}
