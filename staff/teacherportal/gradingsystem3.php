<?php
session_start();
$_SESSION['student-result']='';
include 'functions/actions.php';
$upper=$lower=$grade=$success=$error=$max=$min='';
$obj=new DataOperations();

if($_SESSION['account']){



    if(isset($_POST['submit']))
    {
        $obj=new DataOperations();

        $upper=$obj->con->real_escape_string(htmlentities($_POST['upper']));
        $lower=$obj->con->real_escape_string(htmlentities($_POST['lower']));
        $grade=$obj->con->real_escape_string(htmlentities($_POST['grade']));
        $remarks=$obj->con->real_escape_string(htmlentities($_POST['remarks']));

        if($grade=="A")
        {
            $points=12;
        }
        if($grade=="A-")
        {
            $points=11;
        }
        if($grade=="B+")
        {
            $points=10;
        }
        if($grade=="B")
        {
            $points=9;
        }
        if($grade=="B-")
        {
            $points=8;
        }
        if($grade=="C+")
        {
            $points=7;
        }
        if($grade=="C")
        {
            $points=6;
        }
        if($grade=="C-")
        {
            $points=5;
        }
        if($grade=="D+")
        {
            $points=4;
        }
        if($grade=="D")
        {
            $points=3;
        }
        if($grade=="D-")
        {
            $points=2;
        }
        if($grade=="E")
        {
            $points=1;
        }

        if(!is_numeric($upper) || !is_numeric($lower))
        {
            $error="Maximum and minimum mark must be numerical";
        }
        else if($upper>100 || $lower>100)
        {
            $error="Marks entered must be lower than 100";
        }
        else if($grade == "grade")
        {
            $error="Please select grade";
        }
        else
        {
            $where=array("grade"=>$grade);

            if($obj->fetch_records("grading_system3",$where))
            {
                $error="Grade already exists";
            }
            else
            {
                $data=array(

                    "upper_limit"=>$upper,
                    "lower_limit"=>$lower,
                    "grade"=>$grade,
                    "points"=>$points,
                    "remarks"=>$remarks

                );

                //checking if the grading system is valid

                $get_grades=$obj->fetch_all_records("grading_system3");
                foreach($get_grades as $row)
                {
                    $max=$row['upper_limit'];
                    $min=$row['lower_limit'];
                }
                if($max>=$lower || $min>=$lower || $max>=$upper || $min>=$upper)
                {
                    $error="Invalid upper or lower limit";
                }
                else
                {
                    //saving data to database
                    if($obj->insert_record("grading_system3",$data))
                    {
                        $success="Grade saved successfully";
                    }
                    else
                    {
                        $error=mysqli_error($obj->con);
                    }
                }


            }

        }

    }

}




else{

    header('location:../index.php');

}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Form three grading system</title>
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
        <!--        content-->
        <div class="panel panel-default">
            <div class="panel-heading">
                Edit form 3 grading system
            </div>
            <div class="panel-body">
                <?php



                $where=array("module"=>"grading system");
                $get_state=$obj->fetch_records("modules",$where);
                foreach($get_state as $row)
                {
                    $state=$row['state'];
                }

                if($state=="unlocked")
                {
                    ?>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" style="margin-bottom: 10px;">
                        Clear grading system
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="alert alert-danger">Are you sure you want to clear the current grading system?</div>
                                </div>
                                <div class="modal-footer">
                                    <form action="functions/cleargrade.php" method="post">
                                        <button type="submit" name="clear" class="btn btn-primary">Clear</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        Note!! start with lower grades going up
                        <br/>
                        <div class="form-inline">
                            <form action="<?php echo htmlentities($_SERVER['PHP_SELF'])?>" method="post">
                                <input type="text" class="form-control" placeholder="Minimum mark" name="lower" required/>
                                <input type="text" class="form-control" placeholder="Maximum mark" name="upper" required/>
                                <select class="form-control" name="grade">
                                    <option>grade</option>
                                    <option>A</option>
                                    <option>A-</option>
                                    <option>B+</option>
                                    <option>B</option>
                                    <option>B-</option>
                                    <option>C+</option>
                                    <option>C</option>
                                    <option>C-</option>
                                    <option>D+</option>
                                    <option>D</option>
                                    <option>D-</option>
                                    <option>E</option>
                                </select>
                                <input type="text" class="form-control" placeholder="Remarks" name="remarks" required/>
                                <button type="submit" name="submit" class="btn btn-warning"><span class="glyphicon glyphicon-save"></span>Save</button>
                            </form>
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
                        This module has been locked hence you cannot modify existing grading system. Contact the administrator to unlock module
                    </div>
                <?php
                }

                ?>
                <?php

                if($error)
                {
                    $obj->errorDisplay($error);
                }
                if($success)
                {
                    $obj->successDisplay($success);
                }
                ?>
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Minimum mark</th>
                        <th>Maximum mark</th>
                        <th>Grade</th>
                        <th>Points</th>
                        <th>Remarks</th>
                    </tr>

                    </thead>
                    <tbody>
                    <?php
                    $object=new DataOperations();
                    $get_grades=$object->fetch_all_records("grading_system3");
                    foreach($get_grades as $row)
                    {
                        ?>
                        <tr>
                            <td>
                                <?php echo $row['lower_limit']?>
                            </td>
                            <td>
                                <?php echo $row['upper_limit']?>
                            </td>
                            <td>
                                <?php echo $row['grade']?>
                            </td>
                            <td>
                                <?php echo $row['points']?>
                            </td>
                            <td>
                                <?php echo $row['remarks']?>
                            </td>

                        </tr>
                    <?php
                    }

                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!--        end-->
    </div>
</div>
<!--javascript plugin-->
<?php include "plugins/scripts.php" ?>
<!--//javascript-->
</body>
</html>
