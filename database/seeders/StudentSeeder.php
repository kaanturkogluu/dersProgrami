<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = [
            [
                'first_name' => 'Ahmet',
                'last_name' => 'Yılmaz',
                'email' => 'ahmet.yilmaz@example.com',
                'phone' => '0532 123 45 67',
                'birth_date' => '2000-05-15',
                'student_number' => 'STU001',
                'address' => 'İstanbul, Türkiye',
                'is_active' => true
            ],
            [
                'first_name' => 'Ayşe',
                'last_name' => 'Demir',
                'email' => 'ayse.demir@example.com',
                'phone' => '0533 234 56 78',
                'birth_date' => '1999-08-22',
                'student_number' => 'STU002',
                'address' => 'Ankara, Türkiye',
                'is_active' => true
            ],
            [
                'first_name' => 'Mehmet',
                'last_name' => 'Kaya',
                'email' => 'mehmet.kaya@example.com',
                'phone' => '0534 345 67 89',
                'birth_date' => '2001-03-10',
                'student_number' => 'STU003',
                'address' => 'İzmir, Türkiye',
                'is_active' => true
            ],
            [
                'first_name' => 'Fatma',
                'last_name' => 'Özkan',
                'email' => 'fatma.ozkan@example.com',
                'phone' => '0535 456 78 90',
                'birth_date' => '2000-12-05',
                'student_number' => 'STU004',
                'address' => 'Bursa, Türkiye',
                'is_active' => true
            ],
            [
                'first_name' => 'Ali',
                'last_name' => 'Çelik',
                'email' => 'ali.celik@example.com',
                'phone' => '0536 567 89 01',
                'birth_date' => '1998-07-18',
                'student_number' => 'STU005',
                'address' => 'Antalya, Türkiye',
                'is_active' => true
            ]
        ];

        foreach ($students as $studentData) {
            Student::firstOrCreate(
                ['email' => $studentData['email']],
                $studentData
            );
        }

        $this->command->info('Students created successfully!');
    }
}
