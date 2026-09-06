<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Get order number from URL
$order_number = isset($_GET['order']) ? $_GET['order'] : '';

// Fetch order details if order number exists
$order_details = array();
if($order_number) {
    $query = "SELECT * FROM tblorders WHERE OrderNumber='$order_number' LIMIT 1";
    $result = mysqli_query($con, $query);
    if($result && mysqli_num_rows($result) > 0) {
        $order_details = mysqli_fetch_assoc($result);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    
     <title>Men Salon Management System || Thank You Page</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i%7cMontserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <!-- Style -->
    <link href="css/style.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <?php include_once('includes/header.php');?>
    <div class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="page-caption">
                        <h2 class="page-title">Thank You</h2>
                        <div class="page-breadcrumb">
                            <ol class="breadcrumb">
                                <li><a href="index.php">Home</a></li>
                                <li class="active">Thank You</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container">
            <div class="row">
                
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?php if($order_number && !empty($order_details)): ?>
                    <div class="post-block post-quote">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="quote-content" style="text-align: center; padding: 40px;">
                                    <i class="fa fa-check-circle" style="font-size: 80px; color: #28a745; margin-bottom: 20px;"></i>
                                    <h2 style="color: #28a745; margin-bottom: 20px;">Order Placed Successfully!</h2>
                                    <blockquote style="font-size: 20px; margin-bottom: 30px;">
                                        Thank you for your order!<br>
                                        Your Order Number: <strong style="color: #aa9144;"><?php echo $order_number; ?></strong>
                                    </blockquote>
                                                    
                                    <div style="background: #f8f9fa; padding: 30px; border-radius: 10px; margin: 30px 0; text-align: left;">
                                        <h4 style="margin-bottom: 20px; color: #18150d;">Order Details:</h4>
                                        <div style="margin-bottom: 15px;">
                                            <strong>Customer Name:</strong> <?php echo $order_details['CustomerName']; ?>
                                        </div>
                                        <div style="margin-bottom: 15px;">
                                            <strong>Phone:</strong> <?php echo $order_details['CustomerPhone']; ?>
                                        </div>
                                        <div style="margin-bottom: 15px;">
                                            <strong>Email:</strong> <?php echo $order_details['CustomerEmail']; ?>
                                        </div>
                                        <div style="margin-bottom: 15px;">
                                            <strong>Delivery Address:</strong><br>
                                            <?php echo $order_details['Address']; ?><br>
                                            <?php echo $order_details['City']; ?> - <?php echo $order_details['Pincode']; ?>
                                        </div>
                                        <div style="margin-bottom: 15px;">
                                            <strong>Product:</strong> <?php echo $order_details['ProductName']; ?>
                                        </div>
                                        <div style="margin-bottom: 15px;">
                                            <strong>Quantity:</strong> <?php echo $order_details['Quantity']; ?>
                                        </div>
                                        <div style="margin-bottom: 15px;">
                                            <strong>Total Amount:</strong> <span style="color: #aa9144; font-size: 20px; font-weight: bold;">₹<?php echo number_format($order_details['TotalAmount'], 2); ?></span>
                                        </div>
                                        <div style="margin-bottom: 15px;">
                                            <strong>Payment Method:</strong> 
                                            <?php 
                                            if($order_details['PaymentMethod'] == 'cod') {
                                                echo '<span style="color: #28a745;"><i class="fa fa-money"></i> Cash on Delivery</span>';
                                            } else {
                                                echo '<span style="color: #007bff;"><i class="fa fa-mobile"></i> GPay / UPI Payment</span>';
                                            }
                                            ?>
                                        </div>
                                        <div style="margin-bottom: 15px;">
                                            <strong>Order Status:</strong> 
                                            <span style="background: #ffc107; color: #000; padding: 5px 15px; border-radius: 5px; font-weight: bold;">
                                                <?php echo $order_details['OrderStatus']; ?>
                                            </span>
                                        </div>
                                        <div>
                                            <strong>Order Date:</strong> <?php echo date('d M Y, h:i A', strtotime($order_details['OrderDate'])); ?>
                                        </div>
                                    </div>
                                                    
                                    <?php if($order_details['PaymentMethod'] == 'gpay'): ?>
                                    <div style="background: #e7f3ff; padding: 20px; border-radius: 10px; margin: 20px 0; border-left: 4px solid #007bff;">
                                        <h5 style="color: #007bff; margin-bottom: 10px;"><i class="fa fa-info-circle"></i> Payment Instructions</h5>
                                        <p style="margin: 0;">Please complete your GPay/UPI payment to confirm your order. You will receive payment details on your registered phone number.</p>
                                    </div>
                                    <?php else: ?>
                                    <div style="background: #fff3cd; padding: 20px; border-radius: 10px; margin: 20px 0; border-left: 4px solid #ffc107;">
                                        <h5 style="color: #856404; margin-bottom: 10px;"><i class="fa fa-info-circle"></i> Cash on Delivery</h5>
                                        <p style="margin: 0;">Please keep the exact cash amount ready when our delivery person arrives at your doorstep.</p>
                                    </div>
                                    <?php endif; ?>
                                                    
                                    <div style="margin-top: 30px;">
                                        <a href="bill.php?order=<?php echo $order_number; ?>" class="btn btn-default btn-lg" style="margin: 5px; background: #aa9144; color: #fff;">
                                            <i class="fa fa-file-text"></i> View Invoice/Bill
                                        </a>
                                        <a href="products.php" class="btn btn-default btn-lg" style="margin: 5px;">
                                            <i class="fa fa-shopping-bag"></i> Continue Shopping
                                        </a>
                                        <a href="index.php" class="btn btn-default btn-lg" style="margin: 5px;">
                                            <i class="fa fa-home"></i> Back to Home
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="post-block post-quote">
                        <div class="row ">
                            <div class="col-md-12">
                                <div class="quote-content" style="text-align: center; padding: 40px;">
                                    <i class="fa fa-check-circle" style="font-size: 80px; color: #28a745; margin-bottom: 20px;"></i>
                                    <h2 style="color: #28a745; margin-bottom: 20px;">Thank You!</h2>
                                    <blockquote style="font-size: 20px;">Your appointment has been booked successfully.</blockquote>
                                    <a href="index.php" class="btn btn-default btn-lg" style="margin-top: 20px;">
                                        <i class="fa fa-home"></i> Back to Home
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
            </div>
        </div>
    </div>
<?php include_once('includes/footer.php');?>
    <!-- /.footer-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/menumaker.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/sticky-header.js"></script>
</body>

</html>
