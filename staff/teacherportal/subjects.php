<?php
session_start();
$_SESSION['student-result']='';
if(!$_SESSION['account']){


    header('location:../index.php');

}
else{
    include_once 'functions/actions.php';
    $obj = new DataOperations();

}


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View subjects</title>
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
            <div class="col-md-12" style="background:white; margin-top:20px; padding-top:20px;">
                <div class="panel panel-default">
                    <div class="panel-heading">Subjects data records</div>
                    <div class="panel-body">

                        <table class="table table-bordered table-responsive table-striped">
                         <thead>
                         <tr>
                             <th>Subject name</th>
                             <th>Initials</th>
                         </tr>
                         </thead>
                            <tbody>
                            <?php

                            $obj = new DataOperations();
                            $get_data = $obj->fetch_all_records("subject");

                            foreach($get_data as $row)

                            {
                                ?>

                                <tr>
                                    <td><?php echo $row['SubjectName'] ?></td>
                                    <td><?php echo $row['SubjectKey'] ?></td>
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



        <!--        page inner-->
    </div>
    <!--    page wrapper-->
</div>
<!--javascript plugin-->
<?php include "plugins/scripts.php"?>
<script>
    $('.table').DataTable();
</script>
<!--//javascript-->
</body>
</html>
