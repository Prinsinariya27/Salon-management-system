<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['bpmsaid']==0)) {
  header('location:logout.php');
  } else {
    // Update order status
    if(isset($_POST['update_status'])) {
        $order_id = $_POST['order_id'];
        $status = $_POST['status'];
        mysqli_query($con, "UPDATE tblorders SET OrderStatus='$status' WHERE ID='$order_id'");
        echo "<script>alert('Order status updated!');</script>";
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin - Manage Orders</title>
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
                        <h2 class="page-title">Manage Customer Orders</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">All Orders from Cart</div>
                            <div class="panel-body">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Order Number</th>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Qty</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $ret = mysqli_query($con, "SELECT * FROM tblorders ORDER BY OrderDate DESC");
                                        $cnt = 1;
                                        while ($row = mysqli_fetch_array($ret)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $cnt;?></td>
                                            <td><strong><?php echo $row['OrderNumber'];?></strong></td>
                                            <td>
                                                <img src="images/<?php echo $row['ProductImage'];?>" alt="<?php echo $row['ProductName'];?>" 
                                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px; margin-right: 10px;">
                                                <?php echo $row['ProductName'];?>
                                            </td>
                                            <td>₹<?php echo number_format($row['ProductPrice'], 2);?></td>
                                            <td><?php echo $row['Quantity'];?></td>
                                            <td>₹<?php echo number_format($row['TotalAmount'], 2);?></td>
                                            <td>
                                                <?php if($row['OrderStatus'] == 'Confirmed'): ?>
                                                    <span class="label label-success"><?php echo $row['OrderStatus'];?></span>
                                                <?php elseif($row['OrderStatus'] == 'In Cart'): ?>
                                                    <span class="label label-warning"><?php echo $row['OrderStatus'];?></span>
                                                <?php else: ?>
                                                    <span class="label label-info"><?php echo $row['OrderStatus'];?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo date('d-m-Y h:i A', strtotime($row['OrderDate']));?></td>
                                            <td>
                                                <form method="post" style="display: inline;">
                                                    <input type="hidden" name="order_id" value="<?php echo $row['ID'];?>">
                                                    <select name="status" style="padding: 5px; border-radius: 3px;">
                                                        <option value="">Change Status</option>
                                                        <option value="Pending">Pending</option>
                                                        <option value="Confirmed">Confirmed</option>
                                                        <option value="Processing">Processing</option>
                                                        <option value="Completed">Completed</option>
                                                        <option value="Cancelled">Cancelled</option>
                                                    </select>
                                                    <button type="submit" name="update_status" class="btn btn-sm btn-primary">
                                                        <i class="fa fa-check"></i> Update
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php 
                                        $cnt++;
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Statistics -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">Total Orders</div>
                                    <div class="panel-body" style="font-size: 36px; font-weight: bold; text-align: center;">
                                        <?php
                                        $total = mysqli_query($con, "SELECT COUNT(*) as count FROM tblorders");
                                        $t = mysqli_fetch_assoc($total);
                                        echo $t['count'];
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="panel panel-success">
                                    <div class="panel-heading">Confirmed Orders</div>
                                    <div class="panel-body" style="font-size: 36px; font-weight: bold; text-align: center;">
                                        <?php
                                        $confirmed = mysqli_query($con, "SELECT COUNT(*) as count FROM tblorders WHERE OrderStatus='Confirmed'");
                                        $c = mysqli_fetch_assoc($confirmed);
                                        echo $c['count'];
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="panel panel-warning">
                                    <div class="panel-heading">In Cart Items</div>
                                    <div class="panel-body" style="font-size: 36px; font-weight: bold; text-align: center;">
                                        <?php
                                        $cart = mysqli_query($con, "SELECT COUNT(*) as count FROM tblorders WHERE OrderStatus='In Cart'");
                                        $c = mysqli_fetch_assoc($cart);
                                        echo $c['count'];
                                        ?>
                                    </div>
                                </div>
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
