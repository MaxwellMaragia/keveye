<?php
session_start();
$_SESSION['student']='';
$_SESSION['student_name']='';
if(!isset($_SESSION['librarian_account']))
{
    header("location:../index.php");
}
else
{
    include_once 'functions/actions.php';
    $obj=new DataOperations();
}
?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Librarian portal</title>
    <!-- Bootstrap Styles-->
    <?php include "plugins/resources.php" ?>


</head>

<body>
<!--   navigation plugin-->
<?php include "plugins/navigation.php" ?>
<!--/.navigation-->
<div id="page-wrapper">
    <div id="page-inner">

        <!--        secondary nav-->
        <?php

        include 'plugins/secondarynav.php';

        ?>
        <!--        end nav-->
        <!--        contents-->
        <div class="heading"></div>

        <!--        statistics-->
        <div class="row">
            <div class="col-md-3">
                <div class="tile-stats tile-red">
                    <div class="icon"><i class="fa fa-book"></i></div>
                    <div class="num" data-start="0" data-end="7"
                         data-postfix="" data-duration="1500" data-delay="0">
                        <?php


                        $sql="SELECT * FROM books";
                        $execute=mysqli_query($obj->con,$sql);
                        echo mysqli_num_rows($execute);

                        ?>
                    </div>

                    <h3>Total books</h3>
                    <p>recorded books</p>
                </div>

            </div>
            <div class="col-md-3">

                <div class="tile-stats tile-green">
                    <div class="icon"><i class="glyphicon glyphicon-book"></i></div>
                    <div class="num" data-start="0" data-end="2"
                         data-postfix="" data-duration="800" data-delay="0">
                        <?php
                        $sql="SELECT * FROM book_lend_list";
                        $execute=mysqli_query($obj->con,$sql);
                        echo mysqli_num_rows($execute);
                        ?>
                    </div>

                    <h3>borrowed books</h3>
                    <p>total borrowed</p>
                </div>


            </div>
            <div class="col-md-3">

                <div class="tile-stats tile-aqua">
                    <div class="icon"><i class="fa fa-history"></i></div>
                    <div class="num" data-start="0" data-end="1"
                         data-postfix="" data-duration="500" data-delay="0">
                        <?php

                        $sql="SELECT * FROM book_logs";
                        $execute=mysqli_query($obj->con,$sql);
                        echo mysqli_num_rows($execute);

                        ?>
                    </div>

                    <h3>Activity logs</h3>
                    <p>all activities</p>
                </div>
            </div>

            <div class="col-md-3">

                <div class="tile-stats tile-blue">
                    <div class="icon"><i class="fa fa-graduation-cap"></i></div>
                    <div class="num" data-start="0" data-end="2"
                         data-postfix="" data-duration="500" data-delay="0">
                        <?php

                        $sql="SELECT distinct admission FROM book_lend_list";
                        $execute=mysqli_query($obj->con,$sql);
                        echo mysqli_num_rows($execute);

                        ?>
                    </div>

                    <h3>Uncleared students</h3>
                    <p>Students with books</p>
                </div>

            </div>

        </div>


        <div class="row" style="padding-top:30px;">
            <div class="col-md-8" style="margin-bottom:30px;">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span class="fa fa-list-ul"></span>
                        List of students with uncleared books
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Student names</th>
                                <th>Admission</th>
                                <th>Book title</th>
                                <th>Book serial</th>
                                <th>Issued date</th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            $get_students_list=$obj->fetch_all_records("book_lend_list");

                            foreach($get_students_list as $row)
                            {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['student_names']?></td>
                                        <td><?php echo $row['admission']?></td>
                                        <td><?php echo $row['book_name']?></td>
                                        <td><?php echo $row['book_id']?></td>
                                        <td><?php echo $row['date_assigned']?></td>
                                    </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4 pull-right">
                <div class="list-group">
                    <a href="#" class="list-group-item active">
                        Quick links
                    </a>
                    <a href="addbooks.php" class="list-group-item">Add books</a>
                    <a href="lendbooks.php" class="list-group-item">Issue books</a>
                    <a href="booklist.php" class="list-group-item">Booked books</a>
                    <a href="lendlist.php" class="list-group-item">Students with books</a>
                    <a href="logs.php" class="list-group-item">library activity logs</a>
                    <a href="changepassword.php" class="list-group-item">Update your profile</a>
                </div>
            </div>
        </div>
    </div>
    <!-- /. ROW  -->





</div>
</div>
<?php include "plugins/scripts.php"?>
<script>
    $('.table').DataTable();
</script>
</body>
</html>
