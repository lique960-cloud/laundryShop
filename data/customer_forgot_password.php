<?php
header('Content-Type: application/json; charset=utf-8');
require_once(__DIR__ . '/../database/Database.php');
$db = new Database();
$return = ['valid' => false];

try {
    if(isset($_POST['email'])){
        $email = trim($_POST['email']);

        $customer = $db->getRow("SELECT cust_id FROM customers WHERE cust_email = ?", [$email]);
        if(!$customer){
            $return['msg'] = 'No account found with that email address.';
        } else {
            // Generate a random token
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Invalidate old tokens for this email
            $db->updateRow("UPDATE password_resets SET reset_used = 1 WHERE reset_email = ?", [$email]);

            // Insert new token
            $db->insertRow(
                "INSERT INTO password_resets (reset_email, reset_token, reset_expires) VALUES (?, ?, ?)",
                [$email, $token, $expires]
            );

            $return['valid'] = true;
            $return['token'] = $token;
            // In a production system, you'd email this token instead of returning it
        }
    }
} catch (Exception $ex) {
    $return['msg'] = 'Error: ' . $ex->getMessage();
}

$db->Disconnect();
echo json_encode($return);
exit;
