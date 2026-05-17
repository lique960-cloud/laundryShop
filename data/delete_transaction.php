<?php
header('Content-Type: application/json; charset=utf-8');
require_once('../database/Database.php');
$db = new Database();

$return = ['valid' => false];

if(isset($_POST['id'])){
    $id = intval($_POST['id']);
    
    // Delete sale (sale_items cascade deleted via FK)
    $db->deleteRow("DELETE FROM product_sales WHERE sale_id = ?", [$id]);
    $return['valid'] = true;
}

echo json_encode($return);
$db->Disconnect();
