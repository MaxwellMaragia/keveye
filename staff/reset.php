<?php


 include 'database/actions.php';
 $obj = new DataOperations();
 $success=$error="";

 if(isset($_POST['resetsubmit']))
 {
   $email = $obj->con->real_escape_string(htmlentities($_POST['email']));

   //chech if email exist
   $exist = array("email"=>$email);

   if($obj->fetch_records("staff_accounts", $exist))
   {
   	 //generating unique string
     $uniqidStr = md5(uniqid(mt_rand()));

     //update data with forget pass code
     $where = array("email"=>$email);
     $data = array("resetpassword"=>$uniqidStr);
     
     if($obj->update_record("staff_accounts",$where,$data))
     {
      $resetPassLink = 'https://kipasi.codei.co.ke/resetpassword.php?fp_code='.$uniqidStr;

       //get user details
      $user = array("email"=>$email);
      $user_details = $obj->fetch_records("staff_accounts",$user);
      foreach ($user_details as $row) 
      {
        $name = $row['names'];

      }
       //send password reset link to the email
      $to = $row['email'];
      $subject = "Password Update Request";
      $mailContent = 'Dear '.$name.', 
        <br/>Recently a request was submitted to reset a password for your account. If this was a mistake, just ignore this email and nothing will happen.
        <br/>To reset your password, visit the following link: <a href="'.$resetPassLink.'">'.$resetPassLink.'</a>
        <br/><br/>Regards,
        <br/>Codei';

        //set content-type header for sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        //additional headers
        $headers .= 'From: codei<sender@support.co.ke>' . "\r\n";
        //send email
        $sentmail = mail($to,$subject,$mailContent,$headers);
        if($sentmail)
        {
           $success = "Please check your e-mail, we have sent a password reset link to your registered email";
        }
     }
   }else
   {
   	$error = "User with the email does not exist";
   }
 }
 
 if(isset($_POST['update']))
  {
  $fp_code = '';
  $password = $obj->con->real_escape_string(htmlentities($_POST['password']));
  $confirm_password = $obj->con->real_escape_string(htmlentities($_POST['confirm_pass']));
   if(!empty($password) && !empty($confirm_password) && !empty($_POST['fp_code']))
   {
    $fp_code = $_POST['fp_code'];

    //comparing the passwords
     if($password !== $confirm_password)
     {
       $error = "Passwords do not match!";
     }else
     {
      //check whether identity code exists in the database
      $code_exist = array("resetpassword" => $fp_code);
      $user_code = $obj->fetch_records("staff_accounts", $code_exist);
      if(!empty($user_code))
      {
        //update with the new password
        $password = md5($password);
        $where = array("resetpassword"=>$fp_code);
        $data = array("password"=>$password);
        $update = $obj->update_record("staff_accounts",$where,$data);
        if($update)
        {
          header("location:resetsuccess.php");
        }else
        {
          $error = "Unable to reset your password, please try later!";
        }
      }else
      {
        $error = "Not authorized to reset password for this account";
      }
     }

   }else
   {
    $error = "Please fill all fields";
   }

  }
?>