<?= $this->extend('partials/header'); ?>
<?= $this->section('content'); ?>

<h1>User Login</h1>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
<?php endif; ?>

<?= form_open('users/login'); ?>
    <?= csrf_field(); ?>
    <div>
        <?= form_label('Email:', 'email'); ?>
        <?= form_input(['type' => 'email', 'name' => 'email', 'id' => 'email', 'required' => 'required']); ?>
    </div>
    <div>
        <?= form_label('Password:', 'password'); ?>
        <?= form_password(['name' => 'password', 'id' => 'password', 'required' => 'required']); ?>
    </div>
    <button type="submit">Login</button>
<?= form_close(); ?>

<a href="<?= site_url('users/register'); ?>">Don't have an account? Register</a>

<?= $this->endSection(); ?>
<?= $this->include('partials/footer'); ?>
