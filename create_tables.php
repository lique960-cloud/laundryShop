<?php
require_once('database/Database.php');
$db = new Database();
$sql = file_get_contents('customer_tables.sql');
try {
    $db->datab->exec($sql);
    echo 'Tables created successfully';
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
$db->Disconnect();
?>