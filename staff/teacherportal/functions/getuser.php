<?php

/**
 * Created by PhpStorm.
 * User: maxipain
 * Date: 12/18/2017
 * Time: 12:53 PM
 */
    include "actions.php";
    $obj = new DataOperations();

    $names=$jobnumber=$profile_picture=$email=$subject1=$subject2=$password='';

    $jobnumber = $_SESSION['account'];
    $where = array("JobNumber"=>$jobnumber);
    $get_user = $obj->fetch_records("teacher",$where);

     foreach($get_user as $row)
     {
         $names=$row['Names'];
         $jobnumber=$row['JobNumber'];
         $subject1=$row['Subject1'];
         $subject2=$row['Subject2'];
         $email=$row['email'];
         $password=$row['Password'];
     }


