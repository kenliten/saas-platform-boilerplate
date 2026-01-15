<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Admin;

class AdminController extends BaseController
{
    public function index()
    {
        $model = new Admin();
        $items = $model->all();
        return $this->view('Admin/index', ['items' => $items]);
    }
}