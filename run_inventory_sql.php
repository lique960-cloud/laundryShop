<?php
$host = "127.0.0.1";
$dbname = "laundry_system";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = file_get_contents('inventory_tables.sql');
    $pdo->exec($sql);
    echo 'Inventory tables created successfully';
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
