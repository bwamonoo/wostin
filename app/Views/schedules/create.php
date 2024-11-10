<?= $this->extend('partials/header'); ?>
<?= $this->section('content'); ?>

<div class="container mt-5">
    <h1 class="text-center text-dark mb-4">Schedule Waste Collection</h1>

    <?= form_open('schedules/create', ['class' => 'form']); ?>

    <!-- Collection Date Input -->
    <div class="mb-3">
        <label for="collection_date" class="form-label">Collection Date</label>
        <?= form_input('collection_date', set_value('collection_date'), ['type' => 'date', 'required' => 'required', 'class' => 'form-control']); ?>
    </div>

    <h2 class="mt-4 mb-3">Bins</h2>
    <div id="bins-container">
        <div class="bin-entry mb-4 p-3 border rounded shadow-sm">
            <!-- Bin Size -->
            <div class="mb-3">
                <label for="bin_size" class="form-label">Bin Size</label>
                <select name="bins[0][bin_size]" class="form-select bin-size" required>
                    <?php foreach ($binSizes as $size): ?>
                        <option value="<?= esc($size['id']); ?>" data-multiplier="<?= esc($size['size_multiplier']); ?>">
                            <?= esc($size['size']); ?> - <?= esc($size['description']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Waste Type -->
            <div class="mb-3">
                <label for="waste_type" class="form-label">Waste Type</label>
                <select name="bins[0][waste_type]" class="form-select waste-type" required>
                    <?php foreach ($wasteTypes as $type): ?>
                        <option value="<?= esc($type['id']); ?>" data-cost="<?= esc($type['cost']); ?>">
                            <?= esc($type['type']); ?> - <?= esc($type['description']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Quantity -->
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <?= form_input('bins[0][quantity]', 1, ['type' => 'number', 'class' => 'form-control quantity', 'min' => '1', 'required' => 'required']); ?>
            </div>

            <!-- Bin Cost Display -->
            <div class="mb-3">
                <strong>Cost: </strong>
                <span class="bin-cost">0.00</span>
            </div>
        </div>
    </div>

    <!-- Add Another Bin Button -->
    <button type="button" id="add-bin" class="btn btn-outline-primary mb-4">Add Another Bin</button>

    <!-- Total Cost Display -->
    <div class="mb-4">
        <strong>Total Cost:</strong>
        <span id="total-cost">0.00</span>
    </div>

    <!-- Submit Button -->
    <?= form_submit('submit', 'Create Schedule', ['class' => 'btn btn-success w-100']); ?>

    <?= form_close(); ?>

</div>

<!-- Script for handling dynamic bin entries and total cost calculation -->
<script>
    let binIndex = 1;
    const binsContainer = document.getElementById('bins-container');
    const totalCostElement = document.getElementById('total-cost');

    document.getElementById('add-bin').addEventListener('click', () => {
        // Clone and modify the initial bin entry, updating its name attributes
        const binEntry = document.querySelector('.bin-entry').cloneNode(true);
        binEntry.querySelectorAll('[name]').forEach(input => {
            input.name = input.name.replace(/\[0\]/, `[${binIndex}]`);
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
