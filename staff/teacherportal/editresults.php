<?php
session_start();
if(!$_SESSION['account']){
    header('location:../index.php');
}
else{

    if($_SESSION['edit-results-class'] || $_SESSION['edit-results-term'] || $_SESSION['edit-results-subject']){

        include 'functions/actions.php';
        $obj=new DataOperations();
        $error=$success='';

        if(isset($_POST['delete']))
        {
            $where=array('admission'=>$obj->con->real_escape_string($_POST['delete']),
                         'class' => $_SESSION['edit-results-class'],
                         'period'=>$_SESSION['edit-results-term'],
                         'subject'=>$_SESSION['edit-results-subject']);


              //first delete from the results table



            if($obj->delete_record('results',$where))
            {

             //fetch some records from the final results table
             $where=array('admission'=>$obj->con->real_escape_string($_POST['delete']),
                'class' => $_SESSION['edit-results-class'],
                'period'=>$_SESSION['edit-results-term']);

             $fetch_final=$obj->fetch_records('final_result',$where);
             if($fetch_final)
             {
                 foreach($fetch_final as $row){

                    $mark = $row[$_SESSION['edit-results-subject']];
                    $total = $row['total'];
                    $count = $row['count'];
                    $average = $row['average'];
                    $form = $row['form'];

                 }

                 //if this was the only result in the final results table, we delete it
                 if($count == 1)
                 {


                     if($obj->delete_record('final_result',$where))
                     {
                         $success = "Result for this subject has been deleted";
                     }

                 }

                 //if other results existed in the results table, we re calculate the grades
                 if($count >= 1)
                 {


                     //get minimum number of subjects
                     $where_min = array('form'=>$form);
                     $fetch_min = $obj->fetch_records('minimum_subjects',$where_min);
                     foreach($fetch_min as $row)
                     {
                         $minimum_subjects = $row['minimum'];
                     }

                     //recompute the total, average and grades
                     $total = $total - $mark;
                     $count = $count - 1;
                     $average = $total / $minimum_subjects;

                     //fetch grade
                     $sql = "SELECT * FROM grading_system WHERE upper_limit>=round($average) AND lower_limit<=round($average)";
                     $execute = mysqli_query($obj->con, $sql);

                     if ($execute) {

                         while ($get_grades = mysqli_fetch_assoc($execute)) {
                             $grade = $get_grades['grade'];
                             $points = $get_grades['points'];
                             $remarks = $get_grades['remarks'];
                         }
                     }

                     //update final results table
                     $data = array(

                         $_SESSION['edit-results-subject']=>'',
                         'total'=>$total,
                         'count'=>$count,
                         'average'=>$average,
                         'grade'=>$grade,
                         'points'=>$points,
                         'remarks'=>$remarks


                     );

                     if($obj->update_record('final_result',$where,$data))
                     {
                         $success = "Result has been deleted successfully";
                     }
                     else{
                         $error = mysqli_error($obj->con);
                     }
                 }
             }
             else{

                 //show error if results for subject not found in final results table
                 $error="Error deleting results";

             }

            }

        }
        if(isset($_POST['update']))
        {

            $adm = $obj->con->real_escape_string(htmlentities($_POST['admission']));
            $cat = $obj->con->real_escape_string(htmlentities($_POST['cat']));
            $mid = $obj->con->real_escape_string(htmlentities($_POST['mid']));

            if($cat>100){

                $error = "CAT results can not be more than 100";

            }
            else if($mid>100){

                $error = "Mid term results cannot be more than 100";

            }
            else
            {
                $where=array('admission'=>$adm,
                    'class' => $_SESSION['edit-results-class'],
                    'period'=>$_SESSION['edit-results-term'],
                    'subject'=>$_SESSION['edit-results-subject']);

                //fetch new grade
                $total = $cat + $mid;
                $sql = "SELECT * FROM grading_system WHERE upper_limit>=round($total) AND lower_limit<=round($total)";
                $execute = mysqli_query($obj->con, $sql);

                if ($execute) {

                    while ($get_grades = mysqli_fetch_assoc($execute)) {

                        $grade = $get_grades['grade'];
                        $points = $get_grades['points'];
                        $remarks = $get_grades['remarks'];

                    }
                }

                $data = array(

                    'cat'=>$cat,
                    'mid'=>$mid,
                    'total'=>$total,
                    'grade'=>$grade,
                    'points'=>$points,
                    'remarks'=>$remarks

                );

                //update results in results table
                if($obj->update_record('results',$where,$data))
                {
                    //we now try to update results in final results table

                    $where=array(
                        'admission'=>$adm,
                        'class' => $_SESSION['edit-results-class'],
                        'period'=>$_SESSION['edit-results-term']
                                );

                    //fetch some records from the final results table
                    $fetch_final=$obj->fetch_records('final_result',$where);

                    if($fetch_final) {

                        foreach ($fetch_final as $row) {

                            $mark = $row[$_SESSION['edit-results-subject']];
                            $total_final = $row['total'];
                            $count = $row['count'];
                            $average = $row['average'];
                            $form = $row['form'];

                        }

                      //removing old result and adding the new one
                      $total_final = $total_final - $mark;
                      $total_final = $total_final + $total;

                        //get minimum number of subjects
                        $where_min = array('form'=>$form);
                        $fetch_min = $obj->fetch_records('minimum_subjects',$where_min);
                        foreach($fetch_min as $row)
                        {
                            $minimum_subjects = $row['minimum'];
                        }

                        //recompute the total, average and grades

                        $average = $total_final / $minimum_subjects;

                        //fetch grade
                        $sql = "SELECT * FROM grading_system WHERE upper_limit>=round($average) AND lower_limit<=round($average)";
                        $execute = mysqli_query($obj->con, $sql);

                        if ($execute) {

                            while ($get_grades = mysqli_fetch_assoc($execute)) {
                                $grade = $get_grades['grade'];
                                $points = $get_grades['points'];
                                $remarks = $get_grades['remarks'];
                            }
                        }

                        //update final results table
                        $data = array(

                            $_SESSION['edit-results-subject']=>$total,
                            'total'=>$total_final,
                            'average'=>$average,
                            'grade'=>$grade,
                            'points'=>$points,
                            'remarks'=>$remarks


                        );

                        if($obj->update_record('final_result',$where,$data))
                        {
                            $success = "Results have been updated successfully";
                        }
                        else{

                            $error = mysqli_error($obj->con);

                        }



                    }
                    else
                    {
                       $error = "Update not completed "+mysqli_error($obj->con);
                    }
                }
                else
                {
                    $error = "Error updating results";
                }

            }



        }

    }
    else{

        header('location:editselector.php');

    }



}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EDIT RESULTS</title>
    <!-- Bootstrap Styles-->
    <?php include "plugins/resources.php" ?>

    <style>
        select{margin-top:10px;}
    </style>
