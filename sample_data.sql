-- Sample data for Stationery Shop

-- Insert sample categories
INSERT INTO `categories` (`category_name`, `category_description`) VALUES
('Pens & Pencils', 'Writing instruments including ballpoint pens, gel pens, pencils, and markers'),
('Notebooks & Journals', 'Various types of notebooks, journals, diaries, and writing pads'),
('Office Supplies', 'Essential office items like staplers, paper clips, folders, and organizers'),
('Art Supplies', 'Creative materials for artists including paints, brushes, canvases, and drawing tools'),
('School Supplies', 'Educational materials and supplies for students of all ages'),
('Paper Products', 'Different types of paper, cardstock, and printing materials');

-- Insert sample products
INSERT INTO `products` (`category_id`, `product_name`, `product_description`, `product_price`, `product_quantity`, `product_image`) VALUES
(1, 'Premium Ballpoint Pen Set', 'Set of 5 high-quality ballpoint pens with smooth ink flow. Perfect for professional use.', 299.00, 50, ''),
(1, 'Gel Pen Collection', 'Colorful gel pens set with 12 different colors. Great for note-taking and creative writing.', 199.00, 75, ''),
(1, 'Mechanical Pencil', 'Professional mechanical pencil with 0.5mm lead. Includes eraser and extra leads.', 149.00, 40, ''),
(1, 'Highlighter Set', 'Pack of 6 fluorescent highlighters in different colors for marking important text.', 129.00, 60, ''),

(2, 'Spiral Notebook A4', 'High-quality spiral-bound notebook with 200 pages. Perfect for students and professionals.', 179.00, 100, ''),
(2, 'Leather Journal', 'Premium leather-bound journal with lined pages. Ideal for personal writing and notes.', 599.00, 25, ''),
(2, 'Sticky Notes Pack', 'Assorted sticky notes in multiple sizes and colors for quick reminders.', 89.00, 80, ''),
(2, 'Hardcover Notebook', 'Durable hardcover notebook with dotted pages. Great for bullet journaling.', 249.00, 45, ''),

(3, 'Stapler Heavy Duty', 'Professional heavy-duty stapler that can handle up to 50 sheets at once.', 399.00, 30, ''),
(3, 'Paper Clips Box', 'Box of 1000 standard paper clips in silver finish. Essential office supply.', 49.00, 120, ''),
(3, 'File Folders Set', 'Set of 25 manila file folders for document organization and storage.', 199.00, 55, ''),
(3, 'Desk Organizer', 'Multi-compartment desk organizer to keep your workspace tidy and efficient.', 449.00, 20, ''),

(4, 'Acrylic Paint Set', 'Professional acrylic paint set with 24 colors and brushes included.', 899.00, 15, ''),
(4, 'Sketch Pad A3', 'High-quality sketch pad with 50 sheets of drawing paper. Perfect for artists.', 299.00, 35, ''),
(4, 'Colored Pencils', 'Set of 36 colored pencils with vibrant colors for drawing and coloring.', 349.00, 40, ''),
(4, 'Paint Brushes Set', 'Professional paint brush set with various sizes for different painting techniques.', 199.00, 25, ''),

(5, 'School Supply Kit', 'Complete school supply kit including pens, pencils, erasers, and rulers.', 399.00, 60, ''),
(5, 'Geometry Set', 'Mathematical instruments set including compass, protractor, and rulers.', 159.00, 45, ''),
(5, 'Backpack Organizer', 'Multi-pocket organizer insert for school backpacks to keep supplies sorted.', 299.00, 30, ''),

(6, 'Copy Paper A4', 'High-quality white copy paper, 500 sheets per pack. Perfect for printing and copying.', 199.00, 200, ''),
(6, 'Cardstock Pack', 'Colored cardstock pack with 50 sheets in assorted colors for crafts and projects.', 149.00, 70, ''),
(6, 'Photo Paper', 'Glossy photo paper for high-quality photo printing. Pack of 100 sheets.', 299.00, 40, '');

-- Update admin password (already exists, just ensuring it's set)
-- Password is 'admin123' hashed
UPDATE `users` SET `password` = '$2y$10$QhRfdprTkvL9wIzY5MSf2OEGA4D8VlyDShuM8rgoakZj17uB2xFUG' WHERE `email` = 'admin@stationery.gmail';
