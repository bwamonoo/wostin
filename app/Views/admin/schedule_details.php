<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4 mt-4" style="background-color: #f4f4f4; padding: 20px; border-radius: 8px;">
    <h1 class="text-center mb-5" style="color: #343a40; font-weight: bold;">Schedule Details</h1>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <!-- Schedule Information Card -->
    <div class="card shadow-sm border-0 mb-4" style="background-color: #f8f9fa;">
        <div class="card-body p-4">
            <h5 class="card-title text-primary mb-4"><i class="bi bi-calendar-check"></i> Schedule Information</h5>
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Schedule ID:</strong> <?= esc($schedule['id']) ?></p>
                    <p><strong>User ID:</strong> <?= esc($schedule['user_id']) ?></p>
                    <p><strong>Collection Date:</strong> <?= esc($schedule['collection_date']) ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Status:</strong> <span class="badge bg-<?= esc($schedule['status'] === 'completed' ? 'success' : ($schedule['status'] === 'cancelled' ? 'danger' : 'warning')) ?>"><?= ucfirst(esc($schedule['status'])) ?></span></p>
                </div>
            </div>

            <!-- Status Update Form -->
            <form action="<?= base_url('admin/manage-schedule-status/' . $schedule['id']) ?>" method="post" class="mb-4">
                <div class="mb-3">
                    <label for="status" class="form-label">Update Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="pending" <?= $schedule['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="approved" <?= $schedule['status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
                        <option value="completed" <?= $schedule['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                        <option value="cancelled" <?= $schedule['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Update Status</button>
            </form>

            <!-- Approve / Unapprove Button -->
            <div>
                <?php if ($schedule['pending_approval']): ?>
                    <a href="<?= base_url('admin/approve-schedule/' . $schedule['id']) ?>" class="btn btn-success btn-sm me-2"><i class="bi bi-check-circle-fill"></i> Approve</a>
                <?php else: ?>
                    <a href="<?= base_url('admin/unapprove-schedule/' . $schedule['id']) ?>" class="btn btn-warning btn-sm"><i class="bi bi-x-circle-fill"></i> Unapprove</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bin Details Table -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <h5 class="card-title text-info mb-4"><i class="bi bi-trash"></i> Bin Details</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Quantity</th>
                            <th>Cost</th>
                            <th>Bin Size</th>
                            <th>Waste Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($schedule['bins'] as $bin): ?>
                            <tr>
                                <td><?= esc($bin['quantity']) ?></td>
                                <td>$<?= number_format(esc($bin['cost']), 2) ?></td>
                                <td><?= esc($bin['size']) ?> L</td>
                                <td><?= esc($bin['waste_type']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="text-center mt-4">
        <a href="<?= base_url('admin/schedules') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left-circle"></i> Back to Schedule Management</a>
    </div>
</div>

<script src="<?= base_url('js/bootstrap.bundle.min.js') ?>"></script>
<?= $this->endSection() ?>
