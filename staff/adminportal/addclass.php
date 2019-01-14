<?php

session_start();

if($_SESSION['admin_login']){


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
        <title>Add class</title>
        <!-- Bootstrap Styles-->
        <?php include "plugins/resources.php" ?>
        <?php include "functions/actions.php" ?>
        <?php include "functions/manageClass.php" ?>
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
            <div class="col-md-12 " style="padding-top:20px;">
                <div class="panel panel-default">
                    <div class="panel-heading">Class data records</div>
                    <div class="panel-body">
                    <div class="form-inline alert alert-info" >
            <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">
                <select class="form-control square-border" name="form" required="required">
                    <option value="">Select form(1-4)</option>
                    <option>form 1</option>
                    <option>form 2</option>
                    <option>form 3</option>
                    <option>form 4</option>
                </select>
                <select class="form-control square-border" name="stream" required="required">
                    <option value="">Select stream</option>
                    <?php

                    $get_streams=$obj->fetch_all_records("streams");
                    foreach($get_streams as $row)
                    {
                        $stream_name=$row['stream_name'];

                        echo "<option>$stream_name</option>";
                    }



                    ?>
                </select>
                <button type="submit" class="btn btn-primary square-btn-adjust" name="save">Add class</button>
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
                        <table class="table table-bordered table-striped table-responsive">
                            <thead>
                            <tr>

                                <th>Class</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            $table_row=$obj->fetch_all_records("class");

                            foreach($table_row as $row)
                            {
                                ?>

                                <tr>

                                    <td ><?php echo $row["initials"];?></td>

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
                                                        Are you sure you want to delete form <b></b><?php echo $row['initials']?>?. Deleting a class is not advisable since it may have many dependencies</b>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default"  data-dismiss="modal">Cancel</button>
                                                        <button name="delete" class="btn btn-primary" value="<?php echo $row['id']?>">Delete class</button>
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



    </div><!-- end page inner-->
</div><!--end page wrapper-->
<?php include "plugins/scripts.php"?>


<script>
    $('.table').DataTable();
</script>
</body>
</html>