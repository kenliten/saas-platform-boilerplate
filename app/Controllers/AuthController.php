<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Session;
use App\Core\Database;
use App\Models\User;
use App\Services\EmailService;

class AuthController extends BaseController
{
    public function login()
    {
        if (Session::has('user_id')) {
            header('Location: /dashboard');
            exit;
        }
        return $this->view('auth/login', ['title' => __('login_title')], 'guest');
    }

    public function register()
    {
        if (Session::has('user_id')) {
            header('Location: /dashboard');
            exit;
        }
        return $this->view('auth/register', ['title' => __('register_title')], 'guest');
    }

    public function store()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $db = Database::getConnection();

        $stmt = $db->prepare("INSERT INTO accounts (name, plan) VALUES (?, ?)");
        $stmt->execute(['Personal Account', 'free']);
        $accountId = $db->lastInsertId();

        $hashed = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $db->prepare("INSERT INTO users (account_id, email, password_hash, is_active, role) VALUES (?, ?, ?, 1, 'user')");
            $stmt->execute([$accountId, $email, $hashed]);

            $welcomeMsg = "<div>" . __('onboarding_msg') . "</div>";
            EmailService::send($email, __('welcome_to') . ' ' . env('APP_NAME'), $welcomeMsg);
            $userId = $db->lastInsertId();
            Session::set('user_id', $userId);
            Session::set('role', 'user');
            header('Location: /dashboard');
            exit;
        } catch (\PDOException $e) {
            $this->view('auth/register', ['error' => __('email_registered')], 'guest');
        }
    }

    public function forgotPassword()
    {
        if (Session::has('user_id')) {
            header('Location: /dashboard');
            exit;
        }
        $this->view('auth/forgot-password', ['title' => __('forgot_password_title')], 'guest');
    }

    public function sendResetLink()
    {
        $email = $_POST['email'];
        $token = bin2hex(random_bytes(16));
        $db = Database::getConnection();

        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            $this->view('auth/forgot-password', ['error' => 'Email not found'], 'guest');
        } else {
            $stmt = $db->prepare("INSERT INTO password_resets (email, token) VALUES (?, ?)");
            $stmt->execute([$email, $token]);
            $htmlMessage = __('click_below_to_reset') . ": <a href='http://localhost:8000/reset-password?token=$token'>" . __('reset_password') . "</a>";
            $res = EmailService::send($email, __('reset_password'), $htmlMessage);
            if ($res) {
                header('Location: /thank-you?msg=' . __('reset_link_sent'));
            } else {
                $this->view('auth/forgot-password', ['error' => __('failed_to_send_reset_link')], 'guest');
            }
        }
    }

    public function authenticate()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password_hash'])) {
            if (isset($user['is_active']) && $user['is_active'] == 0) {
                return $this->view('auth/login', [
                    'title' => __('login_title'),
                    'error' => __('account_disabled')
                ], 'guest');
            }

            Session::set('user_id', $user['id']);
            Session::set('role', $user['role']);

            header('Location: /dashboard');
            exit;
        }

        return $this->view('auth/login', [
            'title' => __('login_title'),
            'error' => __('invalid_credentials')
        ], 'guest');
    }

    public function logout()
    {
        Session::destroy();
        header('Location: /login');
        exit;
    }
}
