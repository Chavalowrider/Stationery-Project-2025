<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'classes/Order.php';

requireLogin();

if(!isset($_SESSION['order_success'])) {
    redirect('index.php');
}

$order_id = $_SESSION['order_success'];
unset($_SESSION['order_success']);

$database = new Database();
$db = $database->getConnection();
$order = new Order($db);

$order_details = $order->getOrderDetails($order_id);
$order_items_stmt = $order->getOrderItems($order_id);
$order_items = $order_items_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Successful - Stationery Shop</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f8f9fa;
        }

        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .success-card {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .success-icon {
            font-size: 4rem;
            color: #28a745;
            margin-bottom: 1rem;
            animation: bounce 1s ease;
        }

        .success-title {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 1rem;
        }

        .success-message {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 2rem;
        }

        .order-info {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 2rem;
        }

        .order-details {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #eee;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #eee;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .item-image {
            width: 60px;
            height: 60px;
            background: #f8f9fa;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ddd;
            overflow: hidden;
        }

        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, rgb(23,150,229) 0%, rgb(61,154,236) 100%);
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        @keyframes bounce {
            0%, 20%, 60%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-20px);
            }
            80% {
                transform: translateY(-10px);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .success-card {
                padding: 2rem;
            }

            .success-title {
                font-size: 2rem;
            }

            .order-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-card">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1 class="success-title">Order Placed Successfully!</h1>
            <p class="success-message">
                Thank you for your order. We'll process it shortly and send you updates via email.
            </p>
            
            <div class="order-info">
                <h3>Order #<?php echo $order_id; ?></h3>
                <p>Order Date: <?php echo date('F j, Y', strtotime($order_details['order_date'])); ?></p>
                <p>Total Amount: <?php echo formatPrice($order_details['total_amount']); ?></p>
            </div>
        </div>

        <div class="order-details">
            <div class="order-header">
                <h2>Order Details</h2>
                <span class="status-badge status-pending"><?php echo ucfirst($order_details['order_status']); ?></span>
            </div>

            <?php foreach($order_items as $item): ?>
                <div class="order-item">
                    <div class="item-info">
                        <div class="item-image">
                            <?php if($item['product_image']): ?>
                                <?php 
                                // Handle both old format (just filename) and new format (full path)
                                if (strpos($item['product_image'], 'uploads/') === 0) {
                                    // New format: uploads/products/filename
                                    $image_url = BASE_URL . $item['product_image'];
                                } else {
                                    // Old format: just filename
                                    $image_url = UPLOAD_PATH . $item['product_image'];
                                }
                                ?>
                                <img src="<?php echo $image_url; ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                            <?php else: ?>
                                <i class="fas fa-box"></i>
                            <?php endif; ?>
                        </div>
                        <div>
                            <h4><?php echo htmlspecialchars($item['product_name']); ?></h4>
                            <p>Quantity: <?php echo $item['quantity']; ?></p>
                        </div>
                    </div>
                    <div>
                        <strong><?php echo formatPrice($item['price'] * $item['quantity']); ?></strong>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="action-buttons">
            <a href="orders.php" class="btn btn-primary">
                <i class="fas fa-list"></i> View All Orders
            </a>
            <a href="products.php" class="btn btn-secondary">
                <i class="fas fa-shopping-bag"></i> Continue Shopping
            </a>
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-home"></i> Go to Home
            </a>
        </div>
    </div>
</body>
</html>
