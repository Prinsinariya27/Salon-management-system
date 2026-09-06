<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Products - Quick Setup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #aa9144;
            border-bottom: 3px solid #aa9144;
            padding-bottom: 10px;
        }
        .success {
            color: #28a745;
            background: #d4edda;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .error {
            color: #dc3545;
            background: #f8d7da;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .warning {
            color: #856404;
            background: #fff3cd;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #aa9144;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background: #8e7424;
        }
        .product-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        .product-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #aa9144;
        }
        .summary {
            background: #d4edda;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            font-size: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🛍️ Quick Product Setup</h1>
        <p>Adding sample products to your Men's Salon Management System...</p>
        <hr>

        <?php
        include('Men-Salon-Management-System-Project-PHP/msms/admin/includes/dbconnection.php');

        echo "<p>📊 Checking database connection...</p>";
        
        // Check if tblproducts table exists
        $check_table = mysqli_query($con, "SHOW TABLES LIKE 'tblproducts'");
        if(mysqli_num_rows($check_table) == 0) {
            echo "<p>📋 Creating tblproducts table...</p>";
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
                echo "<div class='success'>✅ Table created successfully!</div>";
            } else {
                echo "<div class='error'>❌ Error creating table: " . mysqli_error($con) . "</div>";
                exit();
            }
        } else {
            echo "<div class='success'>✅ Database table found!</div>";
        }

        // Check if products already exist
        $check_products = mysqli_query($con, "SELECT COUNT(*) as count FROM tblproducts");
        $row = mysqli_fetch_assoc($check_products);

        if($row['count'] > 0) {
            echo "<div class='warning'>⚠️ Database already has <strong>{$row['count']}</strong> products!</div>";
            echo "<p>Do you want to add more sample products?</p>";
            echo "<form method='post'>";
            echo "<button type='submit' name='force_add' class='btn'>Yes, Add More Products</button>";
            echo "<a href='msms/products.php' class='btn'>View Products Page</a>";
            echo "</form>";
            
            if(isset($_POST['force_add'])) {
                // Continue to add products
            } else {
                exit();
            }
        }

        // Sample products array
        $products = array(
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
            array('Beard Comb', 'Handcrafted wooden beard comb. Anti-static and gentle on beard hair.', 250.00, 'Beard Comb.jpg', 'Beard Care', 80, 1)
            array('Beard Growth Oil', 'Stimulating beard growth oil with biotin. Promotes fuller beard growth.', 680.00, 'premium Beard Oil.jpg', 'Beard Care', 38, 1),
            array('Hair Tonic', 'Strengthening hair tonic for healthy growth. Reduces hair fall.', 580.00, 'premium Hair Serum.jpg', 'Hair Care', 40, 1),
            array('Beard Conditioner', 'Softening conditioner for beard hair. Makes beard more manageable.', 520.00, 'premium Beard Balm.jpg', 'Beard Care', 45, 1),
            array('Face Wash', 'Deep cleansing face wash for men. Controls oil and prevents acne.', 320.00, 'Face Scrub.jpg', 'Skin Care', 75, 1),
        );

        echo "<h2>📦 Adding Products...</h2>";
        echo "<div class='product-list'>";
        
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
                echo "<div class='product-item'>";
                echo "<strong>✅ $productname</strong><br>";
                echo "Price: ₹" . number_format($price, 2) . "<br>";
                echo "Category: $category";
                echo "</div>";
            } else {
                echo "<div class='product-item' style='border-left-color: #dc3545;'>";
                echo "<strong>❌ Error adding $productname</strong><br>";
                echo mysqli_error($con);
                echo "</div>";
            }
        }
        
        echo "</div>";
        
        echo "<div class='summary'>";
        echo "🎉 Successfully added <strong>$added</strong> products!";
        echo "</div>";
        
        echo "<hr>";
        echo "<h3>What's Next?</h3>";
        echo "<a href='msms/products.php' class='btn'>🛍️ View Products (Client Side)</a>";
        echo "<a href='msms/admin/manage-products.php' class='btn'>⚙️ Manage Products (Admin)</a>";
        echo "<a href='msms/cart.php' class='btn'>🛒 View Cart</a>";
        ?>
    </div>
</body>
</html>
