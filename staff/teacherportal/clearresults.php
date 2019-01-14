<?php
$_SESSION['student-result']='';
session_start();

if(!$_SESSION['account']){
    header('location:../index.php');
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Delete past results</title>
    <!-- Bootstrap Styles-->
    <?php include "plugins/resources.php" ?>
    <?php include "functions/clearResults.php" ?>

</head>

<body>
<!--   navigation plugin-->
<?php include "plugins/navigation.php" ?>
<!--/.navigation-->
<div id="page-wrapper">
    <div id="page-inner">
        <!--        contents-->
        <?php include 'plugins/secondarynav.php' ?>

         <?php
      
      $where=array("module"=>"clear results");
      $get_state=$obj->fetch_records("modules",$where);
      foreach($get_state as $row)
      {
        $state=$row['state'];
      }

      if($state=="unlocked")
      {
        ?>
        <div class="alert alert-info">
            Notice!!
            <hr/>
            It is important to delete past exam results so as to enhance faster performance of the results processing system that may become clogged with many results as they are uploaded
            <hr/>
            This process cannot be reversed
            <hr/>
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
            <div class="form-inline">
                <form action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="post">
                    <select class="form-control" name="period" required="required">
                        <option value="">Select period</option>
                        <?php

                        $sql="SELECT distinct period FROM results";
                        $execute=mysqli_query($obj->con,$sql);

                        while($get_period=mysqli_fetch_assoc($execute))
                        {
                            $exam_period=$get_period['period'];
                            echo "<option>$exam_period</option>";
                        }
                        ?>
                    </select>
                    <button class="btn btn-primary" type="submit" name="delete">
                        Clear results
                    </button>
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
           This module has been locked. Contact the administrator to unlock module
       </div>
        <?php
      }

    ?>
        
    </div>
</div>
<?php include "plugins/scripts.php" ?>
</body>
</html>