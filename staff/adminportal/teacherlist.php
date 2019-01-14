<?php
session_start();

if(!isset($_SESSION['admin_login'])){

    header('location:../index.php');

}
else{
    include "functions/manageteachers.php";
}


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit | delete | view teachers</title>
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
            <div class="col-md-12 " style="background:white; padding-top:20px;">
                <!-- Advanced Tables -->
                <div class="panel panel-default" >
                    <div class="panel-heading" >List of teachers</div>
                    <div class="panel-body">

                        <?php

                        if($error){

                            $obj->errorDisplay($error);

                        }
                        else if($success){

                            $obj->successDisplay($success);

                        }


                        ?>

                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" style="margin-bottom: 12px;">
                            <span class="glyphicon glyphicon-plus"></span>
                            Add new
                        </button>
                        <div class="table-responsive">
                            <form action="<?php $_SERVER['PHP_SELF']?>" METHOD="post">
                            <table class="table  table-hover table-striped table-bordered" id="dataTables-example">
                                <thead>
                                <tr>

                                    <th>Names</th>
                                    <th>Job number</th>
                                    <th>Subject 1</th>
                                    <th>Subject 2</th>
                                    <th>Account</th>
                                    <th>Actions</th>


                                </tr>
                                </thead>
                                <tbody>
                                  <?php
                                      $where = array('account'=>'teacher');
                                      $fetch_teachers=$obj->fetch_records("staff_accounts",$where);
                                      foreach($fetch_teachers as $row)
                                      {

                                          $names=$row['names'];
                                          $jobnumber=$row['username'];
                                          $subject1=$row['subject1'];
                                          $subject2=$row['subject2'];
                                          $account_status=$row['state'];
                                          if($account_status == "active")
                                          {
                                              $account_status = "deactivate";
                                          }
                                          else
                                          {
                                              $account_status = "activate";
                                          }

                                          echo "

                                          <tr>
                                          <td>$names</td>
                                          <td>$jobnumber</td>
                                          <td>$subject1</td>
                                          <td>$subject2</td>
                                          <td><button name='activate_account' value=$jobnumber class='btn-link'>$account_status</button></td>


                                              ";
                                  ?>
                                              <td>
                                <div class="btn-group">
                                    <a href="#view<?php echo $jobnumber;?>" data-toggle="modal"><button type="button" class="btn btn-info btn-sm"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> </button> </a>
                                    <a href="#delete<?php echo $jobnumber;?>" data-toggle="modal"><button type="button" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> </button> </a>
                                    <a href="#edit<?php echo $jobnumber;?>" data-toggle="modal"><button type="button" class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> </button> </a>

                                </div>
                                              </td>

                            </tr>
                                  <!-- Modal edit-->
                                  <div class="modal fade" id="edit<?php echo $jobnumber;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                      <div class="modal-dialog" role="document">
                                          <div class="modal-content">
                                              <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">
                                              <div class="modal-header">
                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                  <h4 class="modal-title" id="myModalLabel">Edit <?=$names?> details</h4>
                                              </div>
                                              <div class="modal-body">



                                                     <label>Full names</label>
                                                     <input type="text" value="<?=$names?>" name="names" id="names" class="form-control">
                                                     <label>Job number(uneditable)</label>
                                                     <input type="text" value="<?=$jobnumber?>" disabled required class="form-control">
                                                     <input type="hidden" value="<?=$jobnumber?>" name="jobnumber" required>
                                                     <label>Subject 1</label>
                                                     <select class="form-control" name="subject1">
                                                         <option><?=$subject1?></option>
                                                         <?php 
                                                          $fetch_subjects=$obj->fetch_all_records("subject");
                                                          foreach($fetch_subjects as $row)
                                                          {
                                                            echo "<option>".$row['SubjectName']."</option>";
                                                          }
                                                         ?>
                                                     </select>
                                                     <label>Subject 2</label>
                                                     <select class="form-control" name="subject2">
                                                         <option><?=$subject2?></option>
                                                        <?php 
                                                          $fetch_subjects=$obj->fetch_all_records("subject");
                                                          foreach($fetch_subjects as $row)
                                                          {
                                                            echo "<option>".$row['SubjectName']."</option>";
                                                          }
                                                         ?>
                                                     </select>


                                              </div>

                                              <div class="modal-footer">
                                                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                  <input type="submit" name="update" id="update" class="btn btn-primary" value="Update"/>
                                              </div>
                                               </form>
                                          </div>
                                      </div>
                                  </div>
                                  <!--end//modal edit-->

                                  <!-- Modal view-->
                                  <div class="modal fade" id="view<?php echo $jobnumber;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                      <div class="modal-dialog" role="document">
                                          <div class="modal-content">
                                              <div class="modal-header">
                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                  <h4 class="modal-title" id="myModalLabel">Teacher data</h4>
                                              </div>
                                              <div class="modal-body">
                                                  <div class="media">
                                                      <div class="media-left">
                                                          <a href="#">
                                                              <img class="media-object" src="../assets/images/find_user.png" alt="...">
                                                          </a>
                                                      </div>
                                                      <div class="media-body">

                                                          <p>
                                                              Job number: <?php echo $jobnumber;?><br>
                                                              Names:      <?php echo $names;?><br>
                                                              Subject 1:  <?php echo $subject1?><br>
                                                              Subject 2:  <?php echo $subject2?>
                                                          </p>
                                                      </div>
                                                  </div>
                                              </div>

                                              <div class="modal-footer">
                                                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                  <!--end//modal view-->



                                  <!-- Modal delete-->
                                  <div class="modal fade" id="delete<?php echo $jobnumber;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                      <div class="modal-dialog" role="document">
                                          <div class="modal-content">
                                              <div class="modal-header">
                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                  <h4 class="modal-title" id="myModalLabel">Delete prompt</h4>
                                              </div>
                                              <div class="modal-body">
                                                  <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">
                                                  <div class="alert alert-danger">
                                                      Are you sure you want to delete <b></b><?php echo $names?>'s</b> account?
                                                  </div>
                                                  <div class="modal-footer">
                                                      <button type="button" class="btn btn-default"  data-dismiss="modal">Cancel</button>
                                                      <button name="delete" class="btn btn-primary" value="<?php echo $jobnumber?>">Delete account</button>
                                                  </div>
                                                  </form>
                                              </div>
                                          </div>
                                      </div>
                                      <!--end//modal delete-->



                                      <?php
                                      }
                                      ?>
                                </tbody>
                            </table>
                            </FORM>
                        </div>
                    </div>
                </div>
                    </div>
                </div>
                <!--End Advanced Tables -->

        <!-- Modal add new-->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Add new teacher account</h4>
                        </div>
                        <div class="modal-body">



                            <label>Full names</label>
                            <input type="text"  name="names" id="names" class="form-control" placeholder="eg Margaret Wambui Mwangi">
                            <label>Job number(number only)</label>
                            <input type="text"  name="jobnumber" required class="form-control" placeholder="eg 10987">
                            <label>Subject 1</label>
                            <select class="form-control" name="subject1">
                                <option>Select</option>
                                  <?php 
                                          $fetch_subjects=$obj->fetch_all_records("subject");
                                          foreach($fetch_subjects as $row)
                                          {
                                            echo "<option>".$row['SubjectName']."</option>";
                                          }
                                         ?>
                            </select>
                            <label>Subject 2</label>
                            <select class="form-control" name="subject2">
                                <option>Select</option>
                                  <?php 
                                          $fetch_subjects=$obj->fetch_all_records("subject");
                                          foreach($fetch_subjects as $row)
                                          {
                                            echo "<option>".$row['SubjectName']."</option>";
                                          }
                                         ?>
                            </select>


                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" name="add_teacher"  class="btn btn-primary" value="Save"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--end//modal add new-->

        </div>
    </div>
    </div>

<?php include "plugins/scripts.php" ?>


<script>
    $('.table').DataTable();
</script>
</body>
</html>
