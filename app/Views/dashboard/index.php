<?= $this->extend('partials/header') ?>

<?= $this->section('content') ?>

<div class="container my-5 p-5 " >
    <h1 class="text-center text-primary fw-bold mb-5">Welcome back, <?= esc(session()->get('name')) ?>!</h1>

    <!-- New Schedule Button -->
    <div class="d-flex justify-content-start mb-5">
        <a href="<?= site_url('schedules/create'); ?>" class="btn btn-success shadow-sm px-4">+ New Schedule</a>
    </div>

    <!-- Quick Stats Panel -->
    <?= $this->include('dashboard/partials/quick_stats', [
        'totalCollections' => $totalCollections,
        'totalAmountSpent' => $totalAmountSpent,
        'pendingCollections' => $pendingCollections
    ]) ?>

    <!-- Upcoming Collection -->
    <?= $this->include('dashboard/partials/upcoming_collection', ['upcomingSchedule' => $upcomingSchedule]) ?>

    <!-- Recent Schedules -->
    <?= $this->include('dashboard/partials/recent_schedules', ['recentSchedules' => $recentSchedules]) ?>
</div>

<?= $this->endSection() ?>
