<?php 
require_once('../class/Sales.php');
if(isset($_POST['id'])){
	$id = $_POST['id'];

	$result = $sales->delete_sale($id);
	echo json_encode($result);
}//end isset

$sales->Disconnect();