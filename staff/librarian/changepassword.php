<?php

session_start();

if($_SESSION['librarian_account']){

    include "functions/actions.php";
    $confirmpassword=$newpassword=$error=$success='';
    $obj=new DataOperations();

    if(isset($_POST['update']))
    {

        $newpassword=$obj->con->real_escape_string(htmlentities($_POST['newpassword']));
        $confirmpassword=$obj->con->real_escape_string(htmlentities($_POST['confirmpassword']));

        if($newpassword == $confirmpassword)
        {
            if(strlen($newpassword)<8)
            {
                $error="Password must be 8 characters and above";
            }
            else
            {
                $data=array("password"=>md5($newpassword));
                $where=array("username"=>$_SESSION['librarian_account']);

                if($obj->update_record("staff_accounts",$where,$data))
                {
                    $success="Password changed successfully";
                }
                else
                {
                    $error="Could not change password";
                }
            }

        }
        else
        {
            $error="Passwords do not match";
        }

    }

}
else{

    header('location:../index.php');

}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Change password</title>
    <!-- Bootstrap Styles-->
    <?php include "plugins/resources.php" ?>


</head>

<body>
<!--   navigation plugin-->
<?php include "plugins/navigation.php" ?>
<!--/.navigation-->
<div id="page-wrapper">
    <div id="page-inner">

        <?php include 'plugins/secondarynav.php' ?>

        <div class="form-group col-md-6">
            <?php
            if($error)
            {
                $obj->errorDisplay($error);
            }
            if($success)
            {
                $obj->successDisplay($success);
            }
            ?>
            <form action="<?php echo htmlentities($_SERVER['PHP_SELF'])?>" method="post">
                <div class="form-group">
                    <p>New password</p>
                    <input type="password" class="form-control" placeholder="new password" name="newpassword" required/>
                </div>
                <div class="form-group">
                    <p>Confirm password</p>
                    <input type="password" class="form-control" placeholder="confirm password" name="confirmpassword" required/>
                </div>
                <div class="form-group">
                    <button type="submit" name="update" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include "plugins/scripts.php"?>
</body>
</html>
