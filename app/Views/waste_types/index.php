<?= $this->extend('partials/header'); ?>
<?= $this->section('content'); ?>

<h1>Waste Types</h1>
<a href="<?= site_url('waste_types/create'); ?>">Add New Waste Type</a>
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Base Price</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($wasteTypes as $type): ?>
            <tr>
                <td><?= esc($type['name']); ?></td>
                <td><?= esc($type['base_price']); ?></td>
                <td>
                    <a href="<?= site_url('waste_types/edit/' . $type['id']); ?>">Edit</a>
                    <a href="<?= site_url('waste_types/delete/' . $type['id']); ?>" onclick="return confirm('Are you sure?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection(); ?>
<?= $this->include('partials/footer'); ?>
