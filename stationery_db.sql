-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 24, 2025 at 02:13 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stationery_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(150) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `email`, `password`, `full_name`, `created_at`, `last_login`) VALUES
(1, 'admin', 'admin@stationery.gmail', '$2y$10$QhRfdprTkvL9wIzY5MSf2OEGA4D8VlyDShuM8rgoakZj17uB2xFUG', 'System Administrator', '2025-09-10 05:46:07', '2025-09-10 05:51:05');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `added_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `product_id`, `quantity`, `added_at`) VALUES
(6, 7, 99, 1, '2025-09-10 11:01:58');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `category_description` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `category_description`, `created_at`) VALUES
(2, 'Writing Instruments', 'Pens, pencils, markers, and other writing tools for everyday use', '2025-09-10 10:14:52'),
(3, 'Notebooks & Journals', 'Various types of notebooks, journals, and writing pads', '2025-09-10 10:14:52'),
(4, 'Office Supplies', 'Essential office items like staplers, clips, and organizers', '2025-09-10 10:14:52'),
(5, 'Art & Craft Supplies', 'Creative materials for drawing, painting, and crafting', '2025-09-10 10:14:52'),
(6, 'Paper Products', 'Different types of paper for printing, writing, and crafting', '2025-09-10 10:14:52'),
(7, 'Desk Accessories', 'Items to organize and decorate your workspace', '2025-09-10 10:14:52'),
(8, 'Filing & Storage', 'Folders, binders, and storage solutions for documents', '2025-09-10 10:14:52'),
(10, 'Correction & Erasers', 'Tools for correcting mistakes and erasing marks', '2025-09-10 10:14:52'),
(11, 'Measuring Tools', 'Rulers, scales, and other measuring instruments', '2025-09-10 10:14:52');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `order_date` date DEFAULT NULL,
  `payment_status` enum('pending','completed','failed','refunded') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_amount`, `order_status`, `order_date`, `payment_status`) VALUES
(1, 7, 1200.00, 'shipped', '2025-09-08', 'pending'),
(2, 7, 900.00, 'pending', '2025-09-08', 'pending'),
(3, 7, 600.00, 'processing', '2025-09-10', 'pending'),
(4, 7, 480.00, 'delivered', '2025-09-10', 'pending'),
(5, 18, 30.00, 'shipped', '2025-06-21', 'pending'),
(6, 18, 810.00, 'processing', '2025-07-26', 'pending'),
(7, 18, 575.00, 'pending', '2025-07-16', 'failed'),
(8, 19, 540.00, 'delivered', '2025-09-07', 'pending'),
(9, 19, 1245.00, 'delivered', '2025-07-13', 'completed'),
(10, 20, 720.00, 'pending', '2025-08-05', 'failed'),
(11, 20, 545.00, 'processing', '2025-06-28', 'completed'),
(12, 20, 130.00, 'pending', '2025-06-27', 'completed'),
(13, 21, 315.00, 'pending', '2025-07-18', 'completed'),
(14, 21, 1589.00, 'shipped', '2025-06-12', 'completed'),
(15, 22, 610.00, 'pending', '2025-09-01', 'pending'),
(16, 22, 210.00, 'pending', '2025-06-23', 'completed'),
(17, 23, 1418.00, 'shipped', '2025-06-17', 'failed'),
(18, 23, 815.00, 'processing', '2025-07-06', 'failed'),
(19, 24, 1690.00, 'shipped', '2025-07-06', 'pending'),
(20, 25, 2525.00, 'shipped', '2025-08-07', 'pending'),
(21, 25, 1229.00, 'shipped', '2025-06-15', 'completed'),
(22, 25, 240.00, 'pending', '2025-07-20', 'completed'),
(23, 26, 625.00, 'pending', '2025-08-05', 'pending'),
(24, 26, 600.00, 'pending', '2025-09-01', 'completed'),
(25, 26, 470.00, 'processing', '2025-09-06', 'pending'),
(26, NULL, 670.00, 'pending', '2025-07-24', 'failed'),
(27, NULL, 750.00, 'delivered', '2025-06-22', 'pending'),
(28, 29, 180.00, 'cancelled', '2025-09-10', 'pending'),
(29, 30, 1350.00, 'pending', '2025-09-13', 'pending'),
(30, 31, 180.00, 'pending', '2025-12-19', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`item_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, NULL, 4, 300.00),
(2, 2, NULL, 3, 300.00),
(3, 3, NULL, 2, 300.00),
(4, 4, 43, 2, 220.00),
(5, 4, 88, 1, 40.00),
(6, 5, 3, 2, 15.00),
(7, 6, 9, 3, 150.00),
(8, 6, 19, 2, 180.00),
(9, 7, 8, 2, 80.00),
(10, 7, 15, 2, 95.00),
(11, 7, 8, 2, 80.00),
(12, 7, 10, 1, 65.00),
(13, 8, 19, 3, 180.00),
(14, 9, 14, 3, 35.00),
(15, 9, NULL, 2, 300.00),
(16, 9, 19, 3, 180.00),
(17, 10, 13, 2, 220.00),
(18, 10, 11, 1, 180.00),
(19, 10, 7, 2, 25.00),
(20, 10, 7, 2, 25.00),
(21, 11, 16, 1, 450.00),
(22, 11, 15, 1, 95.00),
(23, 12, 10, 2, 65.00),
(24, 13, 5, 2, 45.00),
(25, 13, 14, 2, 35.00),
(26, 13, 14, 2, 35.00),
(27, 13, 12, 1, 85.00),
(28, 14, 18, 3, 28.00),
(29, 14, 3, 3, 15.00),
(30, 14, 6, 2, 250.00),
(31, 14, 13, 3, 220.00),
(32, 14, 9, 2, 150.00),
(33, 15, 4, 2, 120.00),
(34, 15, 10, 2, 65.00),
(35, 15, 4, 2, 120.00),
(36, 16, 20, 1, 45.00),
(37, 16, 17, 3, 55.00),
(38, 17, 6, 2, 250.00),
(39, 17, 16, 1, 450.00),
(40, 17, 13, 2, 220.00),
(41, 17, 18, 1, 28.00),
(42, 18, NULL, 2, 300.00),
(43, 18, 10, 1, 65.00),
(44, 18, 3, 2, 15.00),
(45, 18, 4, 1, 120.00),
(46, 19, NULL, 3, 300.00),
(47, 19, NULL, 2, 300.00),
(48, 19, 15, 2, 95.00),
(49, 20, 6, 2, 250.00),
(50, 20, 16, 1, 450.00),
(51, 20, 13, 3, 220.00),
(52, 20, 16, 2, 450.00),
(53, 20, 2, 1, 15.00),
(54, 21, 15, 1, 95.00),
(55, 21, 18, 3, 28.00),
(56, 21, 6, 3, 250.00),
(57, 21, 14, 3, 35.00),
(58, 21, 10, 3, 65.00),
(59, 22, 4, 2, 120.00),
(60, 23, 11, 2, 180.00),
(61, 23, 13, 1, 220.00),
(62, 23, 3, 3, 15.00),
(63, 24, 14, 3, 35.00),
(64, 24, 4, 3, 120.00),
(65, 24, 20, 3, 45.00),
(66, 25, 17, 2, 55.00),
(67, 25, 11, 2, 180.00),
(68, 26, 19, 3, 180.00),
(69, 26, 10, 2, 65.00),
(70, 27, 10, 3, 65.00),
(71, 27, 20, 2, 45.00),
(72, 27, 20, 3, 45.00),
(73, 27, 12, 2, 85.00),
(74, 27, 8, 2, 80.00),
(75, 28, 11, 1, 180.00),
(76, 29, 100, 3, 450.00),
(77, 30, 42, 1, 180.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_description` text DEFAULT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_quantity` int(11) NOT NULL,
  `product_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `category_id`, `product_name`, `product_description`, `product_price`, `product_quantity`, `product_image`) VALUES
