<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Session;
use App\Models\User;

class AuthController extends BaseController
{
    public function login()
    {
        if (Session::has('user_id')) {
            header('Location: /dashboard');
            exit;
        }
        return $this->view('auth/login', ['title' => 'Login'], 'guest');
    }

    public function register()
    {
        $this->view('auth/register', ['title' => 'Register'], 'guest');
    }

    public function store()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        $db = \App\Core\Database::getConnection();
        
        // 1. Create Account
        $stmt = $db->prepare("INSERT INTO accounts (name, plan) VALUES (?, ?)");
        $stmt->execute(['Personal Account', 'free']);
        $accountId = $db->lastInsertId();
        
        // 2. Create User
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        try {
            // Note: Migrations added is_active default 1, but we can be explicit
            $stmt = $db->prepare("INSERT INTO users (account_id, email, password_hash, is_active, role) VALUES (?, ?, ?, 1, 'user')");
            // Check User model for column name. Original code used 'password_hash' in findByEmail check line 28 of AuthController, 
            // but Migration 001 created table with 'password_hash' or 'password'?
            // Looking at AuthController line 28: password_verify($password, $user['password_hash']) -> So column is password_hash.
            $stmt->execute([$accountId, $email, $hashed]);
            
            // Login
            $userId = $db->lastInsertId();
            Session::set('user_id', $userId);
            Session::set('role', 'user');
            header('Location: /dashboard');
            exit;
        } catch (\PDOException $e) {
            $this->view('auth/register', ['error' => 'Email already registered'], 'guest');
        }
    }

    public function forgotPassword()
    {
        $this->view('auth/forgot-password', ['title' => 'Forgot Password'], 'guest');
    }

    public function sendResetLink()
    {
        $email = $_POST['email'];
        $token = bin2hex(random_bytes(16));
        $db = \App\Core\Database::getConnection();
        
        // Check if user exists first to strictness? Or blind insert?
        // Blind insert is fine for mock.
        $stmt = $db->prepare("INSERT INTO password_resets (email, token) VALUES (?, ?)");
        $stmt->execute([$email, $token]);
        
        header('Location: /thank-you?msg=If an account exists, a reset link has been sent (Mock Token: ' . $token . ')');
        exit;
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
                    'title' => 'Login', 
                    'error' => 'Your account has been disabled.'
                ], 'guest');
            }

            Session::set('user_id', $user['id']);
            Session::set('role', $user['role']);
            
            header('Location: /dashboard');
            exit;
        }

        return $this->view('auth/login', [
            'title' => 'Login', 
            'error' => 'Invalid credentials'
        ], 'guest');
    }

    public function logout()
    {
        Session::destroy();
        header('Location: /login');
        exit;
    }
}
