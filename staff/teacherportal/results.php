<?php
session_start();
$_SESSION['student-result']='';
$_SESSION['view_results_term']='';
$_SESSION['view_results_class']= '';
$_SESSION['view_results_subject']= '';


if(!$_SESSION['account']){


    header('location:../index.php');

}
else{


    include "functions/actions.php";
    $obj=new DataOperations();




    $class=$period=$form=$subject=$error='';

    if(isset($_POST['submit']))
    {
        $period = $obj->con->real_escape_string(htmlspecialchars($_POST['period']));
        $form = $obj->con->real_escape_string(htmlspecialchars($_POST['form']));
        $class = $obj->con->real_escape_string(htmlspecialchars($_POST['class']));
        $subject= $obj->con->real_escape_string(htmlspecialchars($_POST['subject']));

        if($form!="Form" && $class!="Class")
        {
            $error="Please choose only 1 field in the green box";
        }
        else if($form!="Form" && $class=="Class")
        {

            //results for specific form
            if($subject=="all subjects")
            {
                $_SESSION['view_results_term']=$period;
                $_SESSION['view_results_class']= $form[5];
                $_SESSION['view_results_subject']= $subject;

               header("location:allresults.php");


            }
            else
            {
                $_SESSION['view_results_term']=$period;
                $_SESSION['view_results_class']= $form[5];
                $_SESSION['view_results_subject']= $subject;

                header("location:subjectresults.php");
            }
        }
        else if($form=="Form" && $class!="Class")
        {
            //results for specific class
            if($subject == "all subjects")
            {
                $_SESSION['view_results_term']=$period;
                $_SESSION['view_results_class']= $class;
                $_SESSION['view_results_subject']= $subject;

                header("location:classresults.php");
            }
            else
            {
                $_SESSION['view_results_term']=$period;
                $_SESSION['view_results_class']= $class;
                $_SESSION['view_results_subject']= $subject;

                header("location:subject_result_class.php");
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
    <title>Fill all fields to view results</title>
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
            <div class="panel panel-default">
                <div class="panel-heading">
                    Please select the category of results that you want to view
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
                                    <label>Select term/year</label>
                                    <select class="form-control" name="period">

                                        <?php

                                        $sql="SELECT distinct period FROM results";
                                        $execute=mysqli_query($obj->con,$sql);

                                        while($get_period=mysqli_fetch_assoc($execute))
                                        {
                                            $exam_period=$get_period['period'];
                                            echo "<option>$exam_period</option>";
                                        }
                                        ?>
                                    </select>
                                    <label>Select subject</label>
                                    <select name="subject" class="form-control">
                                        <option>all subjects</option>
                                        <?php

                                        $sql="SELECT distinct subject FROM results";
                                        $execute=mysqli_query($obj->con,$sql);

                                        while($get_subject=mysqli_fetch_assoc($execute))
                                        {
                                            echo "<option>".$get_subject['subject']."</option>";
                                        }

                                        ?>
                                    </select>
                                    <br/>
                                    <div class="alert alert-success">
                                        <label>I want to view results for?</label>
                                   <div class="form-inline">
                                       <select class="form-control" name="form" >
                                           <option>Form</option>
                                           <option>Form 1</option>
                                           <option>Form 2</option>
                                           <option>Form 3</option>
                                           <option>Form 4</option>
                                       </select>
                                       <label>Or</label>
                                       <select class="form-control" name="class">
                                           <option>Class</option>
                                           <?php

                                           $sql="SELECT distinct class FROM results";
                                           $execute=mysqli_query($obj->con,$sql);

                                           while($get_class=mysqli_fetch_assoc($execute))
                                           {

                                               echo "<option>".$get_class['class']."</option>";
                                           }
                                           ?>
                                       </select>
                                   </div>
                                    </div>
                                    <button type="submit" name="submit" class="btn btn-lg btn-primary">Proceed</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    </div><!-- end page inner-->
</div><!--end page wrapper-->

<?php include "plugins/scripts.php"?>


</body>
</html>
