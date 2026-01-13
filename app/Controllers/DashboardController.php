<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Session;

class DashboardController extends BaseController
{
    public function index()
    {
        $db = \App\Core\Database::getConnection();
        
        // Metrics
        $userCount = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        
        // Total MRR
        // Assuming accounts table has 'plan' column which stores SLUG, and plans table has 'price'
        $sqlMRR = "
            SELECT SUM(p.price) 
            FROM accounts a 
            JOIN plans p ON a.plan = p.slug 
        ";
        $mrr = $db->query($sqlMRR)->fetchColumn() ?: 0.00;

        // Active Subs (Paid)
        $sqlSubs = "
            SELECT COUNT(*) 
            FROM accounts a 
            JOIN plans p ON a.plan = p.slug 
            WHERE p.price > 0
        ";
        $subsCount = $db->query($sqlSubs)->fetchColumn();
        
        // Chart Data (Mocking last 6 months growth for demo)
        $chartData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'values' => [10, 25, 45, 80, 120, $userCount]
        ];

        $this->view('dashboard/index', [
            'userCount' => $userCount,
            'subsCount' => $subsCount,
            'mrr' => $mrr,
            'chartData' => $chartData
        ]);
    }
}
