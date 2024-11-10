<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-4 mt-5" style="background-color: #f4f4f4; padding: 20px; border-radius: 8px;">
    <h1 class="text-center mb-4 text-dark" style="font-weight: bold;">Unavailability Management</h1>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <!-- Add Unavailable Date Form Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <h5 class="card-title text-info mb-4"><i class="bi bi-calendar-x"></i> Add Unavailable Date</h5>

            <!-- Form to Add Unavailable Date -->
            <form action="<?= base_url('admin/add-unavailable-date') ?>" method="post">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="date" class="form-label">Unavailable Date</label>
                        <input type="date" name="date" id="date" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="reason" class="form-label">Reason (Optional)</label>
                        <input type="text" name="reason" id="reason" class="form-control" placeholder="Reason for unavailability">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Add Date</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Unavailable Dates Table Card -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <h5 class="card-title text-info mb-4"><i class="bi bi-calendar"></i> Unavailable Dates</h5>

            <!-- Unavailable Dates Table -->
            <div class="table-responsive mt-4">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Reason</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($unavailableDates)): ?>
                            <?php foreach ($unavailableDates as $date): ?>
                                <tr>
                                    <td><?= esc($date['date']) ?></td>
                                    <td><?= esc($date['reason']) ?></td>
                                    <td>
                                        <a href="<?= base_url('admin/delete-unavailable-date/' . $date['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this date?');">
                                            <i class="bi bi-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center">No unavailable dates found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
