<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Remove item from cart - Database
if(isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    $session_id = $_SESSION['customer_session'];
    mysqli_query($con, "DELETE FROM tblcart WHERE SessionID='$session_id' AND ProductID='$product_id'");
    echo "<script>alert('Item removed from cart!');</script>";
    echo "<script>window.location.href='cart.php'</script>";
}

// Update cart quantity - Database
if(isset($_POST['update_cart'])) {
    $session_id = $_SESSION['customer_session'];
    foreach($_POST['quantity'] as $product_id => $qty) {
        if($qty > 0) {
            mysqli_query($con, "UPDATE tblcart SET Quantity='$qty' WHERE SessionID='$session_id' AND ProductID='$product_id'");
        } else {
            mysqli_query($con, "DELETE FROM tblcart WHERE SessionID='$session_id' AND ProductID='$product_id'");
        }
    }
    echo "<script>alert('Cart updated!');</script>";
    echo "<script>window.location.href='cart.php'</script>";
}

// Clear entire cart - Database
if(isset($_POST['clear_cart'])) {
    $session_id = $_SESSION['customer_session'];
    mysqli_query($con, "DELETE FROM tblcart WHERE SessionID='$session_id'");
    echo "<script>alert('Cart cleared!');</script>";
    echo "<script>window.location.href='cart.php'</script>";
}

