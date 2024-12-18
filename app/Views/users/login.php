<?= $this->extend('partials/header_nnb'); ?>
<?= $this->section('content'); ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="content p-4">
                <!-- Wostin Logo -->
                <div class="text-center mb-2">
                    <img src="<?= base_url('assets/images/wostin-logo.png'); ?>" alt="Wostin Logo" class="img-fluid" style="max-width: 60px;">
                </div>

                <h2 class="text-center text-secondary fw-bold mb-4">User Login</h2>

                <!-- Display flash messages -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success mb-4">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger mb-4">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <!-- Login Form -->
                <?= form_open('users/login') ?>
                    <div class="form-group mb-4">
                        <?= form_label('Email:', 'email', ['class' => 'form-label text-dark']) ?>
                        <?= form_input(['type' => 'email', 'name' => 'email', 'id' => 'email', 'class' => 'form-control shadow-sm', 'required' => 'required', 'placeholder' => 'Enter your email']); ?>
                    </div>

                    <div class="form-group mb-4">
                        <?= form_label('Password:', 'password', ['class' => 'form-label text-dark']) ?>
                        <?= form_password(['name' => 'password', 'id' => 'password', 'class' => 'form-control shadow-sm', 'required' => 'required', 'placeholder' => 'Enter your password']); ?>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        <?= form_submit(['class' => 'btn btn-primary px-4', 'value' => 'Login']) ?>
                    </div>
                <?= form_close() ?>

                <p class="text-center mt-4" style="color: #808080">
                    Don't have an account? 
                    <a href="<?= site_url('users/register'); ?>">Register</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
<?= $this->include('partials/footer'); ?>
