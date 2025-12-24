<?php
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

try {
    $stmt = $db->query('SELECT COUNT(*) as count FROM orders');
    $result = $stmt->fetch();
    echo 'Orders count: ' . $result['count'] . PHP_EOL;
    
    // Also check if there are any users
    $stmt2 = $db->query('SELECT COUNT(*) as count FROM users WHERE role = "customer"');
    $result2 = $stmt2->fetch();
    echo 'Customer count: ' . $result2['count'] . PHP_EOL;
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
?>
