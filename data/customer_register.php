<?php
header('Content-Type: application/json; charset=utf-8');
require_once(__DIR__ . '/../database/Database.php');
$db = new Database();
$return = ['valid' => false];

try {
    if(isset($_POST['fullname']) && isset($_POST['email']) && isset($_POST['mobile']) && isset($_POST['password'])){
        $fullname = trim($_POST['fullname']);
        $email = trim($_POST['email']);
        $mobile = trim($_POST['mobile']);
        $address = isset($_POST['address']) ? trim($_POST['address']) : '';
        $password = trim($_POST['password']);

        // Validate
        if(strlen($fullname) < 2){
            $return['msg'] = 'Please enter your complete name.';
        } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $return['msg'] = 'Please enter a valid email address.';
        } elseif(strlen($mobile) < 10){
            $return['msg'] = 'Please enter a valid contact number.';
        } elseif(strlen($address) < 10){
            $return['msg'] = 'Please enter a complete address for pickup & delivery.';
        } elseif(strlen($password) < 6){
            $return['msg'] = 'Password must be at least 6 characters.';
        } else {
            // Check if email already exists
            $existing = $db->getRow("SELECT cust_id FROM customers WHERE cust_email = ?", [$email]);
            if($existing){
                $return['msg'] = 'An account with this email already exists.';
            } else {
                $hashedPw = password_hash($password, PASSWORD_DEFAULT);
                $fullname = ucwords(strtolower($fullname));
                
                $result = $db->insertRow(
                    "INSERT INTO customers (cust_fullname, cust_email, cust_mobile, cust_address, cust_password) VALUES (?, ?, ?, ?, ?)",
                    [$fullname, $email, $mobile, $address, $hashedPw]
                );

                if($result){
                    $return['valid'] = true;
                    $return['msg'] = 'Account created successfully!';
                }
            }
        }
    }
} catch (Exception $ex) {
    $return['msg'] = 'Error: ' . $ex->getMessage();
}

$db->Disconnect();
echo json_encode($return);
exit;
