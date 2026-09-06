<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['bpmsaid']==0)) {
  header('location:logout.php');
  } else {
    // Add Product
    if(isset($_POST['submit'])) {
        $productname = $_POST['productname'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $category = $_POST['category'];
        $stock = $_POST['stock'];
        $status = $_POST['status'];
        
        // Handle image upload
        if(!empty($_FILES['image']['name'])) {
            $imgName = $_FILES['image']['name'];
            $imgSize = $_FILES['image']['size'];
            $tmp_name = $_FILES['image']['tmp_name'];
            
            if($imgSize > 2000000) { // Max 2MB
                echo "<script>alert('Image size should be less than 2MB');</script>";
            } else {
                move_uploaded_file($tmp_name, "images/" . $imgName);
                $query = mysqli_query($con, "INSERT INTO tblproducts(ProductName, ProductDescription, ProductPrice, ProductImage, Category, Stock, Status) 
                            VALUES('$productname', '$description', '$price', '$imgName', '$category', '$stock', '$status')");
                
                if($query) {
                    echo "<script>alert('Product added successfully!');</script>";
                    echo "<script>window.location.href='manage-products.php'</script>";
                } else {
                    echo "<script>alert('Something went wrong. Please try again');</script>";
                }
            }
        } else {
            echo "<script>alert('Please select an image');</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin - Add Products</title>
    <link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
    <link href="css/style.css" rel='stylesheet' type='text/css' />
    <link href="css/font-awesome.css" rel="stylesheet">
</head>

<body>
    <?php include_once('includes/sidebar.php');?>
    <div class="page-content">
        <?php include_once('includes/header.php');?>
        
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="page-title">Add New Product</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Product Information</div>
                            <div class="panel-body">
                                <form method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Product Name</label>
                                                <input type="text" name="productname" class="form-control" required="true">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Category</label>
                                                <select name="category" class="form-control" required="true">
                                                    <option value="">Select Category</option>
                                                    <option value="Hair Care">Hair Care</option>
                                                    <option value="Beard Care">Beard Care</option>
                                                    <option value="Skin Care">Skin Care</option>
                                                    <option value="Shaving">Shaving</option>
                                                    <option value="Styling">Styling</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Price (₹)</label>
                                                <input type="number" name="price" class="form-control" required="true" min="0" step="0.01">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Stock Quantity</label>
                                                <input type="number" name="stock" class="form-control" required="true" min="0">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Product Description</label>
                                        <textarea name="description" class="form-control" rows="5" required="true"></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Product Image</label>
                                        <input type="file" name="image" class="form-control" accept="image/*" required="true">
                                        <small>Recommended size: 800x600px, Max 2MB</small>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" class="form-control" required="true">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <button type="submit" name="submit" class="btn btn-primary">Add Product</button>
                                        <a href="manage-products.php" class="btn btn-default">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>

</html>

<?php } ?>
