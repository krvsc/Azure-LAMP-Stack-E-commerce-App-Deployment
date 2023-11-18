<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Azure TechScript Mall</title>
    <!-- Favicon -->
    <link rel="icon" href="img/favicon.png" type="image/png" />
    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Icon CSS-->
    <link rel="stylesheet" href="vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendors/linearicons/linearicons-1.0.0.css">
    <!-- Animations CSS-->
    <link rel="stylesheet" href="vendors/wow-js/animate.css">
    <!-- owl_carousel-->
    <link rel="stylesheet" href="vendors/owl_carousel/owl.carousel.css">
    <!-- Theme style CSS -->
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <!--==========Main Header==========-->
    <header class="main_header_area">
        <!-- ... (unchanged) ... -->
    </header>
    <!--==========Main Header==========-->

    <!--==========Slider area==========-->
    <section class="slider_area row m0">
        <!-- ... (unchanged) ... -->
    </section>
    <!--==========End Slider area==========-->

    <section class="best_business_area row">
        <div class="check_tittle wow fadeInUp" data-wow-delay="0.7s" id="product-list">
            <h2>Product List</h2>
        </div>
        <div class="row it_works">
            <?php
            $link = mysqli_connect('<vm_private_ip>', 'ecomuser', 'ecompassword', 'ecomdb');
            if ($link) {
                $res = mysqli_query($link, "select * from products;");
                while ($row = mysqli_fetch_assoc($res)) {
            ?>
            <div class="col-md-3 col-sm-6 business_content">
                <?php echo '<img src="img/' . $row['ImageUrl'] . '" alt="">' ?>
                <div class="media">
                    <div class="media-left">
                        <!-- ... (unchanged) ... -->
                    </div>
                    <div class="media-body">
                        <a href="#"><?php echo $row['Name'] ?></a>
                        <p>Purchase <?php echo $row['Name'] ?> at the lowest price <span><?php echo $row['Price'] ?>$</span></p>
                    </div>
                </div>
            </div>
            <?php
                }
            } else {
            ?>
            <div style="width: 100%">
                <div class="error-content">
                    <h1>Database connection error</h1>
                    <p>
                    <?php
                        echo mysqli_connect_errno() . ":" . mysqli_connect_error();
                    ?>
                    </p>
                </div>
            </div>
            <?php
            }
            ?>
        </div>
    </section>

    <footer class="footer_area row">
        <div class="container custom-container">
            <!-- ... (unchanged) ... -->
            <div class="copy_right_area">
                <!-- Removed copyright notice as requested -->
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="js/jquery-1.12.4.min.js"></script>
    <!-- Bootstrap -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Wow js -->
    <script src="vendors/wow-js/wow.min.js"></script>
    <!-- Wow js -->
    <script src="vendors/Counter-Up/waypoints.min.js"></script>
    <script src="vendors/Counter-Up/jquery.counterup.min.js"></script>
    <!-- Stellar js -->
    <script src="vendors/stellar/jquery.stellar.js"></script>
    <!-- owl_carousel js -->
    <script src="vendors/owl_carousel/owl.carousel.min.js"></script>
    <!-- Theme js -->
    <script src="js/theme.js"></script>
</body>
</html>
