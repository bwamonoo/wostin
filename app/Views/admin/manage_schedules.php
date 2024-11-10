<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-4 mt-4" style="background-color: #f4f4f4; padding: 20px; border-radius: 8px;">
    <h1 class="text-center mb-5" style="color: #343a40; font-weight: bold;">Schedule Management</h1>

    <!-- Filters Section -->
    <form class="card p-4 mb-5 shadow-sm border-0" style="background-color: #f8f9fa;">
        <h5 class="mb-4 text-primary"><i class="bi bi-funnel-fill"></i> Filter Schedules</h5>
        <div class="row g-3">
            <div class="col-md-4">
                <label for="statusFilter" class="form-label">Status</label>
                <select name="status" id="statusFilter" class="form-select">
                    <option value="">All</option>
                    <option value="pending" <?= $statusFilter === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="approved" <?= $statusFilter === 'approved' ? 'selected' : '' ?>>Approved</option>
                    <option value="completed" <?= $statusFilter === 'completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="cancelled" <?= $statusFilter === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="dateFilter" class="form-label">Collection Date</label>
                <input type="date" name="date" id="dateFilter" class="form-control" value="<?= esc($dateFilter) ?>">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Apply Filters</button>
            </div>
        </div>
    </form>

    <!-- Schedule Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <h5 class="card-title text-info mb-4"><i class="bi bi-table"></i> Schedule List</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Schedule ID</th>
                            <th>User</th>
                            <th>Collection Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($schedules)): ?>
                            <?php foreach ($schedules as $schedule): ?>
                                <tr>
                                    <td><?= esc($schedule['id']) ?></td>
                                    <td><?= esc($schedule['user_id']) ?></td>
                                    <td><?= esc($schedule['collection_date']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= esc($schedule['status'] === 'completed' ? 'success' : ($schedule['status'] === 'cancelled' ? 'danger' : 'warning')) ?>">
                                            <?= ucfirst(esc($schedule['status'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <!-- Update Status Form -->
                                        <form action="<?= base_url('admin/update-schedule-status/' . $schedule['id']) ?>" method="post" class="d-inline">
                                            <select name="status" class="form-select d-inline w-auto">
                                                <option value="pending" <?= $schedule['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                                <option value="completed" <?= $schedule['status'] === 'completed' ? 'selected' : '' ?>>Complete</option>
                                                <option value="cancelled" <?= $schedule['status'] === 'cancelled' ? 'selected' : '' ?>>Cancel</option>
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check-circle"></i> Update</button>
                                        </form>

                                        <!-- Approve / Unapprove Button -->
                                        <?php if ($schedule['pending_approval']): ?>
                                            <a href="<?= base_url('admin/approve-schedule/' . $schedule['id']) ?>" class="btn btn-sm btn-primary"><i class="bi bi-check2-circle"></i> Approve</a>
                                        <?php else: ?>
                                            <a href="<?= base_url('admin/unapprove-schedule/' . $schedule['id']) ?>" class="btn btn-sm btn-warning"><i class="bi bi-x-circle"></i> Unapprove</a>
                                        <?php endif; ?>

                                        <!-- View Details Button -->
                                        <a href="<?= base_url('admin/schedule-details/' . $schedule['id']) ?>" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> View Details</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No schedules found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('js/bootstrap.bundle.min.js') ?>"></script>

<?= $this->endSection() ?>
