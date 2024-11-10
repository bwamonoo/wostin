<?= $this->extend('partials/header_nnb'); ?>
<?= $this->section('content'); ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm rounded p-4">
                <h2 class="text-center text-primary fw-bold mb-4">Phone Verification</h2>
                <p class="text-center mb-4">Please check your phone for the verification code and enter it below:</p>

                <!-- Display flash messages -->
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <!-- Verification Form -->
                <?= form_open(current_url()) ?>
                    <div class="form-group mb-4">
                        <?= form_label('Enter Verification Code', 'phone_code', ['class' => 'form-label']) ?>
                        <?= form_input(['name' => 'phone_code', 'id' => 'phone_code', 'class' => 'form-control shadow-sm', 'required' => 'required']) ?>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        <?= form_submit(['class' => 'btn btn-primary px-4', 'value' => 'Verify Phone']) ?>
                    </div>
                <?= form_close() ?>

                <p class="text-center mt-4">
                    Didn't receive the code? 
                    <a href="<?= site_url('/users/resend-phone-code'); ?>">Resend Verification Code</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
<?= $this->include('partials/footer'); ?>
