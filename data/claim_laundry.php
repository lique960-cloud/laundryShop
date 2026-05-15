<?php 
header('Content-Type: application/json; charset=utf-8');
require_once('../class/Laundry.php');//para e update ang claim to 1
require_once('../class/Sales.php');
$return = ['valid' => false];
if(isset($_POST['id'])){
	$id = intval($_POST['id']);

	$details = $laundry->get_laundry2($id);
	if($details){
		$customer = $details['customer_name'];
		$type_desc = $details['laun_type_desc'];
		$laundry_rec = $details['laun_date_received'];
		$amount = $details['laun_weight'] * $details['laun_type_price'];
		$laun_type_id = $details['laun_type_id'];
		$weight = $details['laun_weight'];

		$saleRes = $sales->new_sales($customer, $type_desc, $laundry_rec, $amount);
		$claimRes = $laundry->claim_laundry($id);

		if($saleRes && $claimRes){
			// Stock Deduction Logic
			$supplies = $laundry->getRows("SELECT * FROM laundry_type_supplies WHERE laun_type_id = ?", [$laun_type_id]);
			foreach($supplies as $s){
				$item_id = $s['item_id'];
				$deduction = $s['quantity_used'] * $weight;
				
				// Deduct from inventory
				$laundry->updateRow("UPDATE inventory SET quantity = quantity - ? WHERE id = ?", [$deduction, $item_id]);
				
				// Log the stock out
				$laundry->insertRow("INSERT INTO inventory_logs (item_id, type, quantity, reason) VALUES (?, 'Stock Out', ?, ?)", 
					[$item_id, $deduction, "Service: $type_desc for $customer"]);
			}
			$return['valid'] = true;
		}
	}
}

echo json_encode($return);
$laundry->Disconnect();