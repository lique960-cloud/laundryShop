<?php
header('Content-Type: application/json; charset=utf-8');
require_once(__DIR__ . '/../database/Database.php');
$db = new Database();
$return = ['valid' => false];

try {
    if(!isset($_SESSION['customer_logged'])){
        $return['msg'] = 'Not logged in.';
    } elseif(isset($_POST['weight']) && isset($_POST['type_id'])){
        $custId = $_SESSION['customer_logged'];
        $weight = floatval($_POST['weight']);
        $typeId = intval($_POST['type_id']);

        // Get customer name
        $customer = $db->getRow("SELECT cust_fullname FROM customers WHERE cust_id = ?", [$custId]);
        if(!$customer){
            $return['msg'] = 'Customer not found.';
        } else {
            $customerName = $customer['cust_fullname'];

            // Get next priority number
            $maxPriority = $db->getRow("SELECT COALESCE(MAX(laun_priority), 0) + 1 as next_p FROM laundry WHERE laun_claimed = 0");
            $priority = $maxPriority['next_p'];

            // Insert into the same laundry table that admin sees
            $result = $db->insertRow(
                "INSERT INTO laundry (customer_name, laun_priority, laun_weight, laun_type_id) VALUES (?, ?, ?, ?)",
                [$customerName, $priority, $weight, $typeId]
            );

            if($result){
                $return['valid'] = true;
                $return['priority'] = $priority;
                $return['weight'] = $weight;
                $return['type_id'] = $typeId;
                $return['msg'] = 'Order placed successfully! Priority #' . $priority;
            }
        }
    }
} catch (Exception $ex) {
    $return['msg'] = 'Error: ' . $ex->getMessage();
}

$db->Disconnect();
echo json_encode($return);
exit;
