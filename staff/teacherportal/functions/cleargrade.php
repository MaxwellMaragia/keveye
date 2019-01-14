<?php

   include "actions.php";
   $obj=new DataOperations();

   if(isset($_POST['clear']))
   {
       $sql="DELETE FROM grading_system";
       $execute=mysqli_query($obj->con,$sql);

       if($execute)
       {
           header('location:../gradingsystem.php?message=grades cleared');
       }
   }