<?php
session_start();
$_SESSION['student-result']='';
if(!$_SESSION['account']){


    header('location:../index.php');

}
else{
    include "functions/actions.php";
    $obj = new DataOperations();
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit | delete | view students</title>
    <!-- Bootstrap Styles-->
    <?php include "plugins/resources.php" ?>

</head>

<body>
<!--   navigation plugin-->
<?php include "plugins/navigation.php" ?>
<!--/.navigation-->
<div id="page-wrapper">
    <div id="page-inner">

    <?php include 'plugins/secondarynav.php' ?>

       <div class="row">
           <div class="col-md-12" style="margin-top:5px;">
               <div class="panel panel-default">
                   <div class="panel-heading">
                       <span class="glyphicon glyphicon-list"></span>
                       List of students
                   </div>
                   <div class="panel-body">
                       <!--        search results-->

                              <table class="table table-bordered table-striped" id="example">
                                  <thead>
                                  <tr>
                                      <th>Names</th>
                                      <th>Admission</th>
                                      <th>Category</th>
                                      <th>Class</th>

                                  </tr>
                                  </thead>
                                  <tbody>
                                  <?php

                                  $obj=new DataOperations();
                                  $get_data = $obj->fetch_all_records("student");

                                  foreach($get_data as $row)
                                  {
                                  $names=$row['names'];
                                  $admission=$row['AdmissionNumber'];
                                  $category=$row['category'];
                                  $initials=$row['class'];

                                      ?>
<!--                                      data display-->
                                     <tr>
                                         <td><?=$names?></td>
                                         <td><?=$admission?></td>
                                         <td><?=$category?></td>
                                         <td><?=$initials?></td>

                                     </tr>
<!--                                      -->
                                  <?php
                                  }
                                  ?>
                                  </tbody>
                              </table>


                       <!--        end search results-->
                   </div>
               </div>
           </div>
       </div>

    </div>
</div>


<?php include "plugins/scripts.php"?>
<?php include 'plugins/table.php'?>
</body>
</html>
