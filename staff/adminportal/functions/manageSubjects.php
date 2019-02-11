<?php

  include "actions.php";

  $obj = new DataOperations();

  $error=$success=$name=$initials='';

  if(isset($_POST['save']))
  {
      $name = $obj->con->real_escape_string(htmlspecialchars($_POST['name']));
      $initials = $obj->con->real_escape_string(htmlspecialchars($_POST['initials']));
      $code = $obj->con->real_escape_string(htmlentities($_POST['code']));

      if(!$obj->validate_string($name))
      {
          $error = "Invalid subject name";
      }
      else if(!$obj->validate_string($initials))
      {
          $error = "Invalid subject initials";
      }
      else
      {
          //checking if subject exists
          $where = array("SubjectName" => $name);
          if($obj->fetch_records("subject",$where))
          {
              $error = "Subject already exists";
          }

          //inserting data
          else
          {

              $data  = array( "SubjectName" => $name , "SubjectKey" => $initials,"code"=>$code);
              $sql="ALTER TABLE `final_result` ADD `$name` VARCHAR(4) NOT NULL AFTER `period`";
              $execute=mysqli_query($obj->con,$sql);

              if($execute)
              {
                  if($obj->insert_record("subject",$data))
                  {
                      $success = "Subject added successfully";
                  }
                  else
                  {
                      $error=mysqli_error($obj->con);
                  }
              }
              else
              {
                  if($obj->insert_record("subject",$data))
                  {
                      $success = "Subject added successfully";
                  }
                  else
                  {
                      $error=mysqli_error($obj->con);
                  }
              }

          }

      }
  }


  if(isset($_POST['delete']))
  {
      $id = $_POST['delete'];
      $where = array("ID"=>$id);

      if($obj->delete_record("subject",$where))
      {
          $success = "Subject deleted successfully";      }
  }