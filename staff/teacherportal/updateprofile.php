<?php
session_start();
$_SESSION['student-result']='';
if(!$_SESSION['account']){

    header('location:login.php');

}


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Update profile</title>
    <!-- Bootstrap Styles-->
    <?php include "plugins/resources.php" ?>
    <?php include "functions/getuser.php" ?>
    <?php include "functions/change_password.php" ?>


</head>

<body>
<!--   navigation plugin-->
<?php include "plugins/navigation.php" ?>
<!--/.navigation-->
<div id="page-wrapper">
    <div id="page-inner">

    <?php include 'plugins/secondarynav.php' ?>
       <div class="row">
           <div class="col-md-10">
               <div class="panel panel-default">
                   <div class="panel-heading">
                       <span class="fa fa-user"></span>
                       Update your profile
                   </div>
                   <div class="panel-body">
                       <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
                           <div class="form-group">
                               <?php

                               if($error)
                               {
                                   $obj->errorDisplay($error);
                               }
                               else if($success)
                               {
                                   $obj->successDisplay($success);
                               }

                               ?>
                               <label>Email</label>
                               <input type="email" class="form-control" name="email" value="<?php echo $email?>"/>
                               <label>Password</label>
                               <input type="password" class="form-control" name="currentpass"/>
                               <label>New password</label>
                               <input type="password" class="form-control" name="newpass"/>
                               <label>Confirm password</label>
                               <input type="password" class="form-control" name="confirmpass"/>
                               <input type="submit" name="update" class="btn btn-primary" value="Update" style="margin-top: 10px;"/>
                           </div>
                       </form>
                   </div>
               </div>
           </div>
       </div>
    </div>
</div>
<?php include "plugins/scripts.php"?>
</body>
</html>