<?php 
require_once('../database/Database.php');
$db = new Database();

$sql = "SELECT i.*, c.category_name, b.brand_name 
        FROM inventory i 
        LEFT JOIN inventory_category c ON i.category_id = c.id 
        LEFT JOIN inventory_brand b ON i.brand_id = b.id 
        ORDER BY i.item_name ASC";
$res = $db->getRows($sql);
?>
<table id="myTable-inventory" class="table table-bordered table-hover">
	<thead>
		<tr>
			<th><center><input type="checkbox" id="check-all-inv"></center></th>
			<th>Item Name</th>
			<th>Category</th>
			<th>Brand</th>
			<th>Quantity</th>
			<th>Unit</th>
			<th>Price</th>
			<th>Status</th>
			<th><center>Action</center></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($res as $r): 
			$statusClass = ($r['quantity'] <= $r['low_stock_threshold']) ? 'stock-low' : 'stock-ok';
			$statusText = ($r['quantity'] <= $r['low_stock_threshold']) ? '<i class="fa fa-warning"></i> Low Stock' : '<i class="fa fa-check"></i> Sufficient';
		?>
			<tr>
				<td><center><input type="checkbox" value="<?= $r['id']; ?>" class="check-inv"></center></td>
				<td><?= $r['item_name']; ?></td>
				<td><span class="badge badge-category"><?= $r['category_name'] ?? 'N/A'; ?></span></td>
				<td><span class="badge badge-brand"><?= $r['brand_name'] ?? 'N/A'; ?></span></td>
				<td class="<?= $statusClass; ?>"><?= number_format($r['quantity'], 2); ?></td>
				<td><?= $r['unit']; ?></td>
				<td>₱<?= number_format($r['price'], 2); ?></td>
				<td class="<?= $statusClass; ?>"><?= $statusText; ?></td>
				<td>
					<center>
						<button onclick="editInventory('<?= $r['id']; ?>');" type="button" class="btn btn-warning btn-xs">
							<i class="fa fa-edit"></i> Edit
						</button>
					</center>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<script>
	$('#check-all-inv').click(function(){
		$('.check-inv').prop('checked', $(this).prop('checked'));
	});
</script>

<?php $db->Disconnect(); ?>
