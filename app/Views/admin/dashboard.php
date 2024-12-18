<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mt-4" style="background-color: #f4f4f4; padding: 20px; border-radius: 8px;">

    <h1 class="text-center mb-5" style="color: #343a40; font-weight: bold;">Admin Dashboard</h1>

     <!-- Dashboard Cards Section -->
     <div class="row text-center mb-5">
        <!-- Total Users -->
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card shadow-sm border-0" style="background-color: #f0f4f8;">
                <div class="card-body">
                    <h5 class="card-title text-primary" style="font-size: 1.1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Total Users</h5>
                    <p class="card-text text-dark" style="font-size: 1.25rem;"><?= $totalUsers ?></p>
                </div>
            </div>
        </div>

        <!-- Pending Schedules -->
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card shadow-sm border-0" style="background-color: #f8f4f0;">
                <div class="card-body">
                    <h5 class="card-title text-warning" style="font-size: 1.1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Pending Schedules</h5>
                    <p class="card-text text-dark" style="font-size: 1.25rem;"><?= $pendingSchedules ?></p>
                </div>
            </div>
        </div>

        <!-- Approved Schedules -->
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card shadow-sm border-0" style="background-color: #f4f0f8;">
                <div class="card-body">
                    <h5 class="card-title text-success" style="font-size: 1.1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Schedules Awaiting Approval</h5>
                    <p class="card-text text-dark" style="font-size: 1.25rem;"><?= $unapprovedSchedules ?></p>
                </div>
            </div>
        </div>

        <!-- Completed Collections -->
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card shadow-sm border-0" style="background-color: #f0f8f4;">
                <div class="card-body">
                    <h5 class="card-title text-info" style="font-size: 1.1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Completed Collections</h5>
                    <p class="card-text text-dark" style="font-size: 1.25rem;"><?= $completedCollections ?></p>
                </div>
            </div>
        </div>

        <!-- Total Income -->
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card shadow-sm border-0" style="background-color: #f8f0f4;">
                <div class="card-body">
                    <h5 class="card-title text-danger" style="font-size: 1.1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Total Income</h5>
                    <p class="card-text text-dark" style="font-size: 1.25rem;">GHS <?= number_format($totalIncome, 2) ?></p>
                </div>
            </div>
        </div>
    </div>



    <!-- Charts Section -->
    <div class="row">
        <!-- Chart 1: Daily Schedules -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <canvas id="schedulesByDayChart" style="height: 300px;"></canvas>
            </div>
        </div>

        <!-- Chart 2: Waste Type Distribution -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <canvas id="wasteTypeDistributionChart" style="height: 300px;"></canvas>
            </div>
        </div>

        <!-- Chart 3: Monthly Revenue -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <canvas id="monthlyRevenueChart" style="height: 300px;"></canvas>
            </div>
        </div>

        <!-- Chart 4: Schedule Status Counts -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <canvas id="statusCountsChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>

</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart 1: Daily Schedules
    const schedulesByDayCtx = document.getElementById('schedulesByDayChart').getContext('2d');
    new Chart(schedulesByDayCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($schedulesByDay, 'date')) ?>,
            datasets: [{
                label: 'Schedules per Day',
                data: <?= json_encode(array_column($schedulesByDay, 'count')) ?>,
                backgroundColor: 'rgba(26, 188, 156, 0.6)',
                borderColor: '#1abc9c',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true, grid: { color: '#ecf0f1' } }
            }
        }
    });

    // Chart 2: Waste Type Distribution
    const wasteTypeDistributionCtx = document.getElementById('wasteTypeDistributionChart').getContext('2d');
    new Chart(wasteTypeDistributionCtx, {
        type: 'pie',
        data: {
            labels: <?= json_encode(array_column($wasteTypeDistribution, 'waste_type')) ?>,
            datasets: [{
                label: 'Waste Type Distribution',
                data: <?= json_encode(array_column($wasteTypeDistribution, 'count')) ?>,
                backgroundColor: ['#3498db', '#2ecc71', '#9b59b6', '#f39c12', '#e74c3c']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Chart 3: Monthly Revenue
    const monthlyRevenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
    new Chart(monthlyRevenueCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode(array_column($monthlyRevenue, 'month')) ?>,
            datasets: [{
                label: 'Revenue by Month',
                data: <?= json_encode(array_column($monthlyRevenue, 'revenue')) ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true, grid: { color: '#ecf0f1' } }
            }
        }
    });

    // Chart 4: Schedule Status Counts
    const statusCountsCtx = document.getElementById('statusCountsChart').getContext('2d');
    new Chart(statusCountsCtx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_column($statusCounts, 'status')) ?>,
            datasets: [{
                label: 'Schedule Status Counts',
                data: <?= json_encode(array_column($statusCounts, 'count')) ?>,
                backgroundColor: ['#ffbc00', '#2ecc71', '#e74c3c', '#1abc9c']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>

<?= $this->endSection() ?>
