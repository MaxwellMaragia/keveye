<?php
session_start();

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
    <title>Activity logs</title>
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



        <div class="row" style="padding-top:30px;">
            <div class="col-md-12" style="margin-bottom:30px;">
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
                                <th>Librarian</th>
                                <th>Action</th>
                                <th>Date/time</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            $get_logs=$obj->fetch_all_records("book_logs");
                            foreach($get_logs as $row)
                            {
                                ?>
                                <tr>
                                    <td><?php echo $row['student_name']?></td>
                                    <td><?php echo $row['student_id']?></td>
                                    <td><?php echo $row['book_title']?></td>
                                    <td><?php echo $row['book_serial']?></td>
                                    <td><?php echo $row['librarian']?></td>
                                    <td><?php echo $row['action']?></td>
                                    <td><?php echo $row['date']?></td>
                                </tr>
                            <?php

                            }


                            ?>

                            </tbody>
                        </table>
                    </div>
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
