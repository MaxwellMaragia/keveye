<?php
session_start();
if(!isset($_SESSION['bursar_account']))
{
    header('location:../index.php');
}
else{

    $_SESSION['student-admission']='';

    include "functions/actions.php";
    $error=$success=$current_fees=$category=$amount=$form='';
    $obj=new DataOperations();

    $get_fees=$obj->fetch_all_records("fee_structure");
    foreach($get_fees as $row)
    {
        $current_fees=$row['amount'];
    }

    /*
     * check if a term is
    in progress
    */

    $where=array("state"=>"in progress");
    $get_term=$obj->fetch_records("terms",$where);
    foreach($get_term as $row)
    {
        $term=$row['term'];
    }

    if(isset($_POST['update']))
    {
        $fee=$obj->con->real_escape_string(htmlentities($_POST['amount']));
        $amount=$obj->con->real_escape_string(htmlentities($_POST['amount']));
        $category=$obj->con->real_escape_string(htmlentities($_POST['category']));
        $form=$obj->con->real_escape_string(htmlentities($_POST['form']));

        if($form == "Select form")
        {
            $error="Please select form";
        }
        else if($category == "Select category")
        {
            $error="Please choose between boarding or day";
        }

        else if(!is_numeric($fee))
        {
           $error="Please enter only digits";
        }
        else{


            $form = $form[5];

            $where=array("term"=>$term,"form"=>$form,"category"=>$category);
            if($obj->fetch_records("fee_structure",$where))
            {
                $error="form $form $category fees for $term already exist";
            }
            else
            {
                $data=array(
                    "form"=>$form,
                    "term"=>$term,
                    "amount"=>$amount,
                    "amount_paid"=>0,
                    "category"=>$category

                );


                //entering the new fees
                if($obj->insert_record("fee_structure",$data))
                {
                        //billing the appropriate students
                        $sql="UPDATE student SET fee_owed=fee_owed+$amount WHERE form='$form' AND category='$category'";

                        if(mysqli_query($obj->con,$sql))
                        {
                            $success="Fee records for form $form $category in $term updated successfully";
                        }
                        else
                        {
                            $error=mysqli_error($obj->con);
                        }
                    }

                else
                {
                    $data=array(
                        "form"=>$form,
                        "term"=>$term,
                        "amount"=>$amount,
                        "amount_paid"=>0,
                        "category"=>$category

                    );

                    //inserting data as new row
                    if($obj->insert_record("fee_structure",$data))
                    {
                        //billing the appropriate students
                        $sql="UPDATE student SET fee_owed=fee_owed+$amount WHERE form='$form' AND category='$category'";

                        if(mysqli_query($obj->con,$sql))
                        {
                            $success="Fee records for form $form $category in $term updated successfully";
                        }
                        else
                        {
                            $error=mysqli_error($obj->con);
                        }
                    }
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
    <title>Set fees</title>
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
       <?php
      
      $where=array("module"=>"set term fees");
      $get_state=$obj->fetch_records("modules",$where);
      foreach($get_state as $row)
      {
        $state=$row['state'];
      }

      if($state=="unlocked")
      {
        ?>
              <div class="panel panel-default">
            <div class="panel-heading">
                <span class="fa fa-money"></span>
                Set up new financial period
            </div>
            <div class="panel-body">


                <div class="col-md-6">
                    <div class="alert alert-info">
                        <h3>Note!!</h3>
                        <p>
                            While setting up new financial period all students current fee records will be updated automatically by substracting the new fee amount from the
                            current fee paid by the students. Hence students who will have paid more than the entered amount will have an overpayment while the others will have a fee balance
                        </p>
                        <p style="color:red">
                            Execution of this query might take some time so have some patience
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <?php

                    if($error)
                    {
                        $obj->errorDisplay($error);
                    }
                    if($success)
                    {
                        $obj->successDisplay($success);
                    }


                    if($get_term)
                    {

                    ?>
                        <div class="alert alert-warning">The current term is <?=$term?></div>

                        <form method="post" class="form-group" action="<?php echo htmlentities($_SERVER['PHP_SELF'])?>">
                            <div class="form-group">
                                <select class="form-control" name="form" required="required">
                                    <option value="">Select form</option>
                                    <option>Form 1</option>
                                    <option>Form 2</option>
                                    <option>Form 3</option>
                                    <option>Form 4</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="category" required="required">
                                    <option value="">Select category</option>
                                    <option>day</option>
                                    <option>boarding</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="amount" placeholder="enter new fee amount" required>
                            </div>

                            <button type="submit" class="btn btn-primary" name="update">Update</button>
                        </form>
                    <?php
                    }
                    else
                    {
                       echo "<br/><div class='alert alert-danger'>Note ! <hr>Please inform the admin to initiate the current or new term so as to begin setting fees</div>";
                    }

                    ?>
                </div>
            </div>
            <div class="panel-footer">


            </div>
        </div>
        <?php
      }
      else
      {
        ?>
       <div class="alert alert-danger">
       Oops!
       <hr>
           This module has been locked. Contact the administrator to unlock module
       </div>
        <?php
      }

    ?>
       </div>

</div>
<?php include "plugins/scripts.php"?>
</body>
</html>