<?php 
require_once('../database/Database.php');
$db = new Database();
if(isset($_POST['brand_name'])){
    try {
        $db->insertRow("INSERT INTO inventory_brand (brand_name) VALUES (?)", [$_POST['brand_name']]);
        echo json_encode(['valid' => true]);
    } catch (Exception $e) { echo json_encode(['valid' => false, 'msg' => $e->getMessage()]); }
}
$db->Disconnect();
?>
