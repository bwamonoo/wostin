<?= $this->extend('partials/header'); ?>
<?= $this->section('content'); ?>

<div class="container my-5 p-4" style="background-color: #f5f5f5;">
    <h1 class="text-center text-primary fw-bold mb-5">Schedule Details</h1>

    <div class="card border-0 shadow-sm mx-auto" style="max-width: 700px;">
        <div class="card-body">
            <div class="mb-4">
                <p><strong>Collection Date:</strong> <?= esc($schedule['collection_date']); ?></p>
                <p><strong>Status:</strong> 
                    <span class="badge <?= ($schedule['status'] === 'completed') ? 'bg-success' : (($schedule['status'] === 'pending') ? 'bg-warning text-dark' : 'bg-secondary'); ?>">
                        <?= ucfirst(esc($schedule['status'])); ?>
                    </span>
                </p>
            </div>

            <h2 class="h5 text-primary fw-bold mb-3">Bins</h2>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>Bin Size</th>
                            <th>Waste Type</th>
                            <th>Quantity</th>
                            <th>Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i = 0; $i < count($scheduleBins); $i++): ?>
                            <tr>
                                <td><?= isset($binSizes[$i % count($binSizes)]['size']) ? esc($binSizes[$i % count($binSizes)]['size']) : 'N/A'; ?></td>
                                <td><?= isset($wasteTypes[$i % count($wasteTypes)]['type']) ? esc($wasteTypes[$i % count($wasteTypes)]['type']) : 'N/A'; ?></td>
                                <td><?= esc($scheduleBins[$i]['quantity']); ?></td>
                                <td><?= esc($scheduleBins[$i]['cost']); ?></td>
                            </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
            </div>

            <div class="text-end mt-4">
                <a href="<?= site_url('schedules'); ?>" class="btn btn-primary">Back to Schedules</a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
<?= $this->include('partials/footer'); ?>
