<div class="card shadow-sm border-0 mb-5">
    <div class="card-body p-4">
        <h5 class="card-title text-info fw-bold mb-4"><i class="bi bi-calendar-check-fill"></i> Recent Schedules</h5>

        <?php if (!empty($recentSchedules)): ?>
            <ul class="list-group list-group-flush">
                <?php foreach ($recentSchedules as $schedule): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Date:</strong> <?= esc($schedule['collection_date']); ?> |
                            <strong>Status:</strong> 
                            <span class="badge <?= ($schedule['status'] === 'completed') ? 'bg-success' : (($schedule['status'] === 'pending') ? 'bg-warning text-dark' : 'bg-secondary'); ?>">
                                <?= ucfirst(esc($schedule['status'])); ?>
                            </span>
                        </div>
                        <a href="<?= site_url('schedules/details/' . $schedule['id']); ?>" class="btn btn-sm btn-outline-info">
                            <i class="bi bi-eye"></i> View Details
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="text-center mt-4">
                <a href="<?= site_url('schedules'); ?>" class="btn btn-primary">View All Schedules</a>
            </div>
        <?php else: ?>
            <p class="text-muted">No recent schedules available.</p>
        <?php endif; ?>
    </div>
</div>
