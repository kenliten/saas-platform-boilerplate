<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Database;
use App\Services\EmailService;

class NewsletterController extends BaseController
{
    public function subscribe()
    {
        $email = $_POST['email'] ?? '';
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->redirect('/?msg=invalid_email');
        }

        $db = Database::getInstance();
        $token = bin2hex(random_bytes(16));

        try {
            $db->query("INSERT INTO newsletter_subscribers (email, verification_token) VALUES (?, ?)", [$email, $token]);
            
            // Send Verification Email
            $link = env('APP_URL') . "/newsletter/verify?token=$token&email=" . urlencode($email);
            $subject = "Verify your newsletter subscription - " . env('APP_NAME');
            $message = "<h1>Verify Subscription</h1><p>Click the link below to confirm your subscription:</p><p><a href='$link'>$link</a></p>";
            
            EmailService::send($email, $subject, $message);
            
            $this->redirect('/thank-you?msg=Please check your email to verify your subscription.');
        } catch (\PDOException $e) {
            // Probably already exists
            $this->redirect('/?msg=already_subscribed');
        }
    }

    public function verify()
    {
        $email = $_GET['email'] ?? '';
        $token = $_GET['token'] ?? '';

        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM newsletter_subscribers WHERE email = ? AND verification_token = ?", [$email, $token]);
        $sub = $stmt->fetch();

        if ($sub) {
            $db->query("UPDATE newsletter_subscribers SET is_verified = 1, verification_token = NULL WHERE id = ?", [$sub['id']]);
            $this->redirect('/thank-you?msg=Subscription verified! Welcome to our newsletter.');
        } else {
            $this->redirect('/?msg=verification_failed');
        }
    }

    public function unsubscribe()
    {
        $email = $_GET['email'] ?? '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $db = Database::getInstance();
            $db->query("DELETE FROM newsletter_subscribers WHERE email = ?", [$email]);
            $this->redirect('/thank-you?msg=You have been unsubscribed.');
        }

        $this->view('newsletter/unsubscribe', ['email' => $email], null);
    }
}
