<?php
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

try {
    // Remove foreign key constraint first
    $db->exec("ALTER TABLE products DROP FOREIGN KEY products_ibfk_2");
    echo "✓ Removed foreign key constraint from products table<br>";
    
    // Remove subcategory_id column from products table
    $db->exec("ALTER TABLE products DROP COLUMN subcategory_id");
    echo "✓ Removed subcategory_id column from products table<br>";
    
    // Drop subcategories table
    $db->exec("DROP TABLE IF EXISTS subcategories");
    echo "✓ Dropped subcategories table<br>";
    
    echo "<br><strong>Subcategory cleanup completed successfully!</strong><br>";
    echo "<a href='admin/products.php'>Go to Product Management</a><br>";
    echo "<a href='admin/categories.php'>Go to Category Management</a>";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
