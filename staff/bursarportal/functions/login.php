<?php
session_start();
$_SESSION['bursar_login']='';

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

    $credentials = array("jobnumber"=>$username,"password"=>$password);

    $login = $obj->fetch_records("bursar",$credentials);

    if($login)
    {
        foreach($login as $row)
        {
            $account_status = $row['account'];
            $email = $row['email'];

        }

        if($account_status == "active")
        {


            //updating last login
            $current_time = date("Y-m-d H:i:s");
            $last_login = array("last_login"=>$current_time);


            if($obj->update_record("bursar",$credentials,$last_login))
            {
                if($email)
                {
                    $_SESSION['bursar_account'] = $username;
                    header("location:index.php");
                }
                else
                {
                    $_SESSION['bursar_login'] = "$username";
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