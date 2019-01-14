<?php
/**
 * Created by PhpStorm.
 * User: maxipain
 * Date: 12/19/2017
 * Time: 10:22 PM
 */
  $success = $error = "";

  if(isset($_POST['update']))
  {

      $email = $obj->con->real_escape_string(htmlspecialchars($_POST['email']));
      $existing_password = $obj->con->real_escape_string(htmlspecialchars($_POST['currentpass']));
      $new_password = $obj->con->real_escape_string(htmlspecialchars($_POST['newpass']));
      $confirm_password = $obj->con->real_escape_string(htmlspecialchars($_POST['confirmpass']));

      $existing_password=md5($existing_password);

      if(!empty($existing_password))
      {
          if(!$obj->validate_email($email))
          {
              $error = "Invalid email address";
          }

          else if($existing_password!=$password)
          {
              $error = "Current password is wrong";
          }
          else if($new_password!=$confirm_password)
          {
              $error = "Passwords do not match";
          }
          else
          {
              $new_password = md5($new_password);
              $where = array("JobNumber"=>$jobnumber);
              $data = array("email"=>$email,"Password"=>$new_password);

              if($obj->update_record("teacher",$where,$data))
              {
                  $success = "Update was successfull";
              }
          }

      }



  }