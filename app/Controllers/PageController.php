<?php

namespace App\Controllers;

use App\Core\BaseController;

class PageController extends BaseController
{
    public function thankYou()
    {
        $message = $_GET['msg'] ?? 'Thank you for your action!';
        $this->view('pages/thank-you', ['message' => $message], 'guest'); 
    }

    public function notFound()
    {
        http_response_code(404);
        $this->view('errors/404', [], 'guest');
    }
}
