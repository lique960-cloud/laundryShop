<?php 
require_once('../database/Database.php');
$db = new Database();

if(isset($_POST['id'])){
	$id = $_POST['id'];
	$sql = "DELETE FROM inventory WHERE id = ?";
	try {
		$db->deleteRow($sql, [$id]);
		// Also delete logs? Usually logs are kept for audit, but let's keep it simple.
		echo json_encode(['valid' => true, 'msg' => 'Item deleted successfully!']);
	} catch (Exception $e) {
		echo json_encode(['valid' => false, 'msg' => $e->getMessage()]);
	}
}
$db->Disconnect();
?>
