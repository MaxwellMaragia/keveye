<?php

session_start();

if(!$_SESSION['admin_login']){


    header('location:../index.php');

}


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit student details</title>
    <!-- Bootstrap Styles-->
    <?php include "plugins/resources.php" ?>
    <?php include "functions/manageStudents.php" ?>

</head>

<body>
<!--   navigation plugin-->
<?php include "plugins/navigation.php" ?>
<!--/.navigation-->
<div id="page-wrapper">
    <div id="page-inner">

        <?php include 'plugins/secondarynav.php' ?>
        <!--        contents-->
        <form  method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>" style="margin-bottom:20px;">
            <div class="input-group-lg">
                <input type="text" id="student-search" class="form-control" placeholder="Name or admission" name="keyword" required="required">
                <input type="submit" name="submit" class="hidden">
            </div>
        </form>
        <?php


        if($success)
        {
            $obj->successDisplay($success);
        }
        else if($error)
        {
            $obj->errorDisplay($error);
        }

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
        $category = $row['category'];
        $house = $row['house'];
        // $fname = $row['fathersNames'];
        // $mname = $row['mothersNames'];
        // $fmobile = $row['fathersmobile'];
        $mobile = $row['mobile'];
        $fees = $row['fee_paid']-$row['fee_owed'];
        $account_status=$row['account'];
        if($account_status == "active")
        {
            $account_status = "deactivate account";
        }
        else
        {
            $account_status = "activate account";
        }



        }
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default" style="margin-top:5px;">
                    <div class="panel-heading">
                        Search results
                    </div>
                    <div class="panel-body">
                        <div class="col-md-6 pull-left">
                            <ul class="list-group">
                                <li class="list-group-item active">
                                    Student details
                                </li>
                                <li class="list-group-item">
                                    Names - <?php echo $names?>
                                </li>
                                <li class="list-group-item">
                                    Admission number - <?php echo $admission?>
                                </li>
                                <li class="list-group-item">
                                    Class - Form <?php echo $class?>
                                </li>
                                <li class="list-group-item">
                                    House - <?php echo $house?>
                                </li>
                            </ul>
                        </div>


                        <div class="col-md-6 pull-left">
                            <ul class="list-group">
                                <li class="list-group-item active">
                                    Contact details
                                </li>
                                <!-- <li class="list-group-item">
                                    Father -
                                    <?php

                                    if(empty($fname))
                                    {
                                        echo "Not entered";
                                    }
                                    else
                                    {
                                        echo $fname." ($fmobile)";
                                    }

                                    ?>
                                </li> -->
                                <li class="list-group-item">
                                    Mobile Number -
                                    <?php

                                       echo $mobile;
                                    ?>
                                </li> 
                                <!-- <li class="list-group-item">
                                    Fees -
                                    <?php
                                    if($fees<0)
                                    {
                                        echo "<f style='color:red;'>(Uncleared) balance ".$fees*-1.;"</f>";
                                    }
                                    else if($fees>=0)
                                    {
                                        echo "<f style='color:#008000;'>(Cleared) Overpayment ".$fees."</f>";
                                    }
                                    ?>.
                                </li> -->
                            </ul>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="btn-group">

                            <form action="<?php echo htmlentities($_SERVER['PHP_SELF'])?>" method="post">
                                <a href="#edit<?php echo $admission;?>" data-toggle="modal"><button type="button" class="btn btn-info"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span>Edit</button> </a>
                                <button class="btn btn-danger" disabled="disabled" type="submit" value="<?php echo $admission?>" name="delete"><span class="glyphicon glyphicon-trash"></span>Delete</button>
                                <button name='activate_account' value="<?php echo $admission?>" class='btn-link'><?php echo $account_status?></button>
                            </form>
                            <!-- Modal edit-->
                            <div class="modal fade" id="edit<?php echo $admission?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalLabel">Edit <?=$names?> details</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Student names</label>
                                                    <input type="text" placeholder="Full names" class="form-control" name="names" value="<?=$names?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Admission</label>
                                                    <input  disabled placeholder="Admission" class="form-control"  value="<?=$admission?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <input type="hidden" name="admission" value="<?=$admission?>">
                                                </div>
                                                <div class="form-group">
                                                    <label>Class</label>
                                                    <select class="form-control" name="class">
                                                        <option><?php echo $class?></option>
                                                        <?php

                                                        $get_streams=$obj->fetch_all_records("class");
                                                        foreach($get_streams as $row)
                                                        {
                                                            $stream_name=$row['initials'];

                                                            echo "<option>$stream_name</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="category">Category</label>
                                                    <select name="category" class="form-control" required="required">
                                                        <option><?=$category?></option>
                                                        <option>
                                                            <?php
                                                            if($category=="day")
                                                            {
                                                                echo "boarding";
                                                            }
                                                            else if($category=="boarding")
                                                            {
                                                                echo "day";
                                                            }
                                                            ?>
                                                        </option>

                                                    </select>
                                                </div>
                                                <!-- <div class="form-group">
                                                    <label>Father</label>
                                                    <input type="text" class="form-control" name="fname" value="<?=$fname?>" placeholder="Fathers names">
                                                </div> -->
                                                <!-- <div class="form-group">
                                                    <label>Mother</label>
                                                    <input type="text" class="form-control" name="mname" value="<?=$mname?>" placeholder="Mothers names">
                                                </div> -->
                                                <!-- <div class="form-group">
                                                    <label>Fathers mobile</label>
                                                    <input type="text" class="form-control" name="fmobile" value="<?=$fmobile?>" placeholder="Fathers mobile number">
                                                </div> -->
                                                <div class="form-group">
                                                    <label>Mobile Phone</label>
                                                    <input type="text" class="form-control" name="mobile" value="<?=$mmobile?>" placeholder="mobile number">
                                                </div>
                                                <div class="form-group">
                                                    <button class="btn btn-primary" name="update" type="submit" >Update</button>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!--end//modal edit-->
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
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger">
                        No student found for name or admission <b><?php echo $keyword?></b>
                    </div>
                </div>
            </div>

        <?php
        }



        }
        ?>
        </div>
    </div>
<?php include "plugins/scripts.php"?>
</body>
</html>
