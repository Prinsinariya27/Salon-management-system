<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/dbconnection.php');

// Get order number from URL
$order_number = isset($_GET['order']) ? $_GET['order'] : '';

if(!$order_number) {
    echo "<script>alert('Invalid order!');</script>";
    echo "<script>window.location.href='products.php'</script>";
    exit();
}

// Fetch all order details
$order_query = "SELECT * FROM tblorders WHERE OrderNumber='$order_number'";
$order_result = mysqli_query($con, $order_query);

if(!$order_result) {
    die("Database Error: " . mysqli_error($con));
}

if(mysqli_num_rows($order_result) == 0) {
    echo "<script>alert('Order not found! Order Number: $order_number');</script>";
    echo "<script>window.location.href='products.php'</script>";
    exit();
}

// Get order items and customer details
$order_items = array();
$customer_details = array();
$grand_total = 0;
$total_items = 0;

while($row = mysqli_fetch_assoc($order_result)) {
    $order_items[] = $row;
    $grand_total += $row['TotalAmount'];
    $total_items += $row['Quantity'];
    
    // Store customer details (same for all items)
    if(empty($customer_details)) {
        $customer_details = array(
            'name' => $row['CustomerName'],
            'phone' => $row['CustomerPhone'],
            'email' => $row['CustomerEmail'],
            'address' => $row['Address'],
            'city' => $row['City'],
            'pincode' => $row['Pincode'],
            'payment_method' => $row['PaymentMethod'],
            'order_date' => $row['OrderDate'],
            'order_status' => $row['OrderStatus']
        );
    }
}

// Format order date
$order_date_formatted = date('d M Y, h:i A', strtotime($customer_details['order_date']));

// Payment method text
$payment_text = ($customer_details['payment_method'] == 'cod') ? 'Cash on Delivery' : 'GPay / UPI Payment';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    
    <title>Order Invoice - <?php echo $order_number; ?></title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i%7cMontserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <!-- Style -->
    <link href="css/style.css" rel="stylesheet">
    <style>
        .bill-header {
            background: linear-gradient(rgba(36, 39, 38, 0.8), rgba(36, 39, 38, 0.8)), rgba(36, 39, 38, 0.8) url(images/page-header.jpg) no-repeat center; 
            background-size: cover;
            padding: 60px 0;
            text-align: center;
            color: #fff;
        }
        .bill-title {
            font-size: 42px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .bill-section {
            padding: 60px 0;
        }
        .invoice-container {
            background: #fff;
            max-width: 900px;
            margin: 0 auto;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
        .invoice-header {
            border-bottom: 3px solid #aa9144;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-name {
            font-size: 32px;
            font-weight: bold;
            color: #aa9144;
            margin-bottom: 5px;
        }
        .company-tagline {
            color: #666;
            font-size: 14px;
        }
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #18150d;
            text-align: right;
        }
        .invoice-number {
            color: #aa9144;
            font-size: 18px;
            text-align: right;
        }
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 30px;
        }
        .info-box {
            flex: 1;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #aa9144;
        }
        .info-box h4 {
            margin: 0 0 15px 0;
            color: #aa9144;
            font-size: 16px;
            text-transform: uppercase;
        }
        .info-box p {
            margin: 8px 0;
            color: #333;
            line-height: 1.6;
        }
        .info-box strong {
            color: #18150d;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }
        .invoice-table thead {
            background: #aa9144;
            color: #fff;
        }
        .invoice-table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 14px;
        }
        .invoice-table td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }
        .invoice-table tbody tr:last-child td {
            border-bottom: none;
        }
        .product-name {
            font-weight: 600;
            color: #18150d;
        }
        .summary-section {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            margin-top: 30px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }
        .summary-row:last-child {
            border-bottom: none;
            font-size: 24px;
            font-weight: bold;
            color: #aa9144;
            padding-top: 15px;
            margin-top: 10px;
            border-top: 2px solid #aa9144;
        }
        .payment-info {
            background: #e7f3ff;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
            border-left: 4px solid #007bff;
        }
        .payment-info.cod {
            background: #fff3cd;
            border-left-color: #ffc107;
        }
        .payment-info h4 {
            margin: 0 0 10px 0;
            color: #007bff;
        }
        .payment-info.cod h4 {
            color: #856404;
        }
        .invoice-footer {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #aa9144;
            text-align: center;
        }
        .thank-you {
            font-size: 24px;
            color: #aa9144;
            margin-bottom: 15px;
        }
        .footer-text {
            color: #666;
            font-size: 14px;
            line-height: 1.8;
        }
        .action-buttons {
            margin-top: 30px;
            text-align: center;
        }
        .btn-print {
            background: #aa9144;
            color: #fff;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            margin: 5px;
            font-size: 16px;
        }
        .btn-download {
            background: #28a745;
            color: #fff;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            margin: 5px;
            font-size: 16px;
        }
        .btn-home {
            background: #6c757d;
            color: #fff;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            margin: 5px;
            font-size: 16px;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
        }
        .status-pending {
            background: #ffc107;
            color: #000;
        }
        .status-confirmed {
            background: #28a745;
            color: #fff;
        }
        
        @media print {
            .bill-header, .action-buttons {
                display: none;
            }
            .invoice-container {
                box-shadow: none;
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <?php include_once('includes/header.php');?>
    
    <!-- Bill Header -->
    <div class="bill-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h1 class="bill-title">Order Invoice</h1>
                </div>
            </div>
        </div>
    </div>
   
    <!-- Bill Section -->
    <div class="bill-section">
        <div class="container">
            <div class="invoice-container">
                <!-- Invoice Header -->
                <div class="invoice-header">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="company-name">Men Salon Management</div>
                            <div class="company-tagline">Premium Grooming Services & Products</div>
                        </div>
                        <div class="col-md-6">
                            <div class="invoice-title">INVOICE</div>
                            <div class="invoice-number"><?php echo $order_number; ?></div>
                        </div>
                    </div>
                </div>
                
                <!-- Order Status -->
                <div style="text-align: center; margin-bottom: 30px;">
                    <span class="status-badge <?php echo ($customer_details['order_status'] == 'Pending') ? 'status-pending' : 'status-confirmed'; ?>">
                        <?php echo $customer_details['order_status']; ?>
                    </span>
                </div>
                
                <!-- Customer & Order Info -->
                <div class="info-section">
                    <div class="info-box">
                        <h4><i class="fa fa-user"></i> Bill To</h4>
                        <p><strong>Name:</strong> <?php echo $customer_details['name']; ?></p>
                        <p><strong>Phone:</strong> <?php echo $customer_details['phone']; ?></p>
                        <p><strong>Email:</strong> <?php echo $customer_details['email']; ?></p>
                    </div>
                    <div class="info-box">
                        <h4><i class="fa fa-map-marker"></i> Delivery Address</h4>
                        <p><?php echo $customer_details['address']; ?></p>
                        <p><?php echo $customer_details['city']; ?> - <?php echo $customer_details['pincode']; ?></p>
                    </div>
                    <div class="info-box">
                        <h4><i class="fa fa-calendar"></i> Order Details</h4>
                        <p><strong>Order Date:</strong> <?php echo $order_date_formatted; ?></p>
                        <p><strong>Payment:</strong> <?php echo $payment_text; ?></p>
                        <p><strong>Items:</strong> <?php echo count($order_items); ?></p>
                    </div>
                </div>
                
                <!-- Products Table -->
                <table class="invoice-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Product Name</th>
                            <th style="width: 120px;">Price</th>
                            <th style="width: 100px;">Qty</th>
                            <th style="width: 130px;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $counter = 1;
                        foreach($order_items as $item): 
                        ?>
                        <tr>
                            <td><?php echo $counter++; ?></td>
                            <td>
                                <div class="product-name"><?php echo $item['ProductName']; ?></div>
                            </td>
                            <td>₹<?php echo number_format($item['ProductPrice'], 2); ?></td>
                            <td><?php echo $item['Quantity']; ?></td>
                            <td><strong>₹<?php echo number_format($item['TotalAmount'], 2); ?></strong></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <!-- Summary -->
                <div class="summary-section">
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span>₹<?php echo number_format($grand_total, 2); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping Charges:</span>
                        <span style="color: #28a745;">FREE</span>
                    </div>
                    <div class="summary-row">
                        <span>Discount:</span>
                        <span>₹0.00</span>
                    </div>
                    <div class="summary-row">
                        <span>Grand Total:</span>
                        <span>₹<?php echo number_format($grand_total, 2); ?></span>
                    </div>
                </div>
                
                <!-- Payment Information -->
                <?php if($customer_details['payment_method'] == 'gpay'): ?>
                <div class="payment-info">
                    <h4><i class="fa fa-mobile"></i> GPay / UPI Payment</h4>
                    <p style="margin: 0;">Payment to be completed via GPay/UPI. Please ensure payment is made to confirm your order.</p>
                </div>
                <?php else: ?>
                <div class="payment-info cod">
                    <h4><i class="fa fa-money"></i> Cash on Delivery</h4>
                    <p style="margin: 0;">Payment will be collected at the time of delivery. Please keep exact change ready.</p>
                </div>
                <?php endif; ?>
                
                <!-- Footer -->
                <div class="invoice-footer">
                    <div class="thank-you">Thank You for Your Order!</div>
                    <div class="footer-text">
                        <p>For any queries, please contact us:</p>
                        <p><i class="fa fa-phone"></i> Phone: +91-XXXXXXXXXX | <i class="fa fa-envelope"></i> Email: info@mensalon.com</p>
                        <p style="margin-top: 15px; font-size: 12px;">This is a computer-generated invoice and does not require a signature.</p>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button class="btn-print" onclick="window.print()">
                        <i class="fa fa-print"></i> Print Invoice
                    </button>
                    <button class="btn-download" onclick="downloadInvoice()">
                        <i class="fa fa-download"></i> Download PDF
                    </button>
                    <a href="products.php" class="btn-home" style="text-decoration: none; display: inline-block;">
                        <i class="fa fa-shopping-bag"></i> Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
   
    <?php include_once('includes/footer.php');?>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    
    <script>
        function downloadInvoice() {
            alert('To save as PDF:\\n1. Click "Print Invoice" button\\n2. Select "Save as PDF" as destination\\n3. Click Save');
            window.print();
        }
    </script>
</body>

</html>
