<?php
session_start();
$_SESSION['student-admission']='';
if(!isset($_SESSION['bursar_account']))
{
    header('location:../index.php');
}
else
{
    include_once 'functions/actions.php';
    $obj = new DataOperations();
}

?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Students with overpayment</title>
    <!-- Bootstrap Styles-->
    <?php include "plugins/resources.php"?>

</head>

<body>
<!--   navigation plugin-->
<?php include "plugins/navigation.php"?>
<!--/.navigation-->
<div id="page-wrapper">
    <div id="page-inner">
        <!--        secondary nav-->
        <?php

        include 'plugins/secondarynav.php';

        ?>
        <!--        end nav-->
       <div class="panel panel-default">
           <div class="panel-heading">
               <span class="fa fa-list-ul"></span>
               List of students with fee overpayment
           </div>
           <div class="panel-body">
               <table class="table table-striped table-bordered" id="example">
                   <thead>
                   <tr>
                       <th>Student names</th>
                       <th>Admission</th>
                       <th>Class</th>
                       <th>Category</th>
                       <th>Fee overpayment</th>

                   </tr>
                   </thead>
                   <tbody>
                   <?php
                   $obj=new DataOperations();
                   $sql="SELECT * FROM student WHERE (fee_owed-fee_paid)<=0 ";
                   $execute=mysqli_query($obj->con,$sql);

                   while($row =mysqli_fetch_assoc($execute))
                   {

                       ?>
                       <tr>
                           <td><?php echo $row['names']?></td>
                           <td><?php echo $row['AdmissionNumber']?></td>
                           <td><?php echo $row['class']?></td>
                           <td><?php echo $row['category']?></td>
                           <td><?php echo $row['fee_paid']-$row['fee_owed']?></td>
                       </tr>
                   <?php


                   }


                   ?>
                   </tbody>
               </table>
           </div>
       </div>

    </div>
</div>
<?php include "plugins/scripts.php" ?>
<?php include "plugins/table.php" ?>
</body>
</html>
