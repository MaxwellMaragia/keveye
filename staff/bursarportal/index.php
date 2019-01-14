<?php
session_start();
$_SESSION['student-admission']='';
$_SESSION['graduate']='';
$_SESSION['fees']='';
$_SESSION['graduate-names']='';
if(!isset($_SESSION['bursar_account']))
{

    header('location:../index.php');
}
else
{
    include "functions/actions.php";
    $obj = new DataOperations();

}

?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bursar portal</title>
    <!-- Bootstrap Styles-->
    <?php include "plugins/resources.php" ?>


</head>

<body>
<!--   navigation plugin-->
<?php include "plugins/navigation.php" ?>
<!--/.navigation-->
<div id="page-wrapper">
    <div id="page-inner">

<!--        secondary nav-->
        <?php

        include 'plugins/secondarynav.php';

        ?>
<!--        end nav-->
        <!--        contents-->
        <div class="heading"></div>

<!--        statistics-->
        <div class="row">
            <div class="col-md-3">
                <div class="tile-stats tile-red">
                    <div class="icon"><i class="fa fa-money"></i></div>
                    <div class="num" data-start="0" data-end="7"
                         data-postfix="" data-duration="1500" data-delay="0">
                        <?php
                         $qty=$fee_balance=0;
                         $obj=new DataOperations();
                         $sql="SELECT * FROM student WHERE (fee_owed-fee_paid)>0";
                         $execute=mysqli_query($obj->con,$sql);
                         while($get_data=mysqli_fetch_assoc($execute))
                         {
                             $qty+=$get_data['fee_owed']-$get_data['fee_paid'];
                         }


                        echo ($qty);
                        ?>
                    </div>

                    <h3>Students fee balance</h3>
                    <p>Total owed by students</p>
                </div>

            </div>
            <div class="col-md-3">

                <div class="tile-stats tile-green">
                    <div class="icon"><i class="glyphicon glyphicon-piggy-bank"></i></div>
                    <div class="num" data-start="0" data-end="2"
                         data-postfix="" data-duration="800" data-delay="0">
                        <?php

                        $sql1="SELECT * FROM student";
                        $execute=mysqli_query($obj->con,$sql1);
                        $total_students= mysqli_num_rows($execute);
                        $sql3="SELECT * FROM student WHERE (fee_owed-fee_paid)>0";
                        $execute3=mysqli_query($obj->con,$sql3);
                        $uncleared_students=mysqli_num_rows($execute3);

                        echo "$uncleared_students/$total_students";

                        ?>
                    </div>

                    <h3>Uncleared students</h3>
                    <p>Students with fee balance</p>
                </div>


            </div>

            <div class="col-md-3">

                <div class="tile-stats tile-blue">
                    <div class="icon"><i class="fa fa-money"></i></div>
                    <div class="num" data-start="0" data-end="2"
                         data-postfix="" data-duration="500" data-delay="0">
                        <?php
                        $total=0;
                        $sql2="SELECT * FROM graduates WHERE fees<0";
                        $execute=mysqli_query($obj->con,$sql2);
                        while($graduates_balance=mysqli_fetch_assoc($execute))
                        {
                            $total+=$graduates_balance['fees']*-1;
                        }
                        echo $total;
                        ?>
                    </div>

                    <h3>Graduates fee balance</h3>
                    <p>Total balance</p>
                </div>

            </div>

            <div class="col-md-3">

                <div class="tile-stats tile-aqua">
                    <div class="icon"><i class="fa fa-graduation-cap"></i></div>
                    <div class="num" data-start="0" data-end="1"
                         data-postfix="" data-duration="500" data-delay="0">
                        <?php

                        $sql1="SELECT * FROM graduates";
                        $execute=mysqli_query($obj->con,$sql1);
                        $total_graduates= mysqli_num_rows($execute);
                        $sql3="SELECT * FROM graduates WHERE fees<0";
                        $execute3=mysqli_query($obj->con,$sql3);
                        $uncleared_graduates=mysqli_num_rows($execute3);

                        echo "$uncleared_graduates/$total_graduates";

                        ?>
                    </div>

                    <h3>Uncleared graduates</h3>
                    <p>(Graduates with fee balance)</p>
                </div>
            </div>


            <div class="col-md-3">

                <div class="tile-stats tile-orange">
                    <div class="icon"><i class="fa fa-graduation-cap"></i></div>
                    <div class="num" data-start="0" data-end="1"
                         data-postfix="" data-duration="500" data-delay="0">
                        <?php

                        $sql1="SELECT SUM(amount_paid) FROM fee_structure";
                        $execute=mysqli_query($obj->con,$sql1);
                        $fee_paid=mysqli_fetch_assoc($execute);
                        $total_paid=$fee_paid['SUM(amount_paid)'];

                        echo $total_paid;

                        ?>
                    </div>

                    <h3>Total fee paid</h3>
                    <p>Sum total of students fees</p>
                </div>
            </div>


            <div class="col-md-3">

                <div class="tile-stats tile-cyan">
                    <div class="icon"><i class="fa fa-graduation-cap"></i></div>
                    <div class="num" data-start="0" data-end="1"
                         data-postfix="" data-duration="500" data-delay="0">
                        <?php


                        $sql="SELECT SUM(amount) FROM income_sources";
                        $execute=mysqli_query($obj->con,$sql);
                        $get_income=mysqli_fetch_assoc($execute);
                        $income=$get_income['SUM(amount)'];

                        echo $total_paid+$income;

                        ?>
                    </div>

                    <h3>Total income</h3>
                    <p>Total including fee</p>
                </div>
            </div>


            <div class="col-md-3">

                <div class="tile-stats tile-brown">
                    <div class="icon"><i class="fa fa-graduation-cap"></i></div>
                    <div class="num" data-start="0" data-end="1"
                         data-postfix="" data-duration="500" data-delay="0">
                        <?php

                        $sql="SELECT SUM(amount) FROM expenses";
                        $execute=mysqli_query($obj->con,$sql);
                        $get_expense=mysqli_fetch_assoc($execute);
                        $expenses=$get_expense['SUM(amount)'];
                        echo $expenses;

                        ?>
                    </div>

                    <h3>Total expenses</h3>
                    <p>All incurred expenses</p>
                </div>
            </div>


            <div class="col-md-3">

                <div class="tile-stats tile-purple">
                    <div class="icon"><i class="fa fa-graduation-cap"></i></div>
                    <div class="num" data-start="0" data-end="1"
                         data-postfix="" data-duration="500" data-delay="0">
                        <?php


                        echo ($total_paid+$income)-$expenses;


                        ?>
                    </div>

                    <h3>Total funds</h3>
                    <p>Calculated after all deductions</p>
                </div>
            </div>



        </div>


        <div class="row" style="padding-top:30px;">
            <div class="col-md-8" style="margin-bottom:30px;">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span class="fa fa-list-ul"></span>
                        This term's fee records
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Form</th>
                                <th>Term</th>
                                <th>Category</th>
                                <th>Fee amount</th>
                                <th>Total paid</th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php


                            $get_termly_fee=$obj->fetch_all_records("fee_structure");

                            foreach($get_termly_fee as $row)
                            {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['form']?></td>
                                        <td><?php echo $row['term']?></td>
                                        <td><?php echo $row['category']?></td>
                                        <td><?php echo $row['amount']?></td>
                                        <td><?php echo $row['amount_paid']?></td>
                                    </tr>
                                <?php


                            }


                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4 pull-right">
                <div class="list-group">
                    <a href="#" class="list-group-item active">
                        Quick links
                    </a>
                    <a href="setfees.php" class="list-group-item">Set this terms fees</a>
                    <a href="enterfees.php" class="list-group-item">Pay fees for student</a>
                    <a href="defaulters.php" class="list-group-item">Students with fee balance</a>
                    <a href="cleared.php" class="list-group-item">Cleared students</a>
                    <a href="unclearedgraduates.php" class="list-group-item">graduates with fee balance</a>
                    <a href="feelogs.php" class="list-group-item">Fee payment logs</a>

                </div>
            </div>
        </div>
    </div>
    <!-- /. ROW  -->





</div>
</div>
<?php include "plugins/scripts.php"?>
<script>
    $('.table').DataTable();
</script>
</body>
</html>
