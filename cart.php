<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'classes/Cart.php';

requireLogin();

$database = new Database();
$db = $database->getConnection();
$cart = new Cart($db);

// Handle cart updates
if($_POST) {
    if(isset($_POST['update_cart'])) {
        foreach($_POST['quantities'] as $cart_id => $quantity) {
            $cart->cart_id = $cart_id;
            $cart->user_id = $_SESSION['user_id'];
            $cart->quantity = max(1, (int)$quantity);
            $cart->updateQuantity();
        }
        $_SESSION['cart_updated'] = true;
        redirect('cart.php');
    }
    
    if(isset($_POST['remove_item'])) {
        $cart->cart_id = $_POST['cart_id'];
        $cart->user_id = $_SESSION['user_id'];
        $cart->removeFromCart();
        redirect('cart.php');
    }
    
    if(isset($_POST['increase_qty'])) {
        $cart->cart_id = $_POST['cart_id'];
        $cart->user_id = $_SESSION['user_id'];
        $current_qty = $_POST['current_qty'];
        $cart->quantity = $current_qty + 1;
        $cart->updateQuantity();
        redirect('cart.php');
    }
    
    if(isset($_POST['decrease_qty'])) {
        $cart->cart_id = $_POST['cart_id'];
        $cart->user_id = $_SESSION['user_id'];
        $current_qty = $_POST['current_qty'];
        $cart->quantity = max(1, $current_qty - 1);
        $cart->updateQuantity();
        redirect('cart.php');
    }
}

// Get cart items
$stmt = $cart->getCartItems($_SESSION['user_id']);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate totals
$cart_total = $cart->getCartTotal($_SESSION['user_id']);
$cart_count = $cart->getCartCount($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Stationery Shop</title>
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

        .cart-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
        }

        .cart-items {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .cart-item {
            display: grid;
            grid-template-columns: 80px 1fr auto auto auto;
            gap: 1rem;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #eee;
        }

        .cart-item:last-child {
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
        }

        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }

        .item-details h3 {
            margin-bottom: 0.5rem;
            color: #333;
        }

        .item-price {
            font-size: 1.2rem;
            font-weight: bold;
            color: rgb(23,150,229);
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .quantity-controls input {
            width: 60px;
            padding: 0.5rem;
            border: 2px solid #e1e5e9;
            border-radius: 5px;
            text-align: center;
        }

        .quantity-btn {
            background: rgb(23,150,229);
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .remove-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .remove-btn:hover {
            background: #c82333;
        }

        .cart-summary {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            height: fit-content;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }

        .summary-row:last-child {
            border-bottom: none;
            font-size: 1.2rem;
            font-weight: bold;
            color: rgb(23,150,229);
        }

        .checkout-btn {
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
            text-decoration: none;
            display: block;
            text-align: center;
            margin-top: 1rem;
        }

        .checkout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(70, 130, 180, 0.3);
        }

        .update-cart-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }

        .update-cart-btn:hover {
            background: #218838;
        }

        .empty-cart {
            text-align: center;
            padding: 3rem;
            color: #666;
        }

        .empty-cart i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #ddd;
        }

        .continue-shopping {
            display: inline-block;
            background: rgb(23,150,229);
            color: white;
            padding: 1rem 2rem;
            text-decoration: none;
            border-radius: 10px;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }

        .continue-shopping:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(70, 130, 180, 0.3);
        }

        /* Success Message */
        .success-message {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .cart-container {
                grid-template-columns: 1fr;
            }

            .cart-item {
                grid-template-columns: 1fr;
                gap: 0.5rem;
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
            <div class="page-header">
                <h1><i class="fas fa-shopping-cart"></i> Shopping Cart</h1>
                <p>Review your items before checkout</p>
            </div>

            <?php if(isset($_SESSION['cart_updated']) && $_SESSION['cart_updated']): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    <span>Cart updated successfully!</span>
                </div>
                <?php unset($_SESSION['cart_updated']); ?>
            <?php endif; ?>

            <?php if(empty($cart_items)): ?>
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>Your cart is empty</h3>
                    <p>Add some products to your cart to get started!</p>
                    <a href="products.php" class="continue-shopping">
                        <i class="fas fa-arrow-left"></i> Continue Shopping
                    </a>
                </div>
            <?php else: ?>
                <div class="cart-container">
                    <div class="cart-items">
                        <form method="POST">
                            <?php foreach($cart_items as $item): ?>
                                <div class="cart-item">
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
                                        <h3><?php echo htmlspecialchars($item['product_name']); ?></h3>
                                        <div class="item-price"><?php echo formatPrice($item['product_price']); ?></div>
                                    </div>
                                    <div class="quantity-controls">
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                            <input type="hidden" name="current_qty" value="<?php echo $item['quantity']; ?>">
                                            <button type="submit" name="decrease_qty" class="quantity-btn">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </form>
                                        <input type="number" name="quantities[<?php echo $item['cart_id']; ?>]" 
                                               value="<?php echo $item['quantity']; ?>" min="1" 
                                               id="qty_<?php echo $item['cart_id']; ?>">
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                            <input type="hidden" name="current_qty" value="<?php echo $item['quantity']; ?>">
                                            <button type="submit" name="increase_qty" class="quantity-btn">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="item-total">
                                        <strong><?php echo formatPrice($item['product_price'] * $item['quantity']); ?></strong>
                                    </div>
                                    <div class="remove-item">
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                            <button type="submit" name="remove_item" class="remove-btn" 
                                                    onsubmit="return confirm('Remove this item from cart?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <button type="submit" name="update_cart" class="update-cart-btn">
                                <i class="fas fa-sync"></i> Update Cart
                            </button>
                        </form>
                    </div>

                    <div class="cart-summary">
                        <h3>Order Summary</h3>
                        <div class="summary-row">
                            <span>Items (<?php echo $cart_count; ?>)</span>
                            <span><?php echo formatPrice($cart_total); ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping</span>
                            <span>Free</span>
                        </div>
                        <div class="summary-row">
                            <span>Total</span>
                            <span><?php echo formatPrice($cart_total); ?></span>
                        </div>
                        <a href="checkout.php" class="checkout-btn">
                            <i class="fas fa-credit-card"></i> Proceed to Checkout
                        </a>
                        <a href="products.php" class="continue-shopping" style="display: block; text-align: center; margin-top: 1rem; background: #6c757d;">
                            <i class="fas fa-arrow-left"></i> Continue Shopping
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

</body>
</html>
