<?php
session_start();
$_SESSION['student-result']='';
if($_SESSION['account']){
    include "functions/actions.php";
    $obj=new DataOperations();
    $class=$_SESSION['view_results_class'];
    $period=$_SESSION['view_results_term'];
    $subject=$_SESSION['view_results_subject'];
}
else
{
    header('location:../index.php');
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View exam results</title>
    <!-- Bootstrap Styles-->
    <?php include "plugins/resources.php" ?>


</head>

<body>
<!--   navigation plugin-->
<?php include "plugins/navigation.php" ?>
<!--/.navigation-->
<div id="page-wrapper">

    <div id="page-inner">

        <?php include 'plugins/viewresultsnav.php' ?>
        <!--        contents-->

        <!--        result display-->
        <?php


        $sql = "SELECT * FROM results WHERE class='$class' AND period='$period' AND subject='$subject' ORDER BY total DESC";
        $execute = mysqli_query($obj->con,$sql);

        //$get_subjects=$obj->fetch_all_records("subject");

        if(mysqli_num_rows($execute)>0) {

            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= $subject ?> Results for form <?= $class ?> in <?= $period ?>
                </div>
                <div class="panel-body">
                    <table class="table table-responsive table-bordered" id="example">
                        <thead>
                        <tr>
                            <th>Names</th>
                            <th>Admission</th>
                            <th style="text-align:center;">CYCLE 1 %</th>
                            <th style="text-align:center;">CYCLE 2 %</th>
                            <th style="text-align:center;">AVG %</th>
                            <th style="text-align:center;">Grade</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($get_results = mysqli_fetch_assoc($execute)) {
                            ?>
                            <tr>
                                <td><?php echo $get_results['names'] ?></td>
                                <td><?php echo $get_results['admission'] ?></td>
                                <td align="middle"><?php echo $get_results['cat']*2 ?></td>
                                <td align="middle"><?php echo $get_results['mid']*2 ?></td>
                                <td align="middle"><?php echo $get_results['total'] ?></td>
                                <td align="middle"><?php echo $get_results['grade'] ?></td>

                            </tr>
                        <?php
                        }?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php
        }

        else
        {
            ?>
            <div class="alert alert-danger">
                Oops!! No <?=$subject?> results found for Form <?=$class?> in <?=$period?> </a>
                <?php
                echo mysqli_error($obj->con);
                ?>
            </div>
        <?php

        }


        ?>
        <!--        end-->
    </div>
</div>
<?php include "plugins/scripts.php"?>
<?php include "plugins/table.php" ?>
</body>
</html>

