<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['bpmsaid']==0)) {
  header('location:logout.php');
  } else {
    // Delete product
    if(isset($_GET['delid'])) {
        $rid = intval($_GET['delid']);
        $query = mysqli_query($con, "DELETE FROM tblproducts WHERE ID='$rid'");
        echo "<script>alert('Product deleted');</script>"; 
        echo "<script>window.location.href='manage-products.php'</script>";
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin - Manage Products</title>
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
                        <h2 class="page-title">Manage Products</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">All Products</div>
                            <div class="panel-body">
                                <a href="add-products.php" class="btn btn-primary mb-3">
                                    <i class="fa fa-plus"></i> Add New Product
                                </a>
                                
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>Stock</th>
                                            <th>Status</th>
                                            <th>Created Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 13px;">
                                        <?php
                                        $ret = mysqli_query($con, "SELECT * FROM tblproducts ORDER BY ID DESC");
                                        while ($row = mysqli_fetch_array($ret)) {
                                        ?>
                                        <tr style="height: 35px;">
                                            <td>
                                                <img src="images/<?php echo $row['ProductImage'];?>" alt="<?php echo $row['ProductName'];?>" 
                                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                            </td>
                                            <td style="padding: 5px;"><?php echo $row['ProductName'];?></td>
                                            <td style="padding: 5px;"><?php echo $row['Category'];?></td>
                                            <td style="padding: 5px;">₹<?php echo number_format($row['ProductPrice'], 2);?></td>
                                            <td style="padding: 5px;"><?php echo $row['Stock'];?></td>
                                            <td>
                                                <?php if($row['Status'] == 1): ?>
                                                    <span class="label label-success">Active</span>
                                                <?php else: ?>
                                                    <span class="label label-danger">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td style="padding: 5px;"><?php echo date('d-m-Y', strtotime($row['CreationDate']));?></td>
                                            <td style="padding: 5px;">
                                                <a href="edit-products.php?editid=<?php echo $row['ID'];?>" class="btn btn-sm btn-primary">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                                <a href="manage-products.php?delid=<?php echo $row['ID'];?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Do you really want to delete?');">
                                                    <i class="fa fa-trash"></i> Delete
                                                </a>
                                            </td>
                                        </tr>
                                        <?php 
                                        } ?>
                                    </tbody>
                                </table>
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
