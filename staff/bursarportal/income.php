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
        $name=$obj->con->real_escape_string(htmlentities($_POST['name']));
        $amount=$obj->con->real_escape_string(htmlentities($_POST['amount']));
        $description=$obj->con->real_escape_string(htmlentities($_POST['description']));
        $party=$obj->con->real_escape_string(htmlentities($_POST['party']));
        $current_time = date("d-m-y H:i");

        $data=array(

            "name"=>$name,
            "amount"=>$amount,
            "description"=>$description,
            "party"=>$party,
            "date"=>$current_time

        );

        if($obj->insert_record("income_sources",$data))
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
    <title>Total financial income</title>
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
                A list and sub total of all income generated
            </div>
            <div class="panel-body">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" style="margin-bottom: 12px;">
                    <span class="glyphicon glyphicon-plus"></span>
                    Add  income
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
                        <th>Name of income</th>
                        <th>Amount</th>
                        <th>Income from</th>
                        <th>Description</th>
                        <th>Date recorded</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    //we first compute school fees
                    $sql="SELECT sum(amount_paid) FROM fee_structure";
                    $execute=mysqli_query($obj->con,$sql);
                    $get_fees=mysqli_fetch_assoc($execute);
                    ?>
                    <tr style="background-color:palegoldenrod">
                       <td>School fees</td>
                       <td><?=$get_fees['sum(amount_paid)']?></td>
                       <td>Students</td>
                       <td>Total fees paid by students</td>
                       <td>Not applicable</td>
                    </tr>
                    <?php
                    $sql="SELECT * FROM income_sources ORDER BY id DESC";
                    $execute=mysqli_query($obj->con,$sql);
                    while($row=mysqli_fetch_assoc($execute))
                    {
                    ?>
                     <tr>
                         <td><?=$row['name']?></td>
                         <td><?=$row['amount']?></td>
                         <td><?=$row['party']?></td>
                         <td><?=$row['description']?></td>
                         <td><?=$row['date']?></td>
                     </tr>
                    <?php
                    }
                    $sql="SELECT SUM(amount) FROM income_sources";
                    $get_total=mysqli_fetch_assoc(mysqli_query($obj->con,$sql));
                    ?>
                    <tr style="background-color: #00c0ef;">
                        <td><b>Total</b></td>
                        <td><?=$get_total['SUM(amount)']+$get_fees['sum(amount_paid)']?></td>
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
                            <h4 class="modal-title" id="myModalLabel">Add income apart from fees</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <select class="form-control" name="name" required="required">
                                    <option value="">Select income type</option>
                                    <option>Sales</option>
                                    <option>Service</option>
                                    <option>Donation</option>
                                    <option>Fine</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="you">Income gotten from ?</label>
                                <input type="text" class="form-control" name="party" placeholder="Enter company name or individual"/>
                            </div>
                            <div class="form-group">
                                <input type="number" class="form-control" name="amount" placeholder="Amount generated (Ksh)" required="required"/>
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
            /div>
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
