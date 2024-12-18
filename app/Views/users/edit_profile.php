<?= $this->extend('partials/header') ?>

<?= $this->section('content') ?>
<div class="container my-5 p-5">
    <h1 class="text-center text-primary fw-bold mb-5">Edit Profile</h1>

    <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger p-2">
                        <ul class="mb-0">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

    <form action="<?= site_url('users/edit-profile') ?>" method="post">
        <div class="form-group mb-4">
            <label for="name" class="form-label" style="color: #808080">Name</label>
            <input type="text" name="name" class="form-control shadow-sm" value="<?= set_value('name', $user['name']) ?>" required>
        </div>

        <div class="form-group mb-4">
            <label for="email" class="form-label" style="color: #808080">Email</label>
            <input type="email" name="email" class="form-control shadow-sm" value="<?= set_value('email', $user['email']) ?>" required>
        </div>

        <!-- <div class="form-group mb-4">
            <label for="phone_number" class="form-label" style="color: #808080">Phone Number</label>
            <input type="text" name="phone_number" class="form-control shadow-sm" value="<?= set_value('phone_number', $user['phone_number']) ?>">
        </div> -->

        <div class="form-group mb-4">
            <label for="location" class="form-label" style="color: #808080">Location</label>
            <input type="text" name="location" class="form-control shadow-sm" value="<?= set_value('location', $user['location']) ?>">
        </div>

        <div class="d-flex justify-content-start mt-4">
            <button type="submit" class="btn btn-success shadow-sm px-4">Save Changes</button>
            <a href="<?= site_url('users/profile') ?>" class="btn btn-outline-secondary ms-3 px-4">Cancel</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
