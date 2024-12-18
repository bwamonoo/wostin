<?= $this->extend('partials/header') ?>

<?= $this->section('content') ?>
<div class="container my-5 p-4" >
    <h1 class="text-center text-primary fw-bold mb-5">Your Profile</h1>

    <div class="card border-0 shadow-sm mx-auto" style="max-width: 600px;">
        <div class="card-body">
            <div class="profile-info mb-4">
                <p class="mb-3"><strong>Name:</strong> <?= esc($user['name']) ?></p>
                <p class="mb-3"><strong>Email:</strong> <?= esc($user['email']) ?></p>
                <!-- <p class="mb-3"><strong>Phone Number:</strong> <?= esc($user['phone_number']) ?></p>
                <p class="mb-3"><strong>Location:</strong> <?= esc($user['location']) ?></p> -->
            </div>
            <div class="text-end">
                <a href="<?= site_url('users/edit-profile') ?>" class="btn btn-primary">Edit Profile</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
