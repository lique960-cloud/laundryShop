<?php 
require_once(__DIR__ . '/../database/Database.php');
$session = new Database();

if(!isset($_SESSION['customer_logged'])){
	header('location: ../index.php');
	exit;
}
