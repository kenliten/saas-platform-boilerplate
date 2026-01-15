<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Database;
use App\Models\Plan;

class HomeController extends BaseController
{
    public function index()
    {
        // TODO: build a sample landing page with this view
        return $this->view('home/index', ['title' => __('index_title') . ' | ' . env('APP_NAME')], 'guest');
    }

    public function about()
    {
        // TODO: build a sample about page with this view
        return $this->view('home/about', ['title' => __('about_title') . ' | ' . env('APP_NAME')], 'guest');
    }

    public function contact()
    {
        // TODO: build a sample contact page with this view
        return $this->view('home/contact', ['title' => __('contact_title') . ' | ' . env('APP_NAME')], 'guest');
    }

    public function sendMessage()
    {
        // TODO: send message to the admin and redirect to the contact page
        header('Location: /contact');
        exit;
    }

    public function pricing()
    {
        $plansModel = new Plan();
        $plans = $plansModel->findAll();

        return $this->view('home/pricing', [
            'title' => __('pricing_title') . ' | ' . env('APP_NAME'),
            'plans' => $plans
        ], 'guest');
    }

    public function faq()
    {
        // TODO: build a sample faq page with this view
        return $this->view('home/faq', ['title' => __('faq_title') . ' | ' . env('APP_NAME')], 'guest');
    }
}
