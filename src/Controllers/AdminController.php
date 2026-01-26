<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\User;

class AdminController extends BaseController
{
    public function index()
    {
        $model = new User();
        $items = $model->all();
        return $this->view('Admin/index', ['items' => $items], 'admin');
    }
}