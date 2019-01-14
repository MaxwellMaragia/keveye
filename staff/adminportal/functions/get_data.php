<?php
/**
 * Created by PhpStorm.
 * User: maxipain
 * Date: 9/14/2017
 * Time: 7:49 PM
 */

  include "functions/actions.php";
  $obj=new DataOperations();
  $total='';


   function totalStudents(){

       global $obj,$total;

       $query="SELECT * FROM student";
       $execute=mysqli_query($obj->con,$query);

       $total=mysqli_num_rows($execute);

       echo "$total";



   }

  function totalTeacher(){

       global $obj,$total;

       $query="SELECT * FROM staff_accounts WHERE account='teacher'";
       $execute=mysqli_query($obj->con,$query);

       $total=mysqli_num_rows($execute);

       echo "$total";



   }

 function totalLibrarian(){

       global $obj,$total;

       $query="SELECT * FROM staff_accounts WHERE account='librarian'";
       $execute=mysqli_query($obj->con,$query);

       $total=mysqli_num_rows($execute);

       echo "$total";



   }

  function totalBursar(){

       global $obj,$total;

       $query="SELECT * FROM staff_accounts WHERE account='bursar'";
       $execute=mysqli_query($obj->con,$query);

       $total=mysqli_num_rows($execute);

       echo "$total";



   }