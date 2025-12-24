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

// Get search and filter parameters
$filters = [
    'search' => isset($_GET['search']) ? sanitize($_GET['search']) : '',
    'category' => isset($_GET['category']) ? (int)$_GET['category'] : 0,
    'min_price' => isset($_GET['min_price']) ? (float)$_GET['min_price'] : 0,
    'max_price' => isset($_GET['max_price']) ? (float)$_GET['max_price'] : 0,
    'sort' => isset($_GET['sort']) ? sanitize($_GET['sort']) : 'name',
    'in_stock' => isset($_GET['in_stock']) ? true : false
];

// Remove empty filters
$filters = array_filter($filters, function($value) {
    return !empty($value) || $value === true;
});

// Get products based on filters
if (!empty($filters)) {
    $stmt = $product->getFilteredProducts($filters);
} else {
    $stmt = $product->read();
}

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get categories for filter
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
    redirect('products.php');
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
    <title>Products - Stationery Shop</title>
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

        /* Filters */
        .filters {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .filter-row {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-box {
            flex: 1;
            min-width: 300px;
        }

        .search-box input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 1rem;
        }

        .search-box input:focus {
            outline: none;
            border-color: rgb(23,150,229);
        }

        .category-filter select {
            padding: 0.75rem 1rem;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 1rem;
            background: white;
        }

        .filter-btn {
            background: linear-gradient(135deg, rgb(23,150,229) 0%, rgb(61,154,236) 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(70, 130, 180, 0.3);
        }

        /* Products Grid */
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
            min-height: 330px;
            max-height: 330px;
            background: #f8f9fa;
            border-radius: 10px 10px 0 0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            padding: 10px;
        }

        .product-image img {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
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

        .product-description {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            line-height: 1.4;
        }

        .product-price {
            font-size: 1.5rem;
            font-weight: bold;
            color: rgb(23,150,229);
            margin-bottom: 1rem;
        }

        .add-to-cart {
            width: 100%;
            background: linear-gradient(135deg, rgb(23,150,229) 0%, rgb(61,154,236) 100%);
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


        .no-products {
            text-align: center;
            padding: 3rem;
            color: #666;
        }

        .no-products i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #ddd;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .filter-row {
                flex-direction: column;
            }

            .search-box {
                min-width: auto;
            }

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
                        <?php if(isAdmin()): ?>
                            <li><a href="admin/dashboard.php"><i class="fas fa-cog"></i> Admin</a></li>
                        <?php endif; ?>
                        <li><a href="auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    <?php else: ?>
                        <li><a href="auth/login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                        <li><a href="auth/register.php"><i class="fas fa-user-plus"></i> Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="page-header">
                <h1>Our Products</h1>
                <p>Discover our wide range of quality stationery items</p>
            </div>

            <?php if(isset($_SESSION['cart_message'])): ?>
                <div style="background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 10px; padding: 1rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-check-circle"></i>
                    <span><?php echo $_SESSION['cart_message']; unset($_SESSION['cart_message']); ?></span>
                </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['cart_error'])): ?>
                <div style="background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 10px; padding: 1rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo $_SESSION['cart_error']; unset($_SESSION['cart_error']); ?></span>
                </div>
            <?php endif; ?>

            <!-- Filters -->
            <div class="filters">
                <form method="GET" class="filter-row">
                    <div class="search-box">
                        <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>">
                    </div>
                    <div class="category-filter">
                        <select name="category">
                            <option value="">All Categories</option>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?php echo $cat['category_id']; ?>" <?php echo ($filters['category'] ?? 0) == $cat['category_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['category_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="price-filter">
                        <input type="number" name="min_price" placeholder="Min Price" value="<?php echo $filters['min_price'] ?? ''; ?>" style="width: 100px; padding: 0.5rem; border: 2px solid #e1e5e9; border-radius: 8px;">
                        <input type="number" name="max_price" placeholder="Max Price" value="<?php echo $filters['max_price'] ?? ''; ?>" style="width: 100px; padding: 0.5rem; border: 2px solid #e1e5e9; border-radius: 8px;">
                    </div>
                    <div class="sort-filter">
                        <select name="sort" style="padding: 0.5rem; border: 2px solid #e1e5e9; border-radius: 8px;">
                            <option value="name" <?php echo ($filters['sort'] ?? 'name') == 'name' ? 'selected' : ''; ?>>Name A-Z</option>
                            <option value="price_low" <?php echo ($filters['sort'] ?? '') == 'price_low' ? 'selected' : ''; ?>>Price Low to High</option>
                            <option value="price_high" <?php echo ($filters['sort'] ?? '') == 'price_high' ? 'selected' : ''; ?>>Price High to Low</option>
                            <option value="newest" <?php echo ($filters['sort'] ?? '') == 'newest' ? 'selected' : ''; ?>>Newest First</option>
                        </select>
                    </div>
                    <div class="stock-filter">
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" name="in_stock" value="1" <?php echo isset($filters['in_stock']) ? 'checked' : ''; ?>>
                            In Stock Only
                        </label>
                    </div>
                    <button type="submit" class="filter-btn">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="products.php" class="filter-btn" style="background: #6c757d; text-decoration: none;">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </form>
            </div>

            <!-- Products Grid -->
            <?php if(empty($products)): ?>
                <div class="no-products">
                    <i class="fas fa-box-open"></i>
                    <h3>No products found</h3>
                    <p>Try adjusting your search criteria or browse all products.</p>
                </div>
            <?php else: ?>
                <div class="products-grid">
                    <?php foreach($products as $prod): ?>
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
                                    <img src="<?php echo $image_url; ?>" alt="<?php echo htmlspecialchars($prod['product_name']); ?>">
                                <?php else: ?>
                                    <i class="fas fa-box"></i>
                                <?php endif; ?>
                            </div>
                            <div class="product-info">
                                <div class="product-name"><?php echo htmlspecialchars($prod['product_name']); ?></div>
                                <?php if($prod['product_description']): ?>
                                    <div class="product-description"><?php echo htmlspecialchars(substr($prod['product_description'], 0, 100)) . (strlen($prod['product_description']) > 100 ? '...' : ''); ?></div>
                                <?php endif; ?>
                                <div class="product-price"><?php echo formatPrice($prod['product_price']); ?></div>
                                <?php if(isLoggedIn()): ?>
                                    <form method="POST" style="margin: 0;">
                                        <input type="hidden" name="product_id" value="<?php echo $prod['product_id']; ?>">
                                        <button type="submit" name="add_to_cart" class="add-to-cart">
                                            <i class="fas fa-cart-plus"></i> Add to Cart
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <a href="auth/login.php" class="add-to-cart">
                                        <i class="fas fa-sign-in-alt"></i> Login to Buy
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

</body>
</html>
