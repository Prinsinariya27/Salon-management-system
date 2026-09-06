<?php
session_start();
include('includes/dbconnection.php');

echo "<h2>Chart Debug Test</h2>";

// Test if tblorders table exists
$check_table = mysqli_query($con, "SHOW TABLES LIKE 'tblorders'");
if(mysqli_num_rows($check_table) > 0) {
    echo "<p style='color: green;'>✅ tblorders table EXISTS</p>";
    
    // Check table structure
    echo "<h3>Table Structure:</h3>";
    $structure = mysqli_query($con, "DESCRIBE tblorders");
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr>";
    while($row = mysqli_fetch_assoc($structure)) {
        echo "<tr><td>{$row['Field']}</td><td>{$row['Type']}</td><td>{$row['Null']}</td><td>{$row['Key']}</td></tr>";
    }
    echo "</table>";
    
    // Check order count
    $total = mysqli_query($con, "SELECT COUNT(*) as count FROM tblorders");
    $total_row = mysqli_fetch_assoc($total);
    echo "<p><strong>Total Orders:</strong> {$total_row['count']}</p>";
    
    // Check if OrderStatus column exists
    $check_status = mysqli_query($con, "SHOW COLUMNS FROM tblorders LIKE 'OrderStatus'");
    if(mysqli_num_rows($check_status) > 0) {
        echo "<p style='color: green;'>✅ OrderStatus column EXISTS</p>";
        
        $pending = mysqli_query($con, "SELECT COUNT(*) as count FROM tblorders WHERE OrderStatus='Pending'");
        $pending_row = mysqli_fetch_assoc($pending);
        echo "<p><strong>Pending Orders:</strong> {$pending_row['count']}</p>";
        
        $confirmed = mysqli_query($con, "SELECT COUNT(*) as count FROM tblorders WHERE OrderStatus='Confirmed'");
        $confirmed_row = mysqli_fetch_assoc($confirmed);
        echo "<p><strong>Confirmed Orders:</strong> {$confirmed_row['count']}</p>";
    } else {
        echo "<p style='color: red;'>❌ OrderStatus column DOES NOT EXIST</p>";
    }
    
    // Check if PaymentMethod column exists
    $check_payment = mysqli_query($con, "SHOW COLUMNS FROM tblorders LIKE 'PaymentMethod'");
    if(mysqli_num_rows($check_payment) > 0) {
        echo "<p style='color: green;'>✅ PaymentMethod column EXISTS</p>";
        
        $cod = mysqli_query($con, "SELECT COUNT(*) as count FROM tblorders WHERE PaymentMethod='cod'");
        $cod_row = mysqli_fetch_assoc($cod);
        echo "<p><strong>COD Orders:</strong> {$cod_row['count']}</p>";
        
        $gpay = mysqli_query($con, "SELECT COUNT(*) as count FROM tblorders WHERE PaymentMethod='gpay'");
        $gpay_row = mysqli_fetch_assoc($gpay);
        echo "<p><strong>GPay Orders:</strong> {$gpay_row['count']}</p>";
    } else {
        echo "<p style='color: red;'>❌ PaymentMethod column DOES NOT EXIST</p>";
    }
    
} else {
    echo "<p style='color: red;'>❌ tblorders table DOES NOT EXIST</p>";
    echo "<p>You need to run the setup script first!</p>";
}

echo "<hr>";
echo "<h3>Chart.js Test</h3>";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Chart Test</title>
    <script src="js/Chart.js"></script>
</head>
<body>
    <h3>Simple Bar Chart Test:</h3>
    <div style="width: 600px; height: 400px;">
        <canvas id="testChart"></canvas>
    </div>
    
    <script>
        var ctx = document.getElementById('testChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Test 1', 'Test 2', 'Test 3'],
                datasets: [{
                    label: 'Test Data',
                    data: [10, 20, 30],
                    backgroundColor: ['red', 'green', 'blue']
                }]
            }
        });
        console.log('Chart loaded successfully!');
    </script>
</body>
</html>
