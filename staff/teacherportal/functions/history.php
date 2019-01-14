<?php
session_start();
$_SESSION['student-result']='';
if($_SESSION['account'])
{
    $_SESSION['exam']='CAT 1';
    //checking if teacher had selected correct details
    if(($_SESSION['year'] && $_SESSION['term'] && $_SESSION['class'] && $_SESSION['subject'] && $_SESSION['exam'] ))
    {
        include 'functions/actions.php';

        $obj = new DataOperations();

        $year = $term = $class = $subject = $exam = $error = $success = $marks = $message = '';

        $year = $_SESSION['year'];
        $term = $_SESSION['term'];
        $class = $_SESSION['class'];
        $form = $class[0];
        $subject = $_SESSION['subject'];
        $exam = $_SESSION['exam'];

        //get min subjects
        $sql="SELECT * FROM minimum_subjects WHERE form=$class[0]";
        $execute=mysqli_query($obj->con,$sql);

        if(mysqli_num_rows($execute)>0)
        {
            $get_min=mysqli_fetch_assoc($execute);
            $min=$get_min['minimum'];
        }
        else
        {
            $error="Please set minimum number of subjects to be done by form $class[0] <a href='minimumsubjects.php'>Here</a> before proceeding";
        }


        $period = $year . " term " . $term;

        if(isset($_POST['submit']))
        {

            //maxipain & kenny @codei

            for($i=0;$i<$_POST['count'];$i++)
            {
                $mark[$i]=$obj->con->real_escape_string(htmlentities($_POST['cat1'][$i]));
                $names[$i]=$obj->con->real_escape_string(htmlentities($_POST['names'][$i]));
                $admission[$i]=$obj->con->real_escape_string(htmlentities($_POST['admission'][$i]));
                $key[$i]=$year . $term . $subject . $_POST['admission'][$i];

                if($mark[$i]>0)
                {
                    if($mark[$i]<=30 && is_numeric($mark[$i]))
                    {
                        /*
                         * if marks are consistent,
                          check if previous results
                          exist
                        */

                        $where=array("admission"=>$admission[$i],"period"=>$period,"subject"=>$subject);
                        $fetch_results=$obj->fetch_records("results",$where);

                        //if results for selected subject exist in results table
                        if($fetch_results)
                        {

                        }
                        //if no result exist in results table
                        else
                        {
                            //fetching the grade for grading

                            $sql_grade="SELECT * FROM grading_system WHERE upper_limit>=$mark[$i] AND lower_limit<=$mark[$i]";
                            $execute_grade=mysqli_query($obj->con,$sql_grade);

                            if($execute_grade) {

                                while ($get_grades = mysqli_fetch_assoc($execute_grade)) {
                                    $grade[$i] = $get_grades['grade'];
                                    $points[$i] = $get_grades['points'];
                                    $remarks[$i] = $get_grades['remarks'];
                                }

                                $data = array(
                                    "names" => $names[$i],
                                    "admission" => $admission[$i],
                                    "class" => $class,
                                    "form" => $form,
                                    "subject" => $subject,
                                    "cat" => $mark[$i],
                                    "total" => $mark[$i],
                                    "grade" =>$grade[$i],
                                    "points"=>$points[$i],
                                    "remarks"=>$remarks[$i],
                                    "period" => $period,
                                    "cat_entered" => 1,
                                    "exam_entered" => 0,
                                    "identifier" => $key[$i]

                                );

                                if($obj->insert_record("results",$data))
                                {
                                    //we now see if this student's results exist in the final results table
                                    $where=array("admission"=>$admission[$i],"period"=>$period);
                                    $fetch_total=$obj->fetch_records("final_result",$where,$data);

                                    //if results exist, we update the current
                                    if($fetch_total)
                                    {

                                        foreach($fetch_total as $row)
                                        {
                                            $total_mark[$i]=$row['total'];

                                        }

                                        //check sciences done
                                        if($class[0]>=3)
                                        {

                                            if($subject == "CRE")
                                            {
                                                include 'functions/cre.php';
                                            }
                                            if($subject == "Physics")
                                            {
                                                include 'functions/physics.php';
                                            }
                                            if($subject == "Geography")
                                            {
                                                include 'functions/geography.php';
                                            }
                                            if($subject == "History")
                                            {
                                                include 'functions/history.php';
                                            }
                                            else
                                            {
                                                $total_mark[$i]=$total_mark[$i]+$mark[$i];
                                                $average[$i]=$total_mark[$i]/$min;

                                                //fetching the new grade using average
                                                $sql_grade="SELECT * FROM grading_system WHERE upper_limit>=round($average[$i]) AND lower_limit<=round($average[$i])";
                                                $execute_grade=mysqli_query($obj->con,$sql_grade);
                                                if($execute_grade) {

                                                    while ($get_grades = mysqli_fetch_assoc($execute_grade)) {
                                                        $grade[$i] = $get_grades['grade'];
                                                        $points[$i] = $get_grades['points'];
                                                        $remarks[$i] = $get_grades['remarks'];
                                                    }

                                                    //updating data to results table
                                                    $data = array(

                                                        $subject => $mark[$i],
                                                        "total" => $total_mark[$i],
                                                        "average" => $average[$i],
                                                        "grade" =>$grade[$i],
                                                        "points"=>$points[$i],
                                                        "remarks"=>$remarks[$i]

                                                    );
                                                    $where=array("admission"=>$admission[$i],"period"=>$period);
                                                    if($obj->update_record("final_result",$where,$data))
                                                    {
                                                        $success="Exam results for form $class in $period have been uploaded successfully";
                                                    }

                                                }
                                            }
                                        }
                                        else
                                        {
                                            $total_mark[$i]=$total_mark[$i]+$mark[$i];
                                            $average[$i]=$total_mark[$i]/$min;



                                            //fetching the new grade using average
                                            $sql_grade="SELECT * FROM grading_system WHERE upper_limit>=round($average[$i]) AND lower_limit<=round($average[$i])";
                                            $execute_grade=mysqli_query($obj->con,$sql_grade);
                                            if($execute_grade) {

                                                while ($get_grades = mysqli_fetch_assoc($execute_grade)) {
                                                    $grade[$i] = $get_grades['grade'];
                                                    $points[$i] = $get_grades['points'];
                                                    $remarks[$i] = $get_grades['remarks'];
                                                }

                                                //updating data to results table
                                                $data = array(

                                                    $subject => $mark[$i],
                                                    "total" => $total_mark[$i],
                                                    "average" => $average[$i],
                                                    "grade" =>$grade[$i],
                                                    "points"=>$points[$i],
                                                    "remarks"=>$remarks[$i]

                                                );
                                                $where=array("admission"=>$admission[$i],"period"=>$period);
                                                if($obj->update_record("final_result",$where,$data))
                                                {
                                                    $success="Exam results for form $class in $period have been uploaded successfully";
                                                }

                                            }
                                        }



                                    }
                                    //if results do not exist
                                    else
                                    {

                                        //entering new data


                                        $data = array(
                                            "names" => $names[$i],
                                            "admission" => $admission[$i],
                                            "class" => $class,
                                            "form" =>$class[0],
                                            "period" => $period,
                                            $subject => $mark[$i],
                                            "total" => $mark[$i],
                                            "count" => 1,
                                            "average" => $mark[$i]/$min,
                                            "grade" =>$grade[$i],
                                            "points"=>$points[$i],
                                            "remarks"=>$remarks[$i]

                                        );

                                        if($obj->insert_record("final_result",$data))
                                        {
                                            $success="Exam results for form $class in $period have been uploaded successfully";
                                        }
                                        else
                                        {
                                            $error="Error in calculating total and average results for some fields";
                                        }
                                    }
                                }


                            }
                            else
                            {
                                $error="Problem with grading system";
                            }

                        }




                    }
                    else if($mark[$i]>30 or !is_numeric($mark[$i]))
                    {
                        $error="Some fields contain invalid marks thus have not been saved (Please note that cat 1 marks should not be more than 30";
                    }

                }

            }

        }
    }
    else
    {
        header('location:enterresults.php?Please fill all fields');
    }

}
else
{
    header('location:login.php');
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Enter CAT results <?=$subject?> for <?=$_SESSION['class']?></title>
    <!-- Bootstrap Styles-->
    <?php include "plugins/resources.php" ?>


</head>

<body>
<!--   navigation plugin-->
<?php include "plugins/navigation.php" ?>
<!--/.navigation-->
<div id="page-wrapper">
    <div id="page-inner">
        <?php include 'plugins/resultsnav.php' ?>

        <!--    content /-->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Form <?=$class?> CAT  (<?=$subject?>) results for <?=$period?>
                    </div>
                    <div class="panel-body">
                        <?php
                        //checking if selected class has students
                        $sql="SELECT * FROM student WHERE class='$class'";
                        $exe=mysqli_query($obj->con,$sql);

                        if(mysqli_num_rows($exe)==0)
                        {
                            ?>
                            <div class="alert alert-danger">
                                No students exists in form <?=$class?><br/>
                                <a class="btn btn-primary" href="enterresults.php">Back</a>
                            </div>
                        <?php
                        }
                        else
                        {
                            ?>
                            <div class="alert alert-info">
                                <b>Teacher instructions !</b><br/>
                                <ul>
                                    <li>If a certain student does not take this subject, leave the field blank, it will not be saved</li>
                                    <li>Marks entered must be numerical and less than or equal to 30, fields with other values will not be saved</li>
                                    <li>After submitting results, verify that all student marks have been entered</li>
                                    <li>If the table is blank, this means that Form <?=$class?> <?=$subject?> CAT 1 results for <?=$period?> have been entered, you can edit their results here</li>
                                </ul>
                            </div>
                            <?php
                            if($success)
                            {
                                $obj->successDisplay($success);
                            }
                            if($error)
                            {
                                $obj->errorDisplay($error);
                            }
                            ?>
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Admission number</th>
                                        <th>Student names</th>
                                        <th>Cat 1 result field (X/30)</th>
                                    </tr>

                                    </thead>
                                    <tbody>
                                    <?php
                                    $sql = "SELECT names,AdmissionNumber FROM student WHERE class='$class' AND AdmissionNumber NOT IN (SELECT admission FROM results WHERE period='$period' AND subject='$subject' AND cat_entered=1)";
                                    $exe = mysqli_query($obj->con, $sql);
                                    $count = mysqli_num_rows($exe);
                                    for ($i = 1; $i <= $count; $i++) {
                                        while ($row = mysqli_fetch_assoc($exe)) {

                                            ?>

                                            <tr>
                                                <td>
                                                    <?php echo $row['AdmissionNumber']?>
                                                    <input type="hidden" name="admission[]"
                                                           value="<?= $row['AdmissionNumber'] ?>"/>
                                                    <input type="hidden" name="names[]" value="<?= $row['names'] ?>"/>
                                                    <input type="hidden" name="class[]" value="<?= $row['class'] ?>"/>
                                                    <input type="hidden" name="count" value="<?= $count ?>"/>
                                                </td>
                                                <td><?php echo $row['names']?></td>
                                                <td><input type="text" class="form-control" name="cat1[]"
                                                        /></td>
                                                </td>
                                            </tr>
                                        <?php

                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                                <button type="submit" name="submit" class="btn btn-primary"><span
                                        class="glyphicon glyphicon-save"></span>Save data
                                </button>
                            </form>

                        <?php
                        }

                        ?>
                    </div>
                </div>
            </div>
        </div>
        <!--            -->
    </div>
</div>
<?php include "plugins/scripts.php"?>
<script>
    $('.table').DataTable();
</script>
</body>
</html>