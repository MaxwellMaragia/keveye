<?php
$_SESSION['student-result']='';
session_start();

if(isset($_SESSION['account'])){


    //for result search module
    $_SESSION['student-result'] = '';
    $_SESSION['search-error']='';
    //    end


    include "functions/actions.php";
    $jobnumber=$_SESSION['account'];
    $obj=new DataOperations();


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
    <title>Teacher dashboard</title>
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


        <!--  title stats-->
        <div class="row">
            <div class="col-md-3">
                <div class="tile-stats tile-red">
                    <div class="icon"><i class="fa fa-graduation-cap"></i></div>
                    <div class="num" data-start="0" data-end="7"
                         data-postfix="" data-duration="1500" data-delay="0">
                        <?php

                        $sql="SELECT * FROM student";
                        $execute=mysqli_query($obj->con,$sql);
                        $total_students=mysqli_num_rows($execute);

                        echo $total_students;

                        ?>
                    </div>

                    <h3>student</h3>
                    <p>Total students</p>
                </div>

            </div>
            <div class="col-md-3">

                <div class="tile-stats tile-green">
                    <div class="icon"><i class="fa fa-user-md"></i></div>
                    <div class="num" data-start="0" data-end="2"
                         data-postfix="" data-duration="800" data-delay="0">
                        <?php

                        $sql="SELECT username FROM staff_accounts WHERE account = 'teacher'";
                        $execute=mysqli_query($obj->con,$sql);
                        $total_teachers=mysqli_num_rows($execute);

                        echo $total_teachers;

                        ?>
                    </div>

                    <h3>teacher</h3>
                    <p>Total teachers</p>
                </div>


            </div>
            <div class="col-md-3">

                <div class="tile-stats tile-aqua">
                    <div class="icon"><i class="fa fa-gears"></i></div>
                    <div class="num" data-start="0" data-end="1"
                         data-postfix="" data-duration="500" data-delay="0">
                        <?php

                        $sql="SELECT * FROM subject";
                        $execute=mysqli_query($obj->con,$sql);
                        $total_students=mysqli_num_rows($execute);

                        echo $total_students;

                        ?>
                    </div>

                    <h3>Subjects</h3>
                    <p>Total subjects</p>
                </div>

            </div>

            <div class="col-md-3">

                <div class="tile-stats tile-blue">
                    <div class="icon"><i class="fa fa-money"></i></div>
                    <div class="num" data-start="0" data-end="2"
                         data-postfix="" data-duration="500" data-delay="0">
                        <?php

                        $sql="SELECT * FROM class";
                        $execute=mysqli_query($obj->con,$sql);
                        $total_classes=mysqli_num_rows($execute);

                        echo $total_classes;

                        ?>
                    </div>

                    <h3>Classes</h3>
                    <p>Total classes</p>
                </div>

            </div>
        </div>
        <div class="row" style="margin-top:20px;">
            <div class="col-md-8">
                <div class="jumbotron">
                    <div class="container">
                        <h1>Welcome Teacher</h1>
                        <p>Use your management module to manage your students in all academic aspects</p>
                        <p><a class="btn btn-primary btn-lg" href="#" role="button">Continue</a></p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 pull-right">
                <div class="list-group">
                    <a href="#" class="list-group-item active">
                        Quick links
                    </a>
                    <a href="searchstudent.php" class="list-group-item">Search for student</a>
                    <a href="studentlist.php" class="list-group-item">List of all students</a>
                    <a href="enterresults.php" class="list-group-item">Enter student results</a>
                    <a href="gradingsystem.php" class="list-group-item">Edit/view grading system</a>
                    <a href="view.php" class="list-group-item">View class exam results</a>
                    <a href="individualresults.php" class="list-group-item">View student exam results</a>
                    <a href="subjects.php" class="list-group-item">View/Add subjects</a>
                    <a href="viewclasses.php" class="list-group-item">View all classes</a>
                </div>
            </div>

        </div>
    </div>
<!--javascript plugin-->
<?php include "plugins/scripts.php"?>
<!--//javascript-->
</body>
</html>

