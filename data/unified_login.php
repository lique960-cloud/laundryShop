<?php 
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../class/User.php';

$return = ['valid' => false];

try {
    if(isset($_POST['credential']) && isset($_POST['pw'])){
        $credential = trim($_POST['credential']);
        $password = trim($_POST['pw']);

        // Try 1: Direct match (plaintext in db)
        $result = $user->login($credential, $password);
        
        // Try 2: MD5 match (legacy md5 in db)
        if(!$result){
            $result = $user->login($credential, md5($password));
        }

        // Try 3: Bcrypt match (password_hash in db)
        if(!$result){
            $userRow = $user->getRow("SELECT * FROM user WHERE user_account = ? LIMIT 1", [$credential]);
            if($userRow && password_verify($password, $userRow['user_password'])){
                $result = $userRow;
            }
        }

        if($result){
            $return['valid'] = true;
            $return['role'] = isset($result['user_role']) ? $result['user_role'] : 'admin';
            $return['url'] = (strtolower($return['role']) == 'cashier') ? 'cashier_home.php' : 'home.php';
            $_SESSION['user_logged'] = $result['user_id'];
            $_SESSION['user_role'] = $return['role'];
            $_SESSION['user_fullname'] = isset($result['user_fullname']) ? $result['user_fullname'] : 'Administrator';

            // Handle Remember Me
            if(isset($_POST['remember_me']) && $_POST['remember_me'] == 1){
                setcookie('remember_user', $credential, time() + (86400 * 30), "/");
                setcookie('remember_pass', $password, time() + (86400 * 30), "/");
            } else {
                setcookie('remember_user', '', time() - 3600, "/");
                setcookie('remember_pass', '', time() - 3600, "/");
            }
        } else {
            $return['msg'] = 'Invalid credentials. Please try again.';
        }
    }
} catch (Exception $ex) {
    $return['msg'] = 'An error occurred. Please try again.';
}

echo json_encode($return);
exit;
