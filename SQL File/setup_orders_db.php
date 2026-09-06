<?php
// Database connection
$con = mysqli_connect("localhost", "root", "", "msmsdb");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "<h2>Creating Orders & Cart Tables...</h2>";

// Create Orders Table
$createOrders = "CREATE TABLE IF NOT EXISTS `tblorders` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `OrderNumber` varchar(50) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `ProductName` varchar(200) NOT NULL,
  `ProductPrice` decimal(10,2) NOT NULL,
  `ProductImage` varchar(255) DEFAULT NULL,
  `Quantity` int(11) NOT NULL DEFAULT '1',
  `TotalAmount` decimal(10,2) NOT NULL,
  `CustomerName` varchar(200) DEFAULT NULL,
  `CustomerEmail` varchar(200) DEFAULT NULL,
  `CustomerPhone` varchar(20) DEFAULT NULL,
  `OrderStatus` varchar(50) DEFAULT 'Pending',
  `OrderDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1";

if (mysqli_query($con, $createOrders)) {
    echo "<p style='color: green;'>✓ Orders table created successfully!</p>";
} else {
    echo "<p style='color: red;'>✗ Error creating orders table: " . mysqli_error($con) . "</p>";
}

// Create Cart Table
$createCart = "CREATE TABLE IF NOT EXISTS `tblcart` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `SessionID` varchar(100) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `ProductName` varchar(200) NOT NULL,
  `ProductPrice` decimal(10,2) NOT NULL,
  `ProductImage` varchar(255) DEFAULT NULL,
  `Quantity` int(11) NOT NULL DEFAULT '1',
  `AddedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1";

if (mysqli_query($con, $createCart)) {
    echo "<p style='color: green;'>✓ Cart table created successfully!</p>";
} else {
    echo "<p style='color: red;'>✗ Error creating cart table: " . mysqli_error($con) . "</p>";
}

echo "<hr>";
echo "<h3>✓ Database setup complete!</h3>";
echo "<h3>Now customers can add products to cart and it will be saved to database.</h3>";
echo "<hr>";
echo "<h3>Next Steps:</h3>";
echo "<ol>";
echo "<li><strong>Products Page:</strong> <a href='../msms/products.php' target='_blank'>Click Here</a></li>";
echo "<li><strong>Cart Page:</strong> <a href='../msms/cart.php' target='_blank'>Click Here</a></li>";
echo "<li><strong>Admin Orders:</strong> <a href='../msms/admin/manage-orders.php' target='_blank'>Click Here</a></li>";
echo "</ol>";
echo "<p><strong>Note:</strong> You can delete this file after setting up the tables.</p>";

mysqli_close($con);
?>
