<?php
session_start();
$_SESSION['student-result']='';
if(!$_SESSION['account']){
    header('location:../index.php');
}
else
{
    if(!($_SESSION['view_results_term'] && $_SESSION['view_results_class'] && $_SESSION['view_results_subject']))
    {
        header("location:results.php");
    }
    else {
        include "functions/actions.php";
        $obj = new DataOperations();
        $form=$_SESSION['view_results_class'];
        $period=$_SESSION['view_results_term'];

        $sql="SELECT * FROM final_result WHERE period='$period' AND form=$form ORDER BY average DESC";

        $execute = mysqli_query($obj->con,$sql);
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View results : overall performance</title>
    <!-- Bootstrap Styles-->
    <?php include "plugins/resources.php" ?>


</head>

<body>
<!--   navigation plugin-->
<?php include "plugins/navigation.php" ?>
<!--/.navigation-->
<div id="page-wrapper">
    <div id="page-inner">
        <!--        contents-->
        <?php include 'plugins/viewresultsnav.php' ?>

        <?php
        if(mysqli_num_rows($execute)>0)
        {
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Total results
                </div>
                <div class="panel-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="example">
                            <thead>
                            <tr>
                                <th>
                                    Student names
                                </th>
                                <th>
                                    Admission
                                </th>
                                <th>
                                    Class
                                </th>
                                <?php

                                $get_subjects = $obj->fetch_all_records("subject");
                                foreach($get_subjects as $row)
                                {
                                    echo "<th>".$row['SubjectName']."</th>";
                                }

                                ?>
                                <th>
                                    Total
                                </th>
                                <th>
                                    Average
                                </th>
                                <th>
                                    Grade
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php






                            while($get_results = mysqli_fetch_assoc($execute))
                            {
                                ?>
                                <tr>
                                    <td><?php echo $get_results['names']?></td>
                                    <td><?php echo $get_results['admission']?></td>
                                    <td><?php echo $get_results['class']?></td>
                                    <?php

                                    foreach($get_subjects as $row)
                                    {
                                        $result=round($get_results[$row['SubjectName']]);
                                        if($result==0)
                                        {
                                            echo "<td>-</td>";
                                        }
                                        else
                                        {
                                            echo "<td style='color:brown'>$result</td>";
                                        }
                                    }

                                    ?>
                                    <td style='color:blue'>
                                        <?php echo floor($get_results['total'])?>
                                    </td>
                                    <td style='color:blue'>
                                        <?php echo $get_results['average']?>
                                    </td>
                                    <td style='color:blue'>
                                        <?php echo $get_results['grade']?>
                                    </td>
                                </tr>
                            <?php
                            }


                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php
        }
        else
        {
            ?>
        <div class="alert alert-danger">No results found for the category you entered <br/>
            <hr/>
            Note that you can only view overall results for students whose marks for every subject have been uploaded
        </div>
        <?php
        }
        ?>
        </div>
    </div>
<?php include "plugins/scripts.php" ?>
<?php include "plugins/table.php" ?>
</body>
</html>
