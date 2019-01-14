<?php

   include "actions.php";
   $obj=new DataOperations();
   $success=$error=$names=$jobnumber='';


//add new query
    if(isset($_POST['addnew']))
    {
        $names = $obj->con->real_escape_string(htmlspecialchars($_POST['names']));
        $jobnumber = $obj->con->real_escape_string(htmlspecialchars($_POST['jobnumber']));

        if(!preg_match("/^[a-zA-Z ]*$/", $names))
        {
            $error = "Only letters and whitespace allowed in name fields";
            return false;
        }
        else if(!filter_var($jobnumber.FILTER_VALIDATE_INT))
        {
            $error="Job number must be number only";
            return false;
        }
    else
    {

        $data = array( "names" => $names,
            "username" => $jobnumber,
            "password" => md5($jobnumber),
            "account" => "bursar",
            "state"=>"active"
        );

        $check_if_exist = array( "username" => $jobnumber);

        if($obj->fetch_records("staff_accounts",$check_if_exist))
        {
            $error = "Account with this jobnumber already exists";
        }
        else
        {
            if($obj->insert_record("staff_accounts",$data))
            {
                $success = "Bursar account created";
                $jobnumber = $names = "";
            }
            else
            {
                $error = "error= ".mysqli_error($obj->con);
            }
        }

    }

}
//end update

  //update bursar account
    if(isset($_POST['update']))
     {


         $names = $obj->con->real_escape_string(htmlspecialchars($_POST['names']));
         $jobnumber = $obj->con->real_escape_string(htmlspecialchars($_POST['jobnumber']));

         if(!$obj->validate_string($names))
         {
             $error = "Only letters and whitespace allowed in name fields";
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
             $data = array( "names" => $names);

             if($obj->update_record("staff_accounts",$where,$data))
             {
               $success = "Data update successful";
               $names = $jobnumber = "";
             }
             else
             {
                 $error = "Failed";

             }
         }
     }
//end update


//delete query
        if(isset($_POST['delete']))
        {
            $jobnumber = $_POST['delete'];
            $where = array( "username" => $jobnumber );

            if($obj->delete_record("staff_accounts",$where))
            {
                $success = "Account deleted successfully";
            }
            else
            {
                $error = "Error ".mysqli_error($obj->con);
            }
        }
//end delete

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

             function getBursar()
             {
                 global $obj;

                 $bursarData = $obj->fetch_all_records("bursar");

                 foreach($bursarData as $row)
                 {
                     $names = $row['names'];
                     echo "<tr><td>$names</td></tr>";
                 }

             }

