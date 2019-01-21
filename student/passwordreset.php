<?php
session_start();
$_SESSION['password-reset']='';

  include 'functions/actions.php';
  $obj=new DataOperations();
  $error="";

  if(isset($_POST['recover']))
  {
    $admission=$obj->con->real_escape_string($_POST['admission']);
    $question=$_POST['sq'];
    $answer=$obj->con->real_escape_string($_POST['sa']);

    //validation
    if(empty($admission) || empty($answer))
    {
      $error="Please fill in all fields";
    }
    else if(!is_numeric($admission))
    {
      $error="Admission number must be numeric";
    }
    else if($question == "Please choose security question")
    {
      $error="Please select the security question you registered with";
    }
    else 
    {
      $where=array("AdmissionNumber"=>$admission);
      $get_student=$obj->fetch_records("student",$where);

      if($get_student)
      {
          foreach($get_student as $row)
          {

            if($question!=$row['security_question'])
            {
              $error="Wrong security question";
            }
            else if($answer!=$row['security_answer'])
            {
              $error="Wrong answer to security question";
            }
            else
            {
              $_SESSION['password-reset']=$admission;
              header('location:newpassword.php');
            }


          }
      }
      else
      {
        $error="Wrong admission number. If you are unable to continue please contact the administrator";
      }

    }

    

  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reset password</title>
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
                <div class="contact_form">
                    <div class="container">
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class=" col-md-8"><h2 style="color: #8C4200;">Password recovery</h2></div>
                        <div class="col-md-2"></div>
                    </div>
                    <div class="contact_form_container">
                        <?php
                        if($error){
                           $obj->errorDisplay($error);
                        }
                        ?>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <input id="contact_form_name" class="input_field contact_form_name" type="number" placeholder="admission number"  name="admission" required="required">
                            <select name="sq" class="input_field contact_form_name" required="required">
                               <option value="">Select your security question</option> 
                               <option >What's the name of your first pet?</option>
                               <option >What's your favorite food?</option>
                               <option >Who is your favorite musician?</option>
                            </select>
                            <input id="contact_form_name" class="input_field contact_form_name" type="text" placeholder="Answer to security question"   name="sa" required="required">
                            <br/>
                            <button id="contact_send_btn" type="submit" class="contact_send_btn trans_200" name="recover">Proceed</button>
                            <a href="student_login.php" style="margin-top: 8px;">Back</a>
                            
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