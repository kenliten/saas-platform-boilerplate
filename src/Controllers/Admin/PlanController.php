<?php

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Models\Plan;
use App\Core\Session;

class PlanController extends BaseController
{
    public function index()
    {
        $this->dieIfNotAdmin();

        $planModel = new Plan();
        $plans = $planModel->all();

        return $this->view('admin/plans/index', [
            'plans' => $plans,
            'title' => __('nav_plans')
        ], 'admin');
    }

    public function create()
    {
        $this->dieIfNotAdmin();

        return $this->view('admin/plans/create', [
            'title' => __('plans')
        ], 'admin');
    }

    public function store()
    {
        $this->dieIfNotAdmin();

        $data = [
            'name' => $_POST['name'] ?? '',
            'price' => $_POST['price'] ?? 0,
            'description' => $_POST['description'] ?? '',
            'slug' => strtolower(str_replace(' ', '-', $_POST['name'] ?? '')),
            'features' => json_encode(array_filter($_POST['features'] ?? [])),
            'limits' => json_encode($_POST['limits'] ?? [])
        ];

        // Basic validation
        if (empty($data['name'])) {
            Session::set('error', 'Plan name is required');
            return $this->redirect('/admin/plans/create');
        }

        $planModel = new Plan();
        $planModel->create($data);

        Session::set('success', 'Plan created successfully');
        return $this->redirect('/admin/plans');
    }

    public function edit($id)
    {
        $this->dieIfNotAdmin();

        $planModel = new Plan();
        $plan = $planModel->find($id);

        if (!$plan) {
            Session::set('error', 'Plan not found');
            return $this->redirect('/admin/plans');
        }

        return $this->view('admin/plans/edit', [
            'plan' => $plan,
            'title' => 'Edit Plan'
        ], 'admin');
    }

    public function update($id)
    {
        $data = [
            'name' => $_POST['name'] ?? '',
            'price' => $_POST['price'] ?? 0,
            'description' => $_POST['description'] ?? '',
            'slug' => strtolower(str_replace(' ', '-', $_POST['name'] ?? '')),
            'features' => json_encode(array_filter($_POST['features'] ?? [])),
            'limits' => json_encode($_POST['limits'] ?? [])
        ];

        $planModel = new Plan();
        $planModel->update($id, $data);

        Session::set('success', 'Plan updated successfully');
        return $this->redirect('/admin/plans');
    }

    public function delete($id)
    {
        $this->dieIfNotAdmin();

        $planModel = new Plan();
        $planModel->delete($id);

        Session::set('success', 'Plan deleted successfully');
        return $this->redirect('/admin/plans');
    }
}
