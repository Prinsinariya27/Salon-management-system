<?php
// Test Bill Page - Check if everything is working
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('includes/dbconnection.php');

echo "<h2>Bill Page Test</h2>";

// Check if tblorders table has required columns
echo "<h3>Checking Database Columns...</h3>";

$required_columns = ['Address', 'City', 'Pincode', 'PaymentMethod', 'CustomerName', 'CustomerPhone', 'CustomerEmail', 'OrderNumber', 'ProductName', 'ProductPrice', 'Quantity', 'TotalAmount', 'OrderStatus', 'OrderDate'];

foreach($required_columns as $column) {
    $result = mysqli_query($con, "SHOW COLUMNS FROM tblorders LIKE '$column'");
    if(mysqli_num_rows($result) > 0) {
        echo "<p style='color: green;'>✓ Column '$column' exists</p>";
    } else {
        echo "<p style='color: red;'>✗ Column '$column' MISSING - Run update_checkout_db.php first!</p>";
    }
}

// Check if there are any orders
echo "<hr><h3>Checking Orders...</h3>";
$order_count = mysqli_query($con, "SELECT COUNT(*) as count FROM tblorders");
$count = mysqli_fetch_assoc($order_count);
echo "<p>Total Orders: " . $count['count'] . "</p>";

if($count['count'] > 0) {
    echo "<h3>Recent Orders:</h3>";
    $orders = mysqli_query($con, "SELECT OrderNumber, CustomerName, TotalAmount, OrderDate FROM tblorders ORDER BY ID DESC LIMIT 5");
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>Order Number</th><th>Customer</th><th>Amount</th><th>Date</th><th>Action</th></tr>";
    while($order = mysqli_fetch_assoc($orders)) {
        echo "<tr>";
        echo "<td>" . $order['OrderNumber'] . "</td>";
        echo "<td>" . ($order['CustomerName'] ?? 'N/A') . "</td>";
        echo "<td>₹" . number_format($order['TotalAmount'], 2) . "</td>";
        echo "<td>" . $order['OrderDate'] . "</td>";
        echo "<td><a href='bill.php?order=" . $order['OrderNumber'] . "'>View Bill</a></td>";
        echo "</tr>";
    }
    echo "</table>";
}

echo "<hr>";
echo "<h3>Test Bill Page:</h3>";
echo "<p><a href='bill.php?order=TEST-123' style='background: #aa9144; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Test Bill Page with Sample Order</a></p>";

echo "<hr>";
echo "<p><strong>Next Steps:</strong></p>";
echo "<ol>";
echo "<li>If any columns are missing, run: <a href='../../update_checkout_db.php'>Update Database</a></li>";
echo "<li>Add products to cart and complete checkout</li>";
echo "<li>Bill page should open automatically</li>";
echo "</ol>";
?>
