# Stationery Shop - Full E-commerce Website

A modern, responsive online stationery shop built with PHP, MySQL, HTML, CSS, and JavaScript.

## Features

### User Features
- **Modern UI/UX**: Clean, responsive design with gradient themes
- **User Registration & Login**: Secure authentication system
- **Product Browsing**: Search and filter products by category
- **Shopping Cart**: Add, update, and remove items
- **Checkout Process**: Complete order placement with multiple payment options
- **Order Management**: View order history and track status
- **Responsive Design**: Works perfectly on desktop, tablet, and mobile

### Admin Features
- **Admin Dashboard**: Comprehensive overview with statistics
- **Product Management**: Add, edit, delete products with image upload
- **Category Management**: Organize products into categories
- **Order Management**: View and update order status
- **User Management**: Monitor registered users
- **Inventory Tracking**: Stock level monitoring

## Installation

1. **Setup XAMPP**: Make sure XAMPP is installed and running
2. **Database Setup**: 
   - Import `stationery_db (1).sql` to create the database structure
   - Optionally import `sample_data.sql` for test data
3. **File Placement**: Ensure all files are in `c:\xampp\htdocs\stationery\`
4. **Permissions**: Make sure the `uploads/products/` directory is writable

## Default Admin Account
- **Email**: admin@stationery.gmail
- **Password**: admin123

## Project Structure

```
stationery/
├── admin/                  # Admin panel
│   ├── dashboard.php      # Admin dashboard
│   ├── products.php       # Product management
│   ├── add_product.php    # Add new product
│   ├── categories.php     # Category management
│   └── add_category.php   # Add new category
├── api/                   # API endpoints
│   └── add_to_cart.php    # Cart API
├── auth/                  # Authentication
│   ├── login.php          # User login
│   ├── register.php       # User registration
│   └── logout.php         # Logout
├── classes/               # PHP classes
│   ├── User.php           # User management
│   ├── Product.php        # Product operations
│   ├── Category.php       # Category operations
│   ├── Cart.php           # Shopping cart
│   └── Order.php          # Order management
├── config/                # Configuration
│   ├── database.php       # Database connection
│   └── config.php         # App configuration
├── uploads/               # File uploads
│   └── products/          # Product images
├── index.php              # Homepage
├── products.php           # Product listing
├── cart.php               # Shopping cart
├── checkout.php           # Checkout process
├── orders.php             # User orders
├── order_details.php      # Order details
└── order_success.php      # Order confirmation
```

## Technologies Used

- **Backend**: PHP 8.x with PDO
- **Database**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3, JavaScript
- **Icons**: Font Awesome 6
- **Styling**: Modern CSS with gradients and animations
- **Security**: Password hashing, SQL injection prevention, XSS protection

## Key Features Implemented

### Security
- Password hashing with PHP's `password_hash()`
- SQL injection prevention using prepared statements
- XSS protection with input sanitization
- Session management for authentication

### Modern UI/UX
- Gradient color schemes
- Smooth animations and transitions
- Card-based layouts
- Responsive grid systems
- Mobile-first design approach

### E-commerce Functionality
- Product catalog with search and filtering
- Shopping cart with quantity management
- Multi-step checkout process
- Order tracking and history
- Inventory management

### Admin Panel
- Comprehensive dashboard with statistics
- CRUD operations for products and categories
- File upload for product images
- Order status management
- User management

## Usage

1. **Access the website**: Navigate to `http://localhost/stationery/`
2. **Browse products**: View products on the homepage or products page
3. **Register/Login**: Create an account or login to existing account
4. **Shop**: Add products to cart and proceed to checkout
5. **Admin access**: Login with admin credentials to access admin panel

## Database Schema

The application uses the following main tables:
- `users`: User accounts and authentication
- `categories`: Product categories
- `products`: Product catalog
- `cart`: Shopping cart items
- `orders`: Order information
- `order_items`: Individual order items

## Future Enhancements

- Payment gateway integration
- Email notifications
- Product reviews and ratings
- Wishlist functionality
- Advanced search filters
- Inventory alerts
- Sales reports and analytics

## Support

For any issues or questions, please check the code comments or refer to the database structure in the SQL files.
