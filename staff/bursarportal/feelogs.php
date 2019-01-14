<?php
session_start();
if(!isset($_SESSION['bursar_account']))
{
    header('location:../index.php');
}
else{
    $_SESSION['student-admission']='';
    }
?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fee logs</title>
    <!-- Bootstrap Styles-->
    <?php include "plugins/resources.php"?>
    <?php include "functions/actions.php"?>

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
              <span class="glyphicon glyphicon-th-list"></span>
              Fee payment records
          </div>
          <div class="panel-body">
          <div class="table-responsive">
              <table class="table table-striped table-bordered">
                  <thead>
                  <tr>
                      <th>Student name</th>
                      <th>Student id</th>
                      <th>Bursar name</th>
                      <th>Term</th>
                      <th>Fee paid</th>
                      <th>Termly fee</th>
                      <th>Receipt number</th>
                      <th>Date/time</th>

                  </tr>
                  <tbody>
                  <?php

                  $obj=new DataOperations();
                  $get_logs=$obj->fetch_all_records("fee_logs");
                  foreach($get_logs as $row)
                  {
                      $student_name=$row['student_name'];
                      $student_id=$row['student_id'];
                      $bursar_name=$row['bursar_name'];
                      $fee=$row['fee_paid'];
                      $receipt=$row['receipt'];
                      $date=$row['date'];

                      ?>
                      <tr>
                          <td><?php echo $student_name?></td>
                          <td><?php echo $student_id?></td>
                          <td><?php echo $bursar_name?></td>
                          <td><?php echo $row['term']?></td>
                          <td><?php echo $fee?></td>
                          <td><?php echo $row['fee_set']?></td>
                          <td><?php echo $receipt?></td>
                          <td><?php echo $date?></td>
                      </tr>
                  <?php

                  }



                  ?>
                  </tbody>
                  </thead>
              </table>
          </div>
          </div>
      </div>
    </div>
</div>
<?php include "plugins/scripts.php"?>
<script>
    $('.table').DataTable();
</script>
</body>
</html>