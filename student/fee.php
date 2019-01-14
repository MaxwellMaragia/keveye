<?php
session_start();
if(isset($_SESSION['student_login']))
{
    include 'functions/actions.php';
    $obj=new DataOperations();
    $where = array('AdmissionNumber'=>$_SESSION['student_login']);
    $fetch_student = $obj->fetch_records('student',$where);

    foreach($fetch_student as $row){
        $names = $row['names'];
        $admission = $row['AdmissionNumber'];
        $class = $row['class'];
    }
}
else{
    header('location:student_login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student's portal</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Course Project">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        #form{
            border-radius: 0;
        }
    </style>

    <?php include_once '../includes/head.php' ?>
</head>
<body>



<div class="super_container">

    <!-- Header -->

    <?php include_once 'includes/student_navbar.php' ?>

    <!-- Home -->

    <div class="home">
        <div class="home_background_container prlx_parent">
            <div class="home_background prlx" style="background-image:url(images/course_6.jpg)"></div>
        </div>

    </div>

    <!-- table -->

    <div class="contact" style="margin-top: -180px">
<?php

  //getting student details
  $obj=new DataOperations();
  $where=array("AdmissionNumber"=>$_SESSION['student_login']);
  $fetch_student=$obj->fetch_records("student",$where);
  foreach($fetch_student as $row)
  {
      $form=$row['form'];
      $category=$row['category'];
  }

    //get term
    $where1=array("state"=>"In progress");
    $get_term=$obj->fetch_records("terms",$where1);

    foreach($get_term as $row)
    {
        $term=$row['term'];
    }
  
  $where2=array("term"=>$term,"form"=>$form,"category"=>$category);

  $get_term_fees=$obj->fetch_records("fee_structure",$where2);

  
   if($get_term_fees)
   {
       foreach ($get_term_fees as $row){

           $fees=$row['amount'];

       }
   }
   else{
       $fees = "Not set yet";
   }

  

  $get_balance=$obj->fetch_records("student",$where);
  foreach($get_balance as $row)
  {
      $fee=$row['fee_owed']-$row['fee_paid'];
      $total_paid=$row['fee_paid'];
      $total_owed=$row['fee_owed'];
      
  }

?>
        <div class="container">
            <br>
            <?php
            
            if($fee>0)
            {

                echo "<div class='alert alert-danger'>Note!<hr>You have a fee balance of $fee. Please complete your fee payment</div>";
                $balance=$fee;
                $overpayment=0;
            }
            else if($fee<=0)
            {
                $fee=$fee*-1;
                $balance=0;
                $overpayment=$fee;
                echo "<div class='alert alert-success'>Congrats!<hr>You have completed your fees. Overpayment is $fee</div>";
            } 
            ?>
            <div class="row justify-content-center">

                <div class="col-md-4 pull-left">

                    </td>
                    <table class="table table-bordered table-striped" style="color:#8C4200;" >
                        <thead>
                        <tr style="background-color:#8C4200;color:white">
                            <th>Record</th>
                            <th>Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                             <tr>
                                <td>This terms fees (<?=$term?>)</td>
                                <td><?php echo $fees?></td>
                            </tr>
                               <tr>
                                <td>Total fee paid</td>
                                <td><?php echo $total_paid?></td>
                            </tr>
                            <tr>
                                <td>Total fee owed</td>
                                <td><?php echo $total_owed?></td>
                            </tr>
                            <tr>
                                <td>Fee balance</td>
                                <td><?php echo $balance?></td>
                            </tr>
                            <tr>
                                <td>Fee overpayment</td>
                                <td><?php echo $overpayment?></td>
                            </tr>
                         
                            <tr>
                                <td>Status</td>
                                <td>
                                    <?php
                                    if($total_paid>$total_owed)
                                    {
                                        echo "Cleared";
                                    }
                                    else if($total_paid<$total_owed)
                                    {
                                        echo "Uncleared";
                                    }
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-md-8">
                    <div class="card bg-light">
                        <div class="card-header">All your fee payments</div>
                        <div class="card-body table-responsive">
                            <div class="text-responsive">
                                <table class="table table-bordered" style="color:#8C4200;">
                                    <thead>
                                    <tr style="background-color:#8C4200;color:white  ">
                                        <th>Amount paid</th>
                                        <th>Term</th>
                                        <th>Amount owed</th>
                                        <th>Date</th>
                                        <th>Receipt number</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                    
                                        $sql="SELECT * FROM fee_logs WHERE student_id='".$_SESSION['student_login']."' ORDER BY ID DESC";
                                        $execute=mysqli_query($obj->con,$sql);
                                        while($get_logs=mysqli_fetch_assoc($execute))
                                        {
                                            ?>
                                            <tr>
                                                <td><?=$get_logs['fee_paid']?></td>
                                                <td><?=$get_logs['term']?></td>
                                                <td><?=$get_logs['fee_set']?></td>
                                                <td><?=$get_logs['date']?></td>
                                                <td><?=$get_logs['receipt']?></td>
                                            </tr>
                                            <?php
                                        }
                                        
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





</div>


<!-- Elements -->



<!-- Footer -->

<?php include_once 'includes/footer.php'?>

</div>

<script src="js/jquery-3.2.1.min.js"></script>
<script src="styles/bootstrap4/popper.js"></script>
<script src="styles/bootstrap4/bootstrap.min.js"></script>
<script src="plugins/greensock/TweenMax.min.js"></script>
<script src="plugins/greensock/TimelineMax.min.js"></script>
<script src="plugins/scrollmagic/ScrollMagic.min.js"></script>
<script src="plugins/greensock/animation.gsap.min.js"></script>
<script src="plugins/greensock/ScrollToPlugin.min.js"></script>
<script src="plugins/progressbar/progressbar.min.js"></script>
<script src="plugins/scrollTo/jquery.scrollTo.min.js"></script>
<script src="plugins/easing/easing.js"></script>
<script src="js/elements_custom.js"></script>

</body>
</html>