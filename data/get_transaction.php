<?php
header('Content-Type: application/json; charset=utf-8');
require_once('../database/Database.php');
$db = new Database();

$return = ['sale' => null, 'items' => []];

if(isset($_POST['id'])){
    $id = intval($_POST['id']);
    $sale = $db->getRow("SELECT * FROM product_sales WHERE sale_id = ?", [$id]);
    if($sale){
        $return['sale'] = $sale;
        $return['items'] = $db->getRows("SELECT * FROM sale_items WHERE sale_id = ?", [$id]);
    }
}

echo json_encode($return);
$db->Disconnect();
