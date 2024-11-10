<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-4 mt-5" style="background-color: #f4f4f4; padding: 20px; border-radius: 8px;">
    <h1 class="text-center mb-4 text-dark" style="font-weight: bold;">Bin and Waste Type Management</h1>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <!-- Bin Sizes Management Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <h5 class="card-title text-info mb-4"><i class="bi bi-box"></i> Manage Bin Sizes</h5>

            <!-- Bin Size Form -->
            <form action="<?= base_url('admin/add-bin-size') ?>" method="post">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="size" class="form-label">Size</label>
                        <input type="text" name="size" id="size" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label for="size_multiplier" class="form-label">Size Multiplier</label>
                        <input type="number" step="0.01" name="size_multiplier" id="size_multiplier" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" name="description" id="description" class="form-control">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Add Bin Size</button>
                    </div>
                </div>
            </form>

            <!-- Bin Sizes Table -->
            <div class="table-responsive mt-4">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Size</th>
                            <th>Multiplier</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($binSizes as $bin): ?>
                            <tr>
                                <td><?= esc($bin['size']) ?></td>
                                <td><?= esc($bin['size_multiplier']) ?></td>
                                <td><?= esc($bin['description']) ?></td>
                                <td>
                                    <a href="<?= base_url('admin/delete-bin-size/' . $bin['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">
                                        <i class="bi bi-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Waste Types Management Card -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <h5 class="card-title text-info mb-4"><i class="bi bi-trash"></i> Manage Waste Types</h5>

            <!-- Waste Type Form -->
            <form action="<?= base_url('admin/add-waste-type') ?>" method="post">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="type" class="form-label">Type</label>
                        <input type="text" name="type" id="type" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label for="cost" class="form-label">Cost</label>
                        <input type="number" step="0.01" name="cost" id="cost" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" name="description" id="description" class="form-control">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Add Waste Type</button>
                    </div>
                </div>
            </form>

            <!-- Waste Types Table -->
            <div class="table-responsive mt-4">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Type</th>
                            <th>Cost</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($wasteTypes as $waste): ?>
                            <tr>
                                <td><?= esc($waste['type']) ?></td>
                                <td><?= esc($waste['cost']) ?></td>
                                <td><?= esc($waste['description']) ?></td>
                                <td>
                                    <a href="<?= base_url('admin/delete-waste-type/' . $waste['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">
                                        <i class="bi bi-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
