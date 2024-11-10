<div class="card shadow-sm border-0 mt-4 mb-5">
    <div class="card-body p-4">
        <h5 class="card-title text-info fw-bold mb-4"><i class="bi bi-calendar3"></i> Upcoming Collection</h5>

        <?php if ($upcomingSchedule): ?>
            <div class="card border-0 shadow-sm p-3 mb-4">
                <p><strong>Collection Date:</strong> <?= esc($upcomingSchedule['collection_date']) ?></p>
                <p><strong>Status:</strong> 
                    <span class="badge <?= ($upcomingSchedule['status'] === 'completed') ? 'bg-success' : (($upcomingSchedule['status'] === 'pending') ? 'bg-warning text-dark' : 'bg-secondary'); ?>">
                        <?= ucfirst(esc($upcomingSchedule['status'])); ?>
                    </span>
                </p>

                <h5 class="mt-4 text-secondary fw-bold"><i class="bi bi-binoculars"></i> Bin Details</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Bin Size</th>
                                <th>Waste Type</th>
                                <th>Quantity</th>
                                <th>Cost</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($upcomingSchedule['bins'] as $bin): ?>
                                <tr>
                                    <td><?= esc($bin['size']) ?></td>
                                    <td><?= esc($bin['waste_type']) ?></td>
                                    <td><?= esc($bin['quantity']) ?></td>
                                    <td>$<?= esc(number_format($bin['cost'], 2)) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <p class="text-muted">No upcoming collection scheduled.</p>
        <?php endif; ?>
    </div>
</div>
