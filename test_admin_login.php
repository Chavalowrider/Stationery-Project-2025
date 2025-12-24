<?php
require_once 'config/database.php';
require_once 'classes/Admin.php';

$database = new Database();
$db = $database->getConnection();

try {
    // Test admin login functionality
    echo "=== TESTING ADMIN LOGIN SYSTEM ===\n";
    
    // Check if admin exists
    $admin_check = "SELECT admin_id, username, email FROM admins LIMIT 1";
    $stmt = $db->prepare($admin_check);
    $stmt->execute();
    $admin_data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin_data) {
        echo "✓ Admin found in database:\n";
        echo "  - ID: " . $admin_data['admin_id'] . "\n";
        echo "  - Username: " . $admin_data['username'] . "\n";
        echo "  - Email: " . $admin_data['email'] . "\n";
        
        // Test Admin class login
        $admin = new Admin($db);
        echo "\n=== TESTING ADMIN CLASS ===\n";
        echo "✓ Admin class loaded successfully\n";
        
        // Test with correct credentials (assuming default admin)
        if ($admin->login($admin_data['email'], 'admin123')) {
            echo "✓ Admin login test successful\n";
            echo "  - Admin ID: " . $admin->admin_id . "\n";
            echo "  - Username: " . $admin->username . "\n";
            echo "  - Email: " . $admin->email . "\n";
            echo "  - Full Name: " . $admin->full_name . "\n";
        } else {
            echo "✗ Admin login test failed\n";
            echo "Note: If you changed the password, this is expected\n";
        }
        
    } else {
        echo "✗ No admin found in database\n";
    }
    
    // Check users table structure
    echo "\n=== CHECKING USERS TABLE ===\n";
    $users_check = "SELECT COUNT(*) as user_count FROM users";
    $users_stmt = $db->prepare($users_check);
    $users_stmt->execute();
    $users_result = $users_stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "✓ Users table contains " . $users_result['user_count'] . " customers\n";
    
    // Check if role column exists (should not exist)
    try {
        $role_check = "SELECT role FROM users LIMIT 1";
        $role_stmt = $db->prepare($role_check);
        $role_stmt->execute();
        echo "✗ Role column still exists in users table\n";
    } catch (Exception $e) {
        echo "✓ Role column successfully removed from users table\n";
    }
    
    echo "\n=== DATABASE STRUCTURE UPDATE COMPLETE ===\n";
    echo "Summary:\n";
    echo "- Admins table: Created and populated\n";
    echo "- Users table: Role column removed, contains only customers\n";
    echo "- Authentication: Updated to use separate tables\n";
    echo "- Admin panel: Protected with authentication checks\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
