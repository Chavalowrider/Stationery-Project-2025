<?php
require_once 'config/config.php';
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

// Start transaction
$db->beginTransaction();

try {
    // Categories data
    $categories = [
        [
            'name' => 'Writing Instruments',
            'description' => 'Pens, pencils, markers, and other writing tools for everyday use'
        ],
        [
            'name' => 'Notebooks & Journals',
            'description' => 'Various types of notebooks, journals, and writing pads'
        ],
        [
            'name' => 'Office Supplies',
            'description' => 'Essential office items like staplers, clips, and organizers'
        ],
        [
            'name' => 'Art & Craft Supplies',
            'description' => 'Creative materials for drawing, painting, and crafting'
        ],
        [
            'name' => 'Paper Products',
            'description' => 'Different types of paper for printing, writing, and crafting'
        ],
        [
            'name' => 'Desk Accessories',
            'description' => 'Items to organize and decorate your workspace'
        ],
        [
            'name' => 'Filing & Storage',
            'description' => 'Folders, binders, and storage solutions for documents'
        ],
        [
            'name' => 'Adhesives & Tapes',
            'description' => 'Glue, tape, and other adhesive products'
        ],
        [
            'name' => 'Correction & Erasers',
            'description' => 'Tools for correcting mistakes and erasing marks'
        ],
        [
            'name' => 'Measuring Tools',
            'description' => 'Rulers, scales, and other measuring instruments'
        ]
    ];

    // Insert categories
    $category_ids = [];
    $category_stmt = $db->prepare("INSERT INTO categories (category_name, category_description) VALUES (?, ?)");
    
    foreach ($categories as $category) {
        $category_stmt->execute([$category['name'], $category['description']]);
        $category_ids[] = $db->lastInsertId();
    }

    echo "✓ Added " . count($categories) . " categories successfully!\n";

    // Products data - 10 products per category = 100 total products
    $products = [
        // Writing Instruments (Category 1)
        ['Ballpoint Pen Blue', 'Smooth writing ballpoint pen in blue ink', 15.00, 50],
        ['Ballpoint Pen Black', 'Reliable black ink ballpoint pen for everyday use', 15.00, 45],
        ['Gel Pen Set', 'Set of 6 colorful gel pens for smooth writing', 120.00, 30],
        ['Mechanical Pencil 0.5mm', 'Precision mechanical pencil with 0.5mm lead', 45.00, 40],
        ['Fountain Pen Classic', 'Elegant fountain pen for professional writing', 250.00, 20],
        ['Highlighter Yellow', 'Bright yellow highlighter for marking text', 25.00, 60],
        ['Marker Set Permanent', 'Set of 4 permanent markers in assorted colors', 80.00, 35],
        ['Sketch Pencil Set', 'Professional sketching pencils (2H to 6B)', 150.00, 25],
        ['Rollerball Pen', 'Smooth rollerball pen with liquid ink', 65.00, 30],
        ['Calligraphy Pen', 'Traditional calligraphy pen for beautiful writing', 180.00, 15],

        // Notebooks & Journals (Category 2)
        ['Spiral Notebook A4', 'A4 size spiral notebook with 200 pages', 85.00, 40],
        ['Hardcover Journal', 'Premium hardcover journal with lined pages', 220.00, 25],
        ['Pocket Notebook', 'Compact pocket-sized notebook for quick notes', 35.00, 60],
        ['Graph Paper Notebook', 'A4 notebook with graph paper for technical drawing', 95.00, 30],
        ['Leather Bound Diary', 'Elegant leather-bound diary with lock', 450.00, 15],
        ['Sticky Notes Pack', 'Pack of colorful sticky notes in various sizes', 55.00, 80],
        ['Memo Pad', 'Small memo pad for quick reminders', 28.00, 70],
        ['Bullet Journal', 'Dotted notebook perfect for bullet journaling', 180.00, 20],
        ['Composition Book', 'Classic composition book with wide ruled lines', 45.00, 50],
        ['Sketch Pad A3', 'Large A3 sketch pad for artists', 120.00, 25],

        // Office Supplies (Category 3)
        ['Stapler Heavy Duty', 'Heavy duty stapler for thick documents', 320.00, 20],
        ['Paper Clips Box', 'Box of 100 standard paper clips', 25.00, 100],
        ['Binder Clips Set', 'Assorted sizes of binder clips', 45.00, 60],
        ['Rubber Stamps', 'Set of office rubber stamps', 180.00, 15],
        ['Hole Punch', 'Two-hole punch for standard documents', 150.00, 25],
        ['Desk Organizer', 'Multi-compartment desk organizer', 280.00, 18],
        ['Letter Opener', 'Stainless steel letter opener', 85.00, 30],
        ['Push Pins', 'Colorful push pins for bulletin boards', 35.00, 75],
        ['Rubber Bands', 'Assorted rubber bands in various sizes', 20.00, 90],
        ['Calculator Basic', 'Basic calculator for office calculations', 220.00, 22],

        // Art & Craft Supplies (Category 4)
        ['Colored Pencils 24 Set', 'Professional colored pencils set of 24', 320.00, 25],
        ['Watercolor Paint Set', 'Complete watercolor painting set', 450.00, 15],
        ['Acrylic Paint Tubes', 'Set of 12 acrylic paint tubes', 380.00, 20],
        ['Paint Brushes Set', 'Variety pack of paint brushes', 180.00, 30],
        ['Craft Scissors', 'Sharp craft scissors for precision cutting', 95.00, 40],
        ['Glue Sticks Pack', 'Pack of 5 glue sticks', 85.00, 50],
        ['Construction Paper', 'Colorful construction paper pack', 65.00, 45],
        ['Modeling Clay', 'Non-toxic modeling clay set', 120.00, 35],
        ['Crayons 64 Pack', 'Large pack of 64 crayons', 150.00, 40],
        ['Craft Knife', 'Precision craft knife with extra blades', 75.00, 25],

        // Paper Products (Category 5)
        ['A4 Copy Paper', 'High quality A4 copy paper (500 sheets)', 180.00, 50],
        ['Photo Paper Glossy', 'Glossy photo paper for printing', 220.00, 30],
        ['Cardstock Paper', 'Heavy cardstock paper for crafts', 95.00, 40],
        ['Tracing Paper', 'Transparent tracing paper pad', 85.00, 35],
        ['Origami Paper', 'Colorful origami paper squares', 65.00, 45],
        ['Envelope Pack', 'Pack of 50 standard envelopes', 45.00, 60],
        ['Index Cards', 'Ruled index cards for studying', 35.00, 70],
        ['Legal Pad', 'Yellow legal pad with perforated pages', 55.00, 50],
        ['Carbon Paper', 'Carbon paper for making copies', 40.00, 25],
        ['Parchment Paper', 'Decorative parchment paper sheets', 75.00, 30],

        // Desk Accessories (Category 6)
        ['Pen Holder', 'Stylish pen and pencil holder', 120.00, 35],
        ['Paper Tray', 'Stackable paper tray for documents', 180.00, 25],
        ['Desk Lamp LED', 'Adjustable LED desk lamp', 850.00, 12],
        ['Mouse Pad', 'Ergonomic mouse pad with wrist support', 220.00, 30],
        ['Desk Calendar', 'Monthly desk calendar with stand', 95.00, 40],
        ['Business Card Holder', 'Professional business card display', 150.00, 20],
        ['Paperweight Glass', 'Decorative glass paperweight', 180.00, 15],
        ['Desk Clock Digital', 'Digital desk clock with alarm', 320.00, 18],
        ['Cable Organizer', 'Desk cable management system', 85.00, 45],
        ['Bookends Metal', 'Heavy duty metal bookends pair', 280.00, 20],

        // Filing & Storage (Category 7)
        ['File Folders Manila', 'Pack of 25 manila file folders', 120.00, 40],
        ['Hanging Folders', 'Letter size hanging folders with tabs', 180.00, 30],
        ['Binder 3-Ring', 'Heavy duty 3-ring binder', 150.00, 35],
        ['Document Wallet', 'Expandable document wallet', 95.00, 25],
        ['Storage Box', 'Cardboard storage box for files', 85.00, 50],
        ['Magazine Holder', 'Desktop magazine and file holder', 120.00, 30],
        ['Accordion File', '13-pocket accordion file organizer', 220.00, 20],
        ['Sheet Protectors', 'Clear sheet protectors pack of 100', 65.00, 60],
        ['Tab Dividers', 'Colored tab dividers for binders', 45.00, 70],
        ['File Cabinet Lock', 'Security lock for file cabinets', 180.00, 15],

        // Adhesives & Tapes (Category 8)
        ['Scotch Tape Clear', 'Clear scotch tape dispenser with refill', 85.00, 60],
        ['Double Sided Tape', 'Strong double-sided mounting tape', 95.00, 45],
        ['Masking Tape', 'General purpose masking tape', 35.00, 80],
        ['Duct Tape Silver', 'Heavy duty silver duct tape', 120.00, 30],
        ['Glue Bottle White', 'White school glue in squeeze bottle', 25.00, 90],
        ['Super Glue', 'Instant bonding super glue', 45.00, 70],
        ['Tape Dispenser Heavy', 'Heavy duty tape dispenser', 220.00, 20],
        ['Washi Tape Set', 'Decorative washi tape collection', 150.00, 35],
        ['Electrical Tape', 'Black electrical insulation tape', 55.00, 50],
        ['Packaging Tape', 'Clear packaging tape for shipping', 75.00, 40],

        // Correction & Erasers (Category 9)
        ['White Out Pen', 'Correction pen for precise corrections', 35.00, 60],
        ['Correction Tape', 'Easy-to-use correction tape dispenser', 55.00, 50],
        ['Pink Eraser Large', 'Large pink eraser for pencil marks', 15.00, 100],
        ['Kneaded Eraser', 'Soft kneaded eraser for artists', 25.00, 80],
        ['Electric Eraser', 'Battery-powered precision eraser', 180.00, 15],
        ['Eraser Caps', 'Pencil eraser caps pack of 20', 20.00, 120],
        ['Correction Fluid', 'Quick-dry correction fluid', 40.00, 70],
        ['Art Gum Eraser', 'Soft art gum eraser for delicate work', 30.00, 60],
        ['Vinyl Eraser', 'White vinyl eraser for ink removal', 45.00, 45],
        ['Correction Pen Fine', 'Fine tip correction pen', 50.00, 40],

        // Measuring Tools (Category 10)
        ['Ruler 12 inch', 'Clear plastic ruler with metric and imperial', 25.00, 80],
        ['Protractor', 'Geometric protractor for angle measurement', 35.00, 60],
        ['Compass Set', 'Mathematical compass with pencil', 85.00, 30],
        ['Triangle Set Square', 'Set of geometric triangles', 65.00, 40],
        ['Measuring Tape', 'Retractable measuring tape 3 meters', 120.00, 25],
        ['Scale Ruler', 'Architect scale ruler', 95.00, 20],
        ['Flexible Curve', 'Bendable curve ruler for drawing', 75.00, 35],
        ['T-Square', 'Professional T-square for drafting', 180.00, 15],
        ['Caliper Digital', 'Digital caliper for precise measurement', 450.00, 10],
        ['Yardstick', 'Wooden yardstick 36 inches', 55.00, 30]
    ];

    // Insert products
    $product_stmt = $db->prepare("INSERT INTO products (category_id, product_name, product_description, product_price, product_quantity) VALUES (?, ?, ?, ?, ?)");
    
    $product_count = 0;
    for ($i = 0; $i < count($products); $i++) {
        $category_index = floor($i / 10); // 10 products per category
        $category_id = $category_ids[$category_index];
        
        $product = $products[$i];
        $product_stmt->execute([
            $category_id,
            $product[0], // name
            $product[1], // description
            $product[2], // price
            $product[3]  // quantity
        ]);
        $product_count++;
    }

    // Commit transaction
    $db->commit();
    
    echo "✓ Added " . $product_count . " products successfully!\n";
    echo "✓ All data has been inserted successfully!\n";
    echo "\nSummary:\n";
    echo "- Categories: " . count($categories) . "\n";
    echo "- Products: " . $product_count . "\n";
    echo "\nYou can now view and manage these items in the admin panel:\n";
    echo "- Categories: admin/categories.php\n";
    echo "- Products: admin/products.php\n";

} catch (Exception $e) {
    // Rollback transaction on error
    $db->rollback();
    echo "Error: " . $e->getMessage() . "\n";
}
?>
