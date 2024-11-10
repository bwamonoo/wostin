<?= $this->extend('partials/header_nnb'); ?>
<?= $this->section('content'); ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm rounded p-4">
                <h2 class="text-center text-primary fw-bold mb-4">Email Verification</h2>
                <p class="text-center mb-4">Please check your email for the verification code and enter it below:</p>

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
                <form action="<?= current_url() ?>" method="post">
                    <?= csrf_field(); ?>
                    <div class="form-group mb-4">
                        <label for="email_code" class="form-label">Enter Verification Code</label>
                        <input type="text" class="form-control shadow-sm" id="email_code" name="email_code" required>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        <button type="submit" class="btn btn-primary px-4">Verify Email</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
<?= $this->include('partials/footer'); ?>
