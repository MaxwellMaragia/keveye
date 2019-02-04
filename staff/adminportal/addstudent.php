<?php

session_start();

if(!$_SESSION['admin_login']){

    header('location:../index.php');

}
else
{
    include "functions/manageStudents.php";
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add student</title>
    <!-- Bootstrap Styles-->
    <?php include "plugins/resources.php" ?>

    <link rel="stylesheet" href="../assets/css/jquery-ui.css"/>

</head>

<body>
<!--   navigation plugin-->
<?php include "plugins/navigation.php" ?>
<!--/.navigation-->
<div id="page-wrapper">
    <div id="page-inner">

        <?php include 'plugins/secondarynav.php' ?>
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Student admission form
                </div>
                <div class="panel-body">
                    <form action="<?php echo htmlentities($_SERVER['PHP_SELF'])?>" method="post">
                        <div class="form-group">
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
                            <div class="col-md-6 col-md-offset-2">

                                <div class="form-group">
                                    <input type="text" placeholder="Full names" class="form-control" name="names" value="<?=$names?>" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" placeholder="Admission" class="form-control" name="admission" value="<?=$admission?>" required>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="house" required="required">
                                        <option value="">Select House</option>
                                        <option>Kilimanjaro</option>
                                        <option>Tsunami</option>
                                        <option>Sunrise</option>
                                        <option>New Markerere</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="class" required="required">
                                        <option value="">Select class</option>
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
                                    <input type="number" placeholder="KCPE marks" class="form-control" name="kcpe" value="" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="mobile" value="<?=$mmobile?>" placeholder="Mobile number" required>
                                </div>
                                <div class="form-group">
                                <button class="btn btn-primary" name="save" type="submit" >Admit student</button>
                                </div>
                                
                            </div>
                            <!-- <div class="col-md-6 pull-right">
                                <label>Parent details </label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="fname" value="<?=$fname?>" placeholder="Fathers names (Not compulsory)">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="mname" value="<?=$mname?>" placeholder="Mothers names (Not compulsory)">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="fmobile" value="<?=$fmobile?>" placeholder="Fathers mobile number (Not compulsory)">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="mmobile" value="<?=$mmobile?>" placeholder="Mothers mobile number (Not compulsory)">
                                </div>
                            </div> -->
                            
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include "plugins/scripts.php" ?>

</body>
</html>
