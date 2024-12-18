<?= $this->extend('partials/header'); ?>
<?= $this->section('content'); ?>

<div class="container my-5 p-5 " >
    <h1 class="text-center text-primary fw-bold mb-5">
        <?= isset($schedule) && $schedule ? 'Edit Schedule' : 'Create Schedule' ?>
    </h1>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger p-2">
            <ul class="mb-0">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php
    $formAction = isset($schedule) && $schedule ? 'schedules/create/' . $schedule['id'] : 'schedules/create';
    ?>
    <?= form_open($formAction, ['method' => 'post']); ?>

    <div class="form-group mb-4">
        <label for="collection_date" class="form-label" style="color: #808080">Collection Date</label>
        <input id="collection_date" name="collection_date" type="text" class="form-control shadow-sm" value="<?= set_value('collection_date', $schedule['collection_date'] ?? '') ?>" required>
    </div>



    <h2 class="text-secondary fw-bold mb-3">Bins</h2>
    <div id="bins-container">
        <?php if (isset($scheduleBins) && $scheduleBins): ?>
            <?php foreach ($scheduleBins as $index => $bin): ?>
                <div class="bin-entry mb-3 p-2 border rounded bg-light shadow-sm" style="border-left: 5px solid #007bff;">
                    <div class="form-group mb-2">
                        <label for="bin_size" class="form-label small" style="color: #808080">Bin Size</label>
                        <select name="bins[<?= $index ?>][bin_size]" class="form-control form-control-sm bin-size" required>
                            <?php foreach ($binSizes as $size): ?>
                                <option value="<?= esc($size['id']); ?>" data-multiplier="<?= esc($size['size_multiplier']); ?>"
                                    <?= $bin['bin_size_id'] == $size['id'] ? 'selected' : '' ?>>
                                    <?= esc($size['size']); ?> - <?= esc($size['description']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group mb-2">
                        <label for="waste_type" class="form-label small" style="color: #808080">Waste Type</label>
                        <select name="bins[<?= $index ?>][waste_type]" class="form-control form-control-sm waste-type" required>
                            <?php foreach ($wasteTypes as $type): ?>
                                <option value="<?= esc($type['id']); ?>" data-cost="<?= esc($type['cost']); ?>"
                                    <?= $bin['waste_type_id'] == $type['id'] ? 'selected' : '' ?>>
                                    <?= esc($type['type']); ?> - <?= esc($type['description']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mb-2">
                        <label for="quantity" class="form-label small" style="color: #808080">Quantity</label>
                        <?= form_input('bins['.$index.'][quantity]', set_value('bins['.$index.'][quantity]', $bin['quantity']), [
                            'type' => 'number',
                            'class' => 'form-control form-control-sm quantity',
                            'min' => '1',
                            'required' => 'required'
                        ]); ?>
                    </div>

                    <div class="mb-2 small" style="color: #808080">
                        <strong>Cost: </strong>
                        <span class="bin-cost"><?= number_format($bin['cost'], 2); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="bin-entry mb-3 p-2 border rounded bg-light shadow-sm" style="border-left: 5px solid #007bff;">
                <div class="form-group mb-2">
                    <label for="bin_size" class="form-label small" style="color: #808080">Bin Size</label>
                    <select name="bins[0][bin_size]" class="form-control form-control-sm bin-size" required>
                        <?php foreach ($binSizes as $size): ?>
                            <option value="<?= esc($size['id']); ?>" data-multiplier="<?= esc($size['size_multiplier']); ?>">
                                <?= esc($size['size']); ?> - <?= esc($size['description']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group mb-2">
                    <label for="waste_type" class="form-label small" style="color: #808080">Waste Type</label>
                    <select name="bins[0][waste_type]" class="form-control form-control-sm waste-type" required>
                        <?php foreach ($wasteTypes as $type): ?>
                            <option value="<?= esc($type['id']); ?>" data-cost="<?= esc($type['cost']); ?>">
                                <?= esc($type['type']); ?> - <?= esc($type['description']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group mb-2">
                    <label for="quantity" class="form-label small" style="color: #808080">Quantity</label>
                    <?= form_input('bins[0][quantity]', 1, [
                        'type' => 'number',
                        'class' => 'form-control form-control-sm quantity',
                        'min' => '1',
                        'required' => 'required'
                    ]); ?>
                </div>

                <div class="mb-2 small" style="color: #808080">
                    <strong>Cost: </strong>
                    <span class="bin-cost">0.00</span>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <button type="button" id="add-bin" class="btn btn-outline-primary shadow-sm mb-4">Add Another Bin</button>
    <div class="mb-4" style="color: #808080">
        <strong>Total Cost:</strong>
        <span id="total-cost">0.00</span>
    </div>

    <div class="d-flex justify-content-start">
        <button type="submit" class="btn btn-success shadow-sm px-4">
            <?= isset($schedule) && $schedule ? 'Update Schedule' : 'Create Schedule'; ?>
        </button>
        <a href="<?= site_url('schedules') ?>" class="btn btn-outline-secondary ms-3 px-4">Cancel</a>
    </div>

    <?= form_close(); ?>
</div>

<script>
    let binIndex = <?= isset($scheduleBins) ? count($scheduleBins) : 1 ?>;
    const binsContainer = document.getElementById('bins-container');
    const totalCostElement = document.getElementById('total-cost');

    document.getElementById('add-bin').addEventListener('click', () => {
        const binEntry = document.querySelector('.bin-entry').cloneNode(true);
        binEntry.querySelectorAll('[name]').forEach(input => {
            input.name = input.name.replace(/\[\d+\]/, `[${binIndex}]`);
            input.value = input.defaultValue;
        });
        binsContainer.appendChild(binEntry);
        binIndex++;
        updateTotalCost();
    });

    binsContainer.addEventListener('input', updateTotalCost);

    function updateTotalCost() {
        let totalCost = 0;
        document.querySelectorAll('.bin-entry').forEach(binEntry => {
            const sizeMultiplier = parseFloat(binEntry.querySelector('.bin-size option:checked').dataset.multiplier);
            const wasteCost = parseFloat(binEntry.querySelector('.waste-type option:checked').dataset.cost);
            const quantity = parseInt(binEntry.querySelector('.quantity').value);
            const binCost = sizeMultiplier * wasteCost * quantity;
            binEntry.querySelector('.bin-cost').textContent = binCost.toFixed(2);
            totalCost += binCost;
        });
        totalCostElement.textContent = totalCost.toFixed(2);
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        flatpickr("#collection_date", {
            dateFormat: "Y-m-d",  // Format to store the date in a consistent way
            minDate: "today",     // Prevent selecting past dates if required
            altInput: true,       // Adds a more readable version of the date
            altFormat: "F j, Y"   // Display date format (e.g., "January 1, 2023")
        });
    });
</script>


<?= $this->endSection(); ?>
<?= $this->include('partials/footer'); ?>
