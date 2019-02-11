<?php
session_start();
$_SESSION['student-result']='';
if(!$_SESSION['account']){
    header('location:../index.php');
}else{
    include "functions/actions.php";
    $obj = new DataOperations();

    //feth
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Search students</title>
    <!-- Bootstrap Styles-->
    <?php include "plugins/resources.php"?>


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
        <!-- search results-->
        <?php

        $obj = new DataOperations();

        if(isset($_POST['submit'])) {

            $keyword = $obj->con->real_escape_string(htmlentities($_POST['keyword']));

            $where = array("AdmissionNumber"=>$keyword,"names"=>$keyword);

            $get_data = $obj->search_engine("student",$where);

            if($get_data)
            {
                foreach($get_data as $row)
                {
                    $names = $row['names'];
                    $admission = $row['AdmissionNumber'];
                    $class = $row['class'];
                }
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default" style="margin-top: 20px;">
                            <div class="panel-heading">
                                Search results
                            </div>
                            <div class="panel-body">

                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            else
            {
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default" style="margin-top: 20px;">
                            <div class="panel-heading">
                                Search results
                            </div>
                            <div class="panel-body">
                                <div class="alert alert-danger">
                                    No results found for keyword <b><?php echo $keyword?></b>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php
            }



        }
        ?>
        <!-- -->
    </div>
</div>
<?php include "plugins/scripts.php"?>
</body>
</html>
