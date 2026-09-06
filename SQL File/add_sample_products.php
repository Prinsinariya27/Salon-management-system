<?php
include('msms/admin/includes/dbconnection.php');

echo "<h2>Adding Products to Database</h2>";
echo "<p>Connecting to database...</p>";

// Check if tblproducts table exists
$check_table = mysqli_query($con, "SHOW TABLES LIKE 'tblproducts'");
if(mysqli_num_rows($check_table) == 0) {
    echo "<p>Creating tblproducts table...</p>";
    $create_table = "CREATE TABLE IF NOT EXISTS `tblproducts` (
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
    
    if(mysqli_query($con, $create_table)) {
        echo "<p style='color: green;'>✅ Table created successfully!</p>";
    } else {
        echo "<p style='color: red;'>❌ Error creating table: " . mysqli_error($con) . "</p>";
        exit();
    }
} else {
    echo "<p style='color: green;'>✅ Table already exists!</p>";
}

// Check if products already exist
$check_products = mysqli_query($con, "SELECT COUNT(*) as count FROM tblproducts");
$row = mysqli_fetch_assoc($check_products);

if($row['count'] > 0) {
    echo "<p style='color: orange;'>⚠️ Database already has {$row['count']} products!</p>";
    echo "<p><a href='check_products_db.php'>View Existing Products</a></p>";
    exit();
}

// Add sample products
echo "<p>Adding sample products...</p>";

$products = array(
    array('Premium Hair Wax', 'Strong hold hair wax for perfect styling. Provides long-lasting hold with natural shine.', 450.00, 'Hair Wax.jpg', 'Hair Care', 50, 1),
    array('Beard Oil', 'Nourishing beard oil with essential oils. Keeps your beard soft, shiny, and healthy.', 550.00, 'Beard Oil.jpg', 'Beard Care', 45, 1),
    array('Hair Styling Gel', 'Professional strength gel for all-day hold. Perfect for any hairstyle.', 380.00, 'Hair Styling Gel.jpg', 'Hair Care', 60, 1),
    array('Shaving Cream', 'Rich lathering shaving cream for smooth shave. Moisturizes and protects skin.', 320.00, 'Shaving Cream.jpg', 'Shaving', 70, 1),
    array('Hair Spray', 'Flexible hold hairspray that keeps your style in place without stiffness.', 420.00, 'Hair Spray.jpg', 'Hair Care', 55, 1),
    array('Beard Balm', 'Conditioning beard balm with shea butter. Tames and shapes your beard.', 580.00, 'Beard Balm.jpg', 'Beard Care', 40, 1),
    array('Hair Pomade', 'Classic pomade for slick, shiny hairstyles. Medium hold with high shine.', 490.00, 'Hair Pomade.jpg', 'Hair Care', 48, 1),
    array('Face Scrub', 'Exfoliating face scrub for men. Removes dead skin and refreshes face.', 350.00, 'Face Scrub.jpg', 'Skin Care', 65, 1),
    array('Moisturizing Lotion', 'Lightweight daily moisturizer for men. Hydrates without greasiness.', 480.00, 'Moisturizing Lotion.jpg', 'Skin Care', 52, 1),
    array('Matte Clay', 'Ultra-strong hold matte clay for textured styles. Natural finish without shine.', 520.00, 'Matte Clay.jpg', 'Hair Care', 42, 1),
    array('Beard Wash', 'Gentle daily cleanser for beards. Removes dirt while keeping natural oils.', 420.00, 'Beard Wash.jpg', 'Beard Care', 55, 1),
    array('Aftershave Balm', 'Soothing aftershave balm with aloe vera. Reduces irritation and redness.', 380.00, 'Aftershave Balm.jpg', 'Shaving', 60, 1),
    array('Hair Serum', 'Nourishing hair serum with argan oil. Adds shine and reduces frizz.', 590.00, 'Hair Serum.jpg', 'Hair Care', 38, 1),
    array('Beard Comb', 'Handcrafted wooden beard comb. Anti-static and gentle on beard hair.', 250.00, 'Beard Comb.jpg', 'Beard Care', 80, 1),
    array('Premium Hair Wax Black', 'Extra strong hold hair wax with natural finish.', 480.00, 'Hair Wax.jpg', 'Hair Care', 45, 1),
    array('Beard Growth Oil', 'Stimulating beard growth oil with biotin. Promotes fuller beard growth.', 680.00, 'Beard Oil.jpg', 'Beard Care', 38, 1),
    array('Hair Tonic', 'Strengthening hair tonic for healthy growth. Reduces hair fall.', 580.00, 'Hair Serum.jpg', 'Hair Care', 40, 1),
    array('Beard Conditioner', 'Softening conditioner for beard hair. Makes beard more manageable.', 520.00, 'Beard Balm.jpg', 'Beard Care', 45, 1),
    array('Face Wash', 'Deep cleansing face wash for men. Controls oil and prevents acne.', 320.00, 'Face Scrub.jpg', 'Skin Care', 75, 1),
);

$added = 0;
foreach($products as $product) {
    $productname = $product[0];
    $description = $product[1];
    $price = $product[2];
    $image = $product[3];
    $category = $product[4];
    $stock = $product[5];
    $status = $product[6];
    
    $query = "INSERT INTO tblproducts(ProductName, ProductDescription, ProductPrice, ProductImage, Category, Stock, Status) 
              VALUES('$productname', '$description', '$price', '$image', '$category', '$stock', '$status')";
    
    if(mysqli_query($con, $query)) {
        $added++;
        echo "<p style='color: green;'>✅ Added: $productname</p>";
    } else {
        echo "<p style='color: red;'>❌ Error adding $productname: " . mysqli_error($con) . "</p>";
    }
}

echo "<hr>";
echo "<h3>Summary:</h3>";
echo "<p style='color: green; font-size: 20px;'>✅ Successfully added <strong>$added</strong> products!</p>";
echo "<p><a href='msms/admin/manage-products.php'>View Products in Admin Panel</a></p>";
echo "<p><a href='check_products_db.php'>Check Database Status</a></p>";
?>
