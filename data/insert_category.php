<?php 
require_once('../database/Database.php');
$db = new Database();
if(isset($_POST['category_name'])){
    try {
        $db->insertRow("INSERT INTO inventory_category (category_name) VALUES (?)", [$_POST['category_name']]);
        echo json_encode(['valid' => true]);
    } catch (Exception $e) { echo json_encode(['valid' => false, 'msg' => $e->getMessage()]); }
}
$db->Disconnect();
?>
