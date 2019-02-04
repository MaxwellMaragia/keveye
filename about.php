<?php include_once 'functions.php'?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Course - Courses</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Course Project">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
    <link href="plugins/fontawesome-free-5.0.1/css/fontawesome-all.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="styles/courses_styles.css">
    <link rel="stylesheet" type="text/css" href="styles/courses_responsive.css">
</head>
<body>

<div class="super_container">

    <!-- Header -->
    <?php include_once 'includes/navbar.php'?>

    <!-- Home -->

    <div class="home">
        <div class="home_background_container prlx_parent">
            <div class="home_background prlx" style="background-image:url(images/about.jpg)"></div>
        </div>
        <div class="home_content">
            <h1>About us</h1>
        </div>
    </div>

    <!-- Icon Boxes -->

    <div class="icon_boxes" style="padding-top: 80px">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="section_title text-center">
                        <h1>Our History</h1>
                    </div>
                </div>
            </div>

            <div class="row icon_boxes_container">

                <div class="col-lg-12 icon_box text-left d-flex flex-column align-items-start justify-content-start">

                    <p>Historical background<br>
                      <span style="color: #8B4513;">Friends School Keveye Girls’ was established as a mixed secondary school in 1974 under the headship of the then Keveye Primary School head teacher Mr. Mathias Iravonga under the sponsorship of the Quakers Church.
                      </span><br>
                        Transformation and development<br>
                        <span style="color:#8B4513;">The school began from scratch with no physical facilities of its own. In the initial stages, it shared a tuition block with Keveye Primary School which was a host to Form 1&2 classes.

                         The transformation process began in 1991, when the school converted into a Girls school with the phasing out of boys who were relocated to neighboring schools namely Chavakali, Mbale, Wangulu and Chandumba secondary schools. Due to focused leadership, government and community support, the young institution grew in leaps and bounds to its present status.

                         Today, the school stands on an 8-acre piece of land with a total population of 1580. It is officially registered as an Extra County 7 streamed girls’ school with enrolment of students from all over the Country. Like any other Public School, its mandate is to offer the 8.4.4 school curriculum of Education and any other planned and/or informal activities. The school is managed by B.O.M and assisted by the PA whose main objective is to raise funds for the schools’ development programmes.
                        </span><br>

                        Geographical location<br>
                        <span style="color:#8B4513;">Located in Vihiga County, Western Region of Kenya. 2Km from Chavakali market along Kisumu Kakamega highway.
                        </span><br>


                    </p>


                </div>

                <div class="col-lg-4 icon_box text-left d-flex flex-column align-items-start justify-content-start" style="padding-top: 30px">
                    <div class="icon_container d-flex flex-column justify-content-end">
                        <span class="fa fa-eye" style="color: #4285f4;"></span>
                    </div>
                    <h3 style="color:#8B4513;">Vision</h3>
                    <p>To produce quality citizens in behavior and academic performance.</p>
                </div>

                <div class="col-lg-4 icon_box text-left d-flex flex-column align-items-start justify-content-start" style="padding-top: 30px">
                    <div class="icon_container d-flex flex-column justify-content-end">
                        <span class="fa fa-fire" style="color: #4285f4;"></span>
                    </div>
                    <h3 style="color:#8B4513;">Mission</h3>
                    <p>Performance through hard work and proper time management to ensure each and every individual who joins MachakosSchool achieves full positive potential.</p>
                </div>

                <div class="col-lg-4 icon_box text-left d-flex flex-column align-items-start justify-content-start" style="padding-top: 30px">
                    <div class="icon_container d-flex flex-column justify-content-end">
                        <span class="fa fa-fire" style="color: #4285f4;"></span>
                    </div>
                    <h3 style="color:#8B4513;">Motto</h3>
                    <p>Uiwi mbee –translated “Wisdom ahead”</p>
                </div>


            </div>


        </div>
    </div>

    <!-- Popular -->

<!--    <div class="popular page_section" style="padding-top: 20px">-->
<!--        <div class="container">-->
<!--            <div class="row">-->
<!--                <div class="col">-->
<!--                    <div class="section_title text-center">-->
<!--                        <h1>Administration</h1>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!---->
<!--            <div class="row course_boxes">-->
<!---->
<!--                --><?php //$team=display('team','6') ?>
<!--                --><?php //while($disp_team=mysqli_fetch_array($team)): ?>
<!--                    <!-- Popular Course Item -->-->
<!--                    <div class="col-lg-4 course_box">-->
<!--                        <div class="card">-->
<!--                            <img class="card-img-top" src="--><?php //echo $disp_team['image']; ?><!--" alt="https://unsplash.com/@kellybrito">-->
<!---->
<!--                            <div class="price_box d-flex flex-row align-items-center">-->
<!---->
<!--                                <div class="course_author_name">--><?php //echo $disp_team['name']; ?><!--, <span>--><?php //echo $disp_team['title']; ?><!--</span></div>-->
<!--                                <div class="course_price d-flex flex-column align-items-center justify-content-center"></div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                --><?php //endwhile; ?>

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

</body>
</html>