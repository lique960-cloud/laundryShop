<?php
header('Content-Type: application/json; charset=utf-8');
require_once(__DIR__ . '/../database/Database.php');
$db = new Database();
$return = ['valid' => false];

try {
    if(isset($_POST['email']) && isset($_POST['password'])){
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        $customer = $db->getRow("SELECT * FROM customers WHERE cust_email = ?", [$email]);
        if($customer && password_verify($password, $customer['cust_password'])){
            $return['valid'] = true;
            $_SESSION['customer_logged'] = $customer['cust_id'];
        } else {
            $return['msg'] = 'Invalid email or password!';
        }
    }
} catch (Exception $ex) {
    $return['msg'] = 'Error: ' . $ex->getMessage();
}

$db->Disconnect();
echo json_encode($return);
exit;
