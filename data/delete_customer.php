<?php
header('Content-Type: application/json; charset=utf-8');
require_once(__DIR__ . '/../database/Database.php');
$db = new Database();
$return = ['valid' => false];

try {
    if(isset($_SESSION['user_logged']) && isset($_POST['id'])){
        $id = intval($_POST['id']);
        $db->deleteRow("DELETE FROM customers WHERE cust_id = ?", [$id]);
        $return['valid'] = true;
        $return['msg'] = 'Customer deleted successfully!';
    } else {
        $return['msg'] = 'Unauthorized action.';
    }
} catch (Exception $ex) {
    $return['msg'] = 'Error: ' . $ex->getMessage();
}

$db->Disconnect();
echo json_encode($return);
exit;
