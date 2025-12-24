<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'classes/Cart.php';
require_once 'classes/Order.php';

requireLogin();

$database = new Database();
$db = $database->getConnection();
$cart = new Cart($db);
$order = new Order($db);

$message = '';
$error = '';

// Get cart items
$stmt = $cart->getCartItems($_SESSION['user_id']);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(empty($cart_items)) {
    redirect('cart.php');
}

$cart_total = $cart->getCartTotal($_SESSION['user_id']);

// Process checkout
if($_POST) {
    $shipping_address = sanitize($_POST['shipping_address']);
    $phone = sanitize($_POST['phone']);
    $payment_method = sanitize($_POST['payment_method']);

    if(empty($shipping_address) || empty($phone)) {
        $error = 'Please fill in all required fields.';
    } elseif(!preg_match('/^\d{10}$/', $phone)) {
        $error = 'Please enter a valid 10-digit phone number.';
    } else {
        // Create order
        $order->user_id = $_SESSION['user_id'];
        $order->total_amount = $cart_total;
        $order->order_status = 'pending';
        $order->order_date = date('Y-m-d');
        $order->payment_status = 'pending';

        if($order->create()) {
            // Add order items
            if($order->addOrderItems($cart_items)) {
                // Clear cart
                $cart->clearCart($_SESSION['user_id']);
                
                // Redirect to success page
                $_SESSION['order_success'] = $order->order_id;
                redirect('order_success.php');
            } else {
                $error = 'Failed to create order items. Please try again.';
            }
        } else {
            $error = 'Failed to create order. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Stationery Shop</title>
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

        /* Main Content */
        .main-content {
            padding: 2rem 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .page-header h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #333;
        }

        .checkout-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
        }

        .checkout-form {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .form-section {
            margin-bottom: 2rem;
        }

        .form-section h3 {
            margin-bottom: 1rem;
            color: #333;
            border-bottom: 2px solid rgb(23,150,229);
            padding-bottom: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: rgb(23,150,229);
            box-shadow: 0 0 0 3px rgba(70, 130, 180, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .payment-methods {
            display: flex;
            justify-content: center;
            max-width: 100%;
        }

        .payment-option {
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            padding: 2rem 3rem;
            text-align: center;
            background: #f8f9fa;
            transition: all 0.3s ease;
            cursor: default;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-width: 300px;
        }

        .payment-option:hover {
            border-color: rgb(23,150,229);
            background: #f1f8ff;
        }

        .payment-option.selected {
            border-color: rgb(23,150,229);
            background: rgba(70, 130, 180, 0.1);
        }

        .payment-option input {
            display: none;
        }

        .payment-option i {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            color: #28a745;
        }

        .order-summary {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            height: fit-content;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #eee;
        }

        .summary-item:last-child {
            border-bottom: none;
            font-size: 1.2rem;
            font-weight: bold;
            color: rgb(23,150,229);
        }

        .item-details {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .item-image {
            width: 50px;
            height: 50px;
            background: #f8f9fa;
            border-radius: 5px;
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

        .place-order-btn {
            width: 100%;
            background: linear-gradient(135deg, rgb(23,150,229) 0%, rgb(61,154,236) 100%);
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .place-order-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(70, 130, 180, 0.3);
        }

        .alert {
            padding: 0.75rem;
            margin-bottom: 1rem;
            border-radius: 10px;
        }

        .alert.error {
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }

        .back-to-cart {
            display: inline-block;
            color: rgb(23,150,229);
            text-decoration: none;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .back-to-cart:hover {
            transform: translateX(-5px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .checkout-container {
                grid-template-columns: 1fr;
            }
            
            .payment-methods {
                width: 100%;
                max-width: 100%;
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
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <a href="cart.php" class="back-to-cart">
                <i class="fas fa-arrow-left"></i> Back to Cart
            </a>

            <div class="page-header">
                <h1><i class="fas fa-credit-card"></i> Checkout</h1>
                <p>Complete your order</p>
            </div>

            <?php if($error): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="checkout-container">
                <div class="checkout-form">
                    <form method="POST">
                        <div class="form-section">
                            <h3><i class="fas fa-shipping-fast"></i> Shipping Information</h3>
                            <div class="form-group">
                                <label for="shipping_address">Shipping Address *</label>
                                <textarea id="shipping_address" name="shipping_address" required placeholder="Enter your complete address..."><?php echo isset($_POST['shipping_address']) ? $_POST['shipping_address'] : ''; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number *</label>
                                <input type="tel" id="phone" name="phone" required 
                                       pattern="[0-9]{10}" 
                                       title="Please enter a 10-digit phone number"
                                       placeholder="Enter 10-digit phone number" 
                                       value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                            </div>
                        </div>

                        <div class="form-section">
                            <h3><i class="fas fa-money-bill-wave"></i> Payment Method</h3>
                            <div class="payment-methods">
                                <div class="payment-option selected">
                                    <input type="hidden" name="payment_method" value="cod">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <div style="font-size: 1.2rem; font-weight: 600; margin-bottom: 0.5rem;">Cash on Delivery</div>
                                    <p style="color: #6c757d; margin: 0;">Pay with cash upon delivery</p>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="place-order-btn">
                            <i class="fas fa-check"></i> Place Order
                        </button>
                    </form>
                </div>

                <div class="order-summary">
                    <h3>Order Summary</h3>
                    <?php foreach($cart_items as $item): ?>
                        <div class="summary-item">
                            <div class="item-details">
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
                                    <div><?php echo htmlspecialchars($item['product_name']); ?></div>
                                    <small>Qty: <?php echo $item['quantity']; ?></small>
                                </div>
                            </div>
                            <div><?php echo formatPrice($item['product_price'] * $item['quantity']); ?></div>
                        </div>
                    <?php endforeach; ?>
                    
                    <div class="summary-item">
                        <span>Shipping</span>
                        <span>Free</span>
                    </div>
                    
                    <div class="summary-item">
                        <span>Total</span>
                        <span><?php echo formatPrice($cart_total); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>
</html>
