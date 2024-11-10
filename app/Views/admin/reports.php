<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4 mt-4" style="background-color: #f4f4f4; padding: 20px; border-radius: 8px;">
    <h1 class="text-center mb-5" style="color: #343a40; font-weight: bold;">Reports</h1>

    <!-- Flash Message -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm border-0" style="background-color: #f8f4f0; height: 150px;">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <h5 class="card-title text-danger mb-2">Total Income</h5>
                    <p class="card-text fs-4 text-dark">$<?= number_format($totalIncome, 2) ?></p>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm border-0" style="background-color: #f0f8f4; height: 150px;">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <h5 class="card-title text-info mb-2">Average Schedule Cost</h5>
                    <p class="card-text fs-4 text-dark">$<?= number_format($averageCost, 2) ?></p>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm border-0" style="background-color: #f4f0f8; height: 150px;">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <h5 class="card-title text-primary mb-2">Total Schedules (Monthly)</h5>
                    <p class="card-text fs-4 text-dark"><?= count($schedulesPerMonth) ?> months of data</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Schedules Chart -->
    <h4 class="text-center mt-4 mb-3" style="color: #343a40;">Schedules Per Month</h4>
    <div class="card shadow-sm p-4 mb-4">
        <canvas id="schedulesChart" height="120"></canvas> <!-- Increased the height of the chart -->
    </div>

    <script>
        // Data for chart
        const labels = <?= json_encode(array_column($schedulesPerMonth, 'month')) ?>;
        const data = <?= json_encode(array_column($schedulesPerMonth, 'count')) ?>;

        const ctx = document.getElementById('schedulesChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Number of Schedules',
                    data: data,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
</div>

<script src="<?= base_url('js/bootstrap.bundle.min.js') ?>"></script>

<?= $this->endSection() ?>
