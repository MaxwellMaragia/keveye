 <?php
 session_start();
 $_SESSION['student-result']='';
if(!$_SESSION['account']){


    header('location:../index.php');


}
else
{
    include_once 'functions/actions.php';
    $obj = new DataOperations();
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>View classes</title>
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


<!--        -->


        <div class="row">
            <div class="col-md-12 " style="padding-top:20px;">
                <div class="panel panel-default">
                    <div class="panel-heading">Class data records</div>
                    <div class="panel-body">
                        <ul class="list-group">
                            <?php

                            $obj=new DataOperations();
                            $get_class=$obj->fetch_all_records("class");

                             foreach($get_class as $row)
                             {
                                 ?>
                                 <li class="list-group-item">
                                     <?php echo $row['initials']?>
                                 </li>
                                 <?php
                             }

                            ?>

                        </ul>

                    </div>
                </div>
            </div>
        </div>



    </div><!-- end page inner-->
</div><!--end page wrapper-->
<?php include "plugins/scripts.php"?>


<script>
    $('.table').DataTable();
</script>
</body>
</html>