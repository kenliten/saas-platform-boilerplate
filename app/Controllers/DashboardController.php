<?php

namespace App\Controllers;

use App\Core\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        $db = \App\Core\Database::getInstance();

        // 1. Total Users
        $users = $db->query("SELECT created_at FROM users WHERE role = 'user'")->fetchAll();
        $userCount = count($users);

        // 2. MRR
        $sqlMRR = "
            SELECT SUM(p.price) 
            FROM accounts a 
            JOIN plans p ON a.plan = p.slug 
            WHERE p.price > 0
        ";
        $mrr = $db->query($sqlMRR)->fetchColumn() ?: 0.00;

        // 3. Active Subscriptions
        $sqlSubs = "
            SELECT COUNT(*) 
            FROM accounts a 
            JOIN plans p ON a.plan = p.slug 
            WHERE p.price > 0
        ";
        $subsCount = $db->query($sqlSubs)->fetchColumn();

        // 4. Chart Data (Cumulative Monthly Growth)
        $monthlyCounts = array_fill(1, 12, 0);
        foreach ($users as $user) {
            $timestamp = strtotime($user['created_at']);
            if ($timestamp) {
                $month = (int)date('n', $timestamp);
                $monthlyCounts[$month]++;
            }
        }

        $chartValues = [];
        $runningTotal = 0;
        for ($i = 1; $i <= 12; $i++) {
            $runningTotal += $monthlyCounts[$i];
            $chartValues[] = $runningTotal;
        }

        $chartData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'values' => $chartValues
        ];

        $this->view('dashboard/index', [
            'userCount' => $userCount,
            'subsCount' => $subsCount,
            'mrr' => $mrr,
            'chartData' => $chartData
        ]);
    }
}
