<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm py-3">
    <div class="container-fluid">
        <a class="navbar-brand text-primary fw-bold fs-2 ms-5" href="<?= site_url('/') ?>">Wostin</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- <li class="nav-item">
                    <a class="nav-link <?= (uri_string() == '/') ? 'active fw-bold' : '' ?>" href="<?= site_url('/') ?>">Home</a>
                </li> -->
                <li class="nav-item">
                    <a class="nav-link <?= (uri_string() == 'dashboard') ? 'active fw-bold' : '' ?>" href="<?= site_url('dashboard') ?>">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (uri_string() == 'schedules') ? 'active fw-bold' : '' ?>" href="<?= site_url('schedules') ?>">Manage Schedules</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (uri_string() == 'users/profile') ? 'active fw-bold' : '' ?>" href="<?= site_url('users/profile') ?>">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (uri_string() == 'users/logout') ? 'active fw-bold' : 'nav-link text-danger' ?>" href="<?= site_url('users/logout') ?>">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
