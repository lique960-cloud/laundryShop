<?php 
require_once('../database/Database.php');
$db = new Database();
$res = $db->getRows("SELECT * FROM inventory_category ORDER BY category_name ASC");
?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Category Name</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($res as $r): ?>
        <tr>
            <td><?= $r['category_name']; ?></td>
            <td><button onclick="deleteCategory(<?= $r['id']; ?>)" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php $db->Disconnect(); ?>
