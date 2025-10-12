<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class PHPMailerService
{
    private $mailer;
    private $config;

    public function __construct()
    {
        $this->config = [
            'host' => env('MAIL_HOST', 'smtp.gmail.com'),
            'port' => (int) env('MAIL_PORT', 587),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'from_email' => env('MAIL_FROM_ADDRESS', env('MAIL_USERNAME')),
            'from_name' => env('MAIL_FROM_NAME', config('app.name')),
            'timeout' => (int) env('MAIL_TIMEOUT', 30),
            'debug' => env('MAIL_DEBUG', false),
            'charset' => env('MAIL_CHARSET', 'UTF-8'),
        ];

        $this->validateConfig();
        $this->initializeMailer();
    }

    /**
     * Konfigürasyonu doğrula
     */
    private function validateConfig()
    {
        $required = ['host', 'username', 'password', 'from_email'];
        $missing = [];

        foreach ($required as $field) {
            if (empty($this->config[$field])) {
                $missing[] = $field;
            }
        }

        if (!empty($missing)) {
            throw new \Exception('Mail konfigürasyonu eksik: ' . implode(', ', $missing) . ' alanları gerekli.');
        }

        // Port kontrolü
        if ($this->config['port'] < 1 || $this->config['port'] > 65535) {
            throw new \Exception('Geçersiz port numarası: ' . $this->config['port']);
        }

        // Email formatı kontrolü
        if (!filter_var($this->config['from_email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Geçersiz gönderen email adresi: ' . $this->config['from_email']);
        }
    }

    private function initializeMailer()
    {
        $this->mailer = new PHPMailer(true);

        try {
            // Server settings
            $this->mailer->isSMTP();
            $this->mailer->Host = $this->config['host'];
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $this->config['username'];
            $this->mailer->Password = $this->config['password'];
            $this->mailer->SMTPSecure = $this->config['encryption'];
            $this->mailer->Port = $this->config['port'];
            $this->mailer->Timeout = $this->config['timeout'];

            // Character set
            $this->mailer->CharSet = $this->config['charset'];

            // Debug mode
            if ($this->config['debug']) {
                $this->mailer->SMTPDebug = SMTP::DEBUG_SERVER;
            }

            // From
            $this->mailer->setFrom($this->config['from_email'], $this->config['from_name']);

            // Additional settings
            $this->mailer->isHTML(true);
            $this->mailer->Encoding = 'base64';

        } catch (Exception $e) {
            \Log::error('PHPMailer initialization failed: ' . $e->getMessage());
            throw new \Exception('Mail servisi başlatılamadı: ' . $e->getMessage());
        }
    }

    /**
     * Tek bir mail gönder
     */
    public function sendMail($to, $toName, $subject, $body, $isHTML = true)
    {
        try {
            // Reset mailer
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();

            // Recipients
            $this->mailer->addAddress($to, $toName);

            // Content
            $this->mailer->isHTML($isHTML);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;

            // Send
            $result = $this->mailer->send();
            
            \Log::info("Mail gönderildi: {$to} - {$subject}");
            return ['success' => true, 'message' => 'Mail başarıyla gönderildi'];

        } catch (Exception $e) {
            \Log::error("Mail gönderilemedi: {$to} - {$e->getMessage()}");
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Toplu mail gönder
     */
    public function sendBulkMail($recipients, $subject, $body, $isHTML = true)
    {
        $results = [];
        $successCount = 0;
        $errorCount = 0;

        foreach ($recipients as $recipient) {
            $result = $this->sendMail(
                $recipient['email'], 
                $recipient['name'], 
                $subject, 
                $body, 
                $isHTML
            );

            $results[] = [
                'email' => $recipient['email'],
                'name' => $recipient['name'],
                'success' => $result['success'],
                'message' => $result['message']
            ];

            if ($result['success']) {
                $successCount++;
            } else {
                $errorCount++;
            }

            // Her mail arasında kısa bekleme
            usleep(100000); // 0.1 saniye
        }

        return [
            'success' => $errorCount === 0,
            'successCount' => $successCount,
            'errorCount' => $errorCount,
            'results' => $results
        ];
    }

    /**
     * Mail konfigürasyonunu test et
     */
    public function testConnection()
    {
        try {
            $this->mailer->smtpConnect();
            $this->mailer->smtpClose();
            return ['success' => true, 'message' => 'SMTP bağlantısı başarılı'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Test maili gönder
     */
    public function sendTestMail($to, $toName = 'Test Kullanıcı')
    {
        $subject = 'Test Maili - ' . config('app.name');
        $body = $this->getTestMailBody();

        return $this->sendMail($to, $toName, $subject, $body);
    }

    /**
     * Test mail içeriği
     */
    private function getTestMailBody()
    {
        return '
        <!DOCTYPE html>
        <html lang="tr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Test Maili</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #007bff; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background-color: #f8f9fa; }
                .success { color: #28a745; font-weight: bold; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>✅ Test Maili Başarılı!</h1>
                </div>
                <div class="content">
                    <p>Merhaba,</p>
                    <p>Bu bir test mailidir. PHPMailer servisi başarıyla çalışıyor!</p>
                    <p class="success">Mail konfigürasyonunuz doğru şekilde ayarlanmış.</p>
                    <p><strong>Gönderim Zamanı:</strong> ' . now()->format('d.m.Y H:i:s') . '</p>
                    <p><strong>SMTP Host:</strong> ' . $this->config['host'] . '</p>
                    <p><strong>Port:</strong> ' . $this->config['port'] . '</p>
                    <p><strong>Şifreleme:</strong> ' . $this->config['encryption'] . '</p>
                </div>
            </div>
        </body>
        </html>';
    }

    /**
     * Mail konfigürasyon bilgilerini getir
     */
    public function getConfig()
    {
        return [
            'host' => $this->config['host'],
            'port' => $this->config['port'],
            'encryption' => $this->config['encryption'],
            'from_email' => $this->config['from_email'],
            'from_name' => $this->config['from_name'],
            'username' => $this->config['username'] ? '***' . substr($this->config['username'], -3) : 'Ayarlanmamış',
            'password' => $this->config['password'] ? '***' . str_repeat('*', strlen($this->config['password']) - 3) : 'Ayarlanmamış',
            'timeout' => $this->config['timeout'],
            'debug' => $this->config['debug'] ? 'Açık' : 'Kapalı',
            'charset' => $this->config['charset'],
            'is_configured' => !empty($this->config['host']) && !empty($this->config['username']) && !empty($this->config['password'])
        ];
    }

    /**
     * Konfigürasyon durumunu kontrol et
     */
    public function isConfigured()
    {
        try {
            $this->validateConfig();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Konfigürasyon hatalarını getir
     */
    public function getConfigErrors()
    {
        $errors = [];
        
        try {
            $this->validateConfig();
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }
        
        return $errors;
    }
}
