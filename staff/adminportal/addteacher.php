<?php

session_start();

if($_SESSION['admin_login']){

   include "functions/ManageTeachers.php";

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
    <title>Add teacher</title>
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


        <div class="row">
            <div class="col-lg-12">

          <!-- form-->
                <div class="panel panel-default teacher-registration">
                    <div class="panel-heading"><i class="fa fa-user"></i>Register teachers (Please do not use apostrophe)</div>
                    <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">

                        <div class="panel-body">

                            <?php

                            if($error){

                                $obj->errorDisplay($error);

                            }
                            else if($success){

                                $obj->successDisplay($success);

                            }


                            ?>

                    <div class="col-md-6 col-md-offset-3">
                        <label>Full Names</label>
                        <input type="text" class="form-control" placeholder="eg Margaret Wambui Mwangi" name="names" value="<?=$names?>" required>
                        <label>Job/staff number</label>
                        <input type="text" class="form-control" placeholder="number only" name="jobnumber" value="<?=$jobnumber?>" required>
                        <label>First subject</label>
                        <select class="form-control" name="subject1" required="required">
                            <option value="">select subject</option>
                            <?php
                            $fetch_subjects=$obj->fetch_all_records("subject");
                            foreach($fetch_subjects as $row)
                            {
                                echo "<option>".$row['SubjectName']."</option>";
                            }
                            ?>
                        </select>
                        <label>Second subject</label>
                        <select class="form-control" name="subject2" required="required">
                            <option value="">select subject</option>
                            <?php
                            $fetch_subjects=$obj->fetch_all_records("subject");
                            foreach($fetch_subjects as $row)
                            {
                                echo "<option>".$row['SubjectName']."</option>";
                            }
                            ?>
                        </select>
                       <div class="form-group" style="margin-top:10px;">
                           <input type="submit" name="add_teacher"  class="btn btn-primary pull-right" value="Save"/>
                           <a href="teacherlist.php">View teacher's list</a>
                       </div>
                    </div>

                    </div>
                    </form>
                </div>
            </div>
         <!-- //form-->

        </div>

<!--        page inner-->
        </div>
<!--    page wrapper-->
    </div>
<!--javascript plugin-->
<?php include "plugins/scripts.php"?>
<!--//javascript-->
</body>
</html>
