<?php

session_start();

if($_SESSION['admin_login']){

    include "functions/manageSubjects.php";
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
    <title>Add subject</title>
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
            <div class="col-md-12" style="background:white; margin-top:20px; padding-top:20px;">
                <div class="panel panel-default">
                    <div class="panel-heading">Subjects data records</div>
                    <div class="panel-body">
                        <div class="form-inline alert alert-info">
                            <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">
                                <input type="text" placeholder="subject name " class="form-control square-border" name="name" required />
                                <input type="text" placeholder="initials eg chem " class="form-control square-border" name="initials" required />
                                <button type="submit" class="btn btn-primary square-btn-adjust" name="save">Add subject</button>
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
                        <table class="table table-bordered table-responsive table-striped">
                         <thead>
                         <tr>
                             <th>Subject name</th>
                             <th>Initials</th>
                             <th style="text-align: center">Delete</th>
                         </tr>
                         </thead>
                            <tbody>
                            <?php

                            $obj = new DataOperations();
                            $get_data = $obj->fetch_all_records("subject");

                            foreach($get_data as $row)

                            {
                                ?>

                                <tr>
                                    <td><?php echo $row['SubjectName'] ?></td>
                                    <td><?php echo $row['SubjectKey'] ?></td>
                                    <td align="center">
                                        <a href="#delete<?php echo $row['ID'];?>" data-toggle="modal"><button type="button" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> </button> </a>
                                    </td>
                                </tr>

                                <!-- Modal delete-->
                                <div class="modal fade" id="delete<?php echo $row['ID'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalLabel">Delete prompt</h4>
                                            </div>
                                            <div class="modal-body">
                                                <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">
                                                    <div class="alert alert-danger">
                                                        Are you sure you want to delete <b></b><?php echo $row['SubjectName']?></b>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default"  data-dismiss="modal">Cancel</button>
                                                        <button name="delete" class="btn btn-primary" value="<?php echo $row['ID']?>">Remove subject</button>
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



        <!--        page inner-->
    </div>
    <!--    page wrapper-->
</div>
<!--javascript plugin-->
<?php include "plugins/scripts.php"?>
<script>
    $('.table').DataTable();
</script>
<!--//javascript-->
</body>
</html>
