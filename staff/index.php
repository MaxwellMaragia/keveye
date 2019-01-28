<?php
session_start();
$error='';

$current_time = date("Y-m-d H:i:s");
$last_login = array("last_login"=>$current_time);

include_once 'database/actions.php';
$obj = new DataOperations();

if(isset($_POST['login']))
{
    $username = $obj->con->real_escape_string(htmlentities($_POST['username']));
    $password = md5($_POST['password']);

    $where = array('username'=>$username,'password'=>$password);
    $get_accounts = $obj->fetch_records('staff_accounts',$where);
    if($get_accounts)
    {
        foreach($get_accounts as $row)
        {
            $account = $row['account'];
            $id = $row['id'];
            $email = $row['email'];

            if($row['state'] == 'locked')
            {
                $error = "Your account has been locked. Contact the administrator for more details";
            }
            else
            {
                if($account == 'admin')
                {

                    if($obj->update_record('staff_accounts',$where,$last_login)){

                        $_SESSION['admin_login']=$username;
                        header('location:adminportal/dashboard.php');
                    }
                }
                if($account == 'teacher')
                {
                    if($obj->update_record("staff_accounts",$where,$last_login))
                    {
                        if($email)
                        {
                            $_SESSION['account'] = $username;
                            header("location:teacherportal/index.php");
                        }
                        else
                        {
                            $_SESSION['registration'] = $username;
                            header('location:register.php');
                        }
                    }
                }
                if($account == 'bursar')
                {
                    if($obj->update_record("staff_accounts",$where,$last_login))
                    {
                        if($email)
                        {
                            $_SESSION['bursar_account'] = $username;
                            header("location:bursarportal/index.php");
                        }
                        else
                        {
                            $_SESSION['registration']=$username;
                            header('location:register.php');
                        }
                    }
                }
                if($account == 'librarian')
                {
                    if($obj->update_record("staff_accounts",$where,$last_login))
                    {
                        if($email)
                        {
                            $_SESSION['librarian_account'] = $username;
                            header("location:librarian/index.php");
                        }
                        else
                        {
                            $_SESSION['registration']=$username;
                            header('location:register.php');
                        }
                    }
                }
            }
        }
    }
    else
    {
        $error = "Wrong Username or Password";
    }
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

    <?php include_once '../includes/head.php'?>

</head>
<body>

<div class="super_container">

    <!-- Header -->

    <?php include_once 'includes/navbar.php'?>

    <!-- Home -->

    <div class="home">
        <div class="home_background_container prlx_parent">
            <div class="home_background prlx" style="background-image:url(../images/search_background.jpg)"></div>
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
                                    <div class=" col-md-6"><h2 style="color: #8C4200;">Staff login</h2></div>
                                    <div class="col-md-3"></div>
                                </div>
                                <div class="contact_form_container">
                                    <?php
                                    if($error){
                                        $obj->errorDisplay($error);
                                    }
                                    ?>
                                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                        <input id="contact_form_name" class="input_field contact_form_name" type="text" placeholder="Staff id/username"  data-error="Name is required." name="username" required="required">
                                        <input id="contact_form_name" class="input_field contact_form_name" type="password" placeholder="password"  data-error="Name is required." name="password" required="required">
                                        <button id="contact_send_btn" type="submit" class="contact_send_btn trans_200" name="login">Login</button>
                                    </form>
                                </div>
                                <a href="resetpass.php">Forgot your password?</a>
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