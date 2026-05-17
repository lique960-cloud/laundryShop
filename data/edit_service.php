<?php 
require_once('../database/Database.php');
$db = new Database();

if(!isset($_SESSION['user_role']) || strtolower($_SESSION['user_role']) != 'admin'){
    echo json_encode(['valid' => false, 'msg' => 'Access denied.']);
    $db->Disconnect();
    exit;
}

if(isset($_POST['id'])){
    $id = $_POST['id'];
    $service_name = $_POST['service_name'];
    $price = $_POST['price'];

    $sql = "UPDATE services SET service_name = ?, price = ? WHERE id = ?";
    $params = [$service_name, $price, $id];
    
    try {
        $db->updateRow($sql, $params);
        echo json_encode(['valid' => true, 'msg' => 'Service updated successfully!']);
    } catch (Exception $e) {
        echo json_encode(['valid' => false, 'msg' => $e->getMessage()]);
    }
}
$db->Disconnect();
?>
