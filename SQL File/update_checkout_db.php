<?php
// Database Update Script for Checkout Feature
// Run this file once to add new columns to tblorders table

include('Men-Salon-Management-System-Project-PHP/msms/includes/dbconnection.php');

echo "<h2>Updating Database for Checkout Feature...</h2>";

// Check and add Address column
$check_address = mysqli_query($con, "SHOW COLUMNS FROM tblorders LIKE 'Address'");
if(mysqli_num_rows($check_address) == 0) {
    mysqli_query($con, "ALTER TABLE tblorders ADD COLUMN Address TEXT DEFAULT NULL AFTER CustomerPhone");
    echo "<p style='color: green;'>✓ Added 'Address' column</p>";
} else {
    echo "<p style='color: blue;'>✓ 'Address' column already exists</p>";
}

// Check and add City column
$check_city = mysqli_query($con, "SHOW COLUMNS FROM tblorders LIKE 'City'");
if(mysqli_num_rows($check_city) == 0) {
    mysqli_query($con, "ALTER TABLE tblorders ADD COLUMN City varchar(100) DEFAULT NULL AFTER Address");
    echo "<p style='color: green;'>✓ Added 'City' column</p>";
} else {
    echo "<p style='color: blue;'>✓ 'City' column already exists</p>";
}

// Check and add Pincode column
$check_pincode = mysqli_query($con, "SHOW COLUMNS FROM tblorders LIKE 'Pincode'");
if(mysqli_num_rows($check_pincode) == 0) {
    mysqli_query($con, "ALTER TABLE tblorders ADD COLUMN Pincode varchar(10) DEFAULT NULL AFTER City");
    echo "<p style='color: green;'>✓ Added 'Pincode' column</p>";
} else {
    echo "<p style='color: blue;'>✓ 'Pincode' column already exists</p>";
}

// Check and add PaymentMethod column
$check_payment = mysqli_query($con, "SHOW COLUMNS FROM tblorders LIKE 'PaymentMethod'");
if(mysqli_num_rows($check_payment) == 0) {
    mysqli_query($con, "ALTER TABLE tblorders ADD COLUMN PaymentMethod varchar(50) DEFAULT NULL AFTER Pincode");
    echo "<p style='color: green;'>✓ Added 'PaymentMethod' column</p>";
} else {
    echo "<p style='color: blue;'>✓ 'PaymentMethod' column already exists</p>";
}

echo "<hr>";
echo "<h3>Updated Table Structure:</h3>";

// Display updated table structure
$result = mysqli_query($con, "DESCRIBE tblorders");
echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
while($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $row['Field'] . "</td>";
    echo "<td>" . $row['Type'] . "</td>";
    echo "<td>" . $row['Null'] . "</td>";
    echo "<td>" . $row['Key'] . "</td>";
    echo "<td>" . $row['Default'] . "</td>";
    echo "</tr>";
}
echo "</table>";

echo "<hr>";
echo "<h3 style='color: green;'>✓ Database updated successfully!</h3>";
echo "<p><a href='Men-Salon-Management-System-Project-PHP/msms/products.php'>Go to Products</a></p>";
echo "<p style='color: red;'><strong>Note:</strong> Delete this file after running it once for security.</p>";
?>