// Checkout - Move cart to orders
if(isset($_POST['checkout'])) {
    $session_id = $_SESSION['customer_session'];
    $order_number = 'ORD-' . date('Ymd') . '-' . rand(1000, 9999);
    
    // Get all cart items
    $cart_items = mysqli_query($con, "SELECT * FROM tblcart WHERE SessionID='$session_id'");
    while($item = mysqli_fetch_assoc($cart_items)) {
        $total = $item['ProductPrice'] * $item['Quantity'];
        mysqli_query($con, "INSERT INTO tblorders(OrderNumber, ProductID, ProductName, ProductPrice, ProductImage, Quantity, TotalAmount, OrderStatus) 
                        VALUES('$order_number', '{$item['ProductID']}', '{$item['ProductName']}', '{$item['ProductPrice']}', '{$item['ProductImage']}', '{$item['Quantity']}', '$total', 'Confirmed')");
    }
    
    // Clear cart
    mysqli_query($con, "DELETE FROM tblcart WHERE SessionID='$session_id'");
    echo "<script>alert('Order placed successfully! Your order number is: $order_number');</script>";
    echo "<script>window.location.href='products.php'</script>";
}

// Calculate totals from database
$total_amount = 0;
$total_items = 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    
    <title>Shopping Cart - Men Salon</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i%7cMontserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <!-- Style -->
    <link href="css/style.css" rel="stylesheet">
    <style>
        .cart-header {
            background: linear-gradient(rgba(36, 39, 38, 0.8), rgba(36, 39, 38, 0.8)), rgba(36, 39, 38, 0.8) url(images/page-header.jpg) no-repeat center; 
            background-size: cover;
            padding: 80px 0;
            text-align: center;
            color: #fff;
        }
        .cart-title {
            font-size: 42px;
            font-weight: 600;
            margin-bottom: 10px;
            letter-spacing: -1px;
        }
        .cart-section {
            padding: 60px 0;
        }
        .cart-table {
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .cart-table thead {
            background: #aa9144;
            color: #fff;
        }
        .cart-table thead th {
            padding: 20px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 14px;
        }
        .cart-table tbody tr {
            border-bottom: 1px solid #eee;
        }
        .cart-table tbody tr:last-child {
            border-bottom: none;
        }
        .cart-table td {
            padding: 20px;
            vertical-align: middle;
        }
        .cart-product-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }
        .cart-product-name {
            font-size: 16px;
            font-weight: 600;
            color: #18150d;
        }
        .cart-product-price {
            font-size: 18px;
            color: #aa9144;
            font-weight: bold;
        }
        .quantity-input {
            width: 80px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 5px;
            font-size: 16px;
        }
        .remove-btn {
            background: #ff4444;
            color: #fff;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }
        .remove-btn:hover {
            background: #cc0000;
        }
        .cart-summary {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            margin-top: 30px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #ddd;
        }
        .summary-row:last-child {
            border-bottom: none;
            font-size: 20px;
            font-weight: bold;
            color: #aa9144;
        }
        .empty-cart {
            text-align: center;
            padding: 60px 20px;
            background: #fff;
            border-radius: 10px;
        }
        .empty-cart i {
            font-size: 80px;
            color: #aa9144;
            margin-bottom: 20px;
        }
        .empty-cart h3 {
            font-size: 24px;
            margin-bottom: 15px;
        }
        .cart-actions {
            margin-top: 30px;
        }
        .btn-update {
            background: #28a745;
            color: #fff;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: 600;
            text-transform: uppercase;
            cursor: pointer;
            margin-right: 10px;
        }
        .btn-clear {
            background: #dc3545;
            color: #fff;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: 600;
            text-transform: uppercase;
            cursor: pointer;
        }
        .btn-checkout {
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
        }
        .btn-checkout:hover {
            background: #8e7424;
        }
    </style>
</head>

<body>
    <?php include_once('includes/header.php');?>
    
    <!-- Cart Header -->
    <div class="cart-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h1 class="cart-title">Shopping Cart</h1>
                </div>
            </div>
        </div>
    </div>
   
    <!-- Cart Section -->
    <div class="cart-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?php
                    // Get cart items from database
                    $session_id = isset($_SESSION['customer_session']) ? $_SESSION['customer_session'] : '';
                    $cart_items = mysqli_query($con, "SELECT * FROM tblcart WHERE SessionID='$session_id'");
                    
                    if(mysqli_num_rows($cart_items) > 0): ?>
                        <form method="post">
                            <table class="cart-table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $item_total = 0;
                                    while($item = mysqli_fetch_assoc($cart_items)): 
                                        $item_total = $item['ProductPrice'] * $item['Quantity'];
                                        $total_amount += $item_total;
                                        $total_items += $item['Quantity'];
                                    ?>
                                    <tr>
                                        <td>
                                            <img src="images/<?php echo $item['ProductImage']; ?>" alt="<?php echo $item['ProductName']; ?>" class="cart-product-img">
                                        </td>
                                        <td>
                                            <div class="cart-product-name"><?php echo $item['ProductName']; ?></div>
                                        </td>
                                        <td>
                                            <span class="cart-product-price">₹<?php echo number_format($item['ProductPrice'], 2); ?></span>
                                        </td>
                                        <td>
                                            <input type="number" name="quantity[<?php echo $item['ProductID']; ?>]" value="<?php echo $item['Quantity']; ?>" min="1" class="quantity-input">
                                        </td>
                                        <td>
                                            <span class="cart-product-price">₹<?php echo number_format($item_total, 2); ?></span>
                                        </td>
                                        <td>
                                            <a href="cart.php?remove=<?php echo $item['ProductID']; ?>" class="remove-btn">
                                                <i class="fa fa-trash"></i> Remove
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                            
                            <div class="cart-actions">
                                <button type="submit" name="update_cart" class="btn-update">
                                    <i class="fa fa-refresh"></i> Update Cart
                                </button>
                                <button type="submit" name="clear_cart" class="btn-clear" onclick="return confirm('Are you sure you want to clear the cart?')">
                                    <i class="fa fa-trash"></i> Clear Cart
                                </button>
                            </div>
                        </form>
                        
                        <div class="cart-summary">
                            <h3 style="margin-bottom: 20px; color: #18150d;">Order Summary</h3>
                            <div class="summary-row">
                                <span>Total Items:</span>
                                <span><?php echo $total_items; ?></span>
                            </div>
                            <div class="summary-row">
                                <span>Subtotal:</span>
                                <span>₹<?php echo number_format($total_amount, 2); ?></span>
                            </div>
                            <div class="summary-row">
                                <span>Shipping:</span>
                                <span>Free</span>
                            </div>
                            <div class="summary-row">
                                <span>Grand Total:</span>
                                <span>₹<?php echo number_format($total_amount, 2); ?></span>
                            </div>
                            <a href="checkout.php" class="btn-checkout" style="display: block; text-align: center; text-decoration: none;">
                                <i class="fa fa-credit-card"></i> Proceed to Checkout
                            </a>
                            <a href="products.php" style="display: block; text-align: center; margin-top: 15px; color: #aa9144;">
                                <i class="fa fa-arrow-left"></i> Continue Shopping
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="empty-cart">
                            <i class="fa fa-shopping-cart"></i>
                            <h3>Your cart is empty!</h3>
                            <p>Add some products to your cart and they will appear here.</p>
                            <a href="products.php" class="btn btn-default btn-lg" style="margin-top: 20px;">
                                <i class="fa fa-shopping-bag"></i> Browse Products
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
   
    <?php include_once('includes/footer.php');?>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
</body>

</html>
