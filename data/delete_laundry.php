<?php 
header('Content-Type: application/json; charset=utf-8');
require_once('../class/Laundry.php');
$return = ['valid' => false];
if(isset($_POST['id'])){
	$id = intval($_POST['id']);
	$result = $laundry->delete_laundry($id);
	$return['valid'] = $result ? true : false;
}

echo json_encode($return);
$laundry->Disconnect();