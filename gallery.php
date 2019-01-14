<?php include_once 'functions.php'?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Gallery</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Course Project">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
    <link href="plugins/fontawesome-free-5.0.1/css/fontawesome-all.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="styles/courses_styles.css">
    <link rel="stylesheet" type="text/css" href="styles/courses_responsive.css">
    <?php include_once 'includes/head.php'?>
</head>
<body>

<div class="super_container">

    <!-- Header -->
    <?php include_once 'includes/navbar.php'?>

    <!-- Home -->

    <div class="home">
        <div class="home_background_container prlx_parent">
            <div class="home_background prlx" style="background-image:url(images/course_6.jpg)"></div>
        </div>
        <div class="home_content">
            <h1>Gallery</h1>
        </div>
    </div>


    <!-- Popular -->

    <div class="popular page_section" style="padding-top: 80px">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="section_title text-center">
                        <h1>Memorable moments</h1>
                    </div>
                </div>
            </div>

            <div class="row course_boxes">
                <?php $pics=display('gallery',3) ?>
                <?php while($disp_pics=mysqli_fetch_array($pics)): ?>
                <!-- Popular Course Item -->
                <div class="col-lg-4 course_box">
                    <div class="card">
                        <img class="card-img-top" src="<?php echo 'images/'.$disp_pics['image'] ?>" alt="https://unsplash.com/@kellybrito">
                    </div>
                </div>
                <?php endwhile; ?>





                <!-- Accordions -->
                <div class="elements_accordions" >

                    <div class="accordion_container" >
                        <div class="container">
                        <div class="accordion d-flex flex-row align-items-center" style="width: 350px;position: relative; margin-top: 50px"> <Phasell></Phasell>Click to view more images.</div>
                        <div class="accordion_panel">

                            <div class="row course_boxes">
                                <?php $extra_pics=display('gallery',30) ?>
                                <?php while($disp_e_pics=mysqli_fetch_array($extra_pics)): ?>
                                <div class="col-lg-4 course_box">
                                    <div class="card">
                                        <img class="card-img-top" src="<?php echo 'images/'.$disp_e_pics['image'] ?>" alt="https://unsplash.com/@kimberlyfarmer">

                                    </div>
                                </div>
                                <?php endwhile;?>

                            </div>
                        </div>

                        </div>
                    </div>
                </div>


            </div>

        </div>
    </div>
</div>

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
<script src="plugins/scrollTo/jquery.scrollTo.min.js"></script>
<script src="plugins/easing/easing.js"></script>
<script src="js/courses_custom.js"></script>
<script src="js/elements_custom.js"></script>

</body>
</html>