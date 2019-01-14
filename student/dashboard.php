<?php
session_start();
if(isset($_SESSION['student_login']))
{
    include 'functions/actions.php';
    $obj=new DataOperations();
    $where = array('AdmissionNumber'=>$_SESSION['student_login']);
    $fetch_student = $obj->fetch_records('student',$where);

    foreach($fetch_student as $row){
        $names = $row['names'];
        $admission = $row['AdmissionNumber'];
        $class = $row['class'];
    }
}
else{
    header('location:student_login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student's portal</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Course Project">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        #form{
            border-radius: 0;
        }
    </style>

    <?php include_once '../includes/head.php'?>
</head>
<body>



<div class="super_container">

    <!-- Header -->

    <?php include_once 'includes/student_navbar.php'?>

    <!-- Home -->

    <div class="home">
        <div class="home_background_container prlx_parent">
            <div class="home_background prlx" style="background-image:url(images/course_6.jpg)"></div>
        </div>

    </div>

    <!-- table -->

    <div class="contact" style="margin-top: -180px">

        <div class="container">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-2">
                    <a href="student_result.php"><img class="card-img-top" src="../images/vectors/results.png"></a>
                </div>
                <div class="col-md-2">
                    <a href="fee.php"><img class="card-img-top" src="../images/vectors/fees.png" ></a>
                </div>
                <div class="col-md-2">
                    <a href="notices.php"><img class="card-img-top" src="../images/vectors/notices.png"></a>
                </div>
                <div class="col-md-3"></div>
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