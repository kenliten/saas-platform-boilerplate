<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
        </div>
    </div>
</div>

<div class="row">
    <!-- Active Users -->
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-primary h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Active Users</h6>
                        <h2 class="my-2"><?= number_format($userCount) ?></h2>
                    </div>
                    <i class="bi bi-people" style="font-size: 2rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Subs -->
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-success h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Active Subscriptions</h6>
                        <h2 class="my-2"><?= number_format($subsCount) ?></h2>
                    </div>
                    <i class="bi bi-credit-card" style="font-size: 2rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- EST. MRR -->
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-info h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Est. MRR</h6>
                        <h2 class="my-2">$<?= number_format($mrr, 2) ?></h2>
                    </div>
                    <i class="bi bi-currency-dollar" style="font-size: 2rem;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="custom-card-title mb-0">User Growth</h5>
            </div>
            <div class="card-body">
                <canvas id="growthChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('growthChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($chartData['labels']) ?>,
                datasets: [{
                    label: 'Total Users',
                    data: <?= json_encode($chartData['values']) ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
