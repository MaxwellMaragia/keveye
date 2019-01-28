<?php
session_start();
if($_SESSION['student_login'])
{

    include 'functions/actions.php';
    $obj=new DataOperations();
    $error=$success='';
    $where = array('AdmissionNumber'=>$_SESSION['student_login']);
    $fetch_student = $obj->fetch_records('student',$where);

    foreach($fetch_student as $row){
        $names = $row['names'];
        $admission = $row['AdmissionNumber'];
        $class = $row['class'];
    }

    if(isset($_POST['submit']))
    {
        
        $pass = $_POST['password'];
        $confirm = $_POST['confirm'];

        if($pass!=$confirm)
        {
            $error = "Passwords do not match";
        }
        else
        {
            $where = array('AdmissionNumber'=>$admission);
            $data = array('password'=>md5($pass));

            if($obj->update_record('student',$where,$data))
            {
                $success = "Password changed sucessfully";
            }
        }
    }



}
else{
    header('location:student_login.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Portal</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Course Project">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
    <link href="plugins/fontawesome-free-5.0.1/css/fontawesome-all.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="styles/contact_styles.css">
    <link rel="stylesheet" type="text/css" href="styles/contact_responsive.css">

    <?php include_once '../includes/head.php' ?>

</head>
<body>

<div class="super_container">

    <!-- Header -->

    <?php include_once 'includes/student_navbar.php' ?>

    <!-- Home -->

    <div class="home">
        <div class="home_background_container prlx_parent">
            <div class="home_background prlx" style="background-image:url(../images/course_9.jpg)"></div>
        </div>
        <div class="home_content">
            <h1>portal</h1>
        </div>
    </div>

    <!-- Register -->

    <div class="register" style="margin-top: -170px">

        <div class="container">
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
            <div class="row row-eq-height">
                <!-- Contact Form -->
                <div class="contact_form" style="padding-top: 20px">
                    <div class="container">
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class=" col-md-8"><h2 style="color: #8C4200;">Change password</h2></div>
                        <div class="col-md-2"></div>
                    </div>
                    <div class="contact_form_container">
                        <?php
                        if($error){
                           $obj->errorDisplay($error);
                        }
                        if($success)
                        {
                            $obj->successDisplay($success);
                        }
                        ?>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                           
                            <input id="contact_form_name" class="input_field contact_form_name" type="password" placeholder="New password"   name="password" required="required">
                            <input id="contact_form_name" class="input_field contact_form_name" type="password" placeholder="Retype password"   name="confirm" required="required">
                            <br/>
                            <button id="contact_send_btn" type="submit" class="contact_send_btn trans_200" name="submit">Change password</button>
                        </form>
                    </div>
                    </div>
                </div>
            </div>
                </div>
                <div class="col-md-4"></div>
            </div>
        </div>
    </div>

    <!-- Elements -->



    <!-- Footer -->

    <?php include_once 'includes/footer.php' ?>

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