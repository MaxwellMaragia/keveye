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
        $class=$_SESSION['view_results_class'];
        $period=$_SESSION['view_results_term'];
        $period=$_SESSION['view_results_term'];

        $sql="SELECT * FROM final_result WHERE period='$period'  AND class='$class' ORDER BY average DESC";

        $execute = mysqli_query($obj->con,$sql);

        if(isset($_POST['download']))
        {
            $_SESSION['class'] = $class;
            $_SESSION['period'] = $period;
            include "reports.php";
        }
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
        <?php include 'plugins/viewfinalresultsnav.php' ?>

        <?php

        if(mysqli_num_rows($execute)>0)
        {
          ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Form <?=$class?> final average results for <?=$period?>
                </div>
                <div class="panel-body">

                    <div class="table-responsive">
                        <form action="<?php echo htmlentities($_SERVER['PHP_SELF'])?>" method="post">
                            <button name="download" type="submit" class="btn btn-primary" style="margin-bottom: 10px;"><span class="glyphicon glyphicon-download-alt"></span> Download report forms</button>
                        <table class="table table-bordered table-striped" id="example">
                            <thead>
                            <tr>
                                <th>
                                    Student names
                                </th>
                                <th>
                                    Admission
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
                                    <?php

                                    foreach($get_subjects as $row)
                                    {
                                        $result=$get_results[$row['SubjectName']];
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
                                        <?php echo $get_results['total']?>
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
                        </form>
                    </div>
                </div>
            </div>
          <?php
            }
            else
            {
                echo "<div class='alert alert-danger'>No results found for form $class in $period</div>";
            }

        ?>
    </div>
</div>
<?php include "plugins/scripts.php" ?>
<?php include "plugins/table.php" ?>
</body>
</html>
