<?php 
require_once('../database/Database.php');
$db = new Database();

if(!isset($_SESSION['user_role']) || strtolower($_SESSION['user_role']) != 'admin'){
    echo json_encode(['valid' => false, 'msg' => 'Access denied.']);
    $db->Disconnect();
    exit;
}

if(isset($_POST['id'])){
	$id = $_POST['id'];
	$item_name = $_POST['item_name'];
	$category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : null;
	$brand_id = !empty($_POST['brand_id']) ? $_POST['brand_id'] : null;
	$quantity = $_POST['quantity'];
	$unit = $_POST['unit'];
	$price = $_POST['price'];
	$low_stock_threshold = $_POST['low_stock_threshold'];

	// Get current quantity for logging if changed manually
	$current = $db->getRow("SELECT quantity FROM inventory WHERE id = ?", [$id]);
	$diff = $quantity - $current['quantity'];

	$sql = "UPDATE inventory SET item_name = ?, category_id = ?, brand_id = ?, quantity = ?, unit = ?, price = ?, low_stock_threshold = ? 
			WHERE id = ?";
	$params = [$item_name, $category_id, $brand_id, $quantity, $unit, $price, $low_stock_threshold, $id];
	
	try {
		$db->updateRow($sql, $params);
		
		if($diff != 0){
			$type = ($diff > 0) ? 'Stock In' : 'Stock Out';
			$db->insertRow("INSERT INTO inventory_logs (item_id, type, quantity, reason) VALUES (?, ?, ?, 'Manual Adjustment')", [$id, $type, abs($diff)]);
		}

		echo json_encode(['valid' => true, 'msg' => 'Item updated successfully!']);
	} catch (Exception $e) {
		echo json_encode(['valid' => false, 'msg' => $e->getMessage()]);
	}
}
$db->Disconnect();
?>
