<?php

namespace App\Services;

use App\Core\SmtpClient;

class EmailService
{
    public static function send($to, $subject, $message)
    {
        $from = env('MAIL_FROM_ADDRESS', 'noreply@example.com');
        $fromName = env('MAIL_FROM_NAME', 'Example');
        $appUrl = env('APP_URL', 'http://localhost:8000');
        $appName = env('APP_NAME', 'Example');

        $logoUrl = $appUrl . '/logo.png';

        $htmlMessage = "
            <div style='font-family: sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #eee; padding: 20px;'>
                <div style='text-align: center; margin-bottom: 20px;'>
                    <img src='$logoUrl' alt='$appName' style='max-height: 50px;'>
                </div>
                <div style='line-height: 1.6; color: #333;'>
                    $message
                </div>
                <div style='margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 12px; color: #999; text-align: center;'>
                    &copy; " . date('Y') . " $appName. All rights reserved.
                </div>
            </div>
        ";

        $smtp = new SmtpClient();
        return $smtp->send($to, $subject, $htmlMessage, $from, $fromName);
    }
}