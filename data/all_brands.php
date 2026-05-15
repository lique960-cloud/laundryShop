<?php 
require_once('../database/Database.php');
$db = new Database();
$res = $db->getRows("SELECT * FROM inventory_brand ORDER BY brand_name ASC");
?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Brand Name</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($res as $r): ?>
        <tr>
            <td><?= $r['brand_name']; ?></td>
            <td><button onclick="deleteBrand(<?= $r['id']; ?>)" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php $db->Disconnect(); ?>
