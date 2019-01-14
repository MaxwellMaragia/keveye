<?php
session_start();
$_SESSION['student-admission']='';
if(!isset($_SESSION['bursar_account']))
{
    header('location:../index.php');
}else{
    include "functions/actions.php";
    $bursar_id=$_SESSION['bursar_account'];
    $obj = new DataOperations();
    $where_bursar=array("username"=>$bursar_id);
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
    <title>Clear graduates</title>
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
         <?php
      
      $where=array("module"=>"clear graduates");
      $get_state=$obj->fetch_records("modules",$where);
      foreach($get_state as $row)
      {
        $state=$row['state'];
      }

      if($state=="unlocked")
      {
        ?>
     <form  method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">
            <div class="input-group-lg">
                <input type="text" id="student-search" class="form-control" placeholder="Graduate name or admission" name="keyword" required="required">
                <input type="submit" name="submit" class="hidden">
            </div>
        </form>

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
               <?php

        if(isset($_POST['pay']))
        {
            $amount=$obj->con->real_escape_string(htmlentities($_POST['amount']));
            if(!is_numeric($amount))
            {
               echo "<script>alert('Amount should only be numerical');</script>";
            }
            else
            {

                $where = array("admission"=>$_SESSION['graduate']);
                $newfee = $amount+$_SESSION['fees'];
                $data = array("fees"=>$newfee);
                $save_record=$obj->update_record("graduates",$where,$data);
                if($save_record)
                {
                    $current_time = date("Y-m-d H:i:s");
                    $data=array("student_id"=>$_SESSION['graduate'],
                        "student_name"=>$_SESSION['graduate-names'],
                        "bursar_name"=>$bursar_name,
                        "fee_paid"=>$amount,
                        "receipt"=>"graduate",
                        "date"=>$current_time);

                    if($obj->insert_record("fee_logs",$data))
                    {
                        echo "<div class='alert alert-success' style='margin-top:10px;'>Fee record updated successfully</div>";
                    }

                }
                else
                {
                    echo "<div class='alert alert-danger' style='margin-top:10px;'>mysqli_error($obj->con)</div>";
                }

            }
        }
        if(isset($_POST['submit']))
        {
            $keyword = $obj->con->real_escape_string(htmlentities($_POST['keyword']));
            $where = array("admission"=>$keyword,"names"=>$keyword);
            $get_graduate = $obj->search_engine("graduates",$where);

            if($get_graduate)
            {
               foreach($get_graduate as $row)
               {
                   $names=$row['names'];
                   $admission=$row['admission'];
                   $fees=$row['fees'];
                   $_SESSION['graduate-names']=$row['names'];
                   $_SESSION['fees']=$row['fees'];
                   $_SESSION['graduate']=$row['admission'];
                   ?>
                   <div class="alert alert-info" style="margin-top:20px;">
                       Enter fees for clearance
                       <hr/>
                       <ul class="list-group">
                           <li class="list-group-item">
                               Names: <?php echo $names?>
                               <li class="list-group-item">Admission: <?php echo $admission?></li>
                               <li class="list-group-item">
                                   Fees:
                                   <?php
                                   if($fees>=0)
                                   {
                                       echo "(Cleared) fee balance = $fees";
                                   }
                                   else if($fees<0)
                                   {
                                       echo "(Uncleared) fee balance =".$fees*-1;
                                   }
                                   ?>
                               </li>
                           </li>
                       </ul>
                       <form class="form-inline" action="<?php echo htmlentities($_SERVER['PHP_SELF'])?>" method="post" style="margin-top:10px;">
                           <input type="text" placeholder="Paid amount" class="form-control" name="amount" required>
                           <button class="btn btn-primary" type="submit" name="pay" >
                               Enter
                           </button>
                       </form>
                   </div>
                  <?php
               }
            }
            else
            {
                echo "<div class='alert alert-danger' style='margin-top:20px;'>No graduate found for name or admission <b>$keyword</b></div>";
            }

        }
        ?>
    </div>
</div>
<?php include "plugins/scripts.php"?>
<script>
    $('.table').DataTable();
</script>
</body>
</html>