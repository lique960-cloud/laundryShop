<?php 
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../class/User.php';

$return = ['valid' => false];

try {
    if(isset($_POST['credential']) && isset($_POST['pw'])){
        $credential = trim($_POST['credential']);
        $password = trim($_POST['pw']);
        $foundUser = false;

        // --- 1. Check Admin (user table) ---
        // Admin login uses username + md5 password
        $result = $user->login($credential, $password);
        if(!$result){
            $result = $user->login($credential, md5($password));
        }

        if($result){
            $return['valid'] = true;
            $return['role'] = 'admin';
            $return['url'] = 'home.php';
            $_SESSION['user_logged'] = $result['user_id'];
            $foundUser = true;

            // Handle Remember Me
            if(isset($_POST['remember_me']) && $_POST['remember_me'] == 1){
                setcookie('remember_user', $credential, time() + (86400 * 30), "/");
                setcookie('remember_pass', $password, time() + (86400 * 30), "/");
            } else {
                setcookie('remember_user', '', time() - 3600, "/");
                setcookie('remember_pass', '', time() - 3600, "/");
            }
        }

        // --- 2. If not admin, check Customer (customers table) ---
        if(!$foundUser){
            $db = new Database();
            // Check by email
            $customer = $db->getRow("SELECT * FROM customers WHERE cust_email = ?", [$credential]);
            
            if($customer && password_verify($password, $customer['cust_password'])){
                $return['valid'] = true;
                $return['role'] = 'customer';
                $return['url'] = 'customer/order.php';
                $_SESSION['customer_logged'] = $customer['cust_id'];
                $foundUser = true;

                // Handle Remember Me
                if(isset($_POST['remember_me']) && $_POST['remember_me'] == 1){
                    setcookie('remember_user', $credential, time() + (86400 * 30), "/");
                    setcookie('remember_pass', $password, time() + (86400 * 30), "/");
                } else {
                    setcookie('remember_user', '', time() - 3600, "/");
                    setcookie('remember_pass', '', time() - 3600, "/");
                }
            }
            $db->Disconnect();
        }

        if(!$foundUser){
            $return['msg'] = 'Invalid credentials. Please try again.';
        }
    }
} catch (Exception $ex) {
    $return['msg'] = 'An error occurred. Please try again.';
}

echo json_encode($return);
exit;
