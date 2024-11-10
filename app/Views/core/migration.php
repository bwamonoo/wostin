
<?= form_open(current_url()); ?>
    <?= csrf_field(); ?>
    <div>
        <?= form_label('Name:', 'name'); ?>
        <?= form_input(['name' => 'name', 'id' => 'name', 'value' => old('name'), 'required' => 'required']); ?>
    </div>

    <div>
        <?= form_label('Password:', 'password'); ?>
        <?= form_password(['name' => 'password', 'id' => 'password', 'required' => 'required']); ?>
    </div>
    <button type="submit">Run Migrations</button>
<?= form_close(); ?>