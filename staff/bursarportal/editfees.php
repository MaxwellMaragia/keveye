<?php
session_start();
$_SESSION['student-admission']='';
if(!isset($_SESSION['bursar_account']))
{
    header('location:../index.php');

}
else{
    $_SESSION['student-admission']='';
    include "functions/actions.php";
    $obj=new DataOperations();
    $error=$success='';

    //check if current term exists
    $where=array("state"=>"in progress");
    $get_term=$obj->fetch_records("terms",$where);
    foreach($get_term as $row)
    {
        $term=$row['term'];
    }

    if(isset($_POST['update']))
    {
        //we have to roll back the students fee records to correct values
        $current_fees=$obj->con->real_escape_string(htmlentities($_POST['oldamount']));
        $newfees=$obj->con->real_escape_string(htmlentities($_POST['amount']));
        $form=$obj->con->real_escape_string(htmlentities($_POST['form']));
        $category=$obj->con->real_escape_string(htmlentities($_POST['category']));
        $term=$obj->con->real_escape_string(htmlentities($_POST['term']));

        //first iteration for deleting current fee
        $sql="UPDATE student SET fee_owed=fee_owed-$current_fees WHERE form='$form' AND category='$category'";
        $execute=mysqli_query($obj->con,$sql);
        if($execute)
        {
        //second iteration for updating new fee in student row records
        $sql="UPDATE student SET fee_owed=fee_owed+$newfees WHERE form='$form' AND category='$category'";
        $execute=mysqli_query($obj->con,$sql);

        if($execute)
        {
        //third iteration for updating in fee_structure table
        $sql="UPDATE fee_structure SET amount=$newfees WHERE form='$form' AND category='$category'";
        $execute=mysqli_query($obj->con,$sql);
        if($execute)
        {
            $success="Fee records for form $form $category for term $term updated successfully";
        }
        else
        {
            $error="Error updating fee records";
        }

        }

        }

    }
}

?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit previously entered fees</title>
    <!-- Bootstrap Styles-->
    <?php include "plugins/resources.php"?>


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
                <span class="fa fa-list-ul"></span>
               You can only edit fee records for the current term only
            </div>
            <div class="panel-body">
                <?php
                if($success)
                {
                    $obj->successDisplay($success);
                }
                else if($error)
                {
                    $obj->errorDisplay($error);
                }

                if($get_term)
                {
                    ?>
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>Term</th>
                            <th>Form</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th style="text-align: center">Edit</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $obj=new DataOperations();
                        $sql = "SELECT * FROM fee_structure WHERE term='$term'";
                        $execute=mysqli_query($obj->con,$sql);

                        while($get_fees =mysqli_fetch_assoc($execute))
                        {
                            $amount=$get_fees['amount'];
                            ?>
                            <tr>
                                <td><?php echo $get_fees['term']?></td>
                                <td><?php echo $get_fees['form']?></td>
                                <td><?php echo $get_fees['category']?></td>
                                <td><?php echo $get_fees['amount']?></td>
                                <td style="text-align: center">
                                    <a href="#edit<?php echo $get_fees['id'];?>" data-toggle="modal"><button type="button" class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> </button> </a>
                                </td>
                            </tr>

                            <!-- Modal edit-->
                            <div class="modal fade" id="edit<?php echo $get_fees['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="myModalLabel">You can only update fee amount</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" value="<?php echo 'Form '.$get_fees['form']?>" disabled>
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" value="<?php echo $get_fees['term'] ?>" disabled>
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" value="<?php echo "Category : ".$get_fees['category']?>" disabled>
                                                </div>
                                                <input type="hidden" name="oldamount" value="<?=$get_fees['amount']?>">
                                                <div class="form-group">
                                                    <input type="text" class="form-control"  value="<?php echo $get_fees['amount']?>" name="amount">
                                                    <input type="hidden"  value="<?php echo $get_fees['form']?>" name="form">
                                                    <input type="hidden"  value="<?php echo $get_fees['term']?>" name="term">
                                                    <input type="hidden"  value="<?php echo $get_fees['category']?>" name="category">
                                                </div>
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
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                <?php
                }
                else
                {
                    echo "<div class='alert alert-danger'>Note ! <hr/>The admin has not initiated the current or new term hence no fee records exist</div>";
                }
                ?>

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
