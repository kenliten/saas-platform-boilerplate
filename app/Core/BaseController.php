<?php

namespace App\Core;

abstract class BaseController
{
    protected function view($view, $data = [], $layout = 'dashboard')
    {
        extract($data);
        
        // Capture view content
        ob_start();
        require __DIR__ . "/../Views/{$view}.php";
        $content = ob_get_clean();
        
        // If layout is specified, render it
        if ($layout) {
            require __DIR__ . "/../Views/layouts/{$layout}.php";
        } else {
            echo $content;
        }
    }

    protected function json($data, $status = 200)
    {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
    }
}
