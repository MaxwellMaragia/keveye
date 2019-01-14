<?php
session_start();

if($_SESSION['admin_login']){

    include "functions/manageBursar.php";
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
    <title>Edit | delete | view Bursars</title>
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
            <div class="col-md-12 " style="margin-top:20px;">
                <!-- Advanced Tables -->
                <div class="panel panel-default">
                    <div class="panel-heading">List of accountants</div>
                    <div class="panel-body">
                        <div class="form-inline alert alert-info">
                            <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">
                                <input type="text" placeholder="bursar name " class="form-control square-border" name="names" value="<?=$names?>" required />
                                <input type="text" placeholder="bursar jobnumber" class="form-control square-border" name="jobnumber" required />
                                <button type="submit" class="btn btn-primary square-btn-adjust" name="addnew">Add bursar</button>
                            </form>
                        </div>
                        <?php

                        if($error){

                            $obj->errorDisplay($error);

                        }
                        else if($success){

                            $obj->successDisplay($success);

                        }


                        ?>
                <div>
                    <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">
                        <table class="table  table-hover table-striped table-bordered" id="dataTables-example">
                            <thead>
                            <tr>

                                <th>Names</th>
                                <th>Job number</th>
                                <th>Email</th>
                                <th>Account</th>
                                <th style="text-align: center;">Actions</th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php

                                $where = array('account'=>'bursar');
                                $get_bursar=$obj->fetch_records("staff_accounts",$where);

                        
                               foreach($get_bursar as $row)
                               {

                                   $names=$row['names'];
                                   $jobnumber=$row['username'];
                                   $account_status=$row['state'];

                                   $email = $row['email'];


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
                                       <td>$email</td>
                                       <td><button name='activate_account' value=$jobnumber class='btn-link'>$account_status</button></td>




                                        ";
                            ?>
                            <td align="center">
                                <a href="#delete<?php echo $jobnumber;?>" data-toggle="modal"><button type="button" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> </button> </a>
                                <a href="#edit<?php echo $jobnumber;?>" data-toggle="modal"><button type="button" class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> </button> </a>
                             </td>

                            </tr>

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
                                </div>
                                <!--end//modal delete-->

                            <!-- Modal edit-->
                            <div class="modal fade" id="edit<?php echo $jobnumber;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="myModalLabel">Update dialog</h4>
                                        </div>
                                        <div class="modal-body">
                                           <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">
                                               <label>Names</label>
                                               <input type="text" class="form-control" value="<?php echo $names ?>" required name="names">
                                               <label>Job number(uneditable)</label>
                                               <input type="text" class="form-control" value="<?php echo $jobnumber ?>" disabled>
                                               <input type="hidden"  value="<?php echo $jobnumber?>" name="jobnumber">
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default"  data-dismiss="modal">Cancel</button>
                                               <input type="submit" name="update" id="update" class="btn btn-primary" value="Update"/>
                                            </div>
                                           </form>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <!--end//modal edit-->





                             <?php }?>
                            </tbody>
                        </table>
                       </form>
                </div>
                </div>
                </div>
            </div>
        </div>
        <!--End Advanced Tables -->
</div>
    </div>
<?php include "plugins/scripts.php"?>
<script>
    $('.table').DataTable();
</script>
</body>
</html>

