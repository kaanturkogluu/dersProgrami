-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 12 Eki 2025, 21:51:36
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `ogrenci`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `color` varchar(255) NOT NULL DEFAULT '#3B82F6',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `color`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'KPSS', 'Kamu Personeli Seçme Sınavı hazırlık dersleri', '#3B82F6', 1, '2025-10-10 16:09:39', '2025-10-10 16:09:39'),
(2, 'TYT', 'Temel Yeterlilik Testi hazırlık dersleri', '#10B981', 1, '2025-10-10 16:09:39', '2025-10-10 16:09:39'),
(3, 'AYT', 'Alan Yeterlilik Testi hazırlık dersleri', '#F59E0B', 1, '2025-10-10 16:09:39', '2025-10-10 16:09:39'),
(4, 'DGS', 'Dikey Geçiş Sınavı', '#EF4444', 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(5, 'ALES', 'Akademik Personel ve Lisansüstü Eğitimi Giriş Sınavı', '#8B5CF6', 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `duration_hours` int(11) NOT NULL DEFAULT 0,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `courses`
--

INSERT INTO `courses` (`id`, `name`, `description`, `category_id`, `duration_hours`, `price`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Matematik', 'Temel matematik konuları ve problem çözme teknikleri', 2, 40, 500.00, 1, '2025-10-10 16:09:39', '2025-10-10 16:09:39'),
(2, 'Türkçe', 'Dil bilgisi, anlam bilgisi ve yazım kuralları', 2, 30, 400.00, 1, '2025-10-10 16:09:39', '2025-10-10 16:09:39'),
(3, 'Tarih', 'Türk ve dünya tarihi konuları', 1, 35, 450.00, 1, '2025-10-10 16:09:39', '2025-10-10 16:09:39'),
(4, 'TYT Matematik', 'Temel matematik konuları', 2, 0, 0.00, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(5, 'TYT Türkçe', 'Türkçe dil bilgisi ve anlam bilgisi', 2, 0, 0.00, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(6, 'TYT Fizik', 'Temel fizik konuları', 2, 0, 0.00, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(7, 'TYT Kimya', 'Temel kimya konuları', 2, 0, 0.00, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(8, 'TYT Biyoloji', 'Temel biyoloji konuları', 2, 0, 0.00, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(9, 'AYT Matematik', 'İleri matematik konuları', 3, 0, 0.00, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(10, 'AYT Fizik', 'İleri fizik konuları', 3, 0, 0.00, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(11, 'AYT Kimya', 'İleri kimya konuları', 3, 0, 0.00, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(12, 'AYT Biyoloji', 'İleri biyoloji konuları', 3, 0, 0.00, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(13, 'AYT Edebiyat', 'Türk edebiyatı ve dil anlatım', 3, 0, 0.00, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(14, 'KPSS Genel Kültür', 'Tarih, coğrafya, vatandaşlık', 1, 0, 0.00, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(15, 'KPSS Genel Yetenek', 'Matematik, Türkçe, mantık', 1, 0, 0.00, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(16, 'KPSS Eğitim Bilimleri', 'Öğretim yöntemleri ve psikoloji', 1, 0, 0.00, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(17, 'KPSS Hukuk', 'Anayasa, idare, ceza hukuku', 1, 0, 0.00, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(18, 'KPSS İktisat', 'Mikro ve makro iktisat', 1, 0, 0.00, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(19, 'DGS Sayısal', 'Matematik ve geometri', 4, 0, 0.00, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(20, 'DGS Sözel', 'Türkçe ve mantık', 4, 0, 0.00, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(21, 'DGS Geometri', 'Geometri konuları', 4, 0, 0.00, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(22, 'DGS Mantık', 'Mantık ve akıl yürütme', 4, 0, 0.00, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(23, 'DGS Türkçe', 'Dil bilgisi ve anlam bilgisi', 4, 0, 0.00, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(24, 'ALES Sayısal', 'Matematik ve geometri', 5, 0, 0.00, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(25, 'ALES Sözel', 'Türkçe ve mantık', 5, 0, 0.00, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(26, 'ALES Geometri', 'Geometri konuları', 5, 0, 0.00, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(27, 'ALES Mantık', 'Mantık ve akıl yürütme', 5, 0, 0.00, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(28, 'ALES Türkçe', 'Dil bilgisi ve anlam bilgisi', 5, 0, 0.00, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `daily_lesson_tracking`
--

CREATE TABLE `daily_lesson_tracking` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `schedule_item_id` bigint(20) UNSIGNED NOT NULL,
  `tracking_date` date NOT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `study_duration_minutes` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `difficulty_level` enum('kolay','orta','zor') DEFAULT NULL,
  `understanding_score` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `daily_lesson_tracking`
--

INSERT INTO `daily_lesson_tracking` (`id`, `student_id`, `schedule_item_id`, `tracking_date`, `is_completed`, `study_duration_minutes`, `notes`, `difficulty_level`, `understanding_score`, `created_at`, `updated_at`) VALUES
(1, 3, 43, '2025-10-12', 1, NULL, NULL, NULL, NULL, '2025-10-12 16:48:59', '2025-10-12 16:49:02');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(11) NOT NULL,
  `created_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_10_10_190146_create_categories_table', 2),
(5, '2025_10_10_190150_create_students_table', 2),
(6, '2025_10_10_190153_create_courses_table', 2),
(7, '2025_10_10_190156_create_topics_table', 2),
(8, '2025_10_10_190159_create_subtopics_table', 2),
(9, '2025_10_11_115149_create_student_schedules_table', 3),
(10, '2025_10_11_115202_create_schedule_items_table', 3),
(11, '2025_10_11_122820_add_subtopic_id_to_schedule_items_table', 4),
(12, '2025_10_11_135829_remove_time_columns_from_schedule_items_table', 5),
(13, '2025_10_11_162619_add_optional_time_columns_to_schedule_items_table', 6),
(14, '2025_10_11_173844_remove_time_columns_from_schedule_items_table_v2', 7),
(16, '2025_10_11_175808_update_area_column_to_json_in_student_schedules_table', 8),
(17, '2025_10_12_191150_add_password_to_students_table', 9);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `schedule_items`
--

CREATE TABLE `schedule_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `schedule_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `topic_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subtopic_id` bigint(20) UNSIGNED DEFAULT NULL,
  `day_of_week` enum('monday','tuesday','wednesday','thursday','friday','saturday','sunday') NOT NULL,
  `notes` text DEFAULT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `scheduled_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `schedule_items`
--

INSERT INTO `schedule_items` (`id`, `schedule_id`, `course_id`, `topic_id`, `subtopic_id`, `day_of_week`, `notes`, `is_completed`, `scheduled_date`, `created_at`, `updated_at`) VALUES
(1, 1, 4, NULL, NULL, 'monday', NULL, 0, NULL, '2025-10-11 09:20:36', '2025-10-11 09:20:36'),
(2, 2, 1, NULL, NULL, 'monday', NULL, 0, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(3, 2, 1, 1, 1, 'tuesday', NULL, 1, NULL, '2025-10-11 14:43:13', '2025-10-12 15:53:14'),
(4, 2, 1, NULL, NULL, 'wednesday', NULL, 0, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(5, 2, 1, NULL, NULL, 'thursday', NULL, 0, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(6, 2, 1, NULL, NULL, 'friday', NULL, 0, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(7, 2, 2, NULL, NULL, 'monday', NULL, 0, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(8, 2, 2, NULL, NULL, 'tuesday', NULL, 0, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(9, 2, 2, NULL, NULL, 'wednesday', NULL, 0, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(10, 2, 2, NULL, NULL, 'thursday', NULL, 0, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(11, 2, 2, NULL, NULL, 'friday', NULL, 0, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(12, 2, 4, NULL, NULL, 'monday', NULL, 0, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(13, 2, 4, NULL, NULL, 'tuesday', NULL, 0, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(14, 2, 4, NULL, NULL, 'wednesday', NULL, 0, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(15, 2, 4, NULL, NULL, 'thursday', NULL, 0, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(16, 2, 4, NULL, NULL, 'friday', NULL, 0, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(17, 2, 5, NULL, NULL, 'monday', NULL, 0, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(18, 2, 5, NULL, NULL, 'tuesday', NULL, 0, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(19, 2, 5, NULL, NULL, 'wednesday', NULL, 0, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(20, 2, 5, NULL, NULL, 'thursday', NULL, 0, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(21, 2, 5, NULL, NULL, 'friday', NULL, 0, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(22, 2, 6, 17, NULL, 'monday', NULL, 1, NULL, '2025-10-11 14:43:13', '2025-10-12 15:58:38'),
(23, 2, 6, NULL, NULL, 'tuesday', NULL, 0, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(24, 2, 6, NULL, NULL, 'wednesday', NULL, 0, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(25, 2, 6, NULL, NULL, 'thursday', NULL, 0, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(26, 2, 6, NULL, NULL, 'friday', NULL, 0, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(27, 2, 7, NULL, NULL, 'monday', NULL, 0, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(28, 2, 7, NULL, NULL, 'tuesday', NULL, 0, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(29, 2, 7, NULL, NULL, 'wednesday', NULL, 0, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(30, 2, 7, NULL, NULL, 'thursday', NULL, 0, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(31, 2, 7, NULL, NULL, 'friday', NULL, 0, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(32, 2, 8, NULL, NULL, 'monday', NULL, 0, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(33, 2, 8, NULL, NULL, 'tuesday', NULL, 0, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(34, 2, 8, NULL, NULL, 'wednesday', NULL, 0, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(35, 2, 8, NULL, NULL, 'thursday', NULL, 0, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(36, 2, 8, NULL, NULL, 'friday', NULL, 0, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(37, 3, 4, NULL, NULL, 'monday', NULL, 0, NULL, '2025-10-12 15:59:12', '2025-10-12 15:59:12'),
(38, 3, 5, NULL, NULL, 'monday', NULL, 0, NULL, '2025-10-12 15:59:12', '2025-10-12 15:59:12'),
(39, 3, 6, NULL, NULL, 'monday', NULL, 0, NULL, '2025-10-12 15:59:12', '2025-10-12 15:59:12'),
(40, 3, 7, NULL, NULL, 'monday', NULL, 0, NULL, '2025-10-12 15:59:12', '2025-10-12 15:59:12'),
(41, 4, 1, 2, 29, 'monday', NULL, 0, NULL, '2025-10-12 16:28:34', '2025-10-12 16:28:34'),
(42, 4, 2, 4, 39, 'monday', NULL, 0, NULL, '2025-10-12 16:28:34', '2025-10-12 16:28:34'),
(43, 4, 1, 2, 29, 'sunday', NULL, 0, NULL, '2025-10-12 16:48:31', '2025-10-12 16:48:31');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('5twVA5uhbtLQrCqaxVW7mfZ5xXFjPk31NCbxztgn', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiRkNJbHlRVDVoazZ1Qk84S2x0QTJUNlY4Mk9wbEdXWnJ6TWd6QUVBQSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NjA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zdHVkZW50L2RhaWx5LXRyYWNraW5nP2RhdGU9MjAyNS0xMC0xMiI7fXM6MTA6InN0dWRlbnRfaWQiO2k6MztzOjEyOiJzdHVkZW50X25hbWUiO3M6MTE6Ik1laG1ldCBLYXlhIjt9', 1760298542),
('rwVw1pGQcwlTEEhotqDclW7DlJhDaevbVa4h1rzG', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZVBKMG9jeVE0QWJ1MkxYR2t5TmRFRU9MRGdMc01haFI2cXFmUWxuYSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kYWlseS1yZXBvcnRzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1760298547);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `student_number` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `students`
--

INSERT INTO `students` (`id`, `first_name`, `last_name`, `email`, `password`, `phone`, `birth_date`, `student_number`, `address`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Ahmet', 'Yılmaz', 'ahmet.yilmaz@example.com', '$2y$12$bfgxvGLIK72hCoe0WrLYD.bRCcbdFZCaG4yNbXKQqf4HVpQOS4Hq6', '0555 123 45 67', '1995-03-15', '2024001', 'İstanbul, Türkiye', 1, '2025-10-10 16:09:39', '2025-10-12 16:12:10'),
(2, 'Ayşe', 'Demir', 'ayse.demir@example.com', '$2y$12$UAQPq0xT/q0XIJrkopYqHu2AwuIIL1A69pvL368HcvVfyIf6n/Qfy', '0555 234 56 78', '1998-07-22', '2024002', 'Ankara, Türkiye', 1, '2025-10-10 16:09:39', '2025-10-12 16:12:10'),
(3, 'Mehmet', 'Kaya', 'mehmet.kaya@example.com', '$2y$12$0HQn4uA935OE2V6f127lKebtiFknWtZ8aAXQN1ddDEc1cbgZvB1qi', '0534 345 67 89', '2001-03-10', 'STU003', 'İzmir, Türkiye', 1, '2025-10-11 08:58:23', '2025-10-12 16:12:10'),
(4, 'Fatma', 'Özkan', 'fatma.ozkan@example.com', '$2y$12$gBCIURCYYOFnTE2MWvUvSOiW.iXweV8NK39/OuBzS9DPwpHsEYeXG', '0535 456 78 90', '2000-12-05', 'STU004', 'Bursa, Türkiye', 1, '2025-10-11 08:58:23', '2025-10-12 16:12:10'),
(5, 'Ali', 'Çelik', 'ali.celik@example.com', '$2y$12$cm1YDeQuwan/682RpfdmXek.v822msIBaEWnAF2jsAJVYyHSCE3wS', '0536 567 89 01', '1998-07-18', 'STU005', 'Antalya, Türkiye', 1, '2025-10-11 08:58:23', '2025-10-12 16:12:10');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `student_schedules`
--

CREATE TABLE `student_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `areas` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`areas`)),
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `schedule_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`schedule_data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `student_schedules`
--

INSERT INTO `student_schedules` (`id`, `student_id`, `name`, `areas`, `start_date`, `end_date`, `is_active`, `description`, `schedule_data`, `created_at`, `updated_at`) VALUES
(1, 1, 'Deneme', '["TYT"]', '2025-10-11', '2025-10-18', 1, NULL, NULL, '2025-10-11 09:20:36', '2025-10-11 09:20:36'),
(2, 1, 'Deneme', '["TYT"]', '2025-10-11', '2026-01-11', 1, NULL, NULL, '2025-10-11 14:43:13', '2025-10-11 14:43:13'),
(3, 2, 'Deneme', '["TYT"]', '2025-10-12', '2026-01-12', 1, NULL, NULL, '2025-10-12 15:59:12', '2025-10-12 15:59:12'),
(4, 3, 'Deneme', '["TYT"]', '2025-10-12', '2026-01-12', 1, NULL, NULL, '2025-10-12 16:28:34', '2025-10-12 16:28:34');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `subtopics`
--

CREATE TABLE `subtopics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `topic_id` bigint(20) UNSIGNED NOT NULL,
  `order_index` int(11) NOT NULL DEFAULT 0,
  `duration_minutes` int(11) NOT NULL DEFAULT 0,
  `content` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `subtopics`
--

INSERT INTO `subtopics` (`id`, `name`, `description`, `topic_id`, `order_index`, `duration_minutes`, `content`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Doğal Sayılar', 'Doğal sayıların özellikleri ve işlemler', 1, 1, 60, 'Doğal sayılar 0, 1, 2, 3, ... şeklinde devam eden sayılardır.', 1, '2025-10-10 16:09:39', '2025-10-10 16:09:39'),
(2, 'Tam Sayılar', 'Pozitif ve negatif tam sayılar', 1, 2, 60, 'Tam sayılar pozitif ve negatif sayıları içerir.', 1, '2025-10-10 16:09:39', '2025-10-10 16:09:39'),
(28, 'Rasyonel Sayılar', 'Sayılar konusunun Rasyonel Sayılar alt konusu', 1, 0, 0, NULL, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(29, 'Denklemler', 'Cebir konusunun Denklemler alt konusu', 2, 0, 0, NULL, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(30, 'Eşitsizlikler', 'Cebir konusunun Eşitsizlikler alt konusu', 2, 0, 0, NULL, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(31, 'Fonksiyonlar', 'Cebir konusunun Fonksiyonlar alt konusu', 2, 0, 0, NULL, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(32, 'Üçgenler', 'Geometri konusunun Üçgenler alt konusu', 32, 0, 0, NULL, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(33, 'Dörtgenler', 'Geometri konusunun Dörtgenler alt konusu', 32, 0, 0, NULL, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(34, 'Çemberler', 'Geometri konusunun Çemberler alt konusu', 32, 0, 0, NULL, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(35, 'İsimler', 'Dil Bilgisi konusunun İsimler alt konusu', 3, 0, 0, NULL, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(36, 'Sıfatlar', 'Dil Bilgisi konusunun Sıfatlar alt konusu', 3, 0, 0, NULL, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(37, 'Zamirler', 'Dil Bilgisi konusunun Zamirler alt konusu', 3, 0, 0, NULL, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(38, 'Sözcük Anlamı', 'Anlam Bilgisi konusunun Sözcük Anlamı alt konusu', 4, 0, 0, NULL, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(39, 'Cümle Anlamı', 'Anlam Bilgisi konusunun Cümle Anlamı alt konusu', 4, 0, 0, NULL, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(40, 'Paragraf Anlamı', 'Anlam Bilgisi konusunun Paragraf Anlamı alt konusu', 4, 0, 0, NULL, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `topics`
--

CREATE TABLE `topics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `order_index` int(11) NOT NULL DEFAULT 0,
  `duration_minutes` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `topics`
--

INSERT INTO `topics` (`id`, `name`, `description`, `course_id`, `order_index`, `duration_minutes`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Sayılar', 'Doğal sayılar, tam sayılar, rasyonel sayılar', 1, 1, 120, 1, '2025-10-10 16:09:39', '2025-10-10 16:09:39'),
(2, 'Cebir', 'Denklemler, eşitsizlikler ve fonksiyonlar', 1, 2, 150, 1, '2025-10-10 16:09:39', '2025-10-10 16:09:39'),
(3, 'Dil Bilgisi', 'İsim, sıfat, zamir, fiil gibi sözcük türleri', 2, 1, 90, 1, '2025-10-10 16:09:39', '2025-10-10 16:09:39'),
(4, 'Anlam Bilgisi', 'Sözcük anlamı, cümle anlamı, paragraf anlamı', 2, 2, 90, 1, '2025-10-10 16:09:39', '2025-10-10 16:09:39'),
(5, 'Osmanlı Tarihi', 'Osmanlı Devleti\'nin kuruluşu, yükselişi ve çöküşü', 3, 1, 120, 1, '2025-10-10 16:09:39', '2025-10-10 16:09:39'),
(6, 'Cumhuriyet Tarihi', 'Türkiye Cumhuriyeti\'nin kuruluşu ve gelişimi', 3, 2, 90, 1, '2025-10-10 16:09:39', '2025-10-10 16:09:39'),
(7, 'Sayılar', 'Doğal sayılar, tam sayılar, rasyonel sayılar', 4, 1, 120, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(8, 'Cebir', 'Denklemler, eşitsizlikler, fonksiyonlar', 4, 2, 150, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(9, 'Geometri', 'Temel geometrik şekiller ve özellikleri', 4, 3, 180, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(10, 'Veri Analizi', 'İstatistik ve olasılık', 4, 4, 90, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(11, 'Problemler', 'Sözel problemler ve çözüm teknikleri', 4, 5, 200, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(12, 'Dil Bilgisi', 'Kelime türleri, cümle bilgisi', 5, 1, 120, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(13, 'Anlam Bilgisi', 'Kelimede anlam, cümlede anlam', 5, 2, 150, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(14, 'Paragraf', 'Paragraf yapısı ve anlam', 5, 3, 180, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(15, 'Yazım Kuralları', 'Noktalama ve yazım kuralları', 5, 4, 90, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(16, 'Anlatım Bozuklukları', 'Anlatım bozukluğu türleri', 5, 5, 100, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(17, 'Fizik Bilimine Giriş', 'Fizik bilimi ve ölçme', 6, 1, 60, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(18, 'Madde ve Özellikleri', 'Maddenin halleri ve özellikleri', 6, 2, 90, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(19, 'Hareket', 'Düzgün hareket, ivmeli hareket', 6, 3, 120, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(20, 'Kuvvet', 'Newton yasaları, sürtünme', 6, 4, 150, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(21, 'Enerji', 'İş, güç, enerji türleri', 6, 5, 120, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(22, 'Kimya Bilimine Giriş', 'Kimya bilimi ve madde', 7, 1, 60, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(23, 'Atom ve Periyodik Sistem', 'Atom yapısı, periyodik tablo', 7, 2, 120, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(24, 'Kimyasal Türler Arası Etkileşimler', 'Bağ türleri ve özellikleri', 7, 3, 150, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(25, 'Maddenin Halleri', 'Katı, sıvı, gaz halleri', 7, 4, 120, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(26, 'Kimyasal Tepkimeler', 'Tepkime türleri ve denkleştirme', 7, 5, 90, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(27, 'Biyoloji Bilimine Giriş', 'Biyoloji bilimi ve canlılık', 8, 1, 60, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(28, 'Canlıların Ortak Özellikleri', 'Canlılığın temel özellikleri', 8, 2, 90, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(29, 'Canlıların Sınıflandırılması', 'Taksonomi ve sınıflandırma', 8, 3, 120, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(30, 'Hücre', 'Hücre yapısı ve organelleri', 8, 4, 150, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(31, 'Canlıların Çeşitliliği', 'Canlı grupları ve özellikleri', 8, 5, 120, 1, '2025-10-11 08:58:00', '2025-10-11 08:58:00'),
(32, 'Geometri', 'Matematik dersinin Geometri konusu', 1, 0, 0, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(33, 'Yazım Kuralları', 'Türkçe dersinin Yazım Kuralları konusu', 2, 0, 0, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(34, 'Dünya Tarihi', 'Tarih dersinin Dünya Tarihi konusu', 3, 0, 0, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(35, 'Temel Matematik', 'TYT Matematik dersinin Temel Matematik konusu', 4, 0, 0, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(36, 'Cebirsel İfadeler', 'TYT Matematik dersinin Cebirsel İfadeler konusu', 4, 0, 0, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(37, 'Geometrik Şekiller', 'TYT Matematik dersinin Geometrik Şekiller konusu', 4, 0, 0, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(38, 'Sözcük Bilgisi', 'TYT Türkçe dersinin Sözcük Bilgisi konusu', 5, 0, 0, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(39, 'Cümle Bilgisi', 'TYT Türkçe dersinin Cümle Bilgisi konusu', 5, 0, 0, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(40, 'Mekanik', 'TYT Fizik dersinin Mekanik konusu', 6, 0, 0, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(41, 'Elektrik', 'TYT Fizik dersinin Elektrik konusu', 6, 0, 0, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(42, 'Dalgalar', 'TYT Fizik dersinin Dalgalar konusu', 6, 0, 0, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(43, 'Atom Teorisi', 'TYT Kimya dersinin Atom Teorisi konusu', 7, 0, 0, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(44, 'Kimyasal Bağlar', 'TYT Kimya dersinin Kimyasal Bağlar konusu', 7, 0, 0, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(45, 'Çözeltiler', 'TYT Kimya dersinin Çözeltiler konusu', 7, 0, 0, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(46, 'Genetik', 'TYT Biyoloji dersinin Genetik konusu', 8, 0, 0, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(47, 'Ekosistem', 'TYT Biyoloji dersinin Ekosistem konusu', 8, 0, 0, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(48, 'Türev', 'AYT Matematik dersinin Türev konusu', 9, 0, 0, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(49, 'İntegral', 'AYT Matematik dersinin İntegral konusu', 9, 0, 0, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(50, 'Limit', 'AYT Matematik dersinin Limit konusu', 9, 0, 0, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(51, 'Modern Fizik', 'AYT Fizik dersinin Modern Fizik konusu', 10, 0, 0, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(52, 'Termodinamik', 'AYT Fizik dersinin Termodinamik konusu', 10, 0, 0, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(53, 'Optik', 'AYT Fizik dersinin Optik konusu', 10, 0, 0, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(54, 'Organik Kimya', 'AYT Kimya dersinin Organik Kimya konusu', 11, 0, 0, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(55, 'Analitik Kimya', 'AYT Kimya dersinin Analitik Kimya konusu', 11, 0, 0, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(56, 'Fizikokimya', 'AYT Kimya dersinin Fizikokimya konusu', 11, 0, 0, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(57, 'Moleküler Biyoloji', 'AYT Biyoloji dersinin Moleküler Biyoloji konusu', 12, 0, 0, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(58, 'Evrim', 'AYT Biyoloji dersinin Evrim konusu', 12, 0, 0, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(59, 'Biyoteknoloji', 'AYT Biyoloji dersinin Biyoteknoloji konusu', 12, 0, 0, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(60, 'Eski Türk Edebiyatı', 'AYT Edebiyat dersinin Eski Türk Edebiyatı konusu', 13, 0, 0, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(61, 'Tanzimat Edebiyatı', 'AYT Edebiyat dersinin Tanzimat Edebiyatı konusu', 13, 0, 0, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(62, 'Cumhuriyet Edebiyatı', 'AYT Edebiyat dersinin Cumhuriyet Edebiyatı konusu', 13, 0, 0, 1, '2025-10-12 16:18:57', '2025-10-12 16:18:57'),
(63, 'Türk Tarihi', 'KPSS Genel Kültür dersinin Türk Tarihi konusu', 14, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(64, 'Türk Coğrafyası', 'KPSS Genel Kültür dersinin Türk Coğrafyası konusu', 14, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(65, 'Vatandaşlık', 'KPSS Genel Kültür dersinin Vatandaşlık konusu', 14, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(66, 'Matematik', 'KPSS Genel Yetenek dersinin Matematik konusu', 15, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(67, 'Türkçe', 'KPSS Genel Yetenek dersinin Türkçe konusu', 15, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(68, 'Mantık', 'KPSS Genel Yetenek dersinin Mantık konusu', 15, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(69, 'Öğretim Yöntemleri', 'KPSS Eğitim Bilimleri dersinin Öğretim Yöntemleri konusu', 16, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(70, 'Gelişim Psikolojisi', 'KPSS Eğitim Bilimleri dersinin Gelişim Psikolojisi konusu', 16, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(71, 'Ölçme ve Değerlendirme', 'KPSS Eğitim Bilimleri dersinin Ölçme ve Değerlendirme konusu', 16, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(72, 'Anayasa Hukuku', 'KPSS Hukuk dersinin Anayasa Hukuku konusu', 17, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(73, 'İdare Hukuku', 'KPSS Hukuk dersinin İdare Hukuku konusu', 17, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(74, 'Ceza Hukuku', 'KPSS Hukuk dersinin Ceza Hukuku konusu', 17, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(75, 'Mikro İktisat', 'KPSS İktisat dersinin Mikro İktisat konusu', 18, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(76, 'Makro İktisat', 'KPSS İktisat dersinin Makro İktisat konusu', 18, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(77, 'Uluslararası İktisat', 'KPSS İktisat dersinin Uluslararası İktisat konusu', 18, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(78, 'Sayısal Mantık', 'DGS Sayısal dersinin Sayısal Mantık konusu', 19, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(79, 'Matematik', 'DGS Sayısal dersinin Matematik konusu', 19, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(80, 'Geometri', 'DGS Sayısal dersinin Geometri konusu', 19, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(81, 'Sözel Mantık', 'DGS Sözel dersinin Sözel Mantık konusu', 20, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(82, 'Türkçe', 'DGS Sözel dersinin Türkçe konusu', 20, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(83, 'Paragraf', 'DGS Sözel dersinin Paragraf konusu', 20, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(84, 'Düzlem Geometri', 'DGS Geometri dersinin Düzlem Geometri konusu', 21, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(85, 'Uzay Geometri', 'DGS Geometri dersinin Uzay Geometri konusu', 21, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(86, 'Analitik Geometri', 'DGS Geometri dersinin Analitik Geometri konusu', 21, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(87, 'Mantık Kuralları', 'DGS Mantık dersinin Mantık Kuralları konusu', 22, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(88, 'Çıkarım', 'DGS Mantık dersinin Çıkarım konusu', 22, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(89, 'Akıl Yürütme', 'DGS Mantık dersinin Akıl Yürütme konusu', 22, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(90, 'Dil Bilgisi', 'DGS Türkçe dersinin Dil Bilgisi konusu', 23, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(91, 'Anlam Bilgisi', 'DGS Türkçe dersinin Anlam Bilgisi konusu', 23, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(92, 'Yazım', 'DGS Türkçe dersinin Yazım konusu', 23, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(93, 'Sayısal Mantık', 'ALES Sayısal dersinin Sayısal Mantık konusu', 24, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(94, 'Matematik', 'ALES Sayısal dersinin Matematik konusu', 24, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(95, 'Geometri', 'ALES Sayısal dersinin Geometri konusu', 24, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(96, 'Sözel Mantık', 'ALES Sözel dersinin Sözel Mantık konusu', 25, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(97, 'Türkçe', 'ALES Sözel dersinin Türkçe konusu', 25, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(98, 'Paragraf', 'ALES Sözel dersinin Paragraf konusu', 25, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(99, 'Düzlem Geometri', 'ALES Geometri dersinin Düzlem Geometri konusu', 26, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(100, 'Uzay Geometri', 'ALES Geometri dersinin Uzay Geometri konusu', 26, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(101, 'Analitik Geometri', 'ALES Geometri dersinin Analitik Geometri konusu', 26, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(102, 'Mantık Kuralları', 'ALES Mantık dersinin Mantık Kuralları konusu', 27, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(103, 'Çıkarım', 'ALES Mantık dersinin Çıkarım konusu', 27, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(104, 'Akıl Yürütme', 'ALES Mantık dersinin Akıl Yürütme konusu', 27, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(105, 'Dil Bilgisi', 'ALES Türkçe dersinin Dil Bilgisi konusu', 28, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(106, 'Anlam Bilgisi', 'ALES Türkçe dersinin Anlam Bilgisi konusu', 28, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58'),
(107, 'Yazım', 'ALES Türkçe dersinin Yazım konusu', 28, 0, 0, 1, '2025-10-12 16:18:58', '2025-10-12 16:18:58');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@ogrenci.com', '2025-10-12 16:13:54', '$2y$12$1IM52Ym7MHuxBP51wr1YOeC/5YdVfOsog7Rwy3NRkkQ9aXXnJ.Vd.', NULL, '2025-10-12 16:13:54', '2025-10-12 16:13:54');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Tablo için indeksler `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Tablo için indeksler `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `courses_category_id_foreign` (`category_id`);

--
-- Tablo için indeksler `daily_lesson_tracking`
--
ALTER TABLE `daily_lesson_tracking`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `daily_tracking_unique` (`student_id`,`schedule_item_id`,`tracking_date`),
  ADD KEY `daily_lesson_tracking_student_id_tracking_date_index` (`student_id`,`tracking_date`),
  ADD KEY `daily_lesson_tracking_schedule_item_id_tracking_date_index` (`schedule_item_id`,`tracking_date`),
  ADD KEY `daily_tracking_student_date` (`student_id`,`tracking_date`),
  ADD KEY `daily_tracking_item_date` (`schedule_item_id`,`tracking_date`);

--
-- Tablo için indeksler `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Tablo için indeksler `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Tablo için indeksler `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Tablo için indeksler `schedule_items`
--
ALTER TABLE `schedule_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedule_items_course_id_foreign` (`course_id`),
  ADD KEY `schedule_items_topic_id_foreign` (`topic_id`),
  ADD KEY `schedule_items_schedule_id_day_of_week_index` (`schedule_id`,`day_of_week`),
  ADD KEY `schedule_items_schedule_id_scheduled_date_index` (`schedule_id`,`scheduled_date`),
  ADD KEY `schedule_items_subtopic_id_foreign` (`subtopic_id`);

--
-- Tablo için indeksler `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Tablo için indeksler `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `students_email_unique` (`email`),
  ADD UNIQUE KEY `students_student_number_unique` (`student_number`);

--
-- Tablo için indeksler `student_schedules`
--
ALTER TABLE `student_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_schedules_student_id_area_index` (`student_id`),
  ADD KEY `student_schedules_student_id_is_active_index` (`student_id`,`is_active`);

--
-- Tablo için indeksler `subtopics`
--
ALTER TABLE `subtopics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subtopics_topic_id_foreign` (`topic_id`);

--
-- Tablo için indeksler `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `topics_course_id_foreign` (`course_id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Tablo için AUTO_INCREMENT değeri `daily_lesson_tracking`
--
ALTER TABLE `daily_lesson_tracking`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Tablo için AUTO_INCREMENT değeri `schedule_items`
--
ALTER TABLE `schedule_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- Tablo için AUTO_INCREMENT değeri `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `student_schedules`
--
ALTER TABLE `student_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `subtopics`
--
ALTER TABLE `subtopics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=278;

--
-- Tablo için AUTO_INCREMENT değeri `topics`
--
ALTER TABLE `topics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
