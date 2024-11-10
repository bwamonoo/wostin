<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- when loaded from public (offline) -->
    <link rel="stylesheet" href="<?= base_url('css/bootstrap.min.css') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="<?= base_url('js/chart.min.js') ?>"></script>

    <!-- when loaded from online (using CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">


    <style>
        /* Sidebar styling */
        .sidebar {
            width: 220px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #343a40;
            color: #ffffff;
            z-index: 1000;
            transition: all 0.3s ease;
        }
        .sidebar h3 {
            color: #ffdd40;
        }
        .sidebar a.nav-link {
            color: #ffffff;
        }
        .sidebar a.nav-link:hover,
        .sidebar a.nav-link.active {
            color: #ffdd40;
            background-color: #495057;
            border-radius: 5px;
        }
        .content {
            margin-left: 230px;
            padding: 10px;
        }
    </style>
</head>
<body>
    <?= $this->include('admin/partials/sidebar') ?>

    <main class="content">
        <?= $this->renderSection('content') ?>
    </main>

    <script src="<?= base_url('js/bootstrap.bundle.min.js') ?>"></script>
    
    <!-- Bootstrap JavaScript (with Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
