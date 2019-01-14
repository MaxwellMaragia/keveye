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
        <div class="container">
            <div class="row">

                <div class="col-md-12">

                    <!-- Default form contact -->
                   <div class="col-md-5">
                       <form class="text-center  p-2" method="post" action="<?php $_SERVER['PHP_SELF'] ?>">
                           <!-- Subject -->

                           <select class="browser-default custom-select mb-4 col-md-12" id="form" name="period" required="required" onchange="this.form.submit();" style="margin-left:-10px;">
                               <option value="" selected disabled>Select term to view results</option>
                               <?php

                                  $where = array('admission'=>$admission);
                                  $fetch_term = $obj->fetch_records('results',$where);

                                  $sql = "SELECT DISTINCT period FROM results WHERE admission=$admission";
                                  $exe = mysqli_query($obj->con,$sql);

                                  while ($exe = mysqli_fetch_assoc($exe)) {
                                    $period = $exe['period'];
                                    echo "<option>$period</option>";
                                  }
                                  
                                                                                                                
                                   ?>
                           </select>

                       </form>
                   </div>
                    <!-- Default form contact -->


                </div>
                <div class="col-md-12">
                  <div class="container">
                      <?php

                      if($fetch_term){


                          if(isset($_POST['period']))
                          {
                              $exam_period=$obj->con->real_escape_string($_POST['period']);
                              $where=array("admission"=>$admission,"period"=>$exam_period);
                              $get_grades=$obj->fetch_records("results",$where);
                              $_SESSION['term']=$exam_period;


                              ?>
                              <h4><?=$exam_period?> results</h4>
                              <div class="row">
                                  <div class="col-md-6 table-responsive" >
                                      <table class="table table-bordered ">
                                          <thead>
                                          <tr style="background-color:#8C4200;color:white  ">
                                              <th>SUBJECT</th>
                                              <th>CYCLE 1 %</th>
                                              <th>CYCLE 2 %</th>
                                              <th>AVG %</th>
                                              <th>GRADE</th>
                                          </tr>
                                          </thead>
                                          <tbody>
                                          <?php

                                          foreach($get_grades as $row)
                                          {
                                              ?>
                                              <tr style="color:#8C4200;">
                                                  <td><?php echo $row['subject']?></td>
                                                  <td><?php echo $row['cat']*2?></td>
                                                  <td><?php echo $row['mid']*2?></td>
                                                  <td><?php echo $row['total']?></td>
                                                  <td><?php echo $row['grade']?></td>
                                              </tr>
                                          <?php
                                          }

                                          ?>
                                          </tbody>
                                      </table>
                                  </div>
                                  <div class="col-md-4 table-responsive" >
                                      <?php

                                      $where=array("admission"=>$admission,"period"=>$exam_period);
                                      $get_final=$obj->fetch_records("final_result",$where);
                                      foreach($get_final as $row)
                                      {
                                          $count=$row['count'];
                                          $form=$row['form'];
                                          $class=$row['class'];
                                          $average=$row['average'];
                                          $total=$row['total'];
                                          $grade=$row['grade'];
                                          $points=$row['points'];
                                          $remarks=$row['remarks'];

                                      }
                                      //fetching minimum number of subjects
                                      $where=array("form"=>$form);
                                      $fetch_minimum=$obj->fetch_records("minimum_subjects",$where);
                                      if($fetch_minimum)
                                      {
                                          foreach($fetch_minimum as $row)
                                          {
                                              $minimum_number=$row['minimum'];
                                          }

                                          ?>
                                          <table class="table table-bordered table-striped" style="background-color:white;color:#8C4200  ">
                                              <tbody>
                                              <tr>
                                                  <td>Total</td>
                                                  <td><?php echo $total." out of ".$minimum_number*100?></td>
                                              </tr>
                                              <tr>
                                                  <td>Average</td>
                                                  <td><?php echo $average?></td>
                                              </tr>
                                              <tr>
                                                  <td>Grade</td>
                                                  <td><?php echo $grade?></td>
                                              </tr>
                                              <tr>
                                                  <td>Points</td>
                                                  <td><?php echo $points?></td>
                                              </tr>
                                              <tr>
                                                  <td>Remarks</td>
                                                  <td><?php echo $remarks?></td>
                                              </tr>
                                              <tr>
                                                  <td>Subjects done</td>
                                                  <td><?php echo $count?></td>
                                              </tr>
                                              <tr>
                                                  <td>Subjects graded</td>
                                                  <td><?php echo $minimum_number?></td>
                                              </tr>
                                              <tr>
                                                  <td>Form position</td>
                                                  <td>
                                                      <?php


                                                      $query = "SELECT admission, average FROM final_result WHERE form='$form' AND period='$exam_period' ORDER BY average DESC";
                                                      $exe = mysqli_query($obj->con,$query);
                                                      $sql_total = "SELECT * FROM student WHERE form='$form'";

                                                      $rank = 0;
                                                      $student = array();
                                                      while($res = mysqli_fetch_array($exe)){
                                                          ++$rank;
                                                          $student[$res['admission']] = $rank;
                                                          $total_in_class=mysqli_num_rows($exe);
                                                      }
                                                      if($rank !== 0){
                                                          $class_position=$student[$admission];
                                                      }
                                                      echo $class_position." out of ".mysqli_num_rows(mysqli_query($obj->con,$sql_total));
                                                      ?>
                                                  </td>
                                              </tr>
                                              <tr>
                                                  <td>Class position</td>
                                                  <td>
                                                      <?php


                                                      $query = "SELECT admission, average FROM final_result WHERE class='$class' AND period='$exam_period' ORDER BY average DESC ";
                                                      $exe = mysqli_query($obj->con,$query);
                                                      $sql_total = "SELECT * FROM student WHERE class='$class'";

                                                      $rank = 0;
                                                      $student = array();
                                                      while($res = mysqli_fetch_array($exe)){
                                                          ++$rank;
                                                          $student[$res['admission']] = $rank;
                                                      }
                                                      if($rank !== 0){
                                                          $form_position=$student[$admission];
                                                      }
                                                      echo $form_position." out of ".mysqli_num_rows(mysqli_query($obj->con,$sql_total));
                                                      ?>
                                                  </td>
                                              </tr>

                                              </tbody>
                                          </table>
                                      <?php

                                      }
                                      else
                                      {
                                          ?>
                                          <div class="alert alert-danger">
                                              The minimum number of subjects for form <?=$form?> must not be less than <?=$minimum_number?>
                                              <hr/>
                                              Only <?=$count?> results have been uploaded for <?=$nam?> hence you cannot view his/her  total, grade and rank
                                          </div>
                                      <?php
                                      }

                                      ?>


                                  </div>
                              </div>
                          <?php

                          }

                     }elseif(!$fetch_term){ ?>
                          <!--Jumbotron-->
                          <div class="jumbotron jumbotron-primary" style="background-color:red;border-radius: 0;color: white;">

                              <div class="container">
                                  <h1 class="h1-reponsive mb-4 mt-2 blue-text font-bold">Opps!!</h1>
                                  <p class="lead" style="color:white;">No results have been uploaded for you yet</p>
                              </div>

                          </div>
                          <!--Jumbotron-->
                      <?php } ?>

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