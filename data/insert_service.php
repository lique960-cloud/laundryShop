<?php 
require_once('../database/Database.php');
$db = new Database();

if(!isset($_SESSION['user_role']) || strtolower($_SESSION['user_role']) != 'admin'){
    echo json_encode(['valid' => false, 'msg' => 'Access denied.']);
    $db->Disconnect();
    exit;
}

if(isset($_POST['service_name'])){
    $service_name = $_POST['service_name'];
    $price = $_POST['price'];

    $sql = "INSERT INTO services (service_name, price) VALUES (?, ?)";
    $params = [$service_name, $price];
    
    try {
        $db->insertRow($sql, $params);
        echo json_encode(['valid' => true, 'msg' => 'Service added successfully!']);
    } catch (Exception $e) {
        echo json_encode(['valid' => false, 'msg' => $e->getMessage()]);
    }
}
$db->Disconnect();
?>
