<?php 
header('Content-Type: application/json; charset=utf-8');
if(session_status() == PHP_SESSION_NONE){
    session_start();
}
require_once __DIR__ . '/../class/User.php';

$return = ['valid' => false];

try {
    if(isset($_POST['un']) && isset($_POST['pw'])){
        $username = trim($_POST['un']);
        $password = trim($_POST['pw']);

        $result = $user->login($username, $password);
        if(!$result){
            $result = $user->login($username, md5($password));
        }

        if($result){
            $return['valid'] = true;
            $return['url'] = 'home.php';
            $_SESSION['user_logged'] = $result['user_id'];
        }
    }
} catch (Exception $ex) {
    $return['error'] = $ex->getMessage();
}

echo json_encode($return);
exit;