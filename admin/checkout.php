<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Check if user has items in cart
$session_id = isset($_SESSION['customer_session']) ? $_SESSION['customer_session'] : '';
$cart_items = mysqli_query($con, "SELECT * FROM tblcart WHERE SessionID='$session_id'");

if(mysqli_num_rows($cart_items) == 0) {
    echo "<script>alert('Your cart is empty!');</script>";
    echo "<script>window.location.href='products.php'</script>";
    exit();
}

// Calculate totals
$total_amount = 0;
$total_items = 0;
$cart_data = array();
while($item = mysqli_fetch_assoc($cart_items)) {
    $item_total = $item['ProductPrice'] * $item['Quantity'];
    $total_amount += $item_total;
    $total_items += $item['Quantity'];
    $cart_data[] = $item;
}

// Process checkout
if(isset($_POST['place_order'])) {
    // Enable error reporting for debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    $customer_name = mysqli_real_escape_string($con, $_POST['customer_name']);
    $customer_phone = mysqli_real_escape_string($con, $_POST['customer_phone']);
    $customer_email = mysqli_real_escape_string($con, $_POST['customer_email']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $city = mysqli_real_escape_string($con, $_POST['city']);
    $pincode = mysqli_real_escape_string($con, $_POST['pincode']);
    $payment_method = mysqli_real_escape_string($con, $_POST['payment_method']);
    
    // Generate order number
    $order_number = 'ORD-' . date('Ymd') . '-' . rand(1000, 9999);
    
    // Get order date
    $order_date = date('Y-m-d H:i:s');
    
    // Determine order status based on payment method
    if($payment_method == 'cod') {
        $order_status = 'Pending'; // Cash on Delivery - pending until delivery
    } else {
        $order_status = 'Confirmed'; // GPay/UPI - confirmed after payment
    }
    
    // Insert order into tblorders
    foreach($cart_data as $item) {
        $product_total = $item['ProductPrice'] * $item['Quantity'];
        
        $query = "INSERT INTO tblorders (
            OrderNumber, 
            ProductID, 
            ProductName, 
            ProductPrice, 
            ProductImage, 
            Quantity, 
            TotalAmount, 
            OrderStatus,
            CustomerName,
            CustomerPhone,
            CustomerEmail,
            Address,
            City,
            Pincode,
            PaymentMethod,
            OrderDate
        ) VALUES (
            '$order_number',
            '{$item['ProductID']}',
            '{$item['ProductName']}',
            '{$item['ProductPrice']}',
            '{$item['ProductImage']}',
            '{$item['Quantity']}',
            '$product_total',
            '$order_status',
            '$customer_name',
            '$customer_phone',
            '$customer_email',
            '$address',
            '$city',
            '$pincode',
            '$payment_method',
            '$order_date'
        )";
        
        if(!mysqli_query($con, $query)) {
            echo "<script>alert('Database Error: " . mysqli_error($con) . "');</script>";
            echo "<script>window.location.href='checkout.php';</script>";
            exit();
        }
    }
    
    // Clear cart
    mysqli_query($con, "DELETE FROM tblcart WHERE SessionID='$session_id'");
    
    // Redirect to bill page using JavaScript (more reliable)
    echo "<script>window.location.href='bill.php?order=" . $order_number . "';</script>";
    echo "<noscript><meta http-equiv='refresh' content='0;url=bill.php?order=" . $order_number . "'></noscript>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    
    <title>Checkout - Men Salon</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i%7cMontserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <!-- Style -->
    <link href="css/style.css" rel="stylesheet">
    <style>
        .checkout-header {
            background: linear-gradient(rgba(36, 39, 38, 0.8), rgba(36, 39, 38, 0.8)), rgba(36, 39, 38, 0.8) url(images/page-header.jpg) no-repeat center; 
            background-size: cover;
            padding: 80px 0;
            text-align: center;
            color: #fff;
        }
        .checkout-title {
            font-size: 42px;
            font-weight: 600;
            margin-bottom: 10px;
            letter-spacing: -1px;
        }
        .checkout-section {
            padding: 60px 0;
        }
        .checkout-form {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
            color: #18150d;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        .order-summary-box {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            position: sticky;
            top: 20px;
        }
        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .payment-methods {
            margin-top: 30px;
        }
        .payment-option {
            background: #fff;
            border: 2px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .payment-option:hover {
            border-color: #aa9144;
        }
        .payment-option.active {
            border-color: #aa9144;
            background: #faf8f0;
        }
        .payment-option input[type="radio"] {
            margin-right: 10px;
        }
        .payment-option label {
            font-weight: 600;
            cursor: pointer;
            margin: 0;
        }
        .payment-icon {
            font-size: 24px;
            margin-right: 10px;
            color: #aa9144;
        }
        .payment-description {
            margin-top: 10px;
            padding-left: 34px;
            color: #666;
            font-size: 14px;
        }
        .btn-place-order {
            background: #aa9144;
            color: #fff;
            border: none;
            padding: 15px 40px;
            border-radius: 5px;
            font-weight: 600;
            text-transform: uppercase;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            margin-top: 20px;
            transition: all 0.3s ease;
        }
        .btn-place-order:hover {
            background: #8e7424;
        }
        .summary-total {
            font-size: 20px;
            font-weight: bold;
            color: #aa9144;
            padding-top: 15px;
            border-top: 2px solid #aa9144;
            margin-top: 15px;
        }
        .required {
            color: red;
        }
    </style>
</head>

<body>
    <?php include_once('includes/header.php');?>
    
    <!-- Checkout Header -->
    <div class="checkout-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h1 class="checkout-title">Checkout</h1>
                </div>
            </div>
        </div>
    </div>
   
    <!-- Checkout Section -->
    <div class="checkout-section">
        <div class="container">
            <form method="post" id="checkoutForm">
                <div class="row">
                    <!-- Customer Details -->
                    <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                        <div class="checkout-form">
                            <h3 style="margin-bottom: 25px; color: #18150d; border-bottom: 2px solid #aa9144; padding-bottom: 10px;">
                                <i class="fa fa-user"></i> Customer Details
                            </h3>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Full Name <span class="required">*</span></label>
                                        <input type="text" name="customer_name" required placeholder="Enter your full name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone Number <span class="required">*</span></label>
                                        <input type="tel" name="customer_phone" required placeholder="Enter your phone number" pattern="[0-9]{10}" maxlength="10">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Email Address <span class="required">*</span></label>
                                <input type="email" name="customer_email" required placeholder="Enter your email address">
                            </div>
                            
                            <h3 style="margin: 30px 0 25px; color: #18150d; border-bottom: 2px solid #aa9144; padding-bottom: 10px;">
                                <i class="fa fa-map-marker"></i> Delivery Address
                            </h3>
                            
                            <div class="form-group">
                                <label>Complete Address <span class="required">*</span></label>
                                <textarea name="address" required placeholder="House/Flat No., Street, Landmark"></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>City <span class="required">*</span></label>
                                        <input type="text" name="city" required placeholder="Enter your city">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Pincode <span class="required">*</span></label>
                                        <input type="text" name="pincode" required placeholder="Enter pincode" pattern="[0-9]{6}" maxlength="6">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Order Summary & Payment -->
                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                        <div class="order-summary-box">
                            <h3 style="margin-bottom: 20px; color: #18150d; border-bottom: 2px solid #aa9144; padding-bottom: 10px;">
                                <i class="fa fa-shopping-bag"></i> Order Summary
                            </h3>
                            
                            <?php foreach($cart_data as $item): ?>
                            <div class="order-item">
                                <div>
                                    <strong><?php echo $item['ProductName']; ?></strong><br>
                                    <small>Qty: <?php echo $item['Quantity']; ?></small>
                                </div>
                                <div>
                                    <strong>₹<?php echo number_format($item['ProductPrice'] * $item['Quantity'], 2); ?></strong>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            
                            <div style="margin-top: 20px;">
                                <div class="order-item">
                                    <span>Subtotal:</span>
                                    <span>₹<?php echo number_format($total_amount, 2); ?></span>
                                </div>
                                <div class="order-item">
                                    <span>Shipping:</span>
                                    <span>Free</span>
                                </div>
                                <div class="order-item summary-total">
                                    <span>Grand Total:</span>
                                    <span>₹<?php echo number_format($total_amount, 2); ?></span>
                                </div>
                            </div>
                            
                            <!-- Payment Methods -->
                            <div class="payment-methods">
                                <h3 style="margin-bottom: 20px; color: #18150d; border-bottom: 2px solid #aa9144; padding-bottom: 10px;">
                                    <i class="fa fa-credit-card"></i> Payment Method
                                </h3>
                                
                                <div class="payment-option active" onclick="selectPayment('cod')">
                                    <input type="radio" name="payment_method" value="cod" id="cod" checked>
                                    <label for="cod">
                                        <i class="fa fa-money payment-icon"></i>
                                        Cash on Delivery
                                    </label>
                                    <div class="payment-description">
                                        Pay with cash when your order is delivered to your doorstep.
                                    </div>
                                </div>
                                
                                <div class="payment-option" onclick="selectPayment('gpay')">
                                    <input type="radio" name="payment_method" value="gpay" id="gpay">
                                    <label for="gpay">
                                        <i class="fa fa-mobile payment-icon"></i>
                                        GPay / UPI Payment
                                    </label>
                                    <div class="payment-description">
                                        Pay securely using Google Pay, PhonePe, Paytm, or any UPI app.
                                    </div>
                                </div>
                            </div>
                            
                            <button type="submit" name="place_order" class="btn-place-order">
                                <i class="fa fa-check-circle"></i> Place Order
                            </button>
                            
                            <a href="cart.php" style="display: block; text-align: center; margin-top: 15px; color: #aa9144;">
                                <i class="fa fa-arrow-left"></i> Back to Cart
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
   
    <?php include_once('includes/footer.php');?>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    
    <script>
        // Flag to track if payment confirmation is done
        var paymentConfirmed = false;
        
        function selectPayment(method) {
            // Remove active class from all options
            document.querySelectorAll('.payment-option').forEach(option => {
                option.classList.remove('active');
            });
            
            // Add active class to selected option
            if(method === 'cod') {
                document.querySelectorAll('.payment-option')[0].classList.add('active');
                document.getElementById('cod').checked = true;
            } else {
                document.querySelectorAll('.payment-option')[1].classList.add('active');
                document.getElementById('gpay').checked = true;
            }
        }
        
        // Form validation and QR code display
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            var phone = document.querySelector('input[name="customer_phone"]').value;
            var pincode = document.querySelector('input[name="pincode"]').value;
            var paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            
            if(phone.length !== 10) {
                alert('Please enter a valid 10-digit phone number');
                e.preventDefault();
                return false;
            }
            
            if(pincode.length !== 6) {
                alert('Please enter a valid 6-digit pincode');
                e.preventDefault();
                return false;
            }
            
            // If GPay is selected and payment not confirmed yet, show QR code modal
            if(paymentMethod === 'gpay' && !paymentConfirmed) {
                e.preventDefault();
                showQRCodeModal();
                return false;
            }
            
            // If payment is confirmed, allow form to submit
            if(paymentMethod === 'gpay' && paymentConfirmed) {
                return true;
            }
        });
        
        function showQRCodeModal() {
            // Create modal overlay
            var modal = document.createElement('div');
            modal.id = 'qrModal';
            modal.style.cssText = 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 9999; display: flex; align-items: center; justify-content: center;';
            
            // Create modal content
            modal.innerHTML = `
                <div style="background: white; padding: 40px; border-radius: 15px; max-width: 500px; width: 90%; text-align: center; position: relative;">
                    <button onclick="closeQRModal()" style="position: absolute; top: 15px; right: 15px; background: #ff4444; color: white; border: none; width: 35px; height: 35px; border-radius: 50%; font-size: 20px; cursor: pointer; font-weight: bold;">&times;</button>
                    
                    <h2 style="color: #aa9144; margin-bottom: 10px;"><i class="fa fa-mobile"></i> GPay / UPI Payment</h2>
                    <p style="color: #666; margin-bottom: 30px;">Scan the QR code below to complete your payment</p>
                    
                    <div style="background: #f8f9fa; padding: 30px; border-radius: 10px; margin-bottom: 20px;">
                        <div style="background: white; padding: 20px; display: inline-block; border-radius: 10px; border: 3px solid #aa9144;">
                            <!-- QR Code Image - Replace with your actual QR code -->
                            <img src="images/gpay-qr-code.png" alt="GPay QR Code" style="width: 250px; height: 250px;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <div style="display: none; width: 250px; height: 250px; background: #fff; padding: 20px;">
                                <i class="fa fa-qrcode" style="font-size: 180px; color: #aa9144;"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div style="background: #e7f3ff; padding: 20px; border-radius: 10px; margin-bottom: 20px; text-align: left;">
                        <h4 style="color: #007bff; margin-bottom: 15px;"><i class="fa fa-info-circle"></i> Payment Instructions:</h4>
                        <ol style="margin: 0; padding-left: 20px; line-height: 1.8;">
                            <li>Open your GPay, PhonePe, Paytm or any UPI app</li>
                            <li>Scan the QR code shown above</li>
                            <li>Pay the amount: <strong style="color: #aa9144; font-size: 18px;">₹<?php echo number_format($total_amount, 2); ?></strong></li>
                            <li>Complete the payment</li>
                            <li>Click "I Have Paid" button below</li>
                        </ol>
                    </div>
                    
                    <div style="background: #fff3cd; padding: 15px; border-radius: 10px; margin-bottom: 20px; border-left: 4px solid #ffc107;">
                        <p style="margin: 0; color: #856404;"><i class="fa fa-warning"></i> <strong>Important:</strong> Please complete the payment before clicking "I Have Paid"</p>
                    </div>
                    
                    <div style="display: flex; gap: 10px;">
                        <button onclick="closeQRModal()" style="flex: 1; padding: 15px; background: #6c757d; color: white; border: none; border-radius: 5px; font-weight: 600; cursor: pointer; font-size: 16px;">
                            <i class="fa fa-times"></i> Cancel
                        </button>
                        <button onclick="confirmPayment()" style="flex: 1; padding: 15px; background: #28a745; color: white; border: none; border-radius: 5px; font-weight: 600; cursor: pointer; font-size: 16px;">
                            <i class="fa fa-check"></i> I Have Paid
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            document.body.style.overflow = 'hidden';
        }
        
        function closeQRModal() {
            var modal = document.getElementById('qrModal');
            if(modal) {
                modal.remove();
                document.body.style.overflow = 'auto';
            }
        }
        
        function confirmPayment() {
            if(confirm('Have you completed the payment of ₹<?php echo number_format($total_amount, 2); ?>?')) {
                // Set flag to allow form submission
                paymentConfirmed = true;
                
                // Show loading message
                var confirmBtn = event.target;
                confirmBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';
                confirmBtn.disabled = true;
                
                // Close modal
                closeQRModal();
                
                // Small delay to ensure modal closes before form submission
                setTimeout(function() {
                    document.getElementById('checkoutForm').submit();
                }, 500);
            }
        }
        
        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            var modal = document.getElementById('qrModal');
            if(modal && e.target === modal) {
                closeQRModal();
            }
        });
    </script>
</body>

</html>
