<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Add to cart functionality - Save to database
if(isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $quantity = 1;
    $total_amount = $product_price * $quantity;
    
    // Generate order number
    $order_number = 'ORD-' . date('Ymd') . '-' . rand(1000, 9999);
    
    // Get session ID or create new one
    if(!isset($_SESSION['customer_session'])) {
        $_SESSION['customer_session'] = session_id();
    }
    $session_id = $_SESSION['customer_session'];
    
    // Check if product already exists in cart table
    $check_query = mysqli_query($con, "SELECT * FROM tblcart WHERE SessionID='$session_id' AND ProductID='$product_id'");
    
    if(mysqli_num_rows($check_query) > 0) {
        // Update quantity if already in cart
        mysqli_query($con, "UPDATE tblcart SET Quantity=Quantity+1, AddedDate=CURRENT_TIMESTAMP WHERE SessionID='$session_id' AND ProductID='$product_id'");
    } else {
        // Insert new cart item to database
        $insert_query = mysqli_query($con, "INSERT INTO tblcart(SessionID, ProductID, ProductName, ProductPrice, ProductImage, Quantity) 
                            VALUES('$session_id', '$product_id', '$product_name', '$product_price', '$product_image', '$quantity')");
    }
    
    // Also save to orders table for tracking
    $order_query = mysqli_query($con, "INSERT INTO tblorders(OrderNumber, ProductID, ProductName, ProductPrice, ProductImage, Quantity, TotalAmount, OrderStatus) 
                        VALUES('$order_number', '$product_id', '$product_name', '$product_price', '$product_image', '$quantity', '$total_amount', 'In Cart')");
    
    echo "<script>alert('Product added to cart successfully!');</script>";
    echo "<script>window.location.href='products.php'</script>";
}

// Calculate cart count from database
$cart_count = 0;
if(isset($_SESSION['customer_session'])) {
    $session_id = $_SESSION['customer_session'];
    $cart_query = mysqli_query($con, "SELECT SUM(Quantity) as total FROM tblcart WHERE SessionID='$session_id'");
    $cart_result = mysqli_fetch_assoc($cart_query);
    $cart_count = $cart_result['total'] ? $cart_result['total'] : 0;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    
    <title>Men Salon - Products</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i%7cMontserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <!-- Style -->
    <link href="css/style.css" rel="stylesheet">
    <!-- Custom CSS for products -->
    <style>
        .product-card {
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            margin-bottom: 30px;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        .product-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .product-card:hover .product-image {
            transform: scale(1.05);
        }
        .product-info {
            padding: 20px;
        }
        .product-title {
            font-size: 18px;
            font-weight: 600;
            color: #18150d;
            margin-bottom: 10px;
        }
        .product-description {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
            line-height: 1.6;
        }
        .product-price {
            font-size: 24px;
            color: #aa9144;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .add-to-cart-btn {
            width: 100%;
            padding: 12px;
            background-color: #aa9144;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .add-to-cart-btn:hover {
            background-color: #8e7424;
        }
        .cart-icon-wrapper {
            position: relative;
            display: inline-block;
        }
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ff0000;
            color: #fff;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        .products-header {
            background: linear-gradient(rgba(36, 39, 38, 0.8), rgba(36, 39, 38, 0.8)), rgba(36, 39, 38, 0.8) url(images/page-header.jpg) no-repeat center; 
            background-size: cover;
            padding: 80px 0;
            text-align: center;
            color: #fff;
        }
        .products-title {
            font-size: 42px;
            font-weight: 600;
            margin-bottom: 10px;
            letter-spacing: -1px;
        }
        .products-subtitle {
            font-size: 18px;
            opacity: 0.9;
        }
    </style>
</head>

<body>
    <?php include_once('includes/header.php');?>
    
    <!-- Products Header -->
    <div class="products-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h1 class="products-title">Our Premium Products</h1>
                    <p class="products-subtitle">Professional grooming products for the modern man - 35+ Products Available!</p>
                </div>
            </div>
        </div>
    </div>
   
    <!-- Products Section -->
    <div class="space-medium bg-white">
        <div class="container">
            <div class="row">
                <?php
                // Fetch products from database
                $ret = mysqli_query($con, "SELECT * FROM tblproducts WHERE Status=1 ORDER BY ID DESC");
                if(mysqli_num_rows($ret) > 0) {
                    while($row = mysqli_fetch_array($ret)) {
                ?>
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <div class="product-card">
                        <div style="overflow: hidden;">
                            <img src="images/<?php echo $row['ProductImage']; ?>" alt="<?php echo $row['ProductName']; ?>" class="product-image" style="height: 280px;">
                        </div>
                        <div class="product-info">
                            <h3 class="product-title" style="font-size: 16px;"><?php echo $row['ProductName']; ?></h3>
                            <p class="product-description" style="font-size: 13px;"><?php echo substr($row['ProductDescription'], 0, 70); ?>...</p>
                            <div class="product-price">₹<?php echo number_format($row['ProductPrice'], 2); ?></div>
                            <form method="post">
                                <input type="hidden" name="product_id" value="<?php echo $row['ID']; ?>">
                                <input type="hidden" name="product_name" value="<?php echo $row['ProductName']; ?>">
                                <input type="hidden" name="product_price" value="<?php echo $row['ProductPrice']; ?>">
                                <input type="hidden" name="product_image" value="<?php echo $row['ProductImage']; ?>">
                                <button type="submit" name="add_to_cart" class="add-to-cart-btn" style="padding: 10px; font-size: 13px;">
                                    <i class="fa fa-shopping-cart"></i> Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php 
                    }
                } else {
                    echo '<div class="col-lg-12 text-center"><p>No products available at the moment.</p></div>';
                }
                ?>
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
    <!-- sticky header -->
    <script src="js/jquery.sticky.js"></script>
    <script src="js/sticky-header.js"></script>
</body>

</html>
