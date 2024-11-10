<div class="row text-center mb-5">
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card shadow-sm border-0" style="background-color: #e9f1ff;">
            <div class="card-body py-4">
                <h5 class="card-title text-primary fw-bold">Total Collections</h5>
                <p class="display-6 text-dark"><?= esc($totalCollections) ?></p>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card shadow-sm border-0" style="background-color: #fff3e6;">
            <div class="card-body py-4">
                <h5 class="card-title text-success fw-bold">Total Amount Spent</h5>
                <p class="display-6 text-dark">$<?= esc($totalAmountSpent) ?></p>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card shadow-sm border-0" style="background-color: #f2e7ff;">
            <div class="card-body py-4">
                <h5 class="card-title text-warning fw-bold">Pending Collections</h5>
                <p class="display-6 text-dark"><?= esc($pendingCollections) ?></p>
            </div>
        </div>
    </div>
</div>
