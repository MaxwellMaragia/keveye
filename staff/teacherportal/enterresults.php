<?php
$_SESSION['student-result']='';
session_start();

if(!$_SESSION['account']){


    header('location:../index.php');

}
else{


    include "functions/actions.php";
    $obj=new DataOperations();

    $_SESSION['year']='';
    $_SESSION['term']='';
    $_SESSION['class']= '';
    $_SESSION['subject']= '';
    $_SESSION['exam']= '';
    $_SESSION['student_id']='';
    $_SESSION['student_search_error']='';

    $class=$year=$term=$error='';

    if(isset($_POST['submit']))
    {
        $year = $obj->con->real_escape_string(htmlspecialchars($_POST['academic-year']));
        $term = $obj->con->real_escape_string(htmlspecialchars($_POST['term']));
        $class= $obj->con->real_escape_string(htmlspecialchars($_POST['class']));
        $subject= $obj->con->real_escape_string(htmlspecialchars($_POST['subject']));
        $exam= $obj->con->real_escape_string(htmlspecialchars($_POST['exam']));

        if(!$obj->validate_int($year)){

            $error = "Enter valid year";

        }
        else if(!is_numeric($term))
        {
            return false;
        }
        else if($class == "Class list")
        {
            $error = "Please select  class";
        }
        else if($subject == "choose")
        {
            $error="Please select subject";
        }
        else if($exam == "choose")
        {
            $error="Please select the exam type";
        }
        else
        {
            $_SESSION['year']=$year;
            $_SESSION['term']=$term;
            $_SESSION['class']= $class;
            $_SESSION['subject']= $subject;
            $_SESSION['exam']= $exam;

            //sending the teacher to the respective page for grading

            if($exam == "CAT")
            {
                header('location:cat.php');
            }
            else if($exam == "EXAM")
            {
                header('location:mainexam.php');
            }


        }

    }

}


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Enter student results</title>
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

    <?php
      
      $where=array("module"=>"add results");
      $get_state=$obj->fetch_records("modules",$where);
      foreach($get_state as $row)
      {
        $state=$row['state'];
      }

      if($state=="unlocked")
      {
        ?>
              <div class="panel panel-default">
                    <div class="panel-heading">
                        Please select the examination period
                    </div>
              <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <?php

                            if($error)
                            {
                                $obj->errorDisplay($error);
                            }

                            ?>
                            <div class="form-group">
                                <form name="form"  method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">

                                <label name="Text">Academic year</label>
                                <input type="text" class="form-control" placeholder=" eg 2018" name="academic-year" value="<?=$year?>"  required/>
                                    <label>Term</label>
                                <select class="form-control" name="term" required="required">
                                    <option value="">Select term</option>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                </select>
                                    <label for="">Select class</label>
                                    <select  class="form-control" name="class" required="required">
                                        <option value="">Class list</option>
                                        <?php
                                        $fetch_class=$obj->fetch_all_records("class");
                                        foreach($fetch_class as $row)
                                        {
                                            echo "<option>".$row['initials']."</option>";
                                        }
                                        ?>
                                    </select>
                                    <label for="">Select subject</label>
                                    <select  class="form-control" name="subject" required="required">
                                        <option value="">choose</option>
                                        <?php
                                        $fetch_subjects=$obj->fetch_all_records("subject");
                                        foreach($fetch_subjects as $row)
                                        {
                                            echo "<option>".$row['SubjectName']."</option>";
                                        }
                                        ?>
                                    </select>
                                    <label for="">Select exam type</label>
                                    <select  class="form-control" name="exam" required="required">
                                        <option value="">choose</option>
                                        <option value="CAT">CYCLE 1</option>
                                        <option value="EXAM">CYCLE 2</option>
                                    </select>
                                <div class="form-group pull-right">
                                    <button type="submit" name="submit" class="btn btn-primary" style="margin-top:10px;">Proceed</button>
                                </div>
                                   </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
      }
      else
      {
        ?>
       <div class="alert alert-danger">
       Oops!
       <hr>
           This module has been locked. Contact the administrator to unlock module
       </div>
        <?php
      }

    ?>

    </div><!-- end page inner-->
</div><!--end page wrapper-->

<?php include "plugins/scripts.php"?>


</body>
</html>

