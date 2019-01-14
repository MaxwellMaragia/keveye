<?php


    include "actions.php";
    $obj=new DataOperations();
    $success=$error=$names=$jobnumber='';


//delete teacher accounts


  if(isset($_POST['delete']))
  {
      $key=$_POST['delete'];
      $where= array("username" => $key);

      if($obj->delete_record("staff_accounts",$where))
      {
          $success = "Account deleted successfully";
      }
      else
      {
          $error = mysqli_error($obj->con);
      }

  }
if(isset($_POST['update']))
{


    $names = $obj->con->real_escape_string(htmlspecialchars($_POST['names']));
    $jobnumber = $obj->con->real_escape_string(htmlspecialchars($_POST['username']));
    $subject1 = $obj->con->real_escape_string(htmlspecialchars($_POST['subject1']));
    $subject2 = $obj->con->real_escape_string(htmlspecialchars($_POST['subject2']));

    if(!$obj->validate_string($names))
    {
        $error = "Only chars and whitespace allowed in name fields";
        return false;
    }
    else if(!$obj->validate_int($jobnumber))
    {
        $error="Job number must be number only";
        return false;
    }
    else
    {

         $where = array( "username" => $jobnumber );
         $data = array( "names" => $names ,
                        "subject1" => $subject1,
                        "subject2" => $subject2 );

        if($obj->update_record("staff_accounts",$where,$data))
        {
            $success = "Data update successful";
        }
        else
        {
            $error = "Failed";
        }
    }
}
//end update

//creating new teacher account

  if(isset($_POST['add_teacher']))
  {

      $jobnumber = $obj->con->real_escape_string(htmlspecialchars($_POST['jobnumber']));
      $names = $obj->con->real_escape_string(htmlspecialchars($_POST['names']));
      $subject1 = $obj->con->real_escape_string(htmlspecialchars($_POST['subject1']));
      $subject2 = $obj->con->real_escape_string(htmlspecialchars($_POST['subject2']));
      $password = md5($jobnumber);

      $check_if_exist = array("username" => $jobnumber);

      if(!$obj->validate_string($names))
      {
         $error="Names must contain letters and space only (Please don't use apostrophe)";
      }
      else if(!$obj->validate_string($subject1))
      {

      }
      else if(!$obj->validate_string($subject2)) {

      }
      else if(!filter_var($jobnumber.FILTER_VALIDATE_INT)){

          $error="Job number must be number only";

      }
      else if($subject1 == $subject2)
      {
          $error="Please choose different subjects";
      }
      else if($obj->fetch_records("staff_accounts",$check_if_exist))
      {
          $error = "Teacher with this jobnumber already exists";
      }
      else
      {


          $data = array(

              "names" => $names,
              "subject1" => $subject1,
              "subject2" => $subject2,
              "username" => $jobnumber,
              "password" => $password,
              "account" => "teacher",
              "state"=>'active'

          );


          if($obj->insert_record("staff_accounts",$data))
          {
              $success = "Account created successfully";
              $names=$jobnumber='';
          }
      }
  }

  //activating or deactivating teacher accounts
  if(isset($_POST['activate_account']))
  {

         $jobnumber = $_POST['activate_account'];
         $where = array("username"=>$jobnumber);


         $account_status = $obj->fetch_records("staff_accounts",$where);

         foreach($account_status as $row)
         {
             $userAccount = $row['state'];
         }



         if($userAccount == "active")
         {
             $data = array("state" => "locked");

             if($obj->update_record("staff_accounts",$where,$data))
             {
                 $success = "Account deactivated";
             }
         }
         else if($userAccount == "locked")
         {
             $data = array("state" => "active");

             if($obj->update_record("staff_accounts",$where,$data))
             {
                 $success = "Account activated";
             }
         }
     }

    