<?php
// Customer login now redirects to the unified login page
require_once(__DIR__ . '/../database/Database.php');
$db = new Database();
if(isset($_SESSION['customer_logged'])){
  header('location: order.php');
  exit;
}
$db->Disconnect();
// Redirect to unified login
header('location: ../index.php');
exit;
?>
