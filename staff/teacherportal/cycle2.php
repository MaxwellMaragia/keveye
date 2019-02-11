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
    <title>Enter results-individual student</title>
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
            $where = array("AdmissionNumber"=> $_SESSION['student-result']);
            $fetch_student=$obj->fetch_records("student",$where);
                foreach ($fetch_student as $row)

                    $adm = $row['AdmissionNumber'];
                    $nam = $row['names'];
                    $class = $row['class'];

                {
                    //echo the student details
                    ?>
                    <ul class="list-group" style="margin-top:10px;">
                        <li class="list-group-item"> <b>Enter Cycle 2 Results For; </b>Student name : <?= $nam ?> | Admission number : <?= $adm ?> | Class : form <?= $class ?></li>
                    </ul>
                <?php

                }
            ?>
                            <div class="col-md-8 col-md-offset-1 form-group">
                                <form name="form"  method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">

                                <label name="Text">Academic year</label>
                                <input type="text" class="form-control" placeholder=" eg 2018" name="academic-year" value=""  required/>
                                <label>Term</label>
                                <select class="form-control" name="term" required="required">
                                    <option value="">Select term</option>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                </select>
                                    <label for="">Teacher initials</label>
                                    <input type="text" name="initials" placeholder="eg HHM" class="form-control" required="required">
                                <label for="">Enter Subject Scores</label>
                                <?php
                                 $fetch_subjects=$obj->fetch_all_records("subject");
                                 foreach ($fetch_subjects as $row) 
                                 {
                                  $sub = $row['SubjectName'];
                                  echo "<input type='number' name='subject' placeholder='$sub' class='form-control'></br>";
                                 }
                                ?>
                                <div class="form-group pull-right">
                                    <button type="submit" name="submit" class="btn btn-primary" style="margin-top:10px;">Proceed</button>
                                </div>
                                   </form>
                            </div>
         <?php
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
