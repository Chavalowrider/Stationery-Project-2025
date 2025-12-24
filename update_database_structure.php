<?php
require_once 'config/config.php';
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

// Start transaction
$db->beginTransaction();

try {
    echo "=== DATABASE STRUCTURE UPDATE ===\n";
    
    // 1. Create admin table
    echo "1. Creating admin table...\n";
    $create_admin_table = "CREATE TABLE IF NOT EXISTS `admins` (
        `admin_id` int(11) NOT NULL AUTO_INCREMENT,
        `username` varchar(100) NOT NULL,
        `email` varchar(100) NOT NULL,
        `password` varchar(255) NOT NULL,
        `full_name` varchar(150) DEFAULT NULL,
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        `last_login` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`admin_id`),
        UNIQUE KEY `username` (`username`),
        UNIQUE KEY `email` (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
    
    $db->exec($create_admin_table);
    echo "✓ Admin table created successfully!\n";
    
    // 2. Insert existing admin user into admin table
    echo "2. Migrating admin user to admin table...\n";
    $get_admin = "SELECT username, email, password FROM users WHERE role = 'admin' LIMIT 1";
    $admin_stmt = $db->prepare($get_admin);
    $admin_stmt->execute();
    $admin_user = $admin_stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin_user) {
        $insert_admin = "INSERT INTO admins (username, email, password, full_name) VALUES (?, ?, ?, ?)";
        $insert_stmt = $db->prepare($insert_admin);
        $insert_stmt->execute([
            $admin_user['username'],
            $admin_user['email'],
            $admin_user['password'],
            'System Administrator'
        ]);
        echo "✓ Admin user migrated successfully!\n";
    } else {
        // Create default admin if none exists
        $default_password = password_hash('admin123', PASSWORD_DEFAULT);
        $insert_admin = "INSERT INTO admins (username, email, password, full_name) VALUES (?, ?, ?, ?)";
        $insert_stmt = $db->prepare($insert_admin);
        $insert_stmt->execute([
            'admin',
            'admin@stationery.com',
            $default_password,
            'System Administrator'
        ]);
        echo "✓ Default admin user created (username: admin, password: admin123)!\n";
    }
    
    // 3. Delete admin users from users table
    echo "3. Removing admin users from users table...\n";
    $delete_admins = "DELETE FROM users WHERE role = 'admin'";
    $db->exec($delete_admins);
    echo "✓ Admin users removed from users table!\n";
    
    // 4. Remove role column from users table
    echo "4. Removing role column from users table...\n";
    $remove_role = "ALTER TABLE users DROP COLUMN role";
    $db->exec($remove_role);
    echo "✓ Role column removed from users table!\n";
    
    // 5. Update orders table to ensure proper foreign key relationship
    echo "5. Checking orders table structure...\n";
    $check_orders_fk = "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE 
                        WHERE TABLE_NAME = 'orders' AND COLUMN_NAME = 'user_id' 
                        AND TABLE_SCHEMA = DATABASE()";
    $fk_stmt = $db->prepare($check_orders_fk);
    $fk_stmt->execute();
    
    if ($fk_stmt->rowCount() == 0) {
        // Add foreign key constraint if it doesn't exist
        $add_fk = "ALTER TABLE orders ADD CONSTRAINT fk_orders_user 
                   FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL";
        $db->exec($add_fk);
        echo "✓ Foreign key constraint added to orders table!\n";
    } else {
        echo "✓ Orders table foreign key already exists!\n";
    }
    
    // Commit transaction
    $db->commit();
    
    echo "\n=== DATABASE UPDATE COMPLETED SUCCESSFULLY ===\n";
    echo "Summary of changes:\n";
    echo "- Created 'admins' table for admin users\n";
    echo "- Migrated admin user to admins table\n";
    echo "- Removed admin users from users table\n";
    echo "- Removed 'role' column from users table\n";
    echo "- Users table now contains only customer data\n";
    echo "- Admins table contains only admin data\n";
    
} catch (Exception $e) {
    // Rollback transaction on error
    $db->rollback();
    echo "Error: " . $e->getMessage() . "\n";
    echo "Database changes have been rolled back.\n";
}
?>
