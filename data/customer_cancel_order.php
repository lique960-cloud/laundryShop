<?php
header('Content-Type: application/json; charset=utf-8');
require_once(__DIR__ . '/../database/Database.php');
$db = new Database();
$return = ['valid' => false];

try {
    if(!isset($_SESSION['customer_logged'])){
        $return['msg'] = 'Not logged in.';
    } elseif(isset($_POST['laun_id'])){
        $launId = intval($_POST['laun_id']);
        $custId = $_SESSION['customer_logged'];

        // Get customer name
        $customer = $db->getRow("SELECT cust_fullname FROM customers WHERE cust_id = ?", [$custId]);
        if(!$customer){
            $return['msg'] = 'Customer not found.';
        } else {
            // Verify this order belongs to the customer AND is not yet claimed
            $order = $db->getRow(
                "SELECT * FROM laundry WHERE laun_id = ? AND customer_name = ? AND laun_claimed = 0",
                [$launId, $customer['cust_fullname']]
            );

            if(!$order){
                $return['msg'] = 'Order not found or already completed.';
            } else {
                $db->deleteRow("DELETE FROM laundry WHERE laun_id = ?", [$launId]);
                $return['valid'] = true;
                $return['msg'] = 'Order cancelled successfully!';
            }
        }
    } else {
        $return['msg'] = 'Missing order ID.';
    }
} catch (Exception $ex) {
    $return['msg'] = 'Error: ' . $ex->getMessage();
}

$db->Disconnect();
echo json_encode($return);
exit;
