<?php

namespace App\Controllers;

use App\Core\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        $db = \App\Core\Database::getConnection();

        $result = $db->query("SELECT created_at FROM users WHERE role = 'user'")->fetchAll();
        $userCount = array_map(function ($date) {
            return $date['created_at'];
        }, $result);

        $sqlMRR = "
            SELECT SUM(p.price) 
            FROM accounts a 
            JOIN plans p ON a.plan = p.slug 
            WHERE p.price > 0
        ";
        $mrr = $db->query($sqlMRR)->fetchColumn() ?: 0.00;

        $sqlSubs = "
            SELECT COUNT(*) 
            FROM accounts a 
            JOIN plans p ON a.plan = p.slug 
            WHERE p.price > 0
        ";
        $subsCount = $db->query($sqlSubs)->fetchColumn();

        $chartData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'values' => $userCount
        ];

        $this->view('dashboard/index', [
            'userCount' => $userCount,
            'subsCount' => $subsCount,
            'mrr' => $mrr,
            'chartData' => $chartData
        ]);
    }
}
