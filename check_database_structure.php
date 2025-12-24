<?php
require_once 'config/config.php';
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

try {
    // Check orders table structure
    echo "=== ORDERS TABLE STRUCTURE ===\n";
    $orders_desc = $db->query("DESCRIBE orders");
    while ($row = $orders_desc->fetch(PDO::FETCH_ASSOC)) {
        echo "Column: " . $row['Field'] . " | Type: " . $row['Type'] . " | Null: " . $row['Null'] . " | Key: " . $row['Key'] . "\n";
    }
    
    echo "\n=== USERS TABLE STRUCTURE ===\n";
    $users_desc = $db->query("DESCRIBE users");
    while ($row = $users_desc->fetch(PDO::FETCH_ASSOC)) {
        echo "Column: " . $row['Field'] . " | Type: " . $row['Type'] . " | Null: " . $row['Null'] . " | Key: " . $row['Key'] . "\n";
    }
    
    echo "\n=== ORDER_ITEMS TABLE STRUCTURE ===\n";
    $order_items_desc = $db->query("DESCRIBE order_items");
    while ($row = $order_items_desc->fetch(PDO::FETCH_ASSOC)) {
        echo "Column: " . $row['Field'] . " | Type: " . $row['Type'] . " | Null: " . $row['Null'] . " | Key: " . $row['Key'] . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
