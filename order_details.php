<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'classes/Order.php';
require_once 'classes/Cart.php';

requireLogin();

if(!isset($_GET['id'])) {
    redirect('orders.php');
}

$order_id = (int)$_GET['id'];

$database = new Database();
$db = $database->getConnection();
$order = new Order($db);
$cart = new Cart($db);

// Get order details
$order_details = $order->getOrderDetails($order_id);

// Check if order belongs to current user (security check)
if(!$order_details || $order_details['user_id'] != $_SESSION['user_id']) {
    redirect('orders.php');
}

// Get order items
$order_items_stmt = $order->getOrderItems($order_id);
$order_items = $order_items_stmt->fetchAll(PDO::FETCH_ASSOC);

// Get cart count
$cart_count = $cart->getCartCount($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Stationery Shop</title>
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

        /* Header */
        .header {
            background: linear-gradient(135deg, rgb(23,150,229) 0%, rgb(61,154,236) 100%);
            color: white;
            padding: 1rem 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            text-decoration: none;
            color: white;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2rem;
            align-items: center;
        }

        .nav-menu a {
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 5px;
        }

        .nav-menu a:hover {
            background: rgba(255,255,255,0.1);
        }

        .cart-icon {
            position: relative;
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ff4757;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Main Content */
        .main-content {
            margin-top: 80px;
            padding: 2rem 0;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .back-link {
            display: inline-block;
            color: rgb(23,150,229);
            text-decoration: none;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            transform: translateX(-5px);
        }

        .order-header {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .order-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .order-title h1 {
            font-size: 2rem;
            color: #333;
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

        .status-processing {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-shipped {
            background: #d4edda;
            color: #155724;
        }

        .status-delivered {
            background: #d1e7dd;
            color: #0f5132;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .order-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .info-card {
            text-align: center;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .info-icon {
            font-size: 2rem;
            color: rgb(23,150,229);
            margin-bottom: 0.5rem;
        }

        .info-label {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .info-value {
            font-size: 1.1rem;
            font-weight: bold;
            color: #333;
        }

        .order-items {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .items-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #eee;
        }

        .order-item {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding: 1.5rem 0;
            border-bottom: 1px solid #eee;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-image {
            width: 80px;
            height: 80px;
            background: #f8f9fa;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #ddd;
            overflow: hidden;
            flex-shrink: 0;
        }

        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .item-details {
            flex: 1;
        }

        .item-name {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .item-quantity {
            color: #666;
            margin-bottom: 0.5rem;
        }

        .item-price {
            font-size: 1.1rem;
            font-weight: bold;
            color: rgb(23,150,229);
        }

        .order-summary {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-top: 2rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .summary-row.total {
            font-size: 1.2rem;
            font-weight: bold;
            color: rgb(23,150,229);
            border-top: 2px solid #ddd;
            padding-top: 1rem;
            margin-top: 1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .order-title {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .order-info-grid {
                grid-template-columns: 1fr;
            }

            .order-item {
                flex-direction: column;
                text-align: center;
            }

            .item-image {
                margin: 0 auto;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="nav-container">
            <a href="index.php" class="logo">
                <i class="fas fa-pencil-alt"></i> Stationery Shop
            </a>
            <nav>
                <ul class="nav-menu">
                    <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="products.php"><i class="fas fa-box"></i> Products</a></li>
                    <li><a href="cart.php" class="cart-icon">
                        <i class="fas fa-shopping-cart"></i> Cart
                        <?php if($cart_count > 0): ?>
                            <span class="cart-count"><?php echo $cart_count; ?></span>
                        <?php endif; ?>
                    </a></li>
                    <li><a href="orders.php"><i class="fas fa-list"></i> Orders</a></li>
                    <?php if(isAdmin()): ?>
                        <li><a href="admin/dashboard.php"><i class="fas fa-cog"></i> Admin</a></li>
                    <?php endif; ?>
                    <li><a href="auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <a href="orders.php" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Orders
            </a>

            <div class="order-header">
                <div class="order-title">
                    <h1>Order #<?php echo $order_details['order_id']; ?></h1>
                    <span class="status-badge status-<?php echo $order_details['order_status']; ?>">
                        <?php echo ucfirst($order_details['order_status']); ?>
                    </span>
                </div>

                <div class="order-info-grid">
                    <div class="info-card">
                        <div class="info-icon"><i class="fas fa-calendar"></i></div>
                        <div class="info-label">Order Date</div>
                        <div class="info-value"><?php echo date('F j, Y', strtotime($order_details['order_date'])); ?></div>
                    </div>
                    <div class="info-card">
                        <div class="info-icon"><i class="fas fa-rupee-sign"></i></div>
                        <div class="info-label">Total Amount</div>
                        <div class="info-value"><?php echo formatPrice($order_details['total_amount']); ?></div>
                    </div>
                    <div class="info-card">
                        <div class="info-icon"><i class="fas fa-credit-card"></i></div>
                        <div class="info-label">Payment Status</div>
                        <div class="info-value"><?php echo ucfirst($order_details['payment_status']); ?></div>
                    </div>
                    <div class="info-card">
                        <div class="info-icon"><i class="fas fa-box"></i></div>
                        <div class="info-label">Items</div>
                        <div class="info-value"><?php echo count($order_items); ?> item(s)</div>
                    </div>
                </div>
            </div>

            <div class="order-items">
                <div class="items-header">
                    <h2>Order Items</h2>
                </div>

                <?php foreach($order_items as $item): ?>
                    <div class="order-item">
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
                        <div class="item-details">
                            <div class="item-name"><?php echo htmlspecialchars($item['product_name']); ?></div>
                            <div class="item-quantity">Quantity: <?php echo $item['quantity']; ?></div>
                            <div class="item-price">Price: <?php echo formatPrice($item['price']); ?> each</div>
                        </div>
                        <div class="item-total">
                            <strong><?php echo formatPrice($item['price'] * $item['quantity']); ?></strong>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="order-summary">
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span><?php echo formatPrice($order_details['total_amount']); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping:</span>
                        <span>Free</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total:</span>
                        <span><?php echo formatPrice($order_details['total_amount']); ?></span>
                    </div>
                    
                </div>
            </div>
        </div>
    </main>
</body>
</html>
