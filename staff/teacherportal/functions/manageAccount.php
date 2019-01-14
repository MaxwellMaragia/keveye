<?php
   include "actions.php";
   $obj = new DataOperations();
   $username=$password=$error='';

   //completing user information
  if(isset($_POST['register']))
  {
      $email = $obj->con->real_escape_string(htmlspecialchars($_POST['email']));
      $password = md5(htmlspecialchars($_POST['password1']));
      $password1 = md5(htmlspecialchars($_POST['password2']));


       if(!$obj->validate_email($email))
       {
           $error = "Invalid email address";
       }
       else if($password != $password1)
       {
           $error = "Passwords must be the same";
       }
       else
       {
           $jobnumber = $_SESSION['teacher_login'];
           $where = array("JobNumber"=>$jobnumber);
           $data = array("email"=>$email,"Password"=>$password

           );

           if($obj->update_record("teacher",$where,$data))
           {
               $_SESSION['account']=$jobnumber;
               header('location:index.php');
           }
           else
           {
               $error = mysqli_error($obj->con);
           }
       }


  }

        if(isset($_POST['update']))
        {

        }

