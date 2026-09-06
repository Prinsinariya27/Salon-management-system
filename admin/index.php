<?php
session_start();
error_reporting(0);

include('includes/dbconnection.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    
    <title>Men Salon Management System || Home Page</title>
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
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js "></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js "></script>
<![endif]-->
</head>

<body>
    <?php include_once('includes/header.php');?>
    <div class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <h1 class="hero-title">Premium Men's Grooming Experience</h1>
                    <p class="hero-text"><strong>Your Style. Your Statement. Your Confidence.</strong></p>
                    <p class="hero-text">Experience world-class grooming services tailored specifically for the modern man.</p>
                    <a href="appointment.php" class="btn btn-default btn-lg">Book Your Appointment</a>
                    <a href="service-list.php" class="btn btn-white btn-lg" style="margin-left: 10px;">View Services</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Services Section -->
    <div class="space-medium bg-white">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="text-center" style="margin-bottom: 50px;">
                        <h2 class="section-title">Our Premium Services</h2>
                        <h5 class="small-title">Professional Grooming Solutions with Images</h5>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php
                $ret=mysqli_query($con,"select * from tblservices LIMIT 6");
                $serviceImages = ['service-single.jpg', 'post-img-1.jpg', 'post-img-2.jpg', 'left-image.jpg', 'about-img.jpg', 'related-post-1.jpg'];
                $imageIndex = 0;
                while ($row=mysqli_fetch_array($ret)) {
                    $serviceImage = isset($row['ServiceImage']) && !empty($row['ServiceImage']) ? $row['ServiceImage'] : $serviceImages[$imageIndex % count($serviceImages)];
                ?>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="service-block text-center" style="padding: 30px; background: #f8f9fa; border-radius: 10px; margin-bottom: 30px;">
                        <div class="service-img" style="margin-bottom: 20px; overflow: hidden; border-radius: 10px;">
                            <img src="images/<?php echo $serviceImage; ?>" alt="<?php echo $row['ServiceName']; ?>" style="width: 100%; height: 250px; object-fit: cover; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                        </div>
                        <div class="service-icon" style="font-size: 60px; color: #aa9144; margin-bottom: 20px;">
                            <i class="fa fa-scissors"></i>
                        </div>
                        <h3 class="service-title"><?php echo $row['ServiceName']; ?></h3>
                        <p style="color: #666; margin: 15px 0;"><?php echo substr($row['ServiceDescription'], 0, 100); ?>...</p>
                        <div class="price" style="font-size: 28px; color: #aa9144; font-weight: bold;">₹<?php echo $row['ServicePrice']; ?></div>
                        <a href="appointment.php" class="btn btn-default" style="margin-top: 15px;">Book Now</a>
                    </div>
                </div>
                <?php 
                $imageIndex++;
                } ?>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <a href="service-list.php" class="btn btn-primary btn-lg">View All Services</a>
                    <a href="products.php" class="btn btn-default btn-lg" style="margin-left: 10px;">Shop Products</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Why Choose Us Section -->
    <div class="space-medium bg-default">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <img src="images/about-img.jpg" alt="About Us" class="img-responsive" style="border-radius: 10px;">
                </div>
                <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                    <div class="well-block">
                        <?php
                        $ret=mysqli_query($con,"select * from tblpage where PageType='aboutus' ");
                        while ($row=mysqli_fetch_array($ret)) {
                        ?>
                        <h2><?php echo $row['PageTitle']; ?></h2>
                        <h5 class="small-title">BEST EXPERIENCE EVER</h5>
                        <p><?php echo $row['PageDescription']; ?></p>
                        <?php } ?>
                        
                        <div class="row" style="margin-top: 40px;">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="feature-left" style="margin-bottom: 30px;">
                                    <div class="feature-icon" style="float: left; margin-right: 15px;">
                                        <i class="fa fa-users icon-4x icon-default"></i>
                                    </div>
                                    <div class="feature-content" style="overflow: hidden;">
                                        <h4>Expert Stylists</h4>
                                        <p>Our team consists of highly skilled professionals with years of experience.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="feature-left" style="margin-bottom: 30px;">
                                    <div class="feature-icon" style="float: left; margin-right: 15px;">
                                        <i class="fa fa-clock-o icon-4x icon-default"></i>
                                    </div>
                                    <div class="feature-content" style="overflow: hidden;">
                                        <h4>Flexible Timing</h4>
                                        <p>Convenient scheduling to fit your busy lifestyle.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="feature-left" style="margin-bottom: 30px;">
                                    <div class="feature-icon" style="float: left; margin-right: 15px;">
                                        <i class="fa fa-star icon-4x icon-default"></i>
                                    </div>
                                    <div class="feature-content" style="overflow: hidden;">
                                        <h4>Premium Products</h4>
                                        <p>We use only the finest quality products for your grooming.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="feature-left" style="margin-bottom: 30px;">
                                    <div class="feature-icon" style="float: left; margin-right: 15px;">
                                        <i class="fa fa-thumbs-up icon-4x icon-default"></i>
                                    </div>
                                    <div class="feature-content" style="overflow: hidden;">
                                        <h4>Satisfaction Guaranteed</h4>
                                        <p>100% satisfaction guaranteed on all our services.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials Section -->
    <div class="space-medium bg-white">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="text-center" style="margin-bottom: 50px;">
                        <h2 class="section-title">Client Testimonials</h2>
                        <h5 class="small-title">What Our Clients Say</h5>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="testimonial-block text-center" style="background: #f8f9fa; padding: 30px; border-radius: 10px; margin-bottom: 30px;">
                        <div class="testimonial-content" style="margin-bottom: 20px;">
                            <i class="fa fa-quote-left" style="font-size: 40px; color: #aa9144; margin-bottom: 20px;"></i>
                            <p class="testimonial-text" style="font-size: 16px; font-style: italic;">"Best barber shop in town! The stylists are true professionals and always give me exactly the cut I want. Highly recommended!"</p>
                        </div>
                        <div class="testimonial-info">
                            <h4 class="testimonial-name">John Smith</h4>
                            <span class="testimonial-meta">Regular Customer</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="testimonial-block text-center" style="background: #f8f9fa; padding: 30px; border-radius: 10px; margin-bottom: 30px;">
                        <div class="testimonial-content" style="margin-bottom: 20px;">
                            <i class="fa fa-quote-left" style="font-size: 40px; color: #aa9144; margin-bottom: 20px;"></i>
                            <p class="testimonial-text" style="font-size: 16px; font-style: italic;">"Outstanding service and attention to detail. The atmosphere is relaxing and the staff is friendly. My go-to place for grooming."</p>
                        </div>
                        <div class="testimonial-info">
                            <h4 class="testimonial-name">Michael Johnson</h4>
                            <span class="testimonial-meta">Loyal Client</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="testimonial-block text-center" style="background: #f8f9fa; padding: 30px; border-radius: 10px; margin-bottom: 30px;">
                        <div class="testimonial-content" style="margin-bottom: 20px;">
                            <i class="fa fa-quote-left" style="font-size: 40px; color: #aa9144; margin-bottom: 20px;"></i>
                            <p class="testimonial-text" style="font-size: 16px; font-style: italic;">"Professional, punctual, and talented. These guys know what they're doing. I've been coming here for 2 years and never disappointed."</p>
                        </div>
                        <div class="testimonial-info">
                            <h4 class="testimonial-name">David Williams</h4>
                            <span class="testimonial-meta">Satisfied Customer</span>
                        </div>
                    </div>
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
    <!-- sticky header -->
    <script src="js/jquery.sticky.js"></script>
    <script src="js/sticky-header.js"></script>
</body>

</html>
