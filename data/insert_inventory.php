<?php 
require_once('../database/Database.php');
$db = new Database();

if(!isset($_SESSION['user_role']) || strtolower($_SESSION['user_role']) != 'admin'){
    echo json_encode(['valid' => false, 'msg' => 'Access denied.']);
    $db->Disconnect();
    exit;
}

if(isset($_POST['item_name'])){
	$item_name = $_POST['item_name'];
	$category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : null;
	$brand_id = !empty($_POST['brand_id']) ? $_POST['brand_id'] : null;
	$quantity = $_POST['quantity'];
	$unit = $_POST['unit'];
	$price = $_POST['price'];
	$low_stock_threshold = $_POST['low_stock_threshold'];

	$sql = "INSERT INTO inventory (item_name, category_id, brand_id, quantity, unit, price, low_stock_threshold) 
			VALUES (?, ?, ?, ?, ?, ?, ?)";
	$params = [$item_name, $category_id, $brand_id, $quantity, $unit, $price, $low_stock_threshold];
	
	try {
		$db->insertRow($sql, $params);
		$last_id = $db->lastID();
		
		// Log the initial stock in
		if($quantity > 0){
			$db->insertRow("INSERT INTO inventory_logs (item_id, type, quantity, reason) VALUES (?, 'Stock In', ?, 'Initial Stock')", [$last_id, $quantity]);
		}

		echo json_encode(['valid' => true, 'msg' => 'Item added successfully!']);
	} catch (Exception $e) {
		echo json_encode(['valid' => false, 'msg' => $e->getMessage()]);
	}
}
$db->Disconnect();
?>
