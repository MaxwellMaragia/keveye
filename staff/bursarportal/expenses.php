<?php
session_start();
$_SESSION['student-admission']='';
if(isset($_SESSION['bursar_account']))
{
    include "functions/actions.php";
    $obj=new DataOperations();

    $error=$success='';

    if(isset($_POST['submit']))
    {
        $recipient=$obj->con->real_escape_string(htmlentities($_POST['recipient']));
        $type=$obj->con->real_escape_string(htmlentities($_POST['type']));
        $amount=$obj->con->real_escape_string(htmlentities($_POST['amount']));
        $description=$obj->con->real_escape_string(htmlentities($_POST['description']));
        $current_time = date("d-m-y H:i:s");

        $data=array(

            "type"=>$type,
            "recipient"=>$recipient,
            "amount"=>$amount,
            "description"=>$description,
            "date"=>$current_time

        );

        if($obj->insert_record("expenses",$data))
        {
            $success="Income added successfully";
        }
        else
        {
            $error="Error saving income";
        }

    }
}
else
{
    header('location:../index.php');
}

?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Total recorded expenses</title>
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
               Expenses incurred starting from the most recent
            </div>
            <div class="panel-body">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" style="margin-bottom: 12px;">
                    <span class="glyphicon glyphicon-plus"></span>
                    Add expense
                </button>
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
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Expense type</th>
                        <th>Amount</th>
                        <th>Description</th>
                        <th>Recipient</th>
                        <th>Date recorded</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    $sql="SELECT * FROM expenses ORDER BY id DESC";
                    $execute=mysqli_query($obj->con,$sql);
                    while($row=mysqli_fetch_assoc($execute))
                    {
                        ?>
                        <tr>
                            <td><?=$row['type']?></td>
                            <td><?=$row['amount']?></td>
                            <td><?=$row['description']?></td>
                            <td><?=$row['recipient']?></td>
                            <td><?=$row['date']?></td>

                        </tr>
                    <?php
                    }
                    $sql="SELECT SUM(amount) FROM expenses";
                    $get_total=mysqli_fetch_assoc(mysqli_query($obj->con,$sql));
                    ?>
                    <tr style="background-color:palegoldenrod">
                        <td><b>Total</b></td>
                        <td><?=$get_total['SUM(amount)']?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal add new-->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Add expenses</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <select name="type" required="required" class="form-control">
                                    <option value="">Select expense type</option>
                                    <option>Purchase</option>
                                    <option>Service</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="recipient" placeholder="Enter recipient (Company or individual)" required="required"/>
                            </div>
                            <div class="form-group">
                                <input type="number" class="form-control" name="amount" placeholder="Amount paid (Ksh)" required="required"/>
                            </div>
                            <div class="form-group">
                                <label>Enter description if applicable</label>
                                <textarea  name="description"  cols=""  class="form-control">

                                </textarea>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" name="submit"  class="btn btn-primary" value="Save"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--end//modal add new-->


    </div>
</div>
<?php include "plugins/scripts.php"?>
<script>
    $('.table').DataTable({
        "order" : []
    });
</script>
</body>
</html>
