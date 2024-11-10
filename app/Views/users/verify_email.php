<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Email</title>
    <link rel="stylesheet" href="path/to/bootstrap.css">
</head>
<body>

<div class="container">
    <h2>Email Verification</h2>

    <p>Please check your email for the verification code and enter it below:</p>

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
            <?= form_label('Enter Verification Code', 'email_code') ?>
            <?= form_input(['name' => 'email_code', 'id' => 'email_code', 'class' => 'form-control', 'required' => 'required']) ?>
        </div>
        <?= form_submit(['class' => 'btn btn-primary', 'value' => 'Verify Email']) ?>
    <?= form_close() ?>

    <p>
        Didn't receive the code? 
        <a href="<?= site_url('/users/resend-email-code'); ?>">Resend Verification Code</a>
    </p>
</div>

</body>
</html>
