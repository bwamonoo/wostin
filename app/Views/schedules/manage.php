<?= $this->extend('partials/header'); ?>
<?= $this->section('content'); ?>

<h1><?= isset($schedule) && $schedule ? 'Edit Schedule' : 'Create Schedule' ?></h1>

<?php
// Set form action based on mode
$formAction = isset($schedule) && $schedule ? 'schedules/manage/' . $schedule['id'] : 'schedules/manage';
?>
<?= form_open($formAction); ?>

<div>
    <label for="collection_date">Collection Date</label>
    <?= form_input('collection_date', set_value('collection_date', $schedule['collection_date'] ?? ''), ['type' => 'date', 'required' => 'required']); ?>
</div>

<h2>Bins</h2>
<div id="bins-container">
    <?php if (isset($scheduleBins) && $scheduleBins): ?>
        <?php foreach ($scheduleBins as $index => $bin): ?>
            <div class="bin-entry">
                <div>
                    <label for="bin_size">Bin Size</label>
                    <select name="bins[<?= $index ?>][bin_size]" class="bin-size" required>
                        <?php foreach ($binSizes as $size): ?>
                            <option value="<?= esc($size['id']); ?>" data-multiplier="<?= esc($size['size_multiplier']); ?>"
                                <?= $bin['bin_size_id'] == $size['id'] ? 'selected' : '' ?>>
                                <?= esc($size['size']); ?> - <?= esc($size['description']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label for="waste_type">Waste Type</label>
                    <select name="bins[<?= $index ?>][waste_type]" class="waste-type" required>
                        <?php foreach ($wasteTypes as $type): ?>
                            <option value="<?= esc($type['id']); ?>" data-cost="<?= esc($type['cost']); ?>"
                                <?= $bin['waste_type_id'] == $type['id'] ? 'selected' : '' ?>>
                                <?= esc($type['type']); ?> - <?= esc($type['description']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="quantity">Quantity</label>
                    <?= form_input('bins['.$index.'][quantity]', set_value('bins['.$index.'][quantity]', $bin['quantity']), ['type' => 'number', 'class' => 'quantity', 'min' => '1', 'required' => 'required']); ?>
                </div>

                <div>
                    <strong>Cost: </strong>
                    <span class="bin-cost"><?= number_format($bin['cost'], 2); ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <!-- Default Bin Entry for New Schedule -->
        <div class="bin-entry">
            <div>
                <label for="bin_size">Bin Size</label>
                <select name="bins[0][bin_size]" class="bin-size" required>
                    <?php foreach ($binSizes as $size): ?>
                        <option value="<?= esc($size['id']); ?>" data-multiplier="<?= esc($size['size_multiplier']); ?>">
                            <?= esc($size['size']); ?> - <?= esc($size['description']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label for="waste_type">Waste Type</label>
                <select name="bins[0][waste_type]" class="waste-type" required>
                    <?php foreach ($wasteTypes as $type): ?>
                        <option value="<?= esc($type['id']); ?>" data-cost="<?= esc($type['cost']); ?>">
                            <?= esc($type['type']); ?> - <?= esc($type['description']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="quantity">Quantity</label>
                <?= form_input('bins[0][quantity]', 1, ['type' => 'number', 'class' => 'quantity', 'min' => '1', 'required' => 'required']); ?>
            </div>

            <div>
                <strong>Cost: </strong>
                <span class="bin-cost">0.00</span>
            </div>
        </div>
    <?php endif; ?>
</div>

<button type="button" id="add-bin">Add Another Bin</button>
<div>
    <strong>Total Cost:</strong>
    <span id="total-cost">0.00</span>
</div>

<?= form_submit('submit', isset($schedule) && $schedule ? 'Update Schedule' : 'Create Schedule'); ?>
<?= form_close(); ?>

<script>
    let binIndex = <?= isset($scheduleBins) ? count($scheduleBins) : 1 ?>;
    const binsContainer = document.getElementById('bins-container');
    const totalCostElement = document.getElementById('total-cost');

    document.getElementById('add-bin').addEventListener('click', () => {
        // Clone and modify the initial bin entry, updating its name attributes
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

<?= $this->endSection(); ?>
<?= $this->include('partials/footer'); ?>
