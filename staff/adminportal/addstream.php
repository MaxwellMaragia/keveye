<?php

session_start();

if(!$_SESSION['admin_login']){

    hheader('location:../index.php');

}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Stream</title>
    <!-- Bootstrap Styles-->
    <?php include "plugins/resources.php" ?>
    <?php include "functions/actions.php" ?>
    <?php include "functions/manageStream.php" ?>

</head>

<body>
<!--   navigation plugin-->
<?php include "plugins/navigation.php" ?>
<!--/.navigation-->
<div id="page-wrapper">
    <div id="page-inner">

<?php include 'plugins/secondarynav.php' ?>

       
        <div class="panel panel-default" style="margin-top:20px;">
            <div class="panel-heading">
                <span class="glyphicon glyphicon-list"></span>
                List of saved streams
            </div>
            <div class="panel-body">
             <form class="form-inline alert alert-info" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>">
            <input type="text" name="stream" placeholder="Stream name e.g S" class="form-control" required="required"/>
            <button type="submit" name="save" class="btn btn-primary">Save</button>
        </form>
                <?php

                 if($success)
                 {
                     $obj->successDisplay($success);
                 }
                 if($error)
                 {
                     $obj->errorDisplay($error);
                 }

                ?>

                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <td>
                            Stream
                        </td>
                        <td>
                            Delete
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php



                       $get_streams=$obj->fetch_all_records("streams");
                       foreach($get_streams as $row)
                       {
                    ?>
                           <tr>
                               <td align="center"><?php echo $row['stream_name']?></td>
                               <td align="center">
                                   <a href="#delete<?php echo $row['id']?>" data-toggle="modal"><button type="button" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete </button> </a>
                               </td>
                           </tr>

                           <!-- Modal delete-->
                           <div class="modal fade" id="delete<?php echo $row['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                               <div class="modal-dialog" role="document">
                                   <div class="modal-content">
                                       <div class="modal-header">
                                           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                           <h4 class="modal-title" id="myModalLabel">Delete prompt</h4>
                                       </div>
                                       <div class="modal-body">
                                           <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">
                                               <div class="alert alert-danger">
                                                   Are you sure you want to delete <b></b><?php echo $row['stream_name']?>?. Deleting a stream is not advisable since it may have many dependencies</b>
                                               </div>
                                               <div class="modal-footer">
                                                   <button type="button" class="btn btn-default"  data-dismiss="modal">Cancel</button>
                                                   <button name="delete" class="btn btn-primary" value="<?php echo $row['id']?>">Delete stream</button>
                                               </div>
                                           </form>
                                       </div>
                                   </div>
                               </div>
                           </div>
                           <!--end//modal delete-->
                    <?php
                       }

                    ?>
                    </tbody>
                </table>
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

