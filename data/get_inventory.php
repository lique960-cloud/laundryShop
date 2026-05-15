<?php 
require_once('../database/Database.php');
$db = new Database();

if(isset($_POST['id'])){
	$id = $_POST['id'];
	$sql = "SELECT * FROM inventory WHERE id = ?";
	$res = $db->getRow($sql, [$id]);
	echo json_encode($res);
}
$db->Disconnect();
?>
