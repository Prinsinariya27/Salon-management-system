<?php
include('msms/admin/includes/dbconnection.php');

echo "<h2>Products Database Check</h2>";

// Check if tblproducts table exists
$check_table = mysqli_query($con, "SHOW TABLES LIKE 'tblproducts'");
if(mysqli_num_rows($check_table) > 0) {
    echo "<p style='color: green; font-size: 18px;'>✅ tblproducts table EXISTS</p>";
    
    // Check table structure
    echo "<h3>Table Structure:</h3>";
    $structure = mysqli_query($con, "DESCRIBE tblproducts");
    echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr>";
    while($row = mysqli_fetch_assoc($structure)) {
        echo "<tr><td>{$row['Field']}</td><td>{$row['Type']}</td><td>{$row['Null']}</td><td>{$row['Key']}</td></tr>";
    }
    echo "</table><br>";
    
    // Check product count
    $total = mysqli_query($con, "SELECT COUNT(*) as count FROM tblproducts");
    $total_row = mysqli_fetch_assoc($total);
    echo "<p><strong>Total Products:</strong> {$total_row['count']}</p>";
    
    if($total_row['count'] > 0) {
        echo "<h3>All Products:</h3>";
        $products = mysqli_query($con, "SELECT * FROM tblproducts ORDER BY ID DESC");
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th></tr>";
        while($row = mysqli_fetch_assoc($products)) {
            $status = $row['Status'] == 1 ? 'Active' : 'Inactive';
            echo "<tr>";
            echo "<td>{$row['ID']}</td>";
            echo "<td>{$row['ProductName']}</td>";
            echo "<td>{$row['Category']}</td>";
            echo "<td>₹" . number_format($row['ProductPrice'], 2) . "</td>";
            echo "<td>{$row['Stock']}</td>";
            echo "<td>{$status}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: orange; font-size: 16px;'>⚠️ Table exists but NO PRODUCTS found!</p>";
        echo "<p>You need to add products using the Add Products page.</p>";
    }
    
} else {
    echo "<p style='color: red; font-size: 18px;'>❌ tblproducts table DOES NOT EXIST</p>";
    echo "<p style='font-size: 16px;'>You need to run the SQL setup script first!</p>";
    echo "<h3>Steps to fix:</h3>";
    echo "<ol>";
    echo "<li>Open phpMyAdmin (http://localhost/phpmyadmin)</li>";
    echo "<li>Select database: <strong>msmsdb</strong></li>";
    echo "<li>Go to SQL tab</li>";
    echo "<li>Copy and paste the SQL from: <code>SQL File/products_table.sql</code></li>";
    echo "<li>Click Go</li>";
    echo "</ol>";
}
?>
