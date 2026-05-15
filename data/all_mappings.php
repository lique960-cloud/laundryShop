<?php 
require_once('../database/Database.php');
$db = new Database();
$sql = "SELECT m.*, lt.laun_type_desc, i.item_name, i.unit 
        FROM laundry_type_supplies m 
        JOIN laundry_type lt ON m.laun_type_id = lt.laun_type_id 
        JOIN inventory i ON m.item_id = i.id 
        ORDER BY lt.laun_type_desc ASC";
$res = $db->getRows($sql);
?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Laundry Service</th>
            <th>Supply Item</th>
            <th>Usage per Unit</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($res as $r): ?>
        <tr>
            <td><?= $r['laun_type_desc']; ?></td>
            <td><?= $r['item_name']; ?></td>
            <td><?= number_format($r['quantity_used'], 3); ?> <?= $r['unit']; ?></td>
            <td><button onclick="deleteMapping(<?= $r['id']; ?>)" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php $db->Disconnect(); ?>