(2, 2, 'Ballpoint Pen Blue', 'Smooth writing ballpoint pen in blue ink', 15.00, 50, 'uploads/products/68c16bece07dd.webp'),
(3, 2, 'Ballpoint Pen Black', 'Reliable black ink ballpoint pen for everyday use', 15.00, 45, 'uploads/products/68c16bf7d8b43.gif'),
(4, 2, 'Gel Pen Set', 'Set of 6 colorful gel pens for smooth writing', 120.00, 30, 'uploads/products/68c16c004f311.jpeg'),
(5, 2, 'Mechanical Pencil 0.5mm', 'Precision mechanical pencil with 0.5mm lead', 45.00, 40, 'uploads/products/68c16c099f79e.jpg'),
(6, 2, 'Fountain Pen Classic', 'Elegant fountain pen for professional writing', 250.00, 20, 'uploads/products/68c16c1673c8b.jpeg'),
(7, 2, 'Highlighter Yellow', 'Bright yellow highlighter for marking text', 25.00, 60, 'uploads/products/68c16acfb9f59.webp'),
(8, 2, 'Marker Set Permanent', 'Set of 4 permanent markers in assorted colors', 80.00, 35, 'uploads/products/68c16c2019b9d.jpg'),
(9, 2, 'Sketch Pencil Set', 'Professional sketching pencils (2H to 6B)', 150.00, 25, 'uploads/products/68c16eda2903a.webp'),
(10, 2, 'Rollerball Pen', 'Smooth rollerball pen with liquid ink', 65.00, 30, 'uploads/products/68c16ef334a30.jpg'),
(11, 2, 'Calligraphy Pen', 'Traditional calligraphy pen for beautiful writing', 180.00, 15, 'uploads/products/68c16f2852cfd.jpg'),
(12, 3, 'Spiral Notebook A4', 'A4 size spiral notebook with 200 pages', 85.00, 40, 'uploads/products/68c1710ced3f1.jpg'),
(13, 3, 'Hardcover Journal', 'Premium hardcover journal with lined pages', 220.00, 25, 'uploads/products/68c1712050fa0.jpeg'),
(14, 3, 'Pocket Notebook', 'Compact pocket-sized notebook for quick notes', 35.00, 60, 'uploads/products/68c1713235889.jpg'),
(15, 3, 'Graph Paper Notebook', 'A4 notebook with graph paper for technical drawing', 95.00, 30, 'uploads/products/68c1713e6bd33.webp'),
(16, 3, 'Leather Bound Diary', 'Elegant leather-bound diary with lock', 450.00, 15, 'uploads/products/68c1714bdd1f0.jpeg'),
(17, 3, 'Sticky Notes Pack', 'Pack of colorful sticky notes in various sizes', 55.00, 80, 'uploads/products/68c1715b34f17.webp'),
(18, 3, 'Memo Pad', 'Small memo pad for quick reminders', 28.00, 70, 'uploads/products/68c17199628e7.webp'),
(19, 3, 'Bullet Journal', 'Dotted notebook perfect for bullet journaling', 180.00, 20, 'uploads/products/68c171aa9b379.webp'),
(20, 3, 'Composition Book', 'Classic composition book with wide ruled lines', 45.00, 50, 'uploads/products/68c171b94ffdb.jpeg'),
(21, 3, 'Sketch Pad A3', 'Large A3 sketch pad for artists', 120.00, 25, 'uploads/products/68c171d224215.webp'),
(22, 4, 'Stapler Heavy Duty', 'Heavy duty stapler for thick documents', 320.00, 20, 'uploads/products/68c171df3fb6e.jpg'),
(23, 4, 'Paper Clips Box', 'Box of 100 standard paper clips', 25.00, 100, 'uploads/products/68c171ef36260.jpg'),
(24, 4, 'Binder Clips Set', 'Assorted sizes of binder clips', 45.00, 60, 'uploads/products/68c171fa0b789.jpg'),
(25, 4, 'Rubber Stamps', 'Set of office rubber stamps', 180.00, 15, 'uploads/products/68c1726cde1ec.webp'),
(26, 4, 'Hole Punch', 'Two-hole punch for standard documents', 150.00, 25, 'uploads/products/68c172155103c.webp'),
(27, 4, 'Desk Organizer', 'Multi-compartment desk organizer', 280.00, 18, 'uploads/products/68c17229bd7bb.jpg'),
(28, 4, 'Letter Opener', 'Stainless steel letter opener', 85.00, 30, 'uploads/products/68c1723835f4b.jpg'),
(29, 4, 'Push Pins', 'Colorful push pins for bulletin boards', 35.00, 75, 'uploads/products/68c1724a05a0b.jpg'),
(30, 4, 'Rubber Bands', 'Assorted rubber bands in various sizes', 20.00, 90, 'uploads/products/68c1725b016f4.webp'),
(31, 4, 'Calculator Basic', 'Basic calculator for office calculations', 220.00, 22, 'uploads/products/68c1a27f8b1bc.webp'),
(32, 5, 'Colored Pencils 24 Set', 'Professional colored pencils set of 24', 320.00, 25, 'uploads/products/68c1a3164d5bf.webp'),
(33, 5, 'Watercolor Paint Set', 'Complete watercolor painting set', 450.00, 15, 'uploads/products/68c44abe9e035.jpeg'),
(34, 5, 'Acrylic Paint Tubes', 'Set of 12 acrylic paint tubes', 380.00, 20, 'uploads/products/68c1733325e39.jpg'),
(35, 5, 'Paint Brushes Set', 'Variety pack of paint brushes', 180.00, 30, 'uploads/products/68c44aad26290.jpeg'),
(36, 5, 'Craft Scissors', 'Sharp craft scissors for precision cutting', 95.00, 40, 'uploads/products/68c1a410dd79d.webp'),
(37, 5, 'Glue Sticks Pack', 'Pack of 5 glue sticks', 85.00, 50, 'uploads/products/68c44a9882022.jpeg'),
(38, 5, 'Construction Paper', 'Colorful construction paper pack', 65.00, 45, 'uploads/products/68c1a34a273cd.jpg'),
(39, 5, 'Modeling Clay', 'Non-toxic modeling clay set', 120.00, 35, 'uploads/products/68c44a79d2bf6.jpeg'),
(40, 5, 'Crayons 64 Pack', 'Large pack of 64 crayons', 150.00, 40, 'uploads/products/68c1a42e7031a.jpg'),
(41, 5, 'Craft Knife', 'Precision craft knife with extra blades', 75.00, 25, 'uploads/products/68c1a3f8b23bf.jpg'),
(42, 6, 'A4 Copy Paper', 'High quality A4 copy paper (500 sheets)', 180.00, 50, 'uploads/products/68c172a661857.webp'),
(43, 6, 'Photo Paper Glossy', 'Glossy photo paper for printing', 220.00, 30, 'uploads/products/68c44a644e623.jpeg'),
(44, 6, 'Cardstock Paper', 'Heavy cardstock paper for crafts', 95.00, 40, 'uploads/products/68c1a2e8b84ad.webp'),
(45, 6, 'Tracing Paper', 'Transparent tracing paper pad', 85.00, 35, 'uploads/products/68c44a529cf68.jpeg'),
(46, 6, 'Origami Paper', 'Colorful origami paper squares', 65.00, 45, 'uploads/products/68c44a40ac517.jpeg'),
(47, 6, 'Envelope Pack', 'Pack of 50 standard envelopes', 45.00, 60, 'uploads/products/68c1a5522ab40.jpg'),
(48, 6, 'Index Cards', 'Ruled index cards for studying', 35.00, 70, 'uploads/products/68c44a2e71c4b.jpeg'),
(49, 6, 'Legal Pad', 'Yellow legal pad with perforated pages', 55.00, 50, 'uploads/products/68c44a190d944.jpeg'),
(50, 6, 'Carbon Paper', 'Carbon paper for making copies', 40.00, 25, 'uploads/products/68c1a2c84e35e.jpg'),
(51, 6, 'Parchment Paper', 'Decorative parchment paper sheets', 75.00, 30, 'uploads/products/68c449fe6278c.jpeg'),
(52, 7, 'Pen Holder', 'Stylish pen and pencil holder', 120.00, 35, 'uploads/products/68c449e8c25e2.jpeg'),
(53, 7, 'Paper Tray', 'Stackable paper tray for documents', 180.00, 25, 'uploads/products/68c449d57f811.jpeg'),
(54, 7, 'Desk Lamp LED', 'Adjustable LED desk lamp', 850.00, 12, 'uploads/products/68c1a49e93ddc.webp'),
(55, 7, 'Mouse Pad', 'Ergonomic mouse pad with wrist support', 220.00, 30, 'uploads/products/68c449c317426.jpeg'),
(56, 7, 'Desk Calendar', 'Monthly desk calendar with stand', 95.00, 40, 'uploads/products/68c1a44e1eada.jpg'),
(57, 7, 'Business Card Holder', 'Professional business card display', 150.00, 20, 'uploads/products/68c1a2002f951.jpg'),
(58, 7, 'Paperweight Glass', 'Decorative glass paperweight', 180.00, 15, 'uploads/products/68c449a8ea54e.jpeg'),
(59, 7, 'Desk Clock Digital', 'Digital desk clock with alarm', 320.00, 18, 'uploads/products/68c1a46ebd76b.jpg'),
(60, 7, 'Cable Organizer', 'Desk cable management system', 85.00, 45, 'uploads/products/68c1a23fc2aa5.webp'),
(61, 7, 'Bookends Metal', 'Heavy duty metal bookends pair', 280.00, 20, 'uploads/products/68c1a1d51ea24.webp'),
(62, 8, 'File Folders Manila', 'Pack of 25 manila file folders', 120.00, 40, 'uploads/products/68c1a5ad00ea0.jpg'),
(63, 8, 'Hanging Folders', 'Letter size hanging folders with tabs', 180.00, 30, 'uploads/products/68c44995967ee.jpeg'),
(64, 8, 'Binder 3-Ring', 'Heavy duty 3-ring binder', 150.00, 35, 'uploads/products/68c1a1a6414ab.jpeg'),
(65, 8, 'Document Wallet', 'Expandable document wallet', 95.00, 25, 'uploads/products/68c1a4b62751e.jpeg'),
(66, 8, 'Storage Box', 'Cardboard storage box for files', 85.00, 50, 'uploads/products/68c44982ee4aa.jpeg'),
(67, 8, 'Magazine Holder', 'Desktop magazine and file holder', 120.00, 30, 'uploads/products/68c449634f397.jpeg'),
(68, 8, 'Accordion File', '13-pocket accordion file organizer', 220.00, 20, 'uploads/products/68c172ca82995.jpeg'),
(69, 8, 'Sheet Protectors', 'Clear sheet protectors pack of 100', 65.00, 60, 'uploads/products/68c449509e49c.jpeg'),
(70, 8, 'Tab Dividers', 'Colored tab dividers for binders', 45.00, 70, 'uploads/products/68c449384ba1c.jpeg'),
(82, 10, 'White Out Pen', 'Correction pen for precise corrections', 35.00, 60, 'uploads/products/68c4484572f23.jpg'),
(83, 10, 'Correction Tape', 'Easy-to-use correction tape dispenser', 55.00, 50, 'uploads/products/68c1a3db89e32.jpeg'),
(84, 10, 'Pink Eraser Large', 'Large pink eraser for pencil marks', 15.00, 100, 'uploads/products/68c44830914be.jpg'),
(85, 10, 'Kneaded Eraser', 'Soft kneaded eraser for artists', 25.00, 80, 'uploads/products/68c4481e51c34.jpg'),
(86, 10, 'Electric Eraser', 'Battery-powered precision eraser', 180.00, 15, 'uploads/products/68c1a509a5014.jpg'),
(87, 10, 'Eraser Caps', 'Pencil eraser caps pack of 20', 20.00, 120, 'uploads/products/68c1a5702627d.jpg'),
(88, 10, 'Correction Fluid', 'Quick-dry correction fluid', 40.00, 70, 'uploads/products/68c1a3735c4bf.jpg'),
(89, 10, 'Art Gum Eraser', 'Soft art gum eraser for delicate work', 30.00, 60, 'uploads/products/68c1a18000e28.jpg'),
(90, 10, 'Vinyl Eraser', 'White vinyl eraser for ink removal', 45.00, 45, 'uploads/products/68c4480d0282d.jpg'),
(91, 10, 'Correction Pen Fine', 'Fine tip correction pen', 50.00, 40, 'uploads/products/68c1a38dde88d.jpeg'),
(92, 11, 'Ruler 12 inch', 'Clear plastic ruler with metric and imperial', 25.00, 80, 'uploads/products/68c447f45b076.jpg'),
(93, 11, 'Protractor', 'Geometric protractor for angle measurement', 35.00, 60, 'uploads/products/68c447db41686.png'),
(94, 11, 'Compass Set', 'Mathematical compass with pencil', 85.00, 30, 'uploads/products/68c1a33326036.jpg'),
(95, 11, 'Triangle Set Square', 'Set of geometric triangles', 65.00, 40, 'uploads/products/68c447c5ada9a.jpg'),
(96, 11, 'Measuring Tape', 'Retractable measuring tape 3 meters', 120.00, 25, 'uploads/products/68c447b0569f1.jpg'),
(97, 11, 'Scale Ruler', 'Architect scale ruler', 95.00, 20, 'uploads/products/68c4475f11ac6.jpg'),
(98, 11, 'Flexible Curve', 'Bendable curve ruler for drawing', 75.00, 35, 'uploads/products/68c4472a99fb4.jpeg'),
(99, 11, 'T-Square', 'Professional T-square for drafting', 180.00, 15, 'uploads/products/68c4471198347.jpeg'),
(100, 11, 'Caliper Digital', 'Digital caliper for precise measurement', 450.00, 10, 'uploads/products/68c1a2a1259aa.jpg'),
(101, 11, 'Yardstick', 'Wooden yardstick 36 inches', 55.00, 30, 'uploads/products/68c446f96d295.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`) VALUES
(7, 'meet', 'asd@gmail.com', '$2y$10$D4oj4z.WScILrVtba4tGtOpMFD8uUioxBf.KqVzvkzy13JdgvoKsm'),
(18, 'john_doe', 'john.doe@email.com', '$2y$10$p6XmRlbpS/5BzTlbjwUflux7OP13vt5IA/Y4iqUo3h/FnaVZda.sa'),
(19, 'sarah_smith', 'sarah.smith@email.com', '$2y$10$Ke2wo9tsWn2jAoZkW9KXFOpVQHnh3fo.ggthYkDJ8YGhb3wiugu62'),
(20, 'mike_johnson', 'mike.johnson@email.com', '$2y$10$KxQvGK5DneYqUWeglTDmDee9tgrtqv.jPKdU2Lz.WHYpNhMOfqqHC'),
(21, 'emily_brown', 'emily.brown@email.com', '$2y$10$Gvg/t45S3cSSzNPZ2SYap.AY1RrCsOoVre64WPi0z/pkzTXR11x8u'),
(22, 'david_wilson', 'david.wilson@email.com', '$2y$10$hLVacSrPuc7jCE5YFRZg1uiF/oe6vz3Kr/QbICOTCkYbJjb7Tn/t2'),
(23, 'lisa_davis', 'lisa.davis@email.com', '$2y$10$3Yn0c/OgRR//KAeLzz83tOyWKCY.XFuKBMGaYi1abK1eR3Y1JHfK2'),
(24, 'robert_miller', 'robert.miller@email.com', '$2y$10$80EcO7ygmZnPYF0MYgKdLeJLJL0Jtmist.svmsnFZvbwlVcrfvdOy'),
(25, 'jennifer_garcia', 'jennifer.garcia@email.com', '$2y$10$MoFEdE4Q5K.9DinppWLj..UyeJH65Yt4inq1xhDfhW9ZAqmoiPotG'),
(26, 'william_martinez', 'william.martinez@email.com', '$2y$10$KkDZjHebi.uCmR/g3OwEAuR9raodN3xvjseWKZE9JPiNrDVodMihe'),
(28, 'jeelk', 'jeel@gmail.com', '$2y$10$J.AJx5T4GOyIR2zux23.x.gwxeoc37H9NWkeTslcZT5bFjLwVwJWi'),
(29, 'JEELKPATEL', 'jeelk@gmail.com', '$2y$10$m8YAB8Lq1smoWB8X7Vc0kuZahRuvgIkCKw.qek8yfsSkJjYvamJVu'),
(30, 'Avi', 'avi@gmail.com', '$2y$10$nObz7Ny3ckQJIsi4QHF/mOVDA9NjLcgWpnmSlq.cKJnzTq4/YjU/6'),
(31, 'user', 'jeelkpatel17@gmail.com', '$2y$10$LH6sRcuonoG0/KhInp896uEIFn1W.kZOiCZ6SMUidDanZkq6hVQNq');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
