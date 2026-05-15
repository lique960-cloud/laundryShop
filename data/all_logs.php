<?php 
require_once('../database/Database.php');
$db = new Database();

$sql = "SELECT l.*, i.item_name, i.unit 
        FROM inventory_logs l 
        JOIN inventory i ON l.item_id = i.id 
        ORDER BY l.date_created DESC";
$res = $db->getRows($sql);
?>
<table id="myTable-logs" class="table table-bordered table-hover">
	<thead>
		<tr>
			<th>Item Name</th>
			<th>Type</th>
			<th>Quantity</th>
			<th>Reason/Remarks</th>
			<th>Date & Time</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($res as $r): 
			$typeClass = ($r['type'] == 'Stock In') ? 'type-in' : 'type-out';
		?>
			<tr>
				<td><?= $r['item_name']; ?></td>
				<td><span class="<?= $typeClass; ?>"><?= $r['type']; ?></span></td>
				<td><?= number_format($r['quantity'], 2); ?> <?= $r['unit']; ?></td>
				<td><?= $r['reason']; ?></td>
				<td><?= date('M d, Y h:i A', strtotime($r['date_created'])); ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php $db->Disconnect(); ?>
