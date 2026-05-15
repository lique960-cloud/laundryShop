<?php 
require_once('database/Database.php');
$db = new Database();
$categories = $db->getRows("SELECT * FROM inventory_category ORDER BY category_name ASC");
$brands = $db->getRows("SELECT * FROM inventory_brand ORDER BY brand_name ASC");
?>
<div class="modal fade" id="modal-inventory">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">New Inventory Item</h4>
			</div>
			<div class="modal-body">
				<form id="form-inventory" class="form-horizontal" role="form">
					<input type="hidden" id="inv-type" value="insert">
					<input type="hidden" id="inv-id" name="id">
					
					<div class="form-group">
					  <label class="control-label col-sm-3" for="item_name">Item Name:</label>
					  <div class="col-sm-9">
					    <input type="text" class="form-control" id="item_name" name="item_name" placeholder="e.g. Liquid Detergent" required>
					  </div>
					</div>

					<div class="form-group">
					  <label class="control-label col-sm-3" for="category_id">Category:</label>
					  <div class="col-sm-9">
					    <select class="form-control" id="category_id" name="category_id">
					    	<option value="">-- Select Category --</option>
					    	<?php foreach($categories as $cat): ?>
					    		<option value="<?= $cat['id']; ?>"><?= $cat['category_name']; ?></option>
					    	<?php endforeach; ?>
					    </select>
					  </div>
					</div>

					<div class="form-group">
					  <label class="control-label col-sm-3" for="brand_id">Brand:</label>
					  <div class="col-sm-9">
					    <select class="form-control" id="brand_id" name="brand_id">
					    	<option value="">-- Select Brand --</option>
					    	<?php foreach($brands as $brand): ?>
					    		<option value="<?= $brand['id']; ?>"><?= $brand['brand_name']; ?></option>
					    	<?php endforeach; ?>
					    </select>
					  </div>
					</div>

					<div class="form-group">
					  <label class="control-label col-sm-3" for="quantity">Quantity:</label>
					  <div class="col-sm-9">
					    <input type="number" step="0.01" class="form-control" id="quantity" name="quantity" value="0" required>
					  </div>
					</div>

					<div class="form-group">
					  <label class="control-label col-sm-3" for="unit">Unit:</label>
					  <div class="col-sm-9">
					    <input type="text" class="form-control" id="unit" name="unit" placeholder="e.g. Liters, kg, Pcs" required>
					  </div>
					</div>

					<div class="form-group">
					  <label class="control-label col-sm-3" for="price">Price:</label>
					  <div class="col-sm-9">
					    <input type="number" step="0.01" class="form-control" id="price" name="price" value="0" required>
					  </div>
					</div>

					<div class="form-group">
					  <label class="control-label col-sm-3" for="low_stock_threshold">Low Stock Alert:</label>
					  <div class="col-sm-9">
					    <input type="number" step="0.01" class="form-control" id="low_stock_threshold" name="low_stock_threshold" value="5" required>
					    <small class="text-muted">You will be alerted when stock falls below this amount.</small>
					  </div>
					</div>

					<div class="form-group"> 
					  <div class="col-sm-offset-3 col-sm-9">
					    <button type="submit" class="btn btn-primary">Save Item</button>
					  </div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
			</div>
		</div>
	</div>
</div>
<?php $db->Disconnect(); ?>
