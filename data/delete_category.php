<?php 
require_once('../database/Database.php');
$db = new Database();
if(isset($_POST['id'])){
    $db->deleteRow("DELETE FROM inventory_category WHERE id = ?", [$_POST['id']]);
    echo json_encode(['valid' => true]);
}
$db->Disconnect();
?>
