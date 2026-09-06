<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['bpmsaid']==0)) {
  header('location:logout.php');
  } else {
    // Update Product
    if(isset($_POST['submit'])) {
        $pid = $_GET['editid'];
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
                // Delete old image
                $oldimg = mysqli_query($con, "SELECT ProductImage FROM tblproducts WHERE ID='$pid'");
                $row = mysqli_fetch_array($oldimg);
                $oldImagePath = "images/" . $row['ProductImage'];
                if(file_exists($oldImagePath) && !empty($row['ProductImage'])) {
                    unlink($oldImagePath);
                }
                
                move_uploaded_file($tmp_name, "images/" . $imgName);
                $query = mysqli_query($con, "UPDATE tblproducts SET ProductName='$productname', 
                            ProductDescription='$description', ProductPrice='$price', 
                            ProductImage='$imgName', Category='$category', Stock='$stock', 
                            Status='$status' WHERE ID='$pid'");
                
                if($query) {
                    echo "<script>alert('Product updated successfully!');</script>";
                    echo "<script>window.location.href='manage-products.php'</script>";
                } else {
                    echo "<script>alert('Something went wrong. Please try again');</script>";
                }
            }
        } else {
            // Update without changing image
            $query = mysqli_query($con, "UPDATE tblproducts SET ProductName='$productname', 
                        ProductDescription='$description', ProductPrice='$price', 
                        Category='$category', Stock='$stock', Status='$status' WHERE ID='$pid'");
            
            if($query) {
                echo "<script>alert('Product updated successfully!');</script>";
                echo "<script>window.location.href='manage-products.php'</script>";
            } else {
                echo "<script>alert('Something went wrong. Please try again');</script>";
            }
        }
    }
    
    // Get product details
    $pid = $_GET['editid'];
    $ret = mysqli_query($con, "SELECT * FROM tblproducts WHERE ID='$pid'");
    $row = mysqli_fetch_array($ret);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin - Edit Products</title>
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
                        <h2 class="page-title">Edit Product</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Product Information</div>
                            <div class="panel-body">
                                <form method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Product Name</label>
                                                <input type="text" name="productname" class="form-control" required="true" value="<?php echo $row['ProductName'];?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Category</label>
                                                <select name="category" class="form-control" required="true">
                                                    <option value="">Select Category</option>
                                                    <option value="Hair Care" <?php if($row['Category']=='Hair Care') echo 'selected'; ?>>Hair Care</option>
                                                    <option value="Beard Care" <?php if($row['Category']=='Beard Care') echo 'selected'; ?>>Beard Care</option>
                                                    <option value="Skin Care" <?php if($row['Category']=='Skin Care') echo 'selected'; ?>>Skin Care</option>
                                                    <option value="Shaving" <?php if($row['Category']=='Shaving') echo 'selected'; ?>>Shaving</option>
                                                    <option value="Styling" <?php if($row['Category']=='Styling') echo 'selected'; ?>>Styling</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Price (₹)</label>
                                                <input type="number" name="price" class="form-control" required="true" min="0" step="0.01" value="<?php echo $row['ProductPrice'];?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Stock Quantity</label>
                                                <input type="number" name="stock" class="form-control" required="true" min="0" value="<?php echo $row['Stock'];?>">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Product Description</label>
                                        <textarea name="description" class="form-control" rows="5" required="true"><?php echo $row['ProductDescription'];?></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Current Image</label><br>
                                        <img src="images/<?php echo $row['ProductImage'];?>" alt="<?php echo $row['ProductName'];?>" 
                                             style="width: 200px; height: 150px; object-fit: cover; border-radius: 5px; margin-bottom: 10px;">
                                        <label>Upload New Image (optional)</label>
                                        <input type="file" name="image" class="form-control" accept="image/*">
                                        <small>Recommended size: 800x600px, Max 2MB. Leave empty to keep current image.</small>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" class="form-control" required="true">
                                            <option value="1" <?php if($row['Status']==1) echo 'selected'; ?>>Active</option>
                                            <option value="0" <?php if($row['Status']==0) echo 'selected'; ?>>Inactive</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <button type="submit" name="submit" class="btn btn-primary">Update Product</button>
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
