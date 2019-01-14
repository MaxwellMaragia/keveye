<?php
session_start();
if(!isset($_SESSION['bursar_account']))
{
    header('location:../index.php');
}
else{

    include "functions/actions.php";
    $bursar_id=$_SESSION['bursar_account'];
    $obj = new DataOperations();
    $where_bursar=array("username"=>$bursar_id,"account"=>'bursar');
    $get_bursar=$obj->fetch_records("staff_accounts",$where_bursar);

    foreach($get_bursar as $row)
    {
        $bursar_name=$row['names'];
    }


}

?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Enter fees</title>
    <!-- Bootstrap Styles-->
    <?php include "plugins/resources.php"?>

<!--    --><?php //include "functions/savefees.php"; ?>

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
        <?php
      
      $where=array("module"=>"pay student fees");
      $get_state=$obj->fetch_records("modules",$where);
      foreach($get_state as $row)
      {
        $state=$row['state'];
      }

      if($state=="unlocked") {

          $where=array("state"=>"in progress");
          $get_term=$obj->fetch_records("terms",$where);
          if($get_term){

              foreach($get_term as $row)
              {
                  $term=$row['term'];
              }

              ?>
              <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">
                  <div class="input-group-lg">
                      <input type="text" id="student-search" class="form-control" placeholder="Name or admission"
                             name="keyword" required="required">
                      <input type="submit" name="submit" class="hidden">
                  </div>
              </form>
          <?php

          }
          else {

              echo "<div class='alert alert-danger'>Please inform the admin to initiate the current term so as to start clearing students</div>";

          }
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


<!--        search results-->
        <?php

//        variables
        $error=$success='';



        if(isset($_POST['submit'])) {

        $keyword = $obj->con->real_escape_string(htmlentities($_POST['keyword']));

        $where = array("AdmissionNumber"=>$keyword,"names"=>$keyword);

        $get_data = $obj->search_engine("student",$where);

        if($get_data)
        {

            foreach($get_data as $row)
            {
                $admission=$row['AdmissionNumber'];
            }

            $_SESSION['student-admission']=$admission;



        ?>

        <?php
        }
        else
        {
            $_SESSION['student-admission']='';
        ?>

            <div class="row">
                <div class="col-md-12">
                    <br>
                    <div class="alert alert-danger" >
                        No student found
                    </div>
                </div>
            </div>

        <?php
        }
        }


        if($_SESSION['student-admission'])
        {
            $admission=$_SESSION['student-admission'];
            $where=array("AdmissionNumber"=>$admission);
            $get_student=$obj->fetch_records("student",$where);
            foreach($get_student as $row)
            {
                $names=$row['names'];
                $class=$row['class'];
                $current_fee=$row['fee_paid'];
                $category=$row['category'];

            }

            //get total fee that the student is owed
            $where=array("form"=>$class[0],"category"=>$category);
            $get_fee=$obj->fetch_records("fee_structure",$where);
            if($get_fee)
            {
                foreach($get_fee as $row)
                {
                    $fee_owed=$row['amount'];

                }
            }
            //if not set in fee structure
            else
            {
                $fee_owed="Not set";

            }

            if(isset($_POST['save']))
            {
                $fee=$obj->con->real_escape_string(htmlentities($_POST['fee']));
                $receipt=$obj->con->real_escape_string(htmlentities($_POST['receipt']));
                if(!is_numeric($fee))
                {
                    $error="Only digits allowed";
                }
                else
                {
                    $newfee = $current_fee + $fee;
                    $admission=$_SESSION['student-admission'];
                    $where=array("AdmissionNumber"=>$admission);
                    $data=array("fee_paid"=>$newfee);

                    //check if data exists in fee structure table
                    $sql="SELECT * FROM fee_structure WHERE form='$class[0]' AND category='$category'";
                    if(mysqli_num_rows(mysqli_query($obj->con,$sql))>0)
                    {
                        if($obj->update_record("student",$where,$data))
                        {
                            $current_time = date("Y-m-d H:i:s");
                            $data=array("student_id"=>$admission,
                                "student_name"=>$names,
                                "bursar_name"=>$bursar_name,
                                "fee_paid"=>$fee,
                                "fee_set"=>$fee_owed,
                                "term"=>$term,
                                "receipt"=>$receipt,
                                "date"=>$current_time);

                            if($obj->insert_record("fee_logs",$data))
                            {
                                //updating total paid in fee_structure table
                                $sql="UPDATE fee_structure SET amount_paid=amount_paid+$fee WHERE form='$class[0]' AND category='$category'";
                                $execute=mysqli_query($obj->con,$sql);
                                if($execute)
                                {
                                    $success="Fee records updated successfully";
                                }

                            }
                        }
                        else{
                            $error=mysqli_error($obj->con);
                        }
                    }
                    //if no fees for student category. echo error
                    else
                    {
                       $error="Please first set fees for form $class[0] $category so as to begin paying fees for students";
                    }
                }
            }
            ?>
            <div class="row">
                <br>
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Enter <?php echo $names?>'s Fees amount
                        </div>

                        <div class="panel-body">
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
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <b>Names:</b>
                                    <?php echo $names?>
                                </li>
                                <li class="list-group-item">
                                    <b>Admission:</b>
                                    <?php echo $admission?>
                                </li>
                                <li class="list-group-item">
                                    <b>Class:</b>
                                    <?php echo $class?>
                                </li>
                                <li class="list-group-item">
                                    <b>Category:</b>
                                    <?php echo $category?>
                                </li>
                                <li class="list-group-item">
                                    <b>Fees:</b>
                                    <?php
                                    $where=array("AdmissionNumber"=>$admission);
                                    $get_fee_paid=$obj->fetch_records("student",$where);
                                    foreach($get_fee_paid as $row)
                                    {
                                        $fee_paid=$row['fee_owed']-$row['fee_paid'];
                                    }
                                    if($fee_paid>0)
                                    {
                                        $fee_paid=$fee_paid;
                                        echo "<font style='color:red;'>(uncleared) Balance <b>$fee_paid</b></font>";

                                    }
                                    else if($fee_paid<=0)
                                    {
                                        if($fee_paid<0)
                                        {
                                            $fee_paid=$fee_paid*-1;
                                        }
                                        echo "<font style='color:green;'>(cleared) Overpayment <b>$fee_paid</b></font>";
                                    }



                                    ?>
                                </li>
                            </ul>
                        </div>
                        <div class="panel-footer">
                            <?php
                            $where=array("form"=>$class[0],"category"=>$category,"term"=>$term);
                            if($obj->fetch_records("fee_structure",$where))
                            {
                                ?>
                                <form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF'])?>">
                                    <div class="form-inline">
                                        <input type="text" placeholder="Fees paid" name="fee" class="form-control" required>
                                        <input type="text" placeholder="Receipt number" name="receipt" class="form-control" required>
                                        <button type="submit" name="save" class="btn btn-primary">Enter</button>
                                    </div>
                                </form>
                            <?php
                            }
                            else
                            {
                                echo "<div class='alert alert-danger'>$term fees for form $class[0] $category has not yet been set. Please <a href='setfees.php'>Set here</a></div>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }

        ?>
    </div>

</div>
<?php include "plugins/scripts.php"?>
</body>
</html>