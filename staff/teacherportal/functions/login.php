<?php
session_start();
$_SESSION['teacher_login']='';

include "actions.php";
/**
login
 */
$username=$password=$error='';

$obj = new DataOperations();


if(isset($_POST['login']))
{
    $username = $obj->con->real_escape_string(htmlentities($_POST['username']));
    $password = md5($_POST['password']);
	//$password = $_POST['password'];
    $credentials = array("JobNumber"=>$username,"Password"=>$password);

    $login = $obj->fetch_records("teacher",$credentials);

    if($login)
    {
        foreach($login as $row)
        {
            $account_status = $row['Account'];
            $email = $row['email'];

        }

        if($account_status == "active")
        {


            //updating last login
            $current_time = date("Y-m-d H:i:s");
            $last_login = array("last_login"=>$current_time);


            if($obj->update_record("teacher",$credentials,$last_login))
            {
                if($email)
                {
                    $_SESSION['account'] = $username;
                    header("location:index.php");
                }
                else
                {
                    $_SESSION['teacher_login'] = "$username";
                    header('location:register.php');
                }
            }
            else
            {
                $error = "Error".mysqli_error($obj->con);

            }


        }
        else
        {
            $error = "Your account is locked .Contact the administrator for more details";
        }
    }
    else
    {
        $error = "Wrong username or password";
    }


}
?>