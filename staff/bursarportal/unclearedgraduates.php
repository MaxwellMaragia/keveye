<?php
session_start();
$_SESSION['student-admission']='';
if(!isset($_SESSION['bursar_account']))
{
    header('location:../index.php');

}else{
    $_SESSION['student-admission']='';
    include_once 'functions/actions.php';
    $obj = new DataOperations();
}

?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Graduates with fee balance</title>
    <!-- Bootstrap Styles-->
    <?php include "plugins/resources.php"?>


</head>

<body>
<!--   navigation plugin-->
<?php include "plugins/navigation.php"?>
<!--/.navigation-->
<div id="page-wrapper">
    <div id="page-inner">
        <!--        secondary nav-->
        <?php

        include 'plugins/secondarynav.php';

        ?>
        <!--        end nav-->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="fa fa-list-ul"></span>
                List of graduates with fee balance
            </div>
            <div class="panel-body">
                <table class="table table-striped table-bordered" id="example">
                    <thead>
                    <tr>
                        <th>Graduate names</th>
                        <th>Admission</th>
                        <th>Fee balance</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $obj=new DataOperations();
                    $sql = "SELECT * FROM graduates WHERE fees<0";
                    $execute=mysqli_query($obj->con,$sql);

                    while($get_graduates =mysqli_fetch_assoc($execute))
                    {
                        ?>
                        <tr>
                            <td><?php echo $get_graduates['names']?></td>
                            <td><?php echo $get_graduates['admission']?></td>
                            <td><?php echo $get_graduates['fees']*-1?></td>
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
<?php include "plugins/scripts.php" ?>
<?php include "plugins/table.php" ?>
</body>
</html>
