<?= $this->extend('partials/header') ?>

<?= $this->section('content') ?>
<div class="container my-5 p-5 rounded shadow" style="background-color: #f9f9f9;">
    <h1 class="text-center text-primary fw-bold mb-5">Edit Profile</h1>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger mb-4">
            <?= session()->getFlashdata('errors') ?>
        </div>
    <?php endif; ?>

    <form action="<?= site_url('users/profile/edit') ?>" method="post">
        <div class="form-group mb-4">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" class="form-control shadow-sm" value="<?= set_value('name', $user['name']) ?>" required>
        </div>

        <div class="form-group mb-4">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control shadow-sm" value="<?= set_value('email', $user['email']) ?>" required>
        </div>

        <div class="form-group mb-4">
            <label for="phone_number" class="form-label">Phone Number</label>
            <input type="text" name="phone_number" class="form-control shadow-sm" value="<?= set_value('phone_number', $user['phone_number']) ?>">
        </div>

        <div class="form-group mb-4">
            <label for="location" class="form-label">Location</label>
            <input type="text" name="location" class="form-control shadow-sm" value="<?= set_value('location', $user['location']) ?>">
        </div>

        <div class="d-flex justify-content-start mt-4">
            <button type="submit" class="btn btn-success shadow-sm px-4">Save Changes</button>
            <a href="<?= site_url('users/profile') ?>" class="btn btn-outline-secondary ms-3 px-4">Cancel</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
