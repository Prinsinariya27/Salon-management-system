<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid']==0)) {
  header('location:logout.php');
  exit();
} 

// Fetch sales data for the chart
// Today's sales
$todysale = 0;
$query6=mysqli_query($con,"select tblinvoice.ServiceId as ServiceId, tblservices.Cost
 from tblinvoice 
  join tblservices  on tblservices.ID=tblinvoice.ServiceId where date(PostingDate)=CURDATE();");
while($row=mysqli_fetch_array($query6))
{
$todays_sale=$row['Cost'];
$todysale+=$todays_sale;
}

// Yesterday's sales
$yesterdaysale = 0;
$query7=mysqli_query($con,"select tblinvoice.ServiceId as ServiceId, tblservices.Cost
 from tblinvoice 
  join tblservices  on tblservices.ID=tblinvoice.ServiceId where date(PostingDate)=CURDATE()-1;");
while($row7=mysqli_fetch_array($query7))
{
$yesterdays_sale=$row7['Cost'];
$yesterdaysale+=$yesterdays_sale;
}

// Last 7 days sales
$sevendaysale = 0;
$query8=mysqli_query($con,"select tblinvoice.ServiceId as ServiceId, tblservices.Cost
 from tblinvoice 
  join tblservices  on tblservices.ID=tblinvoice.ServiceId where date(PostingDate)>=(DATE(NOW()) - INTERVAL 7 DAY);");
while($row8=mysqli_fetch_array($query8))
{
$sevendays_sale=$row8['Cost'];
$sevendaysale+=$sevendays_sale;
}

// Last 30 days sales
$thirtydaysale = 0;
$query30=mysqli_query($con,"select tblinvoice.ServiceId as ServiceId, tblservices.Cost
 from tblinvoice 
  join tblservices  on tblservices.ID=tblinvoice.ServiceId where date(PostingDate)>=(DATE(NOW()) - INTERVAL 30 DAY);");
while($row30=mysqli_fetch_array($query30))
{
$thirtydays_sale=$row30['Cost'];
$thirtydaysale+=$thirtydays_sale;
}

// Total sales
$totalsale = 0;
$query9=mysqli_query($con,"select tblinvoice.ServiceId as ServiceId, tblservices.Cost
 from tblinvoice 
  join tblservices  on tblservices.ID=tblinvoice.ServiceId");
while($row9=mysqli_fetch_array($query9))
{
$total_sale=$row9['Cost'];
$totalsale+=$total_sale;
}


?>
<!DOCTYPE HTML>
<html>
<head>
<title>MSMS | Admin Dashboard</title>

<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- Bootstrap Core CSS -->
<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
<!-- Custom CSS -->
<link href="css/style.css" rel='stylesheet' type='text/css' />
<!-- font CSS -->
<!-- font-awesome icons -->
<link href="css/font-awesome.css" rel="stylesheet"> 
<!-- //font-awesome icons -->
 <!-- js-->
<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/modernizr.custom.js"></script>
<!--webfonts-->
<link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,300,300italic,400italic,700,700italic' rel='stylesheet' type='text/css'>
<!--//webfonts--> 
<!--animate-->
<link href="css/animate.css" rel="stylesheet" type="text/css" media="all">
<script src="js/wow.min.js"></script>
	<script>
		 new WOW().init();
	</script>
<!--//end-animate-->

<!--Calender-->
<link rel="stylesheet" href="css/clndr.css" type="text/css" />
<script src="js/underscore-min.js" type="text/javascript"></script>
<script src= "js/moment-2.2.1.js" type="text/javascript"></script>
<script src="js/clndr.js" type="text/javascript"></script>
<script src="js/site.js" type="text/javascript"></script>
<!--End Calender-->
<!-- Metis Menu -->
<script src="js/metisMenu.min.js"></script>
<script src="js/custom.js"></script>
<link href="css/custom.css" rel="stylesheet">
<!--//Metis Menu -->
</head> 
<body class="cbp-spmenu-push">
<div class="main-content">
		
		 <?php include_once('includes/sidebar.php');?>
		
	<?php include_once('includes/header.php');?>
		<!-- main content start-->
		<div id="page-wrapper" class="row calender widget-shadow">
			<div class="main-page">
				
			
				<div class="row calender widget-shadow">
					<div class="row-one">
					<div class="col-md-4 widget">
						<?php $query1=mysqli_query($con,"Select * from tblcustomers");
$totalcust=mysqli_num_rows($query1);
?>
						<div class="stats-left ">
							<h5>Total</h5>
							<h4>Customer</h4>
						</div>
						<div class="stats-right">
							<label> <?php echo $totalcust;?></label>
						</div>
						<div class="clearfix"> </div>	
					</div>
					<div class="col-md-4 widget states-mdl">
						<?php $query2=mysqli_query($con,"Select * from tblappointment");
$totalappointment=mysqli_num_rows($query2);
?>
						<div class="stats-left">
							<h5>Total</h5>
							<h4>Appointment</h4>
						</div>
						<div class="stats-right">
							<label> <?php echo $totalappointment;?></label>
						</div>
						<div class="clearfix"> </div>	
					</div>
					<div class="col-md-4 widget states-last">
						<?php $query5=mysqli_query($con,"Select * from  tblservices");
$totalser=mysqli_num_rows($query5);
?>
						<div class="stats-left">
							<h5>Total</h5>
							<h4>Services</h4>
						</div>
						<div class="stats-right">
							<label> <?php echo $totalser;?></label>
						</div>
						<div class="clearfix"> </div>	
					</div>
					<div class="clearfix"> </div>	
				</div>
						
					</div>

				<div class="row calender widget-shadow">
					<div class="row-one">
					<div class="col-md-4 widget">
						<?php
$todysale = 0;
//todays sale
 $query6=mysqli_query($con,"select tblinvoice.ServiceId as ServiceId, tblservices.Cost
 from tblinvoice 
  join tblservices  on tblservices.ID=tblinvoice.ServiceId where date(PostingDate)=CURDATE();");
while($row=mysqli_fetch_array($query6))
{
$todays_sale=$row['Cost'];
$todysale+=$todays_sale;

}
 ?>
						<div class="stats-left">
							<h5>Today</h5>
							<h4>Sales</h4>
						</div>
						<div class="stats-right">
							<label> <?php 
if($todysale==''):
echo "0";
else:
echo $todysale;
endif;
?></label>
						</div>
						<div class="clearfix"> </div>	
					</div>
					<div class="col-md-4 widget states-mdl">
						<?php $query10=mysqli_query($con,"Select * from tblproducts");
$totalprod=mysqli_num_rows($query10);
?>
						<div class="stats-left">
							<h5>Total</h5>
							<h4>Products</h4>
						</div>
						<div class="stats-right">
							<label> <?php echo $totalprod;?></label>
						</div>
						<div class="clearfix"> </div>	
					</div>
					<div class="col-md-4 widget states-last">
						<?php
$totalsale = 0;
//Total Sale
 $query9=mysqli_query($con,"select tblinvoice.ServiceId as ServiceId, tblservices.Cost
 from tblinvoice 
  join tblservices  on tblservices.ID=tblinvoice.ServiceId");
while($row9=mysqli_fetch_array($query9))
{
$total_sale=$row9['Cost'];
$totalsale+=$total_sale;

}
 ?>
						<div class="stats-left">
							<h5>Total</h5>
							<h4>Sales</h4>
						</div>
						<div class="stats-right">
							<label>
 <?php 
if($totalsale==''):
echo "0";
else:
echo $totalsale;
endif;
?>								


							</label>
						</div>
						<div class="clearfix"> </div>	
					</div>
					<div class="clearfix"> </div>	
				</div>
							
					</div>
				</div>
						
				
						
				<div class="clearfix"> </div>
			</div>
		</div>
		<!--footer-->
		<?php include_once('includes/footer.php');?>
        <!--//footer-->
	</div>
	<!-- Classie -->
		<script src="js/classie.js"></script>
		<script>
			var menuLeft = document.getElementById( 'cbp-spmenu-s1' ),
				showLeftPush = document.getElementById( 'showLeftPush' ),
				body = document.body;
				
			showLeftPush.onclick = function() {
				classie.toggle( this, 'active' );
				classie.toggle( body, 'cbp-spmenu-push-toright' );
				classie.toggle( menuLeft, 'cbp-spmenu-open' );
				disableOther( 'showLeftPush' );
			};
			

			function disableOther( button ) {
				if( button !== 'showLeftPush' ) {
					classie.toggle( showLeftPush, 'disabled' );
				}
			}
		</script>
	<!--scrolling js-->
	<script src="js/jquery.nicescroll.js"></script>
	<script src="js/scripts.js"></script>
	<!--//scrolling js-->
	<!-- Bootstrap Core JavaScript -->
   <script src="js/bootstrap.js"> </script>
   

</body>
</html>