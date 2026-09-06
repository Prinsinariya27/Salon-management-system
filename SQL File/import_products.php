<?php
// Database connection
$con = mysqli_connect("localhost", "root", "", "msmsdb");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "<h2>Importing 15 Products to Database...</h2>";

// Clear existing products first
mysqli_query($con, "DELETE FROM tblproducts");
echo "<p style='color: blue;'>✓ Cleared existing products...</p>";

// Create table
$createTable = "CREATE TABLE IF NOT EXISTS `tblproducts` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductName` varchar(200) NOT NULL,
  `ProductDescription` text NOT NULL,
  `ProductPrice` decimal(10,2) NOT NULL,
  `ProductImage` varchar(255) DEFAULT NULL,
  `Category` varchar(100) DEFAULT NULL,
  `Stock` int(11) DEFAULT '0',
  `Status` int(11) DEFAULT '1',
  `CreationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1";

if (mysqli_query($con, $createTable)) {
    echo "<p style='color: green;'>✓ Table created successfully!</p>";
} else {
    echo "<p style='color: red;'>✗ Error creating table: " . mysqli_error($con) . "</p>";
}

// Insert 15 products only
$products = [
    ['Premium Hair Wax', 'Strong hold hair wax for perfect styling. Provides long-lasting hold with natural shine.', 450.00, 'fach wash.jpg', 'Hair Care', 50],
    ['Beard Oil', 'Nourishing beard oil with essential oils. Keeps your beard soft, shiny, and healthy.', 550.00, 'fach wash.jpg', 'Beard Care', 45],
    ['Hair Styling Gel', 'Professional strength gel for all-day hold. Perfect for any hairstyle.', 380.00, 'fach wash.jpg', 'Hair Care', 60],
    ['Shaving Cream', 'Rich lathering shaving cream for smooth shave. Moisturizes and protects skin.', 320.00, 'fach wash.jpg', 'Shaving', 70],
    ['Hair Spray', 'Flexible hold hairspray that keeps your style in place without stiffness.', 420.00, 'related-post-1.jpg', 'Hair Care', 55],
    ['Beard Balm', 'Conditioning beard balm with shea butter. Tames and shapes your beard.', 580.00, 'service-single.jpg', 'Beard Care', 40],
    ['Hair Pomade', 'Classic pomade for slick, shiny hairstyles. Medium hold with high shine.', 490.00, 'post-img-1.jpg', 'Hair Care', 48],
    ['Face Scrub', 'Exfoliating face scrub for men. Removes dead skin and refreshes face.', 350.00, 'post-img-2.jpg', 'Skin Care', 65],
    ['Moisturizing Lotion', 'Lightweight daily moisturizer for men. Hydrates without greasiness.', 480.00, 'left-image.jpg', 'Skin Care', 52],
    ['Matte Clay', 'Ultra-strong hold matte clay for textured styles. Natural finish without shine.', 520.00, 'about-img.jpg', 'Hair Care', 42],
    ['Beard Wash', 'Gentle daily cleanser for beards. Removes dirt while keeping natural oils.', 420.00, 'related-post-1.jpg', 'Beard Care', 55],
    ['Aftershave Balm', 'Soothing aftershave balm with aloe vera. Reduces irritation and redness.', 380.00, 'service-single.jpg', 'Shaving', 60],
    ['Hair Serum', 'Nourishing hair serum with argan oil. Adds shine and reduces frizz.', 590.00, 'post-img-1.jpg', 'Hair Care', 38],
    ['Beard Comb', 'Handcrafted wooden beard comb. Anti-static and gentle on beard hair.', 250.00, 'post-img-2.jpg', 'Beard Care', 80],
    ['Face Wash', 'Deep cleansing face wash for men. Controls oil and prevents acne.', 320.00, 'fach wash.jpg', 'Skin Care', 75]
];

$inserted = 0;
$failed = 0;

foreach ($products as $product) {
    $query = "INSERT INTO tblproducts (ProductName, ProductDescription, ProductPrice, ProductImage, Category, Stock, Status) 
              VALUES ('{$product[0]}', '{$product[1]}', {$product[2]}, '{$product[3]}', '{$product[4]}', {$product[5]}, 1)";
    
    if (mysqli_query($con, $query)) {
        $inserted++;
    } else {
        $failed++;
        echo "<p style='color: red;'>Error inserting {$product[0]}: " . mysqli_error($con) . "</p>";
    }
}

echo "<hr>";
echo "<h3 style='color: green;'>✓ Successfully imported <strong>$inserted</strong> products!</h3>";
if ($failed > 0) {
    echo "<h3 style='color: red;'>✗ Failed to import <strong>$failed</strong> products</h3>";
}

echo "<hr>";
echo "<h3>Next Steps:</h3>";
echo "<ol>";
echo "<li>Visit Products Page: <a href='../msms/products.php' target='_blank'>Click Here</a></li>";
echo "<li>View Cart: <a href='../msms/cart.php' target='_blank'>Click Here</a></li>";
echo "<li>Manage Products (Admin): <a href='../msms/admin/manage-products.php' target='_blank'>Click Here</a></li>";
echo "</ol>";

echo "<p><strong>Note:</strong> You can delete this file after importing products.</p>";

mysqli_close($con);
?>
