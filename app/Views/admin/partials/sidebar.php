<div class="sidebar d-flex flex-column p-3">
    <h3 class="text-center mb-4">Admin Panel</h3>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="<?= base_url('admin/dashboard') ?>" class="nav-link <?= (uri_string() == 'admin/dashboard') ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="<?= base_url('admin/users') ?>" class="nav-link <?= (uri_string() == 'admin/users') ? 'active' : '' ?>">
                <i class="bi bi-people"></i> User Management
            </a>
        </li>
        <li>
            <a href="<?= base_url('admin/schedules') ?>" class="nav-link <?= (uri_string() == 'admin/schedules') ? 'active' : '' ?>">
                <i class="bi bi-calendar-check"></i> Schedules
            </a>
        </li>
        <li>
            <a href="<?= base_url('admin/unavailability-management') ?>" class="nav-link <?= (uri_string() == 'admin/unavailability-management') ? 'active' : '' ?>">
                <i class="bi bi-calendar-x"></i> Unavailable Dates
            </a>
        </li>
        <li>
            <a href="<?= base_url('admin/bin-waste-management') ?>" class="nav-link <?= (uri_string() == 'admin/bin-waste-management') ? 'active' : '' ?>">
                <i class="bi bi-box"></i> Bins & Wastes
            </a>
        </li>
        <li>
            <a href="<?= base_url('admin/reports') ?>" class="nav-link <?= (uri_string() == 'admin/reports') ? 'active' : '' ?>">
                <i class="bi bi-bar-chart"></i> Reports
            </a>
        </li>
    </ul>
    <hr>
    <a href="<?= base_url('users/logout') ?>" class="nav-link text-danger">
        <i class="bi bi-box-arrow-right"></i> Logout
    </a>
</div>
