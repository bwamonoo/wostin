<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mt-5" style="background-color: #f4f4f4; padding: 20px; border-radius: 8px;">
    <h1 class="text-center mb-4 text-dark" style="font-weight: bold;">User Management</h1>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <!-- User Table Card -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <h5 class="card-title text-info mb-4"><i class="bi bi-person-circle"></i> Users List</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Location</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= esc($user['name']) ?></td>
                                <td><?= esc($user['email']) ?></td>
                                <td><?= esc($user['phone_number']) ?></td>
                                <td><?= esc($user['location']) ?></td>
                                <td>
                                    <!-- View Button -->
                                    <a href="<?= base_url('admin/user/' . $user['id']) ?>" class="btn btn-info btn-sm me-2">
                                        <i class="bi bi-eye"></i> View
                                    </a>

                                    <!-- Deactivate / Deactivated Button -->
                                    <?php if ($user['email_verified']): ?>
                                        <a href="<?= base_url('admin/deactivate-user/' . $user['id']) ?>" class="btn btn-danger btn-sm">
                                            <i class="bi bi-x-circle"></i> Deactivate
                                        </a>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Deactivated</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('js/bootstrap.bundle.min.js') ?>"></script>

<?= $this->endSection() ?>
