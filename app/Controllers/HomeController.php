<?php

namespace App\Controllers;

use App\Core\BaseController;

class HomeController extends BaseController
{
    public function index()
    {
        return $this->view('home', ['title' => 'Welcome to SaaS Platform']);
    }
}
