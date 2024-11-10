<?php if (!empty($recentSchedules)): ?>
    <ul class="list-group">
        <?php foreach ($recentSchedules as $schedule): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>
                    Date: <?= $schedule['collection_date']; ?> | Status: <?= ucfirst($schedule['status']); ?>
                </span>
                <a href="<?= site_url('schedules/details/' . $schedule['id']); ?>" class="btn btn-info btn-sm">View Details</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No recent schedules.</p>
<?php endif; ?>
