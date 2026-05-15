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

		$saleRes = $sales->new_sales($customer, $type_desc, $laundry_rec, $amount);
		$claimRes = $laundry->claim_laundry($id);

		if($saleRes && $claimRes){
			$return['valid'] = true;
		}
	}
}

echo json_encode($return);
$laundry->Disconnect();