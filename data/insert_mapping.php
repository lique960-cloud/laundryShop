<?php 
require_once('../database/Database.php');
$db = new Database();
if(isset($_POST['laun_type_id'])){
    try {
        $db->insertRow("INSERT INTO laundry_type_supplies (laun_type_id, item_id, quantity_used) VALUES (?, ?, ?)", 
            [$_POST['laun_type_id'], $_POST['item_id'], $_POST['quantity_used']]);
        echo json_encode(['valid' => true]);
    } catch (Exception $e) { echo json_encode(['valid' => false, 'msg' => $e->getMessage()]); }
}
$db->Disconnect();
?>
