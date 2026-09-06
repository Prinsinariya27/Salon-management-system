<?php
// Simple diagnostic page to check what's wrong
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>";
echo "<html><head><title>Checkout Diagnostic</title>";
echo "<style>body{font-family:Arial;padding:20px;} .success{color:green;} .error{color:red;} .info{color:blue;} .box{background:#f5f5f5;padding:15px;margin:10px 0;border-radius:5px;}</style>";
echo "</head><body>";

echo "<h1>Checkout System Diagnostic</h1>";

// Test 1: Database Connection
echo "<h2>Test 1: Database Connection</h2>";
include('includes/dbconnection.php');

if($con) {
    echo "<p class='success'>✓ Database connected successfully</p>";
} else {
    echo "<p class='error'>✗ Database connection failed!</p>";
    die();
}

// Test 2: Check if tblorders table exists
echo "<h2>Test 2: Check Tables</h2>";
$table_check = mysqli_query($con, "SHOW TABLES LIKE 'tblorders'");
if(mysqli_num_rows($table_check) > 0) {
    echo "<p class='success'>✓ tblorders table exists</p>";
} else {
    echo "<p class='error'>✗ tblorders table does NOT exist!</p>";
    echo "<p class='info'>Run this SQL: CREATE TABLE tblorders (...)</p>";
}

$table_check2 = mysqli_query($con, "SHOW TABLES LIKE 'tblcart'");
if(mysqli_num_rows($table_check2) > 0) {
    echo "<p class='success'>✓ tblcart table exists</p>";
} else {
    echo "<p class='error'>✗ tblcart table does NOT exist!</p>";
}

// Test 3: Check required columns
echo "<h2>Test 3: Check Required Columns in tblorders</h2>";
$columns = ['ID', 'OrderNumber', 'ProductID', 'ProductName', 'ProductPrice', 'Quantity', 'TotalAmount', 'OrderStatus', 'CustomerName', 'CustomerPhone', 'CustomerEmail', 'Address', 'City', 'Pincode', 'PaymentMethod', 'OrderDate'];

$missing_columns = [];
foreach($columns as $col) {
    $result = mysqli_query($con, "SHOW COLUMNS FROM tblorders LIKE '$col'");
    if(mysqli_num_rows($result) > 0) {
        echo "<p class='success'>✓ Column '$col' exists</p>";
    } else {
        echo "<p class='error'>✗ Column '$col' MISSING</p>";
        $missing_columns[] = $col;
    }
}

if(count($missing_columns) > 0) {
    echo "<div class='box'>";
    echo "<h3 class='error'>Missing Columns Found!</h3>";
    echo "<p><strong>Run this immediately:</strong></p>";
    echo "<p><a href='../../update_checkout_db.php' style='background:#aa9144;color:#fff;padding:10px 20px;text-decoration:none;border-radius:5px;'>Update Database Now</a></p>";
    echo "</div>";
}

// Test 4: Check session
echo "<h2>Test 4: Session Check</h2>";
session_start();
if(isset($_SESSION['customer_session'])) {
    echo "<p class='success'>✓ Session exists: " . $_SESSION['customer_session'] . "</p>";
    
    // Check cart items
    $session_id = $_SESSION['customer_session'];
    $cart_check = mysqli_query($con, "SELECT COUNT(*) as count FROM tblcart WHERE SessionID='$session_id'");
    $cart_count = mysqli_fetch_assoc($cart_check);
    echo "<p class='info'>Cart items: " . $cart_count['count'] . "</p>";
} else {
    echo "<p class='error'>✗ No session found! Add items to cart first.</p>";
}

// Test 5: Test Order Creation
echo "<h2>Test 5: Test Order Creation</h2>";
$test_order_number = 'TEST-' . time();
$test_query = "INSERT INTO tblorders (OrderNumber, ProductID, ProductName, ProductPrice, Quantity, TotalAmount, OrderStatus, CustomerName, CustomerPhone, CustomerEmail, Address, City, Pincode, PaymentMethod, OrderDate) 
               VALUES ('$test_order_number', 1, 'Test Product', 100.00, 1, 100.00, 'Pending', 'Test User', '9999999999', 'test@test.com', 'Test Address', 'Test City', '123456', 'cod', NOW())";

if(mysqli_query($con, $test_query)) {
    echo "<p class='success'>✓ Order created successfully!</p>";
    echo "<p class='info'>Test Order Number: $test_order_number</p>";
    echo "<p><a href='bill.php?order=$test_order_number' style='background:#28a745;color:#fff;padding:10px 20px;text-decoration:none;border-radius:5px;'>View Test Bill</a></p>";
    
    // Delete test order
    mysqli_query($con, "DELETE FROM tblorders WHERE OrderNumber='$test_order_number'");
    echo "<p class='info'>Test order cleaned up</p>";
} else {
    echo "<p class='error'>✗ Failed to create test order!</p>";
    echo "<p class='error'>Error: " . mysqli_error($con) . "</p>";
}

// Test 6: Check bill.php exists
echo "<h2>Test 6: File Check</h2>";
if(file_exists('bill.php')) {
    echo "<p class='success'>✓ bill.php exists</p>";
} else {
    echo "<p class='error'>✗ bill.php NOT found!</p>";
}

if(file_exists('checkout.php')) {
    echo "<p class='success'>✓ checkout.php exists</p>";
} else {
    echo "<p class='error'>✗ checkout.php NOT found!</p>";
}

echo "<hr>";
echo "<h2>Summary & Next Steps</h2>";
echo "<div class='box'>";

if(count($missing_columns) == 0) {
    echo "<h3 class='success'>✓ All tests passed!</h3>";
    echo "<p>Your system should be working. Try checkout again.</p>";
    echo "<ol>";
    echo "<li><a href='products.php'>Go to Products</a></li>";
    echo "<li>Add items to cart</li>";
    echo "<li>Go to checkout</li>";
    echo "<li>Fill form and click Place Order</li>";
    echo "<li>Bill page should open</li>";
    echo "</ol>";
} else {
    echo "<h3 class='error'>✗ Issues found!</h3>";
    echo "<p><strong>Action Required:</strong></p>";
    echo "<p>1. <a href='../../update_checkout_db.php'>Click here to update database</a></p>";
    echo "<p>2. After update, refresh this page to verify</p>";
}

echo "</div>";

echo "</body></html>";
?>
