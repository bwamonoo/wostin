<?= $this->extend('partials/header'); ?>
<?= $this->section('content'); ?>

<div class="container my-4" style="background-color: #f4f4f4; padding: 30px; border-radius: 8px;">
    <h1 class="text-center mb-4"><?= isset($schedule) && $schedule ? 'Edit Schedule' : 'Create Schedule' ?></h1>

    <!-- Display validation errors -->
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php
    // Set form action based on mode
    $formAction = isset($schedule) && $schedule ? 'schedules/edit/' . $schedule['id'] : 'schedules/create';
    ?>
    <?= form_open($formAction); ?>

    <div class="mb-3">
        <label for="collection_date" class="form-label">Collection Date</label>
        <?= form_input('collection_date', set_value('collection_date', $schedule['collection_date'] ?? ''), [
            'id' => 'collection_date',
            'type' => 'text',
            'class' => 'form-control',
            'required' => 'required'
        ]); ?>
    </div>

    <!-- Include Flatpickr CSS and JS from CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr("#collection_date", {
            dateFormat: "Y-m-d",
            minDate: "today",
            allowInput: true
        });
    </script>

    <h2 class="mt-4">Bins</h2>
    <div id="bins-container" class="p-3" style="border: 1px solid #ddd; border-radius: 8px; background-color: #fff;">
        <?php if (isset($scheduleBins) && $scheduleBins): ?>
            <?php foreach ($scheduleBins as $index => $bin): ?>
                <div class="bin-entry mb-3 p-3 rounded shadow-sm" style="background-color: #f9f9f9;">
                    <div class="mb-3">
                        <label for="bin_size" class="form-label">Bin Size</label>
                        <select name="bins[<?= $index ?>][bin_size]" class="form-select bin-size" required>
                            <?php foreach ($binSizes as $size): ?>
                                <option value="<?= esc($size['id']); ?>" data-multiplier="<?= esc($size['size_multiplier']); ?>"
                                    <?= $bin['bin_size_id'] == $size['id'] ? 'selected' : '' ?>>
                                    <?= esc($size['size']); ?> - <?= esc($size['description']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="waste_type" class="form-label">Waste Type</label>
                        <select name="bins[<?= $index ?>][waste_type]" class="form-select waste-type" required>
                            <?php foreach ($wasteTypes as $type): ?>
                                <option value="<?= esc($type['id']); ?>" data-cost="<?= esc($type['cost']); ?>"
                                    <?= $bin['waste_type_id'] == $type['id'] ? 'selected' : '' ?>>
                                    <?= esc($type['type']); ?> - <?= esc($type['description']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <?= form_input('bins['.$index.'][quantity]', set_value('bins['.$index.'][quantity]', $bin['quantity']), [
                            'type' => 'number',
                            'class' => 'form-control quantity',
                            'min' => '1',
                            'required' => 'required'
                        ]); ?>
                    </div>

                    <div>
                        <strong>Cost: </strong>
                        <span class="bin-cost"><?= number_format($bin['cost'], 2); ?></span>
                    </div>

                    <!-- Remove Bin Button (Disabled for the First Bin) -->
                    <button type="button" class="btn btn-danger mt-3 remove-bin" <?= ($index === 0) ? 'disabled' : ''; ?>>Remove Bin</button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Default Bin Entry for New Schedule -->
            <div class="bin-entry mb-3 p-3 rounded shadow-sm" style="background-color: #f9f9f9;">
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

                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <?= form_input('bins[0][quantity]', 1, [
                        'type' => 'number',
                        'class' => 'form-control quantity',
                        'min' => '1',
                        'required' => 'required'
                    ]); ?>
                </div>

                <div>
                    <strong>Cost: </strong>
                    <span class="bin-cost">0.00</span>
                </div>

                <!-- Remove Bin Button (Disabled for the First Bin) -->
                <button type="button" class="btn btn-danger mt-3 remove-bin" disabled>Remove Bin</button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Add Bin Button Positioned More Prominently -->
    <div class="text-center mt-4">
        <button type="button" id="add-bin" class="btn btn-outline-primary mb-3">+ Add Another Bin</button>
    </div>

    <!-- Total Cost Positioned Above Submit Button -->
    <div class="text-center mt-4 mb-4">
        <strong>Total Cost:</strong>
        <span id="total-cost" class="h4 text-success">0.00</span>
    </div>

    <!-- Create Schedule Button Styled to Stand Out -->
    <div class="mt-4">
        <?= form_submit('submit', isset($schedule) && $schedule ? 'Update Schedule' : 'Create Schedule', [
            'class' => 'btn btn-gradient-success w-100 shadow-lg border-0 text-white py-3 rounded-lg'
        ]); ?>
    </div>

    <?= form_close(); ?>

    <script>
        let binIndex = <?= isset($scheduleBins) ? count($scheduleBins) : 1 ?>;
        const binsContainer = document.getElementById('bins-container');
        const totalCostElement = document.getElementById('total-cost');

        document.getElementById('add-bin').addEventListener('click', () => {
            const binEntry = document.querySelector('.bin-entry').cloneNode(true);
            
            // Clear values in the cloned bin entry
            binEntry.querySelectorAll('[name]').forEach(input => {
                input.name = input.name.replace(/\[\d+\]/, `[${binIndex}]`);
                if (input.classList.contains('quantity')) input.value = '1';  // Set default quantity
                else input.value = ''; // Clear other fields
            });
            
            // Reset cost display for the new bin entry
            binEntry.querySelector('.bin-cost').textContent = '0.00';
            
            // Append the new bin entry
            binsContainer.appendChild(binEntry);
            
            // Increase index for next bin
            binIndex++;
            
            // Update the remove bin button state and total cost
            updateTotalCost();
            updateRemoveBinButtons();
        });

        // Event delegation for remove button functionality
        binsContainer.addEventListener('click', function(event) {
            if (event.target && event.target.classList.contains('remove-bin')) {
                event.target.closest('.bin-entry').remove();
                updateTotalCost();
                updateRemoveBinButtons();
            }
        });

        // Update the cost for each bin and total cost
        function updateTotalCost() {
            let totalCost = 0;
            const bins = document.querySelectorAll('.bin-entry');
            
            bins.forEach(bin => {
                const binSize = bin.querySelector('.bin-size');
                const wasteType = bin.querySelector('.waste-type');
                const quantity = bin.querySelector('.quantity').value;

                const sizeMultiplier = binSize.options[binSize.selectedIndex].dataset.multiplier;
                const wasteCost = wasteType.options[wasteType.selectedIndex].dataset.cost;

                const binCost = sizeMultiplier * wasteCost * quantity;
                bin.querySelector('.bin-cost').textContent = binCost.toFixed(2);
                totalCost += binCost;
            });

            totalCostElement.textContent = totalCost.toFixed(2);
        }

        // Enable/disable remove bin buttons based on the number of bins
        function updateRemoveBinButtons() {
            const bins = document.querySelectorAll('.bin-entry');
            bins.forEach((bin, index) => {
                const removeButton = bin.querySelector('.remove-bin');
                removeButton.disabled = (index === 0);  // Disable remove button for the first bin
            });
        }

        // Initialize cost calculation
        updateTotalCost();
    </script>
</div>

<?= $this->endSection(); ?>
