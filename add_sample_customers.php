<?php
require_once 'config/config.php';
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

// Start transaction
$db->beginTransaction();

try {
    // Sample customers data
    $customers = [
        ['john_doe', 'john.doe@email.com', 'customer123'],
        ['sarah_smith', 'sarah.smith@email.com', 'customer456'],
        ['mike_johnson', 'mike.johnson@email.com', 'customer789'],
        ['emily_brown', 'emily.brown@email.com', 'customer101'],
        ['david_wilson', 'david.wilson@email.com', 'customer202'],
        ['lisa_davis', 'lisa.davis@email.com', 'customer303'],
        ['robert_miller', 'robert.miller@email.com', 'customer404'],
        ['jennifer_garcia', 'jennifer.garcia@email.com', 'customer505'],
        ['william_martinez', 'william.martinez@email.com', 'customer606'],
        ['amanda_lopez', 'amanda.lopez@email.com', 'customer707']
    ];

    // Insert customers
    $customer_stmt = $db->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'customer')");
    $customer_ids = [];
    
    foreach ($customers as $customer) {
        $hashed_password = password_hash($customer[2], PASSWORD_DEFAULT);
        $customer_stmt->execute([$customer[0], $customer[1], $hashed_password]);
        $customer_ids[] = $db->lastInsertId();
    }

    echo "✓ Added " . count($customers) . " sample customers successfully!\n";

    // Create some sample orders for these customers
    $order_stmt = $db->prepare("INSERT INTO orders (user_id, total_amount, order_status, payment_status, order_date) VALUES (?, ?, ?, ?, ?)");
    $order_item_stmt = $db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    
    // Get some product IDs for sample orders
    $product_query = "SELECT product_id, product_price FROM products LIMIT 20";
    $product_stmt = $db->prepare($product_query);
    $product_stmt->execute();
    $products = $product_stmt->fetchAll(PDO::FETCH_ASSOC);

    $order_count = 0;
    $statuses = ['pending', 'processing', 'shipped', 'delivered'];
    $payment_statuses = ['pending', 'completed', 'failed'];
    
    // Create random orders for customers
    foreach ($customer_ids as $index => $customer_id) {
        // Each customer gets 1-3 orders
        $num_orders = rand(1, 3);
        
        for ($i = 0; $i < $num_orders; $i++) {
            $total_amount = 0;
            $order_status = $statuses[array_rand($statuses)];
            $payment_status = $payment_statuses[array_rand($payment_statuses)];
            $order_date = date('Y-m-d', strtotime('-' . rand(1, 90) . ' days'));
            
            // Calculate total amount first
            $num_items = rand(1, 5);
            $order_items = [];
            
            for ($j = 0; $j < $num_items; $j++) {
                $product = $products[array_rand($products)];
                $quantity = rand(1, 3);
                $price = $product['product_price'];
                $total_amount += $price * $quantity;
                
                $order_items[] = [
                    'product_id' => $product['product_id'],
                    'quantity' => $quantity,
                    'price' => $price
                ];
            }
            
            // Insert order
            $order_stmt->execute([$customer_id, $total_amount, $order_status, $payment_status, $order_date]);
            $order_id = $db->lastInsertId();
            
            // Insert order items
            foreach ($order_items as $item) {
                $order_item_stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
            }
            
            $order_count++;
        }
    }

    // Commit transaction
    $db->commit();
    
    echo "✓ Added " . $order_count . " sample orders successfully!\n";
    echo "✓ Sample customer data has been inserted successfully!\n";
    echo "\nSummary:\n";
    echo "- Customers: " . count($customers) . "\n";
    echo "- Orders: " . $order_count . "\n";
    echo "\nYou can now view the customers in the admin panel:\n";
    echo "- Customers: admin/customers.php\n";

} catch (Exception $e) {
    // Rollback transaction on error
    $db->rollback();
    echo "Error: " . $e->getMessage() . "\n";
}
?>
