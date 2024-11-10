<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Phone</title>
    <link rel="stylesheet" href="path/to/bootstrap.css">
</head>
<body>

<div class="container">
    <h2>Phone Verification</h2>

    <p>Please check your phone for the verification code and enter it below:</p>

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

    <!-- Verification Form -->
    <?= form_open(current_url()) ?>
        <div class="form-group">
            <?= form_label('Enter Verification Code', 'phone_code') ?>
            <?= form_input(['name' => 'phone_code', 'id' => 'phone_code', 'class' => 'form-control', 'required' => 'required']) ?>
        </div>
        <?= form_submit(['class' => 'btn btn-primary', 'value' => 'Verify Phone']) ?>
    <?= form_close() ?>

    <p>
        Didn't receive the code? 
        <a href="<?= site_url('/users/resend-phone-code'); ?>">Resend Verification Code</a>
    </p>
</div>

</body>
</html>
