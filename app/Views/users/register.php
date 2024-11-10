<?= $this->extend('partials/header'); ?>
<?= $this->section('content'); ?>

<h1>User Registration</h1>

<!-- Display validation errors -->
<?php if (session()->getFlashdata('errors')): ?>
    <ul>
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <li><?= esc($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<!-- Display flash messages -->
<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<!-- Display success messages -->
<?php if (session()->getFlashdata('success')): ?>
    <p><?= esc(session()->getFlashdata('success')) ?></p>
<?php endif; ?>

<?= form_open(current_url()); ?>
    <?= csrf_field(); ?>
    <div>
        <?= form_label('Name:', 'name'); ?>
        <?= form_input(['name' => 'name', 'id' => 'name', 'value' => old('name'), 'required' => 'required']); ?>
    </div>
    <div>
        <?= form_label('Email:', 'email'); ?>
        <?= form_input(['type' => 'email', 'name' => 'email', 'id' => 'email', 'value' => old('email'), 'required' => 'required']); ?>
    </div>
    <div>
        <?= form_label('Phone Number:', 'phone_number'); ?>
        <?= form_input(['type' => 'text', 'name' => 'phone_number', 'id' => 'phone_number', 'value' => old('phone_number'), 'required' => 'required']); ?>
    </div>
    <div>
        <?= form_label('Password:', 'password'); ?>
        <?= form_password(['name' => 'password', 'id' => 'password', 'required' => 'required']); ?>
    </div>
    <div>
        <?= form_label('Location:', 'location'); ?>
        <?= form_input(['name' => 'location', 'id' => 'location', 'value' => old('location'), 'required' => 'required']); ?>
    </div>

    <?= form_hidden('user_group_id', "2") ?>

    <button type="submit">Register</button>
<?= form_close(); ?>

<a href="<?= site_url('users/login'); ?>">Already have an account? Login</a>

<?= $this->endSection(); ?>
<?= $this->include('partials/footer'); ?>
