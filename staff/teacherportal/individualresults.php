<?php

session_start();

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
    <title>View results-individual student</title>
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
        <form  method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">
            <div class="input-group-lg">
                <input type="text" id="student-search" class="form-control" placeholder="Name or admission" name="keyword" required="required">
                <input type="submit" name="submit" class="hidden">
            </div>
        </form>
        <?php
        $obj=new DataOperations();


        //if the user searches for a student
        if(isset($_POST['submit'])) {

            $_SESSION['search-error']='';
            $_SESSION['student-result']='';

            $keyword = $obj->con->real_escape_string(htmlentities($_POST['keyword']));

            $where = array("AdmissionNumber" => $keyword, "names" => $keyword);

            $get_data = $obj->search_engine("student", $where);

            if ($get_data) {
                foreach ($get_data as $row) {



                    $_SESSION['student-result'] = $row['AdmissionNumber'];
                    echo "<script>document.window('individualresults.php')</script>";


                }
            }
            else
            {
                $_SESSION['search-error']="No student found for the name or admission number you entered";
            }
        }


        //using session variable to store admission number to prevent duplicate fetch
        //initialised in index.php page
        if($_SESSION['student-result'])
        {
            $where=array("AdmissionNumber"=>$_SESSION['student-result']);


            //fetch student records from students database

            $fetch_student=$obj->fetch_records("student",$where);
            if($fetch_student) {

                foreach ($fetch_student as $row)

                    $adm = $row['AdmissionNumber'];
                $nam = $row['names'];
                $class = $row['class'];

                {
                    //echo the student details
                    ?>
                    <ul class="list-group" style="margin-top:10px;">
                        <li class="list-group-item">Student names : <?= $nam ?> | Admission number : <?= $adm ?> | Class : form <?= $class ?></li>
                    </ul>
                <?php




                }

                //fetch exam records from results database
                $stu_id=$_SESSION['student-result'];
                $sql = "SELECT distinct period FROM results WHERE admission='$stu_id'";
                $execute=mysqli_query($obj->con,$sql);

                //if student has exam results in database
                if(mysqli_num_rows($execute))
                {
                    ?>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Exam results
                        </div>
                        <div class="panel-body">
                            <div class="alert alert-info">
                                <form action="<?php echo htmlentities($_SERVER['PHP_SELF'])?>" method="post">
                                    <select name="period" class="form-control" onchange="this.form.submit();">
                                        <option>Choose term/year</option>
                                        <?php

                                        //show the dropdown for choosing exam result period(term)
                                        while($get_subject = mysqli_fetch_assoc($execute))
                                        {
                                            $examination_period=$get_subject['period'];

                                            echo "<option>$examination_period</option>";
                                        }



                                        ?>
                                    </select>

                                </form>
                            </div>


                            <?php

                            if(isset($_POST['period']))
                            {
                                $exam_period=$obj->con->real_escape_string($_POST['period']);
                                $where=array("admission"=>$_SESSION['student-result'],"period"=>$exam_period);
                                $get_grades=$obj->fetch_records("results",$where);
                                $_SESSION['term']=$exam_period;


                                ?>
                                <div class="row">
                                    <div class="col-md-8">
                                        <table class="table table-bordered table-responsive">
                                            <thead>
                                            <tr>
                                                <th>SUBJECT</th>
                                                <th>CYCLE 1 %</th>
                                                <th>CYCLE 2 %</th>
                                                <th>AVG %</th>
                                                <th>GRADE</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php

                                            foreach($get_grades as $row)
                                            {
                                                ?>
                                                <tr>
                                                    <td><?php echo $row['subject']?></td>
                                                    <td><?php echo $row['cat']*2?></td>
                                                    <td><?php echo $row['mid']*2?></td>
                                                    <td><?php echo $row['total']?></td>
                                                    <td><?php echo $row['grade']?></td>
                                                </tr>
                                            <?php
                                            }

                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-4">
                                        <?php

                                        $where=array("admission"=>$_SESSION['student-result'],"period"=>$exam_period);
                                        $get_final=$obj->fetch_records("final_result",$where);
                                        foreach($get_final as $row)
                                        {
                                            $count=$row['count'];
                                            $form=$row['form'];
                                            $class=$row['class'];
                                            $average=$row['average'];
                                            $total=$row['total'];
                                            $grade=$row['grade'];
                                            $points=$row['points'];
                                            $remarks=$row['remarks'];

                                        }
                                        //fetching minimum number of subjects
                                        $where=array("form"=>$form);
                                        $fetch_minimum=$obj->fetch_records("minimum_subjects",$where);
                                        if($fetch_minimum)
                                        {
                                            foreach($fetch_minimum as $row)
                                            {
                                                $minimum_number=$row['minimum'];
                                            }

                                                ?>
                                                <table class="table table-bordered table-striped">
                                                    <tbody>
                                                    <tr>
                                                        <td>Total</td>
                                                        <td><?php echo $total." out of ".$minimum_number*100?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Average</td>
                                                        <td><?php echo $average?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Grade</td>
                                                        <td><?php echo $grade?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Points</td>
                                                        <td><?php echo $points?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Remarks</td>
                                                        <td><?php echo $remarks?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Subjects done</td>
                                                        <td><?php echo $count?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Subjects graded</td>
                                                        <td><?php echo $minimum_number?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Form position</td>
                                                        <td>
                                                            <?php


                                                            $query = "SELECT admission, average FROM final_result WHERE form='$form' AND period='$exam_period' ORDER BY average DESC";
                                                            $exe = mysqli_query($obj->con,$query);
                                                            $sql_total = "SELECT admission FROM final_result WHERE form='$form'";

                                                            $rank = 0;
                                                            $student = array();
                                                            while($res = mysqli_fetch_array($exe)){
                                                                ++$rank;
                                                                $student[$res['admission']] = $rank;
                                                                $total_in_class=mysqli_num_rows($exe);
                                                            }
                                                            if($rank !== 0){
                                                                $class_position=$student[$adm];
                                                            }
                                                            echo $class_position." out of ".mysqli_num_rows(mysqli_query($obj->con,$sql_total));
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Class position</td>
                                                        <td>
                                                            <?php


                                                            $query = "SELECT admission, average FROM final_result WHERE class='$class' AND period='$exam_period' ORDER BY average DESC ";
                                                            $exe = mysqli_query($obj->con,$query);
                                                            $sql_total = "SELECT admission FROM final_result WHERE class='$class'";

                                                            $rank = 0;
                                                            $student = array();
                                                            while($res = mysqli_fetch_array($exe)){
                                                                ++$rank;
                                                                $student[$res['admission']] = $rank;
                                                            }
                                                            if($rank !== 0){
                                                                $form_position=$student[$adm];
                                                            }
                                                            echo $form_position." out of ".mysqli_num_rows(mysqli_query($obj->con,$sql_total));
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <a href="report.php" TARGET="_blank" class="btn btn-info"><span class="glyphicon glyphicon-print"></span> Print report card</a>

                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <?php

                                            }
                                            else
                                            {
                                                ?>
                                                <div class="alert alert-danger">
                                                    The minimum number of subjects for form <?=$form?> must not be less than <?=$minimum_number?>
                                                    <hr/>
                                                    Only <?=$count?> results have been uploaded for <?=$nam?> hence you cannot view his/her  total, grade and rank
                                                </div>
                                            <?php
                                            }

                                            ?>


                                    </div>
                                </div>
                            <?php

                            }

                            ?>
                        </div>
                    </div>

                <?php
                }

                //if student has no results in database
                else
                {
                    echo "<div class='alert alert-danger'>No exam results have been uploaded for $nam</div>";
                }
            }
        }

        //incase no student is found
        if($_SESSION['search-error'])
        {
            echo "<div class='alert alert-danger' style='margin-top:10px;'>".$_SESSION['search-error']."</div>";
        }




        ?>
    </div>
</div>
<?php include "plugins/scripts.php"?>

</body>
</html>
