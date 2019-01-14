<?php
/**
 * Created by PhpStorm.
 * User: maxipain
 * Date: 1/10/2018
 * Time: 9:23 AM
 */


 if(isset($_POST['save']))
 {
     $fee=$obj->con->real_escape_string(htmlentities($_POST['fee']));
     if(!is_numeric($fee))
     {
         $error="Only digits allowed";
     }
     else
     {
         $newfee = $current_fee + $fee;
         $admission=$_SESSION['student-admission'];
         $where=array("AdmissionNumber"=>$admission);
         $data=array("fees"=>$newfee);

         if($obj->update_record("student",$where,$data))
         {
             $success="fee updated successfully";
         }
         else{
             $error="Error updating fees";
         }




     }
 }