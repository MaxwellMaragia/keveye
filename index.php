<?php include_once 'functions.php'?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <title>FRIENDS SCHOOL KEVEYE GIRLS</title>
    <?php include_once 'includes/head.php'?>
</head>
<body>

<div class="super_container">

    <?php include_once 'includes/navbar.php';?>


    <?php
    $pic = display("carousel_pics","3");

    ?>
    <!-- Home -->

    <div class="home">

        <!-- Hero Slider -->
        <div class="hero_slider_container">
            <div class="hero_slider owl-carousel">

                <?php
                while($get_img=mysqli_fetch_array($pic)):
                    ?>
                    <!-- Hero Slide -->
                    <?php $img_name = $get_img['image'];?>
                    <div class="hero_slide">
                        <div class="hero_slide_background" style="background-image:url(<?php echo 'carousel/'.$img_name;?>)"></div>
                        <div class="hero_slide_container d-flex flex-column align-items-center justify-content-center">
                            <div class="hero_slide_content text-center">
                                <h1 data-animation-in="fadeInUp" data-animation-out="animate-out fadeOut"><?php echo $get_img['short_message']?></h1>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>







            </div>


        </div>

    </div>




    <!-- Services -->

    <div class="services page_section" style="padding-top: 60px">

        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="section_title text-center">
                        <h1 style="font-size: 16pt">About us</h1>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-lg-12 service_item text-left d-flex flex-column align-items-start justify-content-start">

                    <p>HISTORICAL BACKGROUND
                      <span style="color: #8B4513;">Friends School Keveye Girls’ was established as a mixed secondary school in 1974 under the headship of the then Keveye Primary School head teacher Mr. Mathias Iravonga under the sponsorship of the Quakers Church.
                      </span>

                        TRANSFORMATION AND DEVELOPMENT
                        <span style="color:#8B4513;">The school began from scratch with no physical facilities of its own. In the initial stages, it shared a tuition block with Keveye Primary School which was a host to Form 1&2 classes.

                         The transformation process began in 1991, when the school converted into a Girls school with the phasing out of boys who were relocated to neighboring schools namely Chavakali, Mbale, Wangulu and Chandumba secondary schools. Due to focused leadership, government and community support, the young institution grew in leaps and bounds to its present status.

                         Today, the school stands on an 8-acre piece of land with a total population of 1580. It is officially registered as an Extra County 7 streamed girls’ school with enrolment of students from all over the Country. Like any other Public School, its mandate is to offer the 8.4.4 school curriculum of Education and any other planned and/or informal activities. The school is managed by B.O.M and assisted by the PA whose main objective is to raise funds for the schools’ development programmes...
                        </span>

                        <br>
                        <a style="background-color: #8B4513; color:white" class="btn " href="about.php">read more...</a>
                    </p>


                    <p style="color:white" >The school started in a modest way in 1915 as a boys Primary School at the current Machakos Girls grounds. It was known as African Government School. It was not until 1939 that it need to separate the girls from the boys arose and in 1950 the boys were relocated to the present site of Machakos School. It admitted African students only and hence the name “Government African Boys School”.</p>


                </div>

            </div>
        </div>
    </div>


    <!-- Popular -->

    <div class="popular page_section" style="margin-top: -150px">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="section_title text-center">
                        <h1 style="font-size: 16pt">Special moments</h1>
                    </div>
                </div>
            </div>


            <div class="row course_boxes" style="margin-top: 20px">

                <?php $home_gallery=display("gallery","3") ?>
                <?php while($disp_gallery=mysqli_fetch_array($home_gallery)): ?>
                    <!-- Popular Course Item -->
                    <div class="col-lg-4 course_box">
                        <div class="card">
                            <img class="card-img-top" src="<?php echo 'gallery/'.$disp_gallery['image'] ?>" style="width: 350px;height: 230px;">

                        </div>
                    </div>
                <?php endwhile; ?>




            </div>
        </div>
    </div>



    <!--events-->
    <?php $count=array(); ?>
    <?php $news_count=display("news_events","3") ?>
    <?php while($disp_news_count=mysqli_fetch_array($news_count)): ?>

        <?php
        $count[]= $disp_news_count['id'];
        ?>
    <?php endwhile; ?>
    <div class="container" style="padding-top: 60px; margin-top: -100px">
        <div class="row" style="padding-bottom: 20px">
            <div class="col">
                <div class="section_title text-center">
                    <h1 style="font-size: 16pt">Upcoming events</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <?php $news=display_specific("news_events","id",$count[0]) ?>
            <?php while($disp_news=mysqli_fetch_array($news)): ?>

                <div class="col-md-4">
                    <div class="card text-white bg-primary mb-3" style="max-width: 20rem; height: 15rem">
                        <div class="card-header"><?php echo $disp_news['title']  ?></div>
                        <div class="card-body">
                            <h2 class="card-title">Event date:<?php echo $disp_news['time_date']  ?></h2>
                            <p class="card-text text-white" style="text-overflow:ellipsis;word-wrap:break-word;overflow:hidden;max-height:50px;line-height:1.0em;"><?php echo $disp_news['details']  ?></p>
                            <p><span><a href="news.php" >read more...</a></span></p>

                        </div>
                    </div>
                </div>
            <?php endwhile; ?>


            <?php $news1=display_specific("news_events","id",$count[1]) ?>
            <?php while($disp_news1=mysqli_fetch_array($news1)): ?>

                <div class="col-md-4">
                    <div class="card text-white bg-warning mb-3" style="max-width: 20rem; height: 15rem">
                        <div class="card-header"><?php echo $disp_news1['title']  ?></div>
                        <div class="card-body">
                            <h2 class="card-title">Event date:<?php echo $disp_news1['time_date']  ?></h2>
                            <p class="card-text text-white" style="text-overflow:ellipsis;word-wrap:break-word;overflow:hidden;max-height:50px;line-height:1.0em;"><?php echo $disp_news1['details']  ?></p>
                            <p><a href="news.php">read more...</a></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>


            <?php $news1=display_specific("news_events","id",$count[2]) ?>
            <?php while($disp_news1=mysqli_fetch_array($news1)): ?>

                <div class="col-md-4">
                    <div class="card text-white bg-danger mb-3" style="max-width: 20rem; height: 15rem">
                        <div class="card-header"><?php echo $disp_news1['title']  ?></div>
                        <div class="card-body">
                            <h2 class="card-title">Event date:<?php echo $disp_news1['time_date']  ?></h2>
                            <p class="card-text text-white" style="text-overflow:ellipsis;word-wrap:break-word;overflow:hidden;max-height:50px;line-height:1.0em;"><?php echo $disp_news1['details']  ?></p>
                            <p><a href="news.php">read more...</a></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>



    <!-- Events -->



    <!-- Footer -->

    <?php include_once 'includes/footer.php'?>

</div>
<script>
    $('.carousel.carousel-multi-item.v-2 .carousel-item').each(function(){
        var next = $(this).next();
        if (!next.length) {
            next = $(this).siblings(':first');
        }
        next.children(':first-child').clone().appendTo($(this));

        for (var i=0;i<3;i++) {
            next=next.next();
            if (!next.length) {
                next = $(this).siblings(':first');
            }
            next.children(':first-child').clone().appendTo($(this));
        }
    });
</script>
<script src="js/jquery-3.2.1.min.js"></script>
<script src="styles/bootstrap4/popper.js"></script>
<script src="styles/bootstrap4/bootstrap.min.js"></script>
<script src="plugins/greensock/TweenMax.min.js"></script>
<script src="plugins/greensock/TimelineMax.min.js"></script>
<script src="plugins/scrollmagic/ScrollMagic.min.js"></script>
<script src="plugins/greensock/animation.gsap.min.js"></script>
<script src="plugins/greensock/ScrollToPlugin.min.js"></script>
<script src="plugins/OwlCarousel2-2.2.1/owl.carousel.js"></script>
<script src="plugins/scrollTo/jquery.scrollTo.min.js"></script>
<script src="plugins/easing/easing.js"></script>
<script src="js/custom.js"></script>

</body>
</html>