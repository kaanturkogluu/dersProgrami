<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Topic;
use App\Models\Subtopic;

class CourseTopicsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = Course::all();
        
        foreach ($courses as $course) {
            $this->command->info("Ders işleniyor: " . $course->name);
            
            // Her ders için 3 konu oluştur
            $topics = $this->createTopicsForCourse($course);
            
            // Her konu için 3 alt konu oluştur
            foreach ($topics as $topic) {
                $this->createSubtopicsForTopic($topic);
            }
        }
        
        $this->command->info('Tüm konular ve alt konular başarıyla oluşturuldu!');
    }
    
    private function createTopicsForCourse($course)
    {
        $topics = [];
        
        // Ders adına göre konuları belirle
        $topicData = $this->getTopicDataForCourse($course->name);
        
        foreach ($topicData as $index => $topicName) {
            $topic = Topic::firstOrCreate(
                [
                    'course_id' => $course->id,
                    'name' => $topicName
                ],
                [
                    'course_id' => $course->id,
                    'name' => $topicName,
                    'description' => $course->name . ' dersinin ' . $topicName . ' konusu',
                    'is_active' => true
                ]
            );
            $topics[] = $topic;
        }
        
        return $topics;
    }
    
    private function createSubtopicsForTopic($topic)
    {
        // Konu adına göre alt konuları belirle
        $subtopicData = $this->getSubtopicDataForTopic($topic->name);
        
        foreach ($subtopicData as $index => $subtopicName) {
            Subtopic::firstOrCreate(
                [
                    'topic_id' => $topic->id,
                    'name' => $subtopicName
                ],
                [
                    'topic_id' => $topic->id,
                    'name' => $subtopicName,
                    'description' => $topic->name . ' konusunun ' . $subtopicName . ' alt konusu',
                    'is_active' => true
                ]
            );
        }
    }
    
    private function getTopicDataForCourse($courseName)
    {
        $topicMap = [
            // TYT Dersleri
            'Matematik' => ['Sayılar', 'Cebir', 'Geometri'],
            'Türkçe' => ['Dil Bilgisi', 'Anlam Bilgisi', 'Yazım Kuralları'],
            'TYT Matematik' => ['Temel Matematik', 'Cebirsel İfadeler', 'Geometrik Şekiller'],
            'TYT Türkçe' => ['Sözcük Bilgisi', 'Cümle Bilgisi', 'Paragraf'],
            'TYT Fizik' => ['Mekanik', 'Elektrik', 'Dalgalar'],
            'TYT Kimya' => ['Atom Teorisi', 'Kimyasal Bağlar', 'Çözeltiler'],
            'TYT Biyoloji' => ['Hücre', 'Genetik', 'Ekosistem'],
            
            // AYT Dersleri
            'AYT Matematik' => ['Türev', 'İntegral', 'Limit'],
            'AYT Fizik' => ['Modern Fizik', 'Termodinamik', 'Optik'],
            'AYT Kimya' => ['Organik Kimya', 'Analitik Kimya', 'Fizikokimya'],
            'AYT Biyoloji' => ['Moleküler Biyoloji', 'Evrim', 'Biyoteknoloji'],
            'AYT Edebiyat' => ['Eski Türk Edebiyatı', 'Tanzimat Edebiyatı', 'Cumhuriyet Edebiyatı'],
            
            // KPSS Dersleri
            'Tarih' => ['Osmanlı Tarihi', 'Cumhuriyet Tarihi', 'Dünya Tarihi'],
            'KPSS Genel Kültür' => ['Türk Tarihi', 'Türk Coğrafyası', 'Vatandaşlık'],
            'KPSS Genel Yetenek' => ['Matematik', 'Türkçe', 'Mantık'],
            'KPSS Eğitim Bilimleri' => ['Öğretim Yöntemleri', 'Gelişim Psikolojisi', 'Ölçme ve Değerlendirme'],
            'KPSS Hukuk' => ['Anayasa Hukuku', 'İdare Hukuku', 'Ceza Hukuku'],
            'KPSS İktisat' => ['Mikro İktisat', 'Makro İktisat', 'Uluslararası İktisat'],
            
            // DGS Dersleri
            'DGS Sayısal' => ['Sayısal Mantık', 'Matematik', 'Geometri'],
            'DGS Sözel' => ['Sözel Mantık', 'Türkçe', 'Paragraf'],
            'DGS Geometri' => ['Düzlem Geometri', 'Uzay Geometri', 'Analitik Geometri'],
            'DGS Mantık' => ['Mantık Kuralları', 'Çıkarım', 'Akıl Yürütme'],
            'DGS Türkçe' => ['Dil Bilgisi', 'Anlam Bilgisi', 'Yazım'],
            
            // ALES Dersleri
            'ALES Sayısal' => ['Sayısal Mantık', 'Matematik', 'Geometri'],
            'ALES Sözel' => ['Sözel Mantık', 'Türkçe', 'Paragraf'],
            'ALES Geometri' => ['Düzlem Geometri', 'Uzay Geometri', 'Analitik Geometri'],
            'ALES Mantık' => ['Mantık Kuralları', 'Çıkarım', 'Akıl Yürütme'],
            'ALES Türkçe' => ['Dil Bilgisi', 'Anlam Bilgisi', 'Yazım'],
        ];
        
        return $topicMap[$courseName] ?? ['Genel Konu 1', 'Genel Konu 2', 'Genel Konu 3'];
    }
    
    private function getSubtopicDataForTopic($topicName)
    {
        $subtopicMap = [
            // Matematik Konuları
            'Sayılar' => ['Doğal Sayılar', 'Tam Sayılar', 'Rasyonel Sayılar'],
            'Cebir' => ['Denklemler', 'Eşitsizlikler', 'Fonksiyonlar'],
            'Geometri' => ['Üçgenler', 'Dörtgenler', 'Çemberler'],
            'Temel Matematik' => ['Temel İşlemler', 'Kesirler', 'Yüzdeler'],
            'Cebirsel İfadeler' => ['Polinomlar', 'Çarpanlara Ayırma', 'Rasyonel İfadeler'],
            'Geometrik Şekiller' => ['Açılar', 'Üçgenler', 'Dörtgenler'],
            'Türev' => ['Türev Tanımı', 'Türev Kuralları', 'Türev Uygulamaları'],
            'İntegral' => ['Belirsiz İntegral', 'Belirli İntegral', 'İntegral Uygulamaları'],
            'Limit' => ['Limit Tanımı', 'Limit Kuralları', 'Süreklilik'],
            'Sayısal Mantık' => ['Sayı Dizileri', 'Sayı Örüntüleri', 'Mantık Soruları'],
            'Matematik' => ['Aritmetik', 'Cebir', 'Geometri'],
            
            // Türkçe Konuları
            'Dil Bilgisi' => ['İsimler', 'Sıfatlar', 'Zamirler'],
            'Anlam Bilgisi' => ['Sözcük Anlamı', 'Cümle Anlamı', 'Paragraf Anlamı'],
            'Yazım Kuralları' => ['Büyük Harf', 'Noktalama', 'Yazım Hataları'],
            'Sözcük Bilgisi' => ['Kök ve Ek', 'Sözcük Türleri', 'Sözcük Anlamı'],
            'Cümle Bilgisi' => ['Cümle Çeşitleri', 'Cümle Öğeleri', 'Cümle Vurgusu'],
            'Paragraf' => ['Paragraf Yapısı', 'Ana Düşünce', 'Yardımcı Düşünceler'],
            'Sözel Mantık' => ['Mantık Soruları', 'Çıkarım', 'Akıl Yürütme'],
            'Türkçe' => ['Dil Bilgisi', 'Anlam Bilgisi', 'Yazım'],
            
            // Fizik Konuları
            'Mekanik' => ['Hareket', 'Kuvvet', 'Enerji'],
            'Elektrik' => ['Elektrik Alanı', 'Elektrik Akımı', 'Elektrik Devreleri'],
            'Dalgalar' => ['Dalga Hareketi', 'Ses Dalgaları', 'Işık Dalgaları'],
            'Modern Fizik' => ['Atom Teorisi', 'Kuantum Fiziği', 'Radyoaktivite'],
            'Termodinamik' => ['Sıcaklık', 'Isı', 'Entropi'],
            'Optik' => ['Işık Teorisi', 'Aynalar', 'Mercekler'],
            
            // Kimya Konuları
            'Atom Teorisi' => ['Atom Yapısı', 'Elektron Dağılımı', 'Periyodik Tablo'],
            'Kimyasal Bağlar' => ['İyonik Bağ', 'Kovalent Bağ', 'Metalik Bağ'],
            'Çözeltiler' => ['Çözelti Türleri', 'Çözünürlük', 'Konsantrasyon'],
            'Organik Kimya' => ['Hidrokarbonlar', 'Fonksiyonel Gruplar', 'Reaksiyonlar'],
            'Analitik Kimya' => ['Kalitatif Analiz', 'Kantitatif Analiz', 'Spektroskopi'],
            'Fizikokimya' => ['Termodinamik', 'Kinetik', 'Elektrokimya'],
            
            // Biyoloji Konuları
            'Hücre' => ['Hücre Yapısı', 'Hücre Organelleri', 'Hücre Bölünmesi'],
            'Genetik' => ['Kalıtım', 'Gen Mutasyonları', 'Populasyon Genetiği'],
            'Ekosistem' => ['Besin Zinciri', 'Enerji Akışı', 'Madde Döngüleri'],
            'Moleküler Biyoloji' => ['DNA Yapısı', 'Protein Sentezi', 'Enzimler'],
            'Evrim' => ['Doğal Seçilim', 'Adaptasyon', 'Türleşme'],
            'Biyoteknoloji' => ['Gen Mühendisliği', 'Klonlama', 'Biyosensörler'],
            
            // Edebiyat Konuları
            'Eski Türk Edebiyatı' => ['Divan Edebiyatı', 'Halk Edebiyatı', 'Tasavvuf Edebiyatı'],
            'Tanzimat Edebiyatı' => ['Tanzimat Dönemi', 'Servet-i Fünun', 'Fecr-i Ati'],
            'Cumhuriyet Edebiyatı' => ['Milli Edebiyat', 'Cumhuriyet Dönemi', 'Modern Edebiyat'],
            
            // Tarih Konuları
            'Osmanlı Tarihi' => ['Kuruluş Dönemi', 'Yükselme Dönemi', 'Gerileme Dönemi'],
            'Cumhuriyet Tarihi' => ['Kurtuluş Savaşı', 'Cumhuriyetin İlanı', 'Atatürk Dönemi'],
            'Dünya Tarihi' => ['Antik Çağ', 'Orta Çağ', 'Yeni Çağ'],
            'Türk Tarihi' => ['İlk Türk Devletleri', 'Selçuklu Dönemi', 'Osmanlı Dönemi'],
            'Türk Coğrafyası' => ['Fiziki Coğrafya', 'Beşeri Coğrafya', 'Ekonomik Coğrafya'],
            'Vatandaşlık' => ['Anayasa', 'Temel Haklar', 'Devlet Teşkilatı'],
            
            // Eğitim Bilimleri
            'Öğretim Yöntemleri' => ['Geleneksel Yöntemler', 'Modern Yöntemler', 'Teknoloji Destekli Öğretim'],
            'Gelişim Psikolojisi' => ['Bilişsel Gelişim', 'Sosyal Gelişim', 'Duygusal Gelişim'],
            'Ölçme ve Değerlendirme' => ['Test Türleri', 'Değerlendirme Yöntemleri', 'Not Verme'],
            
            // Hukuk Konuları
            'Anayasa Hukuku' => ['Temel İlkeler', 'Temel Haklar', 'Devlet Teşkilatı'],
            'İdare Hukuku' => ['İdari İşlemler', 'İdari Yargı', 'Kamu Hizmetleri'],
            'Ceza Hukuku' => ['Suç Teorisi', 'Ceza Türleri', 'Ceza Muhakemesi'],
            
            // İktisat Konuları
            'Mikro İktisat' => ['Talep ve Arz', 'Piyasa Dengesi', 'Tüketici Teorisi'],
            'Makro İktisat' => ['Milli Gelir', 'Enflasyon', 'İstihdam'],
            'Uluslararası İktisat' => ['Dış Ticaret', 'Ödemeler Dengesi', 'Kur Politikaları'],
            
            // Geometri Konuları
            'Düzlem Geometri' => ['Açılar', 'Üçgenler', 'Dörtgenler'],
            'Uzay Geometri' => ['Prizmalar', 'Piramitler', 'Küre'],
            'Analitik Geometri' => ['Koordinat Sistemi', 'Doğru Denklemleri', 'Çember Denklemleri'],
            
            // Mantık Konuları
            'Mantık Kuralları' => ['Önermeler', 'Çıkarım', 'Doğruluk Tabloları'],
            'Çıkarım' => ['Tümdengelim', 'Tümevarım', 'Analoji'],
            'Akıl Yürütme' => ['Problem Çözme', 'Karar Verme', 'Eleştirel Düşünme'],
        ];
        
        return $subtopicMap[$topicName] ?? ['Alt Konu 1', 'Alt Konu 2', 'Alt Konu 3'];
    }
}
