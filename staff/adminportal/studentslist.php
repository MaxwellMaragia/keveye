<?php

session_start();

if(!$_SESSION['admin_login']){


    header('location:../index.php');

}
else{
    include "functions/managestudents.php";
}


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>All students</title>
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
        <!--        contents-->

        <div class="row">
            <div class="col-md-12 " style="background:white; padding-top:20px;">
                <!-- Advanced Tables -->
                <div class="panel panel-default">
                    <div class="panel-heading">List of all current students</div>
                    <div class="panel-body">

                        <div>
                   

                                <?php

                                if($error){

                                    $obj->errorDisplay($error);

                                }
                                else if($success){

                                    $obj->successDisplay($success);

                                }


                                ?>
                                <table class="table  table-hover table-striped table-bordered display nowrap" id="example">
                                    <thead>
                                    <tr>

                                        <th>Names</th>
                                        <th>Admission</th>
                                        <th>Class</th>
                                        <th>Category</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                     <?php



                                     $get_data = $obj->fetch_all_records("student");

                                     foreach($get_data as $row)
                                     {
                                         $names=$row['names'];
                                         $admission=$row['AdmissionNumber'];
                                         $class=$row['class'];
                                         $category=$row['category'];
                                         ?>
<!--                 echo table rows-->
                                         <tr>
                                             <td><?php echo $names?></td>
                                             <td><?php echo $admission?></td>
                                             <td><?php echo $class?></td>
                                             <td><?php echo $category?></td>
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
        </div>
        <!--End Advanced Tables -->
        <!--        -->
    </div>
</div>


<?php include "plugins/scripts.php"?>
<?php include "plugins/tablescripts.php" ?>
<?php include "plugins/table.php" ?>
</body>
</html>
