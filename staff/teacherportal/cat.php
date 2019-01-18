<?php
session_start();
$_SESSION['student-result']='';
if($_SESSION['account'])
{
    $_SESSION['exam']='CAT 1';
    //checking if teacher had selected correct details
  if(($_SESSION['year'] && $_SESSION['term'] && $_SESSION['class'] && $_SESSION['subject'] && $_SESSION['exam'] && $_SESSION['initials']))
  {
      include 'functions/actions.php';

      $obj = new DataOperations();

      $year = $term = $class = $subject = $exam = $error = $success = $marks = $message = '';

      $year = $_SESSION['year'];
      $term = $_SESSION['term'];
      $class = $_SESSION['class'];
      $initials = $_SESSION['initials'];
      $form = $class[0];
      $subject = $_SESSION['subject'];
      $exam = $_SESSION['exam'];
      $limit = 100;

      //get min subjects
      $sql="SELECT * FROM minimum_subjects WHERE form=$class[0]";
      $execute=mysqli_query($obj->con,$sql);

      if(mysqli_num_rows($execute)>0)
      {
          $get_min=mysqli_fetch_assoc($execute);
          $min=$get_min['minimum'];
      }
      else
      {
          $error="Please set minimum number of subjects to be done by form $class[0] <a href='minimumsubjects.php'>Here</a> before proceeding";
      }


      $period = $year . " term " . $term;

      if(isset($_POST['submit']))
      {

          //maxipain & kenny @codei

          for($i=0;$i<$_POST['count'];$i++)
          {
              $mark[$i]=$obj->con->real_escape_string(htmlentities($_POST['cat1'][$i]));
              $names[$i]=$obj->con->real_escape_string(htmlentities($_POST['names'][$i]));
              $admission[$i]=$obj->con->real_escape_string(htmlentities($_POST['admission'][$i]));
              $key[$i]=$year . $term . $subject . $_POST['admission'][$i];

              if($mark[$i]>0)
              {
                  if($mark[$i]<=$limit && is_numeric($mark[$i]))
                  {
                      /*
                       * if marks are consistent,
                        check if previous results
                        exist
                      */
                      $mark[$i] = $mark[$i] / 2;

                      $where=array("admission"=>$admission[$i],"period"=>$period,"subject"=>$subject);
                      $fetch_results=$obj->fetch_records("results",$where);

                      //if results for selected subject exist in results table
                      if($fetch_results)
                      {

                          foreach($fetch_results as $row)
                          {
                              $total[$i] = $row['total'] + $mark[$i];

                              //getting grade for updating
                              $sql = "SELECT * FROM grading_system WHERE upper_limit>=round($total[$i]) AND lower_limit<=round($total[$i])";
                              $execute = mysqli_query($obj->con, $sql);

                              if ($execute) {

                                  while ($get_grades = mysqli_fetch_assoc($execute)) {
                                      $grade[$i] = $get_grades['grade'];
                                      $points[$i] = $get_grades['points'];
                                      $remarks[$i] = $get_grades['remarks'];
                                  }

                                  //updating data to subject results table


                                  $data = array(

                                      "cat" => $mark[$i],
                                      "total" => $total[$i],
                                      "grade" => $grade[$i],
                                      "points" => $points[$i],
                                      "remarks" => $remarks[$i],
                                      "cat_entered" => '1'


                                  );

                                  if($obj->update_record("results",$where,$data))
                                  {

                                      //updating in final results table

                                      //getting from final results table so as to update
                                      $where=array("admission"=>$admission[$i],"period"=>$period);
                                      $get_final=$obj->fetch_records("final_result",$where);

                                      foreach($get_final as $row)
                                      {
                                          $total_mark[$i]=$row['total']+$mark[$i];
                                          $total_new[$i]=$total_mark[$i];
                                          $subject_total[$i]=$row[$subject]+$mark[$i];
                                          $count[$i] = $row['count'];

                                      }


                                          $average[$i]=$total_mark[$i]/$min;


                                      //get new grade
                                      $sql = "SELECT * FROM grading_system WHERE upper_limit>=round($average[$i]) AND lower_limit<=round($average[$i])";
                                      $execute = mysqli_query($obj->con, $sql);

                                      if ($execute) {

                                          while ($get_grades = mysqli_fetch_assoc($execute)) {
                                              $grade[$i] = $get_grades['grade'];
                                              $points[$i] = $get_grades['points'];
                                              $remarks[$i] = $get_grades['remarks'];
                                          }
                                      }
                                      //data update in final results table

                                      //updating data to results table
                                      $data = array(

                                          $subject => $subject_total[$i],
                                          "total" => $total_new[$i],
                                          "cumulative"=>$total_mark[$i],
                                          "average" => $average[$i],
                                          "grade" => $grade[$i],
                                          "points" => $points[$i],
                                          "remarks" => $remarks[$i]

                                      );
                                      $where = array("admission" => $admission[$i], "period" => $period);

                                      if ($obj->update_record("final_result", $where, $data)) {

                                          $success = "Exam results for form $class in $period have been uploaded successfully";

                                      }
                                   }

                               }

                          }

                      }
                      //if no result exist in results table
                      else
                      {
                            //fetching the grade for grading

                          $sql_grade="SELECT * FROM grading_system WHERE upper_limit>=round($mark[$i]) AND lower_limit<=round($mark[$i])";
                          $execute_grade=mysqli_query($obj->con,$sql_grade);

                          if($execute_grade) {

                              while ($get_grades = mysqli_fetch_assoc($execute_grade)) {
                                  $grade[$i] = $get_grades['grade'];
                                  $points[$i] = $get_grades['points'];
                                  $remarks[$i] = $get_grades['remarks'];
                              }

                              $data = array(
                                  "names" => $names[$i],
                                  "admission" => $admission[$i],
                                  "class" => $class,
                                  "form" => $form,
                                  "subject" => $subject,
                                  "cat" => $mark[$i],
                                  "total" => $mark[$i],
                                  "grade" =>$grade[$i],
                                  "points"=>$points[$i],
                                  "remarks"=>$remarks[$i],
                                  "initials"=>$initials,
                                  "period" => $period,
                                  "cat_entered" => 1,
                                  "exam_entered" => 0,
                                  "identifier" => $key[$i]

                              );

                              if($obj->insert_record("results",$data))
                              {
                                  //we now see if this student's results exist in the final results table
                                  $where=array("admission"=>$admission[$i],"period"=>$period);
                                  $fetch_total=$obj->fetch_records("final_result",$where,$data);

                                  //if results exist, we update the current
                                  if($fetch_total)
                                  {

                                      foreach($fetch_total as $row)
                                      {
                                          $total_mark[$i]=$row['total']+$mark[$i];
                                          $total_new[$i]=$total_mark[$i];
                                          $subject_total[$i]=$row[$subject]+$mark[$i];
                                          $count[$i]=$row['count']+1;

                                      }

                                      $average[$i]=$total_mark[$i]/$min;



                                          //calculate the grade

                                          $sql_grade = "SELECT * FROM grading_system WHERE upper_limit>=round($average[$i]) AND lower_limit<=round($average[$i])";
                                          $execute_grade = mysqli_query($obj->con, $sql_grade);

                                          if ($execute_grade) {

                                              while ($get_grades = mysqli_fetch_assoc($execute_grade)) {
                                                  $grade[$i] = $get_grades['grade'];
                                                  $points[$i] = $get_grades['points'];
                                                  $remarks[$i] = $get_grades['remarks'];
                                              }
                                          }

                                          //data to be updated


                                          //updating data to results table
                                          $data = array(

                                              $subject => $subject_total[$i],
                                              "total" => $total_new[$i],
                                              "cumulative"=>$total_mark[$i],
                                              "average" => $average[$i],
                                              "grade" => $grade[$i],
                                              "count" => $count[$i],
                                              "points" => $points[$i],
                                              "remarks" => $remarks[$i]

                                          );
                                          $where = array("admission" => $admission[$i], "period" => $period);

                                          if ($obj->update_record("final_result", $where, $data)) {
                                              $success = "Exam results for form $class in $period have been uploaded successfully";
                                          }



                                      }
                                  //if results do not exist
                                  else
                                  {

                                      //entering new data
									  
									  //calculate the grade
									  
									      $average[$i]=$mark[$i]/$min;

                                          $sql_grade = "SELECT * FROM grading_system WHERE upper_limit>=round($average[$i]) AND lower_limit<=round($average[$i])";
                                          $execute_grade = mysqli_query($obj->con, $sql_grade);

                                          if ($execute_grade) {

                                              while ($get_grades = mysqli_fetch_assoc($execute_grade)) {
                                                  $grade[$i] = $get_grades['grade'];
                                                  $points[$i] = $get_grades['points'];
                                                  $remarks[$i] = $get_grades['remarks'];
                                              }
                                          }


                                      $data = array(
                                          "names" => $names[$i],
                                          "admission" => $admission[$i],
                                          "class" => $class,
                                          "form" =>$class[0],
                                          "period" => $period,
                                          $subject => $mark[$i],
                                          "total" => $mark[$i],
                                          "cumulative"=>$mark[$i],
                                          "count" => 1,
                                          "average" => $average[$i],
                                          "grade" =>$grade[$i],
                                          "points"=>$points[$i],
                                          "remarks"=>$remarks[$i],
                                          "term"=>$term

                                      );

                                      if($obj->insert_record("final_result",$data))
                                      {
                                          $success="Exam results for form $class in $period have been uploaded successfully";
                                      }
                                      else
                                      {
                                          $error="Error in calculating total and average results for some fields";
                                      }
                                  }
                              }


                          }
                          else
                          {
                              $error="Problem with grading system";
                          }

                      }




                  }
                  else if($mark[$i]>$limit or !is_numeric($mark[$i]))
                  {
                      $error="Some fields contain invalid marks thus have not been saved (Please note that cat 1 marks should not be more than $limit)";
                  }

              }

          }

      }
  }
  else
  {
    header('location:enterresults.php?Please fill all fields');
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
        <title>Enter CYCLE 1 results <?=$subject?> for <?=$_SESSION['class']?></title>
        <!-- Bootstrap Styles-->
        <?php include "plugins/resources.php" ?>


    </head>

<body>
    <!--   navigation plugin-->
<?php include "plugins/navigation.php" ?>
    <!--/.navigation-->
    <div id="page-wrapper">
        <div id="page-inner">
            <?php include 'plugins/resultsnav.php' ?>

<!--    content /-->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Form <?=$class?> CYCLE 1  (<?=$subject?>) results for <?=$period?>
                        </div>
                        <div class="panel-body">
                            <?php
                             //checking if selected class has students
                            $sql="SELECT * FROM student WHERE class='$class'";
                            $exe=mysqli_query($obj->con,$sql);

                            if(mysqli_num_rows($exe)==0)
                            {
                                ?>
                                <div class="alert alert-danger">
                                    No students exists in form <?=$class?><br/>
                                    <a class="btn btn-primary" href="enterresults.php">Back</a>
                                </div>
                                <?php
                            }
                            else
                            {
                                ?>
                                <div class="alert alert-info">
                                    <b>Teacher instructions !</b><br/>
                                    <ul>
                                        <li>If a certain student does not take this subject, leave the field blank, it will not be saved</li>
                                        <li>Marks entered must be numerical and less than or equal to <?=$limit?>, fields with other values will not be saved</li>
                                        <li>After submitting results, verify that all student marks have been entered</li>
                                        </ul>
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
                                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
                                <table class="table table-bordered table-striped" >
                                    <thead>
                                    <tr>
                                        <th>Admission number</th>
                                        <th>Student names</th>
                                        <th>Cat 1 result field (X/
                                            <?php
                                            echo +$limit;
                                            ?>
                                            )
                                        </th>
                                    </tr>

                                    </thead>
                                    <tbody>
                                    <?php
                                    $sql = "SELECT names,AdmissionNumber FROM student WHERE class='$class' AND AdmissionNumber NOT IN (SELECT admission FROM results WHERE period='$period' AND subject='$subject' AND cat_entered=1)";
                                    $exe = mysqli_query($obj->con, $sql);
                                    $count = mysqli_num_rows($exe);
                                    for ($i = 1; $i <= $count; $i++) {
                                        while ($row = mysqli_fetch_assoc($exe)) {

                                            ?>

                                            <tr>
                                                <td>
                                                    <?php echo $row['AdmissionNumber']?>
                                                    <input type="hidden" name="admission[]"
                                                           value="<?= $row['AdmissionNumber'] ?>"/>
                                                    <input type="hidden" name="names[]" value="<?= $row['names'] ?>"/>
                                                    <input type="hidden" name="class[]" value="<?= $row['class'] ?>"/>
                                                    <input type="hidden" name="count" value="<?= $count ?>"/>
                                                </td>
                                                <td><?php echo $row['names']?></td>
                                                <td><input type="number" class="form-control" name="cat1[]"

                                                        <?php
                                                           if($term == 3)
                                                           {
                                                               echo "disabled";
                                                           }
                                                           ?>
                                                    />
                                                </td>
                                                </td>
                                            </tr>
                                        <?php

                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                                <button type="submit" name="submit" class="btn btn-primary"   <?php
                                if($term == 3)
                                {
                                    echo "disabled";

                                }
                                ?>
                                    /><span
                                        class="glyphicon glyphicon-save"></span>Save data
                                </button>
                                </form>

                            <?php
                            }

                            ?>
                        </div>
                    </div>
                </div>
            </div>
<!--            -->
            </div>
        </div>
    <?php include "plugins/scripts.php"?>

    <script>
        $('.table').DataTable({
			"lengthMenu": [[-1], ["all"]]
		});
		
    </script>
</body>
</html>