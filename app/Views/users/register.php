<?= $this->extend('partials/header_nnb'); ?>
<?= $this->section('content'); ?>

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
            <!-- Wostin Branding -->
            <div class="text-center mb-4">
                <h1 class="text-primary fw-bold fs-2">Wostin</h1>
            </div>

            <div class="p-3 rounded shadow-sm" style="background-color: #f9f9f9;">
            <h2 class="text-center text-secondary fw-bold mb-3 fs-3">User Registration</h2>


                <!-- Display validation errors -->
                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger p-2">
                        <ul class="mb-0">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Display flash messages -->
                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger p-2">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success p-2">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?= form_open(current_url(), ['class' => 'needs-validation']); ?>
                    <?= csrf_field(); ?>

                    <div class="mb-2">
                        <?= form_label('Name', 'name', ['class' => 'form-label']); ?>
                        <?= form_input(['name' => 'name', 'id' => 'name', 'value' => old('name'), 'class' => 'form-control form-control-sm shadow-sm', 'required' => 'required']); ?>
                    </div>

                    <div class="mb-2">
                        <?= form_label('Email', 'email', ['class' => 'form-label']); ?>
                        <?= form_input(['type' => 'email', 'name' => 'email', 'id' => 'email', 'value' => old('email'), 'class' => 'form-control form-control-sm shadow-sm', 'required' => 'required']); ?>
                    </div>

                    <div class="mb-2">
                        <?= form_label('Phone Number', 'phone_number', ['class' => 'form-label']); ?>
                        <?= form_input(['type' => 'text', 'name' => 'phone_number', 'id' => 'phone_number', 'value' => old('phone_number'), 'class' => 'form-control form-control-sm shadow-sm', 'required' => 'required']); ?>
                    </div>

                    <div class="mb-2">
                        <?= form_label('Password', 'password', ['class' => 'form-label']); ?>
                        <?= form_password(['name' => 'password', 'id' => 'password', 'class' => 'form-control form-control-sm shadow-sm', 'required' => 'required']); ?>
                    </div>

                    <div class="mb-2">
                        <?= form_label('Location', 'location', ['class' => 'form-label']); ?>
                        <?= form_input(['name' => 'location', 'id' => 'location', 'value' => old('location'), 'class' => 'form-control form-control-sm shadow-sm', 'required' => 'required']); ?>
                    </div>

                    <?= form_hidden('user_group_id', "2") ?>

                    <button type="submit" class="btn btn-primary w-100 shadow-sm py-2 mt-3">Register</button>
                <?= form_close(); ?>

                <div class="text-center mt-2">
                    <a href="<?= site_url('users/login'); ?>" class="text-secondary">Already have an account? Login</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
<?= $this->include('partials/footer'); ?>
