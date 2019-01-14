<?php include_once 'functions.php'?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>News</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Course Project">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <?php include_once 'includes/head.php'?>
</head>
<body>

<div class="super_container">

    <!-- Header -->

    <?php include_once 'includes/navbar.php'?>

    <!-- Home -->

    <div class="home">
        <div class="home_background_container prlx_parent">
            <div class="home_background prlx" style="background-image:url(images/search_background.jpg)"></div>
        </div>
        <div class="home_content">
            <h1>News & Events</h1>
        </div>
    </div>

    <!-- Register -->

    <div class="register" style="padding-top: 50px">

        <div class="container">

            <div class="row row-eq-height">
                <div class="col-lg-6 nopadding">

                    <!-- Register -->

                    <div class="register_section d-flex flex-column align-items-center justify-content-center">
                        <div class="register_content text-center">
                            <h1 class="register_title">Here are some of our events</h1>
                            <p class="register_text"> for any enquiries write to us via our email</p>

                        </div>
                    </div>

                </div>

                <div class="col-lg-6 nopadding">

                    <!-- Search -->

                    <div class="search_section d-flex flex-column align-items-center justify-content-center">
                        <div class="search_background" style="background-image:url(images/search_background.jpg);"></div>
                        <div class="search_content text-center">
                            <h1 class="search_title">Click to view more details</h1>
                            <div class="col-lg-">

                                <!-- Accordions -->
                                <div class="elements_accordions">

                                    <?php $events=display('news_events','5') ?>
                                    <?php while($disp_event=mysqli_fetch_array($events)): ?>
                                        <div class="accordion_container">
                                            <div class="accordion d-flex flex-row align-items-center"> <Phasell></Phasell><?php echo $disp_event['title'].','.'&nbsp'.$disp_event['time_date'] ?></div>
                                            <div class="accordion_panel">
                                                <p style="text-overflow:ellipsis;word-wrap:break-word;overflow:hidden;max-height:auto;line-height:1.8em;"><?php echo $disp_event['details']?></p>
                                                <img style="width: 100px; height: 80px;" class="card-img-top" src="<?php echo 'admin/images/'.$disp_event['image'] ?>" alt="machakos high school">
                                            </div>
                                        </div>
                                    <?php endwhile; ?>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Elements -->



    <!-- Footer -->

    <?php include_once 'includes/footer.php'?>

</div>

<script src="js/jquery-3.2.1.min.js"></script>
<script src="styles/bootstrap4/popper.js"></script>
<script src="styles/bootstrap4/bootstrap.min.js"></script>
<script src="plugins/greensock/TweenMax.min.js"></script>
<script src="plugins/greensock/TimelineMax.min.js"></script>
<script src="plugins/scrollmagic/ScrollMagic.min.js"></script>
<script src="plugins/greensock/animation.gsap.min.js"></script>
<script src="plugins/greensock/ScrollToPlugin.min.js"></script>
<script src="plugins/progressbar/progressbar.min.js"></script>
<script src="plugins/scrollTo/jquery.scrollTo.min.js"></script>
<script src="plugins/easing/easing.js"></script>
<script src="js/elements_custom.js"></script>

</body>
</html>