<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/dbconnection.php');

echo "<!DOCTYPE html>";
echo "<html><head><title>Check Appointments</title>";
echo "<style>body{font-family:Arial;padding:20px;} table{border-collapse:collapse;width:100%;margin:20px 0;} th,td{border:1px solid #ddd;padding:10px;text-align:left;} th{background:#aa9144;color:#fff;} .success{color:green;} .error{color:red;}</style>";
echo "</head><body>";

echo "<h1>Appointment Database Check</h1>";

// Check if table exists
$table_check = mysqli_query($con, "SHOW TABLES LIKE 'tblappointment'");
if(mysqli_num_rows($table_check) > 0) {
    echo "<p class='success'>✓ tblappointment table exists</p>";
} else {
    echo "<p class='error'>✗ tblappointment table does NOT exist!</p>";
    die();
}

// Check table structure
echo "<h2>Table Structure:</h2>";
$structure = mysqli_query($con, "DESCRIBE tblappointment");
echo "<table><tr><th>Column</th><th>Type</th><th>Null</th><th>Default</th></tr>";
while($col = mysqli_fetch_assoc($structure)) {
    echo "<tr><td>{$col['Field']}</td><td>{$col['Type']}</td><td>{$col['Null']}</td><td>{$col['Default']}</td></tr>";
}
echo "</table>";

// Count total appointments
$count_query = mysqli_query($con, "SELECT COUNT(*) as total FROM tblappointment");
$count = mysqli_fetch_assoc($count_query);
echo "<h2>Total Appointments: {$count['total']}</h2>";

if($count['total'] > 0) {
    // Show all appointments
    echo "<h2>All Appointments in Database:</h2>";
    $all_appointments = mysqli_query($con, "SELECT * FROM tblappointment ORDER BY ID DESC LIMIT 20");
    
    echo "<table>";
    echo "<tr><th>ID</th><th>AptNumber</th><th>Name</th><th>Phone</th><th>Service</th><th>Date</th><th>Time</th><th>Status</th></tr>";
    
    while($row = mysqli_fetch_assoc($all_appointments)) {
        echo "<tr>";
        echo "<td>{$row['ID']}</td>";
        echo "<td>{$row['AptNumber']}</td>";
        echo "<td>{$row['Name']}</td>";
        echo "<td>{$row['PhoneNumber']}</td>";
        echo "<td>" . (isset($row['Service']) ? $row['Service'] : 'N/A') . "</td>";
        echo "<td>{$row['AptDate']}</td>";
        echo "<td>{$row['AptTime']}</td>";
        echo "<td>" . (isset($row['Status']) ? $row['Status'] : 'N/A') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Test search queries
    echo "<h2>Test Search Queries:</h2>";
    
    // Get first appointment name for testing
    mysqli_data_seek($all_appointments, 0);
    $first = mysqli_fetch_assoc($all_appointments);
    $test_name = $first['Name'];
    $test_phone = $first['PhoneNumber'];
    $test_apt = $first['AptNumber'];
    
    echo "<h3>Test 1: Search by Name '$test_name'</h3>";
    $test1 = mysqli_query($con, "SELECT * FROM tblappointment WHERE Name LIKE '%$test_name%'");
    echo "<p>Found: " . mysqli_num_rows($test1) . " results</p>";
    if(mysqli_num_rows($test1) > 0) {
        echo "<p class='success'>✓ Search by name works!</p>";
    } else {
        echo "<p class='error'>✗ Search by name failed!</p>";
    }
    
    echo "<h3>Test 2: Search by Phone '$test_phone'</h3>";
    $test2 = mysqli_query($con, "SELECT * FROM tblappointment WHERE PhoneNumber LIKE '%$test_phone%'");
    echo "<p>Found: " . mysqli_num_rows($test2) . " results</p>";
    if(mysqli_num_rows($test2) > 0) {
        echo "<p class='success'>✓ Search by phone works!</p>";
    } else {
        echo "<p class='error'>✗ Search by phone failed!</p>";
    }
    
    echo "<h3>Test 3: Search by AptNumber '$test_apt'</h3>";
    $test3 = mysqli_query($con, "SELECT * FROM tblappointment WHERE AptNumber LIKE '%$test_apt%'");
    echo "<p>Found: " . mysqli_num_rows($test3) . " results</p>";
    if(mysqli_num_rows($test3) > 0) {
        echo "<p class='success'>✓ Search by appointment number works!</p>";
    } else {
        echo "<p class='error'>✗ Search by appointment number failed!</p>";
    }
    
    echo "<h3>Test 4: Combined Search (OR query)</h3>";
    $test4 = mysqli_query($con, "SELECT * FROM tblappointment WHERE AptNumber LIKE '%$test_name%' OR Name LIKE '%$test_name%' OR PhoneNumber LIKE '%$test_name%'");
    echo "<p>Found: " . mysqli_num_rows($test4) . " results for '$test_name'</p>";
    if(mysqli_num_rows($test4) > 0) {
        echo "<p class='success'>✓ Combined search works!</p>";
    } else {
        echo "<p class='error'>✗ Combined search failed!</p>";
    }
    
} else {
    echo "<p class='error'>✗ No appointments found in database!</p>";
    echo "<p>You need to create appointments first from the frontend.</p>";
}

echo "<hr>";
echo "<h2>Search Tips:</h2>";
echo "<ul>";
echo "<li>Use exact names from the table above</li>";
echo "<li>Try partial phone numbers (e.g., last 5 digits)</li>";
echo "<li>Try appointment numbers (e.g., APT-001)</li>";
echo "<li>Search is case-insensitive</li>";
echo "</ul>";

echo "<hr>";
echo "<h2>Test Search Page:</h2>";
echo "<p><a href='search-appointment.php' style='background:#aa9144;color:#fff;padding:10px 20px;text-decoration:none;border-radius:5px;'>Go to Search Appointment</a></p>";

echo "</body></html>";
?>
