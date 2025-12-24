<?php
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

try {
    // Add category_image column to categories table if it doesn't exist
    $check_column_query = "SHOW COLUMNS FROM categories LIKE 'category_image'";
    $check_stmt = $db->prepare($check_column_query);
    $check_stmt->execute();
    
    if ($check_stmt->rowCount() == 0) {
        $add_column_query = "ALTER TABLE categories ADD COLUMN category_image VARCHAR(255) DEFAULT NULL AFTER category_description";
        $db->exec($add_column_query);
        echo "✓ Added category_image column to categories table<br>";
    } else {
        echo "✓ category_image column already exists in categories table<br>";
    }
    
    // Create upload directories if they don't exist
    $upload_dirs = [
        'uploads/categories',
        'uploads/products'
    ];
    
    foreach ($upload_dirs as $dir) {
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
            echo "✓ Created directory: $dir<br>";
        } else {
            echo "✓ Directory already exists: $dir<br>";
        }
    }
    
    echo "<br><strong>Database and directory setup completed successfully!</strong><br>";
    echo "<a href='admin/index.php'>Go to Admin Panel</a>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
