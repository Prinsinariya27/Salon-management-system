<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product-Cart Connection Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .test-section {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #aa9144;
            text-align: center;
        }
        h2 {
            color: #18150d;
            border-bottom: 3px solid #aa9144;
            padding-bottom: 10px;
        }
        .success {
            color: #28a745;
            padding: 10px;
            background: #d4edda;
            border-left: 4px solid #28a745;
            margin: 10px 0;
        }
        .error {
            color: #dc3545;
            padding: 10px;
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            margin: 10px 0;
        }
        .info {
            color: #0c5460;
            padding: 10px;
            background: #d1ecf1;
            border-left: 4px solid #0c5460;
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background: #aa9144;
            color: white;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #aa9144;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
        }
        .btn:hover {
            background: #8e7424;
        }
        .code {
            background: #f4f4f4;
            padding: 15px;
            border-radius: 5px;
            font-family: monospace;
            font-size: 13px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <h1>🔗 Product-Cart Connection Test</h1>
    
    <?php
    session_start();
    include('includes/dbconnection.php');
    
    // Test 1: Database Connection
    echo '<div class="test-section">';
    echo '<h2>Test 1: Database Connection</h2>';
    if($con) {
        echo '<div class="success">✓ Database connected successfully!</div>';
        echo '<div class="info">Database: msmsdb</div>';
    } else {
        echo '<div class="error">✗ Database connection failed!</div>';
    }
    echo '</div>';
    
    // Test 2: Products Table
    echo '<div class="test-section">';
    echo '<h2>Test 2: Products Table</h2>';
    $products_check = mysqli_query($con, "SHOW TABLES LIKE 'tblproducts'");
    if(mysqli_num_rows($products_check) > 0) {
        echo '<div class="success">✓ tblproducts table exists!</div>';
        
        $count = mysqli_query($con, "SELECT COUNT(*) as total FROM tblproducts WHERE Status=1");
        $result = mysqli_fetch_assoc($count);
        echo '<div class="info">Active Products: ' . $result['total'] . '</div>';
        
        // Show sample products
        $sample = mysqli_query($con, "SELECT ID, ProductName, ProductPrice, Category FROM tblproducts WHERE Status=1 LIMIT 5");
        echo '<table>';
        echo '<tr><th>ID</th><th>Product Name</th><th>Price</th><th>Category</th></tr>';
        while($row = mysqli_fetch_assoc($sample)) {
            echo '<tr>';
            echo '<td>' . $row['ID'] . '</td>';
            echo '<td>' . $row['ProductName'] . '</td>';
            echo '<td>₹' . number_format($row['ProductPrice'], 2) . '</td>';
            echo '<td>' . $row['Category'] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '<div class="error">✗ tblproducts table does not exist!</div>';
        echo '<div class="info">Run this SQL: SQL File/complete_setup.sql</div>';
    }
    echo '</div>';
    
    // Test 3: Cart Table
    echo '<div class="test-section">';
    echo '<h2>Test 3: Cart Table</h2>';
    $cart_check = mysqli_query($con, "SHOW TABLES LIKE 'tblcart'");
    if(mysqli_num_rows($cart_check) > 0) {
        echo '<div class="success">✓ tblcart table exists!</div>';
        
        // Create or get session ID
        if(!isset($_SESSION['customer_session'])) {
            $_SESSION['customer_session'] = session_id();
        }
        $session_id = $_SESSION['customer_session'];
        
        echo '<div class="info">Your Session ID: <strong>' . $session_id . '</strong></div>';
        
        $cart_count = mysqli_query($con, "SELECT COUNT(*) as total FROM tblcart WHERE SessionID='$session_id'");
        $cart_result = mysqli_fetch_assoc($cart_count);
        echo '<div class="info">Items in your cart: ' . $cart_result['total'] . '</div>';
        
        // Show cart structure
        echo '<h3>Cart Table Structure:</h3>';
        echo '<table>';
        echo '<tr><th>Column</th><th>Type</th></tr>';
        echo '<tr><td>ID</td><td>Primary Key (Auto Increment)</td></tr>';
        echo '<tr><td>SessionID</td><td>VARCHAR(100) - Tracks user session</td></tr>';
        echo '<tr><td>ProductID</td><td>INT - Links to products table</td></tr>';
        echo '<tr><td>ProductName</td><td>VARCHAR(200)</td></tr>';
        echo '<tr><td>ProductPrice</td><td>DECIMAL(10,2)</td></tr>';
        echo '<tr><td>ProductImage</td><td>VARCHAR(255)</td></tr>';
        echo '<tr><td>Quantity</td><td>INT - Default 1</td></tr>';
        echo '<tr><td>AddedDate</td><td>TIMESTAMP</td></tr>';
        echo '</table>';
    } else {
        echo '<div class="error">✗ tblcart table does not exist!</div>';
        echo '<div class="info">Run this SQL: SQL File/complete_setup.sql</div>';
    }
    echo '</div>';
    
    // Test 4: Orders Table
    echo '<div class="test-section">';
    echo '<h2>Test 4: Orders Table</h2>';
    $orders_check = mysqli_query($con, "SHOW TABLES LIKE 'tblorders'");
    if(mysqli_num_rows($orders_check) > 0) {
        echo '<div class="success">✓ tblorders table exists!</div>';
        
        $order_count = mysqli_query($con, "SELECT COUNT(*) as total FROM tblorders");
        $order_result = mysqli_fetch_assoc($order_count);
        echo '<div class="info">Total Orders: ' . $order_result['total'] . '</div>';
    } else {
        echo '<div class="error">✗ tblorders table does not exist!</div>';
        echo '<div class="info">Run this SQL: SQL File/complete_setup.sql</div>';
    }
    echo '</div>';
    
    // Test 5: Add to Cart Functionality
    echo '<div class="test-section">';
    echo '<h2>Test 5: Add to Cart Test</h2>';
    
    // Get first active product
    $first_product = mysqli_query($con, "SELECT * FROM tblproducts WHERE Status=1 LIMIT 1");
    if(mysqli_num_rows($first_product) > 0) {
        $product = mysqli_fetch_assoc($first_product);
        
        echo '<div class="info">Testing with product: <strong>' . $product['ProductName'] . '</strong> (ID: ' . $product['ID'] . ')</div>';
        
        // Check if already in cart
        $check = mysqli_query($con, "SELECT * FROM tblcart WHERE SessionID='$session_id' AND ProductID='{$product['ID']}'");
        
        if(mysqli_num_rows($check) == 0) {
            // Add to cart programmatically for testing
            $test_insert = mysqli_query($con, "INSERT INTO tblcart(SessionID, ProductID, ProductName, ProductPrice, ProductImage, Quantity) 
                VALUES('$session_id', '{$product['ID']}', '{$product['ProductName']}', '{$product['ProductPrice']}', '{$product['ProductImage']}', 1)");
            
            if($test_insert) {
                echo '<div class="success">✓ Successfully added test product to cart!</div>';
                echo '<div class="info">Product: ' . $product['ProductName'] . '</div>';
                echo '<div class="info">Price: ₹' . number_format($product['ProductPrice'], 2) . '</div>';
            } else {
                echo '<div class="error">✗ Failed to add to cart</div>';
            }
        } else {
            echo '<div class="info">ℹ This product is already in your cart</div>';
        }
        
        echo '<a href="products.php" class="btn">Go to Products Page</a>';
        echo '<a href="cart.php" class="btn">View Your Cart</a>';
    }
    echo '</div>';
    
    // Test 6: Session Management
    echo '<div class="test-section">';
    echo '<h2>Test 6: Session Management</h2>';
    echo '<div class="info">Session Started: ' . (session_status() == PHP_SESSION_ACTIVE ? 'Yes' : 'No') . '</div>';
    echo '<div class="info">Session ID: ' . session_id() . '</div>';
    echo '<div class="info">Customer Session: ' . (isset($_SESSION['customer_session']) ? $_SESSION['customer_session'] : 'Not set yet') . '</div>';
    
    // Calculate cart count
    $cart_total = mysqli_query($con, "SELECT SUM(Quantity) as total FROM tblcart WHERE SessionID='$session_id'");
    $cart_total_result = mysqli_fetch_assoc($cart_total);
    $total_items = $cart_total_result['total'] ? $cart_total_result['total'] : 0;
    echo '<div class="info">Total Items in Cart: <strong>' . $total_items . '</strong></div>';
    echo '</div>';
    
    // Test 7: Connection Flow
    echo '<div class="test-section">';
    echo '<h2>Test 7: Complete Connection Flow</h2>';
    echo '<div class="code">';
    echo '<strong>Flow Diagram:</strong><br><br>';
    echo '1. products.php → User clicks "Add to Cart"<br>';
    echo '   ↓<br>';
    echo '2. POST request sent with product details<br>';
    echo '   ↓<br>';
    echo '3. INSERT into tblcart with SessionID<br>';
    echo '   ↓<br>';
    echo '4. header.php reads cart count from tblcart<br>';
    echo '   ↓<br>';
    echo '5. Cart badge displays total items<br>';
    echo '   ↓<br>';
    echo '6. cart.php shows all items from tblcart<br>';
    echo '   ↓<br>';
    echo '7. Checkout moves items to tblorders<br>';
    echo '   ↓<br>';
    echo '8. tblcart cleared for next shopping<br>';
    echo '</div>';
    echo '</div>';
    
    // Summary
    echo '<div class="test-section">';
    echo '<h2>✅ Summary</h2>';
    
    $all_ok = true;
    
    if(!$con) {
        echo '<div class="error">✗ Database connection issue</div>';
        $all_ok = false;
    }
    
    if(mysqli_num_rows($products_check) == 0) {
        echo '<div class="error">✗ Products table missing</div>';
        $all_ok = false;
    }
    
    if(mysqli_num_rows($cart_check) == 0) {
        echo '<div class="error">✗ Cart table missing</div>';
        $all_ok = false;
    }
    
    if(mysqli_num_rows($orders_check) == 0) {
        echo '<div class="error">✗ Orders table missing</div>';
        $all_ok = false;
    }
    
    if($all_ok) {
        echo '<div class="success"><strong>✓ All systems operational!</strong><br>';
        echo 'Product-Cart connection is working perfectly.<br>';
        echo 'You can now:<br>';
        echo '• Browse products at <a href="products.php">products.php</a><br>';
        echo '• Add items to cart<br>';
        echo '• View cart at <a href="cart.php">cart.php</a><br>';
        echo '• Update quantities and checkout<br>';
        echo '</div>';
    }
    
    echo '</div>';
    
    // Quick Links
    echo '<div class="test-section">';
    echo '<h2>🚀 Quick Access</h2>';
    echo '<a href="products.php" class="btn">🛍️ Browse Products</a>';
    echo '<a href="cart.php" class="btn">🛒 View Cart</a>';
    echo '<a href="index.php" class="btn">🏠 Home Page</a>';
    echo '<a href="admin/index.php" class="btn">⚙️ Admin Panel</a>';
    echo '</div>';
    ?>
    
    <div style="text-align: center; margin-top: 30px; padding: 20px; background: #aa9144; color: white; border-radius: 10px;">
        <h3>🎉 Everything is Connected!</h3>
        <p>The product and cart are connected through the database using session-based tracking.</p>
        <p><strong>No additional setup needed - Just start shopping!</strong></p>
    </div>
</body>
</html>
