<?php
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

try {
    // Remove duplicate users with empty username/email
    $query = "DELETE FROM users WHERE username = '' AND email = ''";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    echo "Database cleaned successfully. Duplicate entries removed.";
    
} catch(PDOException $exception) {
    echo "Error: " . $exception->getMessage();
}
?>
