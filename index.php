<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'classes/Product.php';
require_once 'classes/Category.php';
require_once 'classes/Cart.php';

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);
$category = new Category($db);
$cart = new Cart($db);

// Get featured products (latest 8 products)
$stmt = $product->read();
$featured_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
$featured_products = array_slice($featured_products, 0, 8);

// Get categories
$categories_stmt = $category->read();
$categories = $categories_stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle add to cart
if($_POST && isset($_POST['add_to_cart']) && isLoggedIn()) {
    $cart->user_id = $_SESSION['user_id'];
    $cart->product_id = $_POST['product_id'];
    $cart->quantity = 1;
    if($cart->addToCart()) {
        $_SESSION['cart_message'] = 'Product added to cart successfully!';
    } else {
        $_SESSION['cart_error'] = 'Failed to add product to cart.';
    }
    redirect('index.php');
}

// Get cart count if user is logged in
$cart_count = 0;
if(isLoggedIn()) {
    $cart_count = $cart->getCartCount($_SESSION['user_id']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stationery Shop - Your One-Stop Shop for All Stationery Needs</title>
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
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, rgb(23,150,229) 0%, rgb(61, 154, 236) 100%);
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

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, rgb(23,150,229) 0%, rgb(61, 154, 236) 100%);
            color: white;
            padding: 8rem 0 4rem;
            text-align: center;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            animation: fadeInUp 1s ease;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            animation: fadeInUp 1s ease 0.2s both;
        }

        .cta-button {
            display: inline-block;
            background: white;
            color: rgb(23,150,229);
            padding: 1rem 2rem;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            transition: all 0.3s ease;
            animation: fadeInUp 1s ease 0.4s both;
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        /* Categories Section */
        .categories {
            padding: 4rem 0;
            background: #f8f9fa;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-title h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #333;
        }

        .section-title p {
            font-size: 1.1rem;
            color: #666;
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .category-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            text-decoration: none;
            color: #333;
        }

        .category-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }

        .category-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: rgb(23,150,229);
        }

        /* Products Section */
        .products {
            padding: 4rem 0;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }

        .product-image {
            width: 100%;
            height: 200px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: #ddd;
        }

        .product-info {
            padding: 1.5rem;
        }

        .product-name {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .product-price {
            font-size: 1.5rem;
            font-weight: bold;
            color: rgb(23,150,229);
            margin-bottom: 1rem;
        }

        .add-to-cart {
            width: 100%;
            background: linear-gradient(135deg, rgb(23,150,229) 0%, rgb(61, 154, 236) 100%);
            color: white;
            border: none;
            padding: 0.75rem;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .add-to-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(70, 130, 180, 0.3);
        }


        /* Footer */
        .footer {
            background: #333;
            color: white;
            padding: 3rem 0 1rem;
            text-align: center;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: #0073E6;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-menu {
                gap: 1rem;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .section-title h2 {
                font-size: 2rem;
            }

            .categories-grid,
            .products-grid {
                grid-template-columns: 1fr;
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
                    <?php if(isLoggedIn()): ?>
                        <li><a href="cart.php" class="cart-icon">
                            <i class="fas fa-shopping-cart"></i> Cart
                            <?php if($cart_count > 0): ?>
                                <span class="cart-count"><?php echo $cart_count; ?></span>
                            <?php endif; ?>
                        </a></li>
                        <li><a href="orders.php"><i class="fas fa-list"></i> Orders</a></li>
                        <li><a href="auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    <?php else: ?>
                        <li><a href="auth/login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                        <li><a href="auth/register.php"><i class="fas fa-user-plus"></i> Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Welcome to Stationery Shop</h1>
            <p>Your one-stop destination for all stationery needs. From pens to notebooks, we have everything you need for work, school, and creativity.</p>
            <a href="products.php" class="cta-button">Shop Now</a>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="categories">
        <div class="container">
            <div class="section-title">
                <h2>Shop by Category</h2>
                <p>Explore our wide range of stationery categories</p>
            </div>
            <div class="categories-grid">
                <?php if(empty($categories)): ?>
                    <div class="category-card">
                        <div class="category-icon"><i class="fas fa-pen"></i></div>
                        <h3>Pens & Pencils</h3>
                        <p>Writing instruments for every need</p>
                    </div>
                    <div class="category-card">
                        <div class="category-icon"><i class="fas fa-book"></i></div>
                        <h3>Notebooks</h3>
                        <p>Quality notebooks and journals</p>
                    </div>
                    <div class="category-card">
                        <div class="category-icon"><i class="fas fa-paperclip"></i></div>
                        <h3>Office Supplies</h3>
                        <p>Essential office accessories</p>
                    </div>
                    <div class="category-card">
                        <div class="category-icon"><i class="fas fa-palette"></i></div>
                        <h3>Art Supplies</h3>
                        <p>Creative tools for artists</p>
                    </div>
                    
                <?php else: ?>
                    <?php foreach($categories as $cat): ?>
                        <a href="products.php?category=<?php echo $cat['category_id']; ?>" class="category-card">
                            <div class="category-icon"><i class="fas fa-folder"></i></div>
                            <h3><?php echo htmlspecialchars($cat['category_name']); ?></h3>
                            <p><?php echo htmlspecialchars($cat['category_description']); ?></p>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="products">
        <div class="container">
            <div class="section-title">
                <h2>Featured Products</h2>
                <p>Check out our latest and most popular items</p>
            </div>
            <div class="products-grid">
                <?php if(empty($featured_products)): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <i class="fas fa-pen"></i>
                        </div>
                        <div class="product-info">
                            <div class="product-name">Premium Ballpoint Pen</div>
                            <div class="product-price">₹99.00</div>
                            <button class="add-to-cart">Add to Cart</button>
                        </div>
                    </div>
                    <div class="product-card">
                        <div class="product-image">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="product-info">
                            <div class="product-name">Spiral Notebook A4</div>
                            <div class="product-price">₹149.00</div>
                            <button class="add-to-cart">Add to Cart</button>
                        </div>
                    </div>
                    <div class="product-card">
                        <div class="product-image">
                            <i class="fas fa-highlighter"></i>
                        </div>
                        <div class="product-info">
                            <div class="product-name">Highlighter Set</div>
                            <div class="product-price">₹199.00</div>
                            <button class="add-to-cart">Add to Cart</button>
                        </div>
                    </div>
                    <div class="product-card">
                        <div class="product-image">
                            <i class="fas fa-paperclip"></i>
                        </div>
                        <div class="product-info">
                            <div class="product-name">Paper Clips Box</div>
                            <div class="product-price">₹49.00</div>
                            <button class="add-to-cart">Add to Cart</button>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach($featured_products as $prod): ?>
                        <div class="product-card">
                            <div class="product-image">
                                <?php if($prod['product_image']): ?>
                                    <?php 
                                    // Handle both old format (just filename) and new format (full path)
                                    if (strpos($prod['product_image'], 'uploads/') === 0) {
                                        // New format: uploads/products/filename
                                        $image_url = BASE_URL . $prod['product_image'];
                                    } else {
                                        // Old format: just filename
                                        $image_url = UPLOAD_PATH . $prod['product_image'];
                                    }
                                    ?>
                                    <img src="<?php echo $image_url; ?>" alt="<?php echo htmlspecialchars($prod['product_name']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <i class="fas fa-box"></i>
                                <?php endif; ?>
                            </div>
                            <div class="product-info">
                                <div class="product-name"><?php echo htmlspecialchars($prod['product_name']); ?></div>
                                <div class="product-price"><?php echo formatPrice($prod['product_price']); ?></div>
                                <?php if(isLoggedIn()): ?>
                                    <form method="POST" style="margin: 0;">
                                        <input type="hidden" name="product_id" value="<?php echo $prod['product_id']; ?>">
                                        <button type="submit" name="add_to_cart" class="add-to-cart">Add to Cart</button>
                                    </form>
                                <?php else: ?>
                                    <a href="auth/login.php" class="add-to-cart" style="display: block; text-align: center; text-decoration: none; color: white;">Login to Buy</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-links">
                <a href="index.php">Home</a>
                <a href="products.php">Products</a>
                <a href="#contact">Contact</a>
                <a href="#about">About</a>
                <a href="#privacy">Privacy Policy</a>
            </div>
            <p>&copy; 2025 Stationery Shop. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
