<?php
session_start();
if($_SESSION['registration']){

    $error='';

    $current_time = date("Y-m-d H:i:s");
    $last_login = array("last_login"=>$current_time);

    include_once 'database/actions.php';
    $obj = new DataOperations();

    if(isset($_POST['submit']))
    {
        $email = $obj->con->real_escape_string(htmlentities($_POST['email']));
        $password = $_POST['password'];
        $confirm = $_POST['confirm'];

        if($password!=$confirm)
        {
            $error = "Passwords must match";
        }
        else if(strlen($password)<8){

            $error = "Password must be 8 characters or more";

        }
        else
        {
            $username = $_SESSION['registration'];
            $where=array('username'=>$username);
            $data=array('email'=>$email,'password'=>md5($password));

            $get_data = $obj->fetch_records('staff_accounts',$where);
            foreach($get_data as $row)
            {
                $account_type = $row['account'];
            }

            if($obj->update_record('staff_accounts',$where,$data))
            {
                if($account_type == 'teacher')
                {
                    $_SESSION['account']=$username;
                    header('location:teacherportal/index.php');
                }
                else if($account_type == 'bursar')
                {
                    $_SESSION['bursar_account']=$username;
                    header('location:bursarportal/index.php');
                }
                else if($account_type == 'librarian')
                {
                    $_SESSION['librarian_account']=$username;
                    header('location:librarian/index.php');
                }
            }
            else
            {
                $error = "Error updating account";
            }
        }
    }
}
else
{
    header('location:index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>KIPASI</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css"/>
    <link rel="stylesheet" href="assets/css/layout.css"/>
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css"/>
    <script type="text/javascript" src="assets/bootstrap/js/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="assets/bootstrap/js/bootstrap.js"></script></head>

</head>
<body>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">KIPASI</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

            <ul class="nav navbar-nav navbar-right">

            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
<div class="banner col-md-12">
    <div class="col-md-4">
        <h3>
            KIPASI SECONDARY SCHOOL<br>
            <small>Strive for success</small>
        </h3>
    </div>
</div>

<div class="container">
    <div class="col-md-6 login">
        <h3>
            Complete your profile
        </h3>
        <hr>
        <div class="form-group">
            <?php

            if($error)
            {
                $obj->errorDisplay($error);
            }
            ?>
            <form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF'])?>">
                <input type="email" class="form-control" placeholder="Email address" name="email" required="required">
                <input type="password" class="form-control" placeholder="new password" name="password" required="required">
                <input type="password" class="form-control" placeholder="retype password" name="confirm" required="required">

                <button class="btn btn-primary" name="submit" type="submit">Proceed</button>
            </form>


        </div>
    </div>
</div>
<footer>
    <div class="container">

        <div class="col-md-12 text-center">
            <hr>
            <span>Copyright © <a href="https://codei.co.ke">CODEI SYSTEMS</a>  2018</span>
        </div>
    </div>
</footer>
</body>
</html>