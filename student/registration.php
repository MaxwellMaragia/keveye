<?php
session_start();
if($_SESSION['student-registration'])
{

    include 'functions/actions.php';
    $obj=new DataOperations();
    $error='';
	

    if(isset($_POST['submit']))
    {
        $sq = htmlentities($_POST['sq']);
        $sa = htmlentities($_POST['sa']);
        $pass = $_POST['password'];
        $confirm = $_POST['confirm'];

        if($pass!=$confirm)
        {
            $error = "Passwords do not match";
        }
        else
        {
            $where = array('AdmissionNumber'=>$_SESSION['student-registration']);
			
            $data = array('security_question'=>$sq,'security_answer'=>$sa,'password'=>md5($pass));

            if($obj->update_record('student',$where,$data))
            {
                $_SESSION['student_login']=$_SESSION['student-registration'];
				
                header('location:student_result.php');
            }
			else{
				$error = mysqli_error($obj->con);
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

    <?php include_once 'includes/navbar.php' ?>

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
                        <div class="col-md-3"></div>
                        <div class=" col-md-6"><h2 style="color: #8C4200;">Registration</h2></div>
                        <div class="col-md-3"></div>
                    </div>
                    <div class="contact_form_container">
                        <?php
                        if($error){
                           $obj->errorDisplay($error);
                        }
                        ?>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <select name="sq" class="input_field contact_form_name" required="required">
                               <option value="">Select security question</option> 
                               <option >What is the name of your first pet?</option>
                               <option >What is your favorite food?</option>
                               <option >Who is your favorite musician?</option>
                            </select>
                            <input id="contact_form_name" class="input_field contact_form_name" type="text" placeholder="Answer to security question"   name="sa" required="required">
                           
                            <input id="contact_form_name" class="input_field contact_form_name" type="password" placeholder="New password"   name="password" required="required">
                            <input id="contact_form_name" class="input_field contact_form_name" type="password" placeholder="Retype password"   name="confirm" required="required">
                            <br/>
                            <button id="contact_send_btn" type="submit" class="contact_send_btn trans_200" name="submit">Proceed</button>
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