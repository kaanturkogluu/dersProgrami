<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin kullanıcısı oluştur
        User::firstOrCreate(
            ['email' => 'admin@ogrenci.com'],
            [
                'name' => 'Admin',
                'email' => 'admin@ogrenci.com',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin kullanıcısı oluşturuldu!');
        $this->command->info('E-posta: admin@ogrenci.com');
        $this->command->info('Şifre: admin123');
    }
}
