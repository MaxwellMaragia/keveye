<?php
    session_start();
    $_SESSION['student-result']='';
    $_SESSION['edit-results-class']='';
    $_SESSION['edit-results-term']='';
    $_SESSION['edit-results-subject']='';


    if(!$_SESSION['account']){
        header('location:../index.php');
    }
    else{
        include 'functions/actions.php';
        $obj=new DataOperations();
        $error='';

        if(isset($_POST['submit']))
        {
            $_SESSION['edit-results-class']=$obj->con->real_escape_string(htmlentities($_POST['class']));
            $_SESSION['edit-results-term']=$obj->con->real_escape_string(htmlentities($_POST['period']));
            $_SESSION['edit-results-subject']=$obj->con->real_escape_string(htmlentities($_POST['subject']));

            header('location:editresults.php');
        }

    }

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EDIT RESULTS</title>
    <!-- Bootstrap Styles-->
    <?php include "plugins/resources.php" ?>

    <style>
        select{margin-top:10px;}
    </style>
</head>

<body>
<!--   navigation plugin-->
<?php include "plugins/navigation.php" ?>
<!--/.navigation-->
<div id="page-wrapper">
    <div id="page-inner">

    <div class="panel panel-default">
        <div class="panel-heading">
            Select term, subject and class to edit results
        </div>
        <div class="panel-body">
            <div class="form-group col-md-6 col-md-offset-3">
                <form name="form"  method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">

                    <?php
                      if($error)
                      {
                          $obj->errorDisplay($error);
                      }

                    ?>


                    <select class="form-control" name="period" required="required">
                        <option value="">Select term</option>
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
                    <select name="subject" class="form-control" required="required">
                        <option value="">Select subject</option>
                        <?php

                        $sql="SELECT distinct subject FROM results";
                        $execute=mysqli_query($obj->con,$sql);

                        while($get_subject=mysqli_fetch_assoc($execute))
                        {
                            echo "<option>".$get_subject['subject']."</option>";
                        }

                        ?>
                    </select>
                    <select class="form-control" name="class" required="required">
                        <option value="">Select class</option>
                        <?php

                        $sql="SELECT distinct class FROM results";
                        $execute=mysqli_query($obj->con,$sql);

                        while($get_class=mysqli_fetch_assoc($execute))
                        {

                            echo "<option>".$get_class['class']."</option>";
                        }
                        ?>
                    </select>
                    <br/>
                    <button type="submit" name="submit" class="btn btn-primary">Proceed</button>
                </form>
            </div>
        </div>
    </div>

    </div>
</div>
<?php include "plugins/scripts.php"?>
</body>

</html>