</head>

<body>
<!--   navigation plugin-->
<?php include "plugins/navigation.php" ?>
<!--/.navigation-->
<div id="page-wrapper">
    <div id="page-inner">

        <div class="panel panel-default">
            <div class="panel-heading">
                <?=$_SESSION['edit-results-class'].' '.$_SESSION['edit-results-subject'].' results for '.$_SESSION['edit-results-term'] ?>
            </div>
            <div class="panel-body">
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
              <div class="table-responsive">
                  <table class="table table-bordered table-striped" >
                      <thead>
                      <tr>
                          <th>Names</th>
                          <th>Admission</th>
                          <th>Cat/100</th>
                          <th>Exam/100</th>
                          <th>Edit/Delete</th>
                      </tr>
                      </thead>
                      <tbody>
                      <?php

                       $where = array('class' => $_SESSION['edit-results-class'],
                                      'period'=>$_SESSION['edit-results-term'] ,
                                      'subject'=>$_SESSION['edit-results-subject']);

                       $get_data = $obj->fetch_records('results',$where);
                       foreach($get_data as $row)
                       {
                           ?>

                           <tr>
                               <td><?=$row['names']?></td>wa
                               <td><?=$row['admission']?></td>
                               <td><?=$row['cat']?></td>
                               <td><?=$row['mid']?></td>
                               <td>
                                   <a href="#delete<?=$row['id']?>" data-toggle="modal"><button type="button" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> </button> </a>
                                   <a href="#edit<?=$row['id']?>" data-toggle="modal"><button type="button" class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> </button> </a>
                               </td>
                           </tr>

                           <!-- Modal delete-->
                           <div class="modal fade" id="delete<?=$row['id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                               <div class="modal-dialog" role="document">
                                   <div class="modal-content">
                                       <div class="modal-header">
                                           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                           <h4 class="modal-title" id="myModalLabel">Delete prompt</h4>
                                       </div>
                                       <div class="modal-body">
                                           <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">
                                               <div class="alert alert-danger">
                                                   Are you sure you want to delete <b></b><?php echo $row['names']?>'s <?=$row['subject']?> results for <?=$row['period']?>
                                               </div>
                                               <div class="modal-footer">
                                                   <button type="button" class="btn btn-default"  data-dismiss="modal">Cancel</button>
                                                   <button name="delete" class="btn btn-primary" value="<?=$row['admission']?>">Delete results</button>
                                               </div>
                                           </form>
                                       </div>
                                   </div>
                               </div>
                           </div>
                           <!--end//modal delete-->

                           <!-- Modal edit-->
                           <div class="modal fade" id="edit<?=$row['id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                               <div class="modal-dialog" role="document">
                                   <div class="modal-content">
                                       <div class="modal-header">
                                           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                           <h4 class="modal-title" id="myModalLabel">Update dialog</h4>
                                       </div>
                                       <div class="modal-body">
                                           <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">
                                               <label>Cat results</label>
                                               <input type="number" class="form-control" value="<?=$row['cat']?>" required="required" name="cat">
                                               <label>Exam results</label>
                                               <input type="number" class="form-control" value="<?=$row['mid']?>" required="required" name="mid">
                                               <input type="hidden"  value="<?=$row['admission']?>" name="admission">
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
