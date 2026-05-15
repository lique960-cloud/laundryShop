<?php
header('Content-Type: application/json; charset=utf-8');
require_once(__DIR__ . '/../database/Database.php');
$db = new Database();
$return = ['valid' => false];

try {
    if(isset($_POST['token']) && isset($_POST['password'])){
        $token = trim($_POST['token']);
        $password = trim($_POST['password']);

        // Find valid token
        $reset = $db->getRow(
            "SELECT * FROM password_resets WHERE reset_token = ? AND reset_used = 0 AND reset_expires > NOW()",
            [$token]
        );

        if(!$reset){
            $return['msg'] = 'Invalid or expired reset token.';
        } else {
            $email = $reset['reset_email'];
            $hashedPw = password_hash($password, PASSWORD_DEFAULT);

            // Update password
            $db->updateRow("UPDATE customers SET cust_password = ? WHERE cust_email = ?", [$hashedPw, $email]);

            // Mark token as used
            $db->updateRow("UPDATE password_resets SET reset_used = 1 WHERE reset_id = ?", [$reset['reset_id']]);

            $return['valid'] = true;
            $return['msg'] = 'Password reset successfully!';
        }
    }
} catch (Exception $ex) {
    $return['msg'] = 'Error: ' . $ex->getMessage();
}

$db->Disconnect();
echo json_encode($return);
exit;
