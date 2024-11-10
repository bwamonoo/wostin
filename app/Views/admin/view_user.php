<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mt-4" style="background-color: #f4f4f4; padding: 20px; border-radius: 8px;">
    <h2 class="mb-4">User Details</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="card-title"><?= esc($user['name']) ?></h4>
            <hr>
            <div class="mb-2">
                <strong>Email:</strong> <?= esc($user['email']) ?>
            </div>
            <div class="mb-2">
                <strong>Phone:</strong> <?= esc($user['phone_number']) ?>
            </div>
            <div class="mb-2">
                <strong>Location:</strong> <?= esc($user['location']) ?>
            </div>

            <div class="my-3">
                <strong>Status:</strong>
                <?php if ($user['email_verified']): ?>
                    <span class="badge bg-success">Active</span>
                    <a href="<?= base_url('admin/deactivate-user/' . $user['id']) ?>" class="btn btn-outline-danger btn-sm ms-3">Deactivate</a>
                <?php else: ?>
                    <span class="badge bg-danger">Inactive</span>
                    <a href="<?= base_url('admin/reactivate-user/' . $user['id']) ?>" class="btn btn-outline-success btn-sm ms-3">Reactivate</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <h3 class="mt-5 mb-4">User Schedules</h3>

    <?php if (!empty($schedules)): ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>Schedule ID</th>
                        <th>Collection Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($schedules as $schedule): ?>
                        <tr>
                            <td><?= esc($schedule['id']) ?></td>
                            <td><?= esc($schedule['collection_date']) ?></td>
                            <td><span class="badge <?= ($schedule['status'] === 'Completed') ? 'bg-success' : 'bg-warning' ?>"><?= esc($schedule['status']) ?></span></td>
                            <td>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#scheduleModal<?= $schedule['id'] ?>">View Details</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-center mt-3 text-muted">No schedules available.</p>
    <?php endif; ?>
</div>

<!-- Modals for each schedule -->
<?php foreach ($schedules as $schedule): ?>
    <div class="modal fade" id="scheduleModal<?= $schedule['id'] ?>" tabindex="-1" aria-labelledby="scheduleModalLabel<?= $schedule['id'] ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scheduleModalLabel<?= $schedule['id'] ?>">Schedule Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Schedule ID:</strong> <?= esc($schedule['id']) ?></p>
                    <p><strong>Collection Date:</strong> <?= esc($schedule['collection_date']) ?></p>
                    <p><strong>Status:</strong> <?= esc($schedule['status']) ?></p>
                    <h5 class="mt-3">Bins</h5>
                    <table class="table table-bordered">
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
                                    <td><?= esc($bin['cost']) ?></td>
                                    <td><?= esc($bin['size']) ?></td>
                                    <td><?= esc($bin['waste_type']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?= $this->endSection() ?>
