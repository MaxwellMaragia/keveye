<?php
session_start();
$_SESSION['student-result']='';
if($_SESSION['account']){

    include "functions/actions.php";
    $obj=new DataOperations();
    $where_teacher = array('username'=>$_SESSION['account']);
    $fetch_teacher = $obj->fetch_records('staff_accounts',$where_teacher);
    foreach($fetch_teacher as $row)
    {
        $display_name =  $row['names'];
    }

    $error=$success=$minimum='';
    if(isset($_POST['submit']))
    {
        $form=$obj->con->real_escape_string(htmlentities($_POST['form']));
        $minimum=$obj->con->real_escape_string(htmlentities($_POST['minimum']));

        $form=$form[5];

        if(is_numeric($minimum))
        {
            //if all fields are filled correctly

            //check if exist
            $where=array("form"=>$form);
            if($obj->fetch_records("minimum_subjects",$where))
            {
                $error="Minimum number of subjects for form $form have already been saved. Click on the edit button to edit";
            }
            else
            {
                $data=array("form"=>$form,"minimum"=>$minimum);
                if($obj->insert_record("minimum_subjects",$data))
                {
                    $success="Details saved successfully";

                }
                else
                {

                }
            }
        }
        else
        {
            $error="Please fill in valid number";
        }
    }

    if(isset($_POST['update']))
    {
        $form=$obj->con->real_escape_string(htmlentities($_POST['form']));
        $minimum=$obj->con->real_escape_string(htmlentities($_POST['minimum']));

        if(!is_numeric($minimum))
        {
            $error="Enter valid number please";
        }
        else
        {
            //updating
            $where=array("form"=>$form);
            $data=array("minimum"=>$minimum);

            if($obj->update_record("minimum_subjects",$where,$data))
            {
                $success="Minimum number of subjects for form $form have been updated";

            }
        }

    }

}
else
{
    header('location:../index.php');
}


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Minimum number of subjects per form</title>
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
            <div class="col-md-12" style="margin-top:5px;">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span class="glyphicon glyphicon-list"></span>
                        Number of subjects per form
                    </div>
                    <div class="panel-body">

                        <div class="alert alert-info form-inline">
                            <p>Please fill the minimum number of subjects to be taken by each form</p>
                            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="post">
                                <select name="form" class="form-control" required="required">
                                    <option value="">Select form</option>
                                    <option>Form 1</option>
                                    <option>Form 2</option>
                                    <option>Form 3</option>
                                    <option>Form 4</option>
                                </select>
                                <input type="text" class="form-control" placeholder="Number of subjects" required="required" name="minimum">
                            <button class="btn btn-primary" type="submit" name="submit">Save</button>
                            </form>
                        </div>
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

                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>

                                <th>Form</th>
                                <th>Minimum number of subjects</th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php


                            $get_data = $obj->fetch_all_records("minimum_subjects");

                            foreach($get_data as $row)
                            {
                                $form=$row['form'];
                                $minimum=$row['minimum'];


                                ?>

                                <tr>
                                    <td>Form <?=$form?></td>
                                    <td><?=$minimum?></td>
                                    <td style="text-align: center">
                                        <a href="#edit<?php echo $row['id'];?>" data-toggle="modal"><button type="button" class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> </button> </a>
                                    </td>
                                </tr>

                                <!-- Modal edit-->
                                <div class="modal fade" id="edit<?php echo $row['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalLabel">Update minimum number of subjects for form <?=$row['form']?></h4>
                                            </div>
                                            <div class="modal-body">
                                                <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" value="<?php echo "Form : ".$row['form']?>" disabled>
                                                    </div>
                                                    <div class="form-group">
                                                        <label >Minimum number of subjects</label>
                                                        <input type="text" class="form-control"  value="<?php echo $row['minimum']?>" name="minimum" required="required">
                                                        <input type="hidden"  value="<?php echo $row['form']?>" name="form" required="required">
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


                        <!--        end search results-->
                    </div>
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
