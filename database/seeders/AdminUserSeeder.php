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
        // Production'da environment variable'dan admin bilgilerini al
        $adminEmail = env('ADMIN_EMAIL', 'admin@example.com');
        $adminPassword = env('ADMIN_PASSWORD');
        
        if (!$adminPassword) {
            $this->command->error('ADMIN_PASSWORD environment variable ayarlanmamış!');
            return;
        }
        
        // Admin kullanıcısı oluştur
        User::firstOrCreate(
            ['email' => $adminEmail],
            [
                'name' => env('ADMIN_NAME', 'Admin'),
                'email' => $adminEmail,
                'password' => Hash::make($adminPassword),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin kullanıcısı oluşturuldu.');
        // Production'da hassas bilgileri yazdırma
        if (config('app.debug')) {
            $this->command->info('E-posta: ' . $adminEmail);
        }
    }
}
