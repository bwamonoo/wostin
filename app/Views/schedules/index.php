<?= $this->extend('partials/header'); ?>
<?= $this->section('content'); ?>

<div class="container my-5 p-4" >
    <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger mb-4">
                <?= session()->getFlashdata('error') ?>
            </div>
    <?php endif; ?>

    <h1 class="text-center text-primary fw-bold mb-5">Manage Your Schedules</h1>

    <!-- Filter Form -->
    <form action="<?= site_url('schedules'); ?>" method="get" class="d-flex gap-3 justify-content-center mb-4">
        <div class="input-group" style="max-width: 400px;">
            <label for="status" class="input-group-text bg-primary text-white">Filter:</label>
            <select name="status" id="status" class="form-select">
                <option value="">All</option>
                <option value="pending" <?= set_select('status', 'pending', $status === 'pending'); ?>>Pending</option>
                <option value="awaiting_approval" <?= set_select('status', 'awaiting_approval', $status === 'awaiting_approval'); ?>>Awaiting Approval</option>
                <option value="completed" <?= set_select('status', 'completed', $status === 'completed'); ?>>Completed</option>
                <option value="cancelled" <?= set_select('status', 'cancelled', $status === 'cancelled'); ?>>Cancelled</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary shadow-sm">Apply</button>
    </form>

    <!-- New Schedule Button -->
    <div class="text-center mb-5">
        <a href="<?= site_url('schedules/create'); ?>" class="btn btn-success btn-lg shadow">+ Add Schedule</a>
    </div>

    <!-- Schedules Card Layout -->
    <div class="row">
        <?php if (!empty($schedules)): ?>
            <?php foreach ($schedules as $schedule): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <div class="mb-3">
                                <h5 class="card-title text-primary mb-1"><?= esc($schedule['collection_date']); ?></h5>
                                <span class="badge <?= ($schedule['status'] === 'completed') ? 'bg-success' : (($schedule['status'] === 'pending') ? 'bg-warning text-dark' : 'bg-secondary'); ?>">
                                    <?= ucfirst(esc($schedule['status'])); ?>
                                </span>
                            </div>
                            <div class="mt-auto d-flex justify-content-end">
                                <div class="btn-group">
                                    <a href="<?= site_url('schedules/details/' . $schedule['id']); ?>" class="btn btn-outline-info btn-sm">View</a>
                                    <a href="<?= site_url('schedules/edit/' . $schedule['id']); ?>" class="btn btn-outline-warning btn-sm">Edit</a>
                                    <a href="<?= site_url('schedules/delete/' . $schedule['id']); ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-muted">No schedules found.</p>
        <?php endif; ?>
    </div>

    <!-- Modal for viewing schedule details -->
    <div id="schedule-details-modal" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Schedule Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Collection Date:</strong> <span id="modal-collection-date"></span></p>
                    <p><strong>Status:</strong> <span id="modal-status"></span></p>
                    <p><strong>Bins:</strong> <pre id="modal-bins" class="bg-light p-3 rounded"></pre></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.view-details').forEach(button => {
        button.addEventListener('click', function() {
            const scheduleId = this.dataset.id;
            fetch(`<?= site_url('schedules/details'); ?>/${scheduleId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modal-collection-date').innerText = data.collection_date;
                    document.getElementById('modal-status').innerText = data.status;
                    document.getElementById('modal-bins').innerText = data.bins.map(bin => 
                        `Bin Size: ${bin.bin_size}, Waste Type: ${bin.waste_type}, Cost: ${bin.cost}, Quantity: ${bin.quantity}`
                    ).join('\n');
                    new bootstrap.Modal(document.getElementById('schedule-details-modal')).show();
                })
                .catch(error => console.error('Error fetching details:', error));
        });
    });
</script>

<?= $this->endSection(); ?>
<?= $this->include('partials/footer'); ?>
