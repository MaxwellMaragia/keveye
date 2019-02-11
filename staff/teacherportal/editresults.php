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
        $term = $_SESSION['edit-results-term'][10];
        $form = $_SESSION['edit-results-term'][0];
        $period = $_SESSION['edit-results-term'];
        $grading_system = "grading_system$form";



        if(isset($_POST['delete']))
        {

            $admission = $obj->con->real_escape_string($_POST['delete']);
            $cat = $obj->con->real_escape_string($_POST['cat']);
            $mid = $obj->con->real_escape_string($_POST['mid']);
            $where=array('admission'=>$obj->con->real_escape_string($_POST['delete']),
                         'class' => $_SESSION['edit-results-class'],
                         'period'=>$_SESSION['edit-results-term'],
                         'subject'=>$_SESSION['edit-results-subject']);

            //get minimum number of subjects
            $where_min = array('form'=>$form);
            $fetch_min = $obj->fetch_records('minimum_subjects',$where_min);
            foreach($fetch_min as $row)
            {
                $minimum_subjects = $row['minimum'];
            }


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
                         if($cat>0)
                         {
                             if($obj->delete_record('cycle_one',$where))
                             {
                                 $success = "Result for this subject has been deleted";
                             }
                         }
                         if($mid>0)
                         {
                             if($obj->delete_record('cycle_two',$where))
                             {
                                 $success = "Result for this subject has been deleted";
                             }
                         }
                     }

                 }

                 //if other results existed in the results table, we re calculate the grades
                 if($count > 1)
                 {

                     //recompute the total, average and grades
                     //grading algorithm
                     $sql = "SELECT sum(points),sum(total) FROM results WHERE admission='$admission'  AND period = '$period'";
                     $exe = mysqli_query($obj->con,$sql);
                     $get_points = mysqli_fetch_array($exe);
                     $points = $get_points['sum(points)'];
                     $total_mark = $get_points['sum(total)'];

                     if(($form>=3 || ($form==2 && $term==3)))
                     {
                         //biology
                         $sql = "SELECT points,total FROM results WHERE subject='Biology' AND admission='$admission' AND period='$period'";
                         $exe = mysqli_query($obj->con, $sql);
                         $get_bio = mysqli_fetch_array($exe);
                         $biology = $get_bio['points'];
                         $bio = $get_bio['total'];

                         //physics
                         $sql = "SELECT points,total FROM results WHERE subject='Physics' AND admission='$admission'  AND period='$period'";
                         $exe = mysqli_query($obj->con, $sql);
                         $get_phy = mysqli_fetch_array($exe);
                         $physics = $get_phy['points'];
                         $phy = $get_phy['total'];

                         //chemistry
                         $sql = "SELECT points,total FROM results WHERE subject='Chemistry' AND admission='$admission'  AND period='$period'";
                         $exe = mysqli_query($obj->con, $sql);
                         $get_chem = mysqli_fetch_array($exe);
                         $chemistry = $get_chem['points'];
                         $chem = $get_chem['total'];


                         //agriculture
                         $sql = "SELECT points,total FROM results WHERE subject='Agriculture' AND admission='$admission'  AND period='$period'";
                         $exe = mysqli_query($obj->con, $sql);
                         $get_agri = mysqli_fetch_array($exe);
                         $agriculture = $get_agri['points'];
                         $agri = $get_agri['total'];

                         //business
                         $sql = "SELECT points,total FROM results WHERE subject='Business' AND admission='$admission'  AND period='$period'";
                         $exe = mysqli_query($obj->con, $sql);
                         $get_bus = mysqli_fetch_array($exe);
                         $business = $get_bus['points'];
                         $bus = $get_bus['total'];

                         //geography
                         $sql = "SELECT points,total FROM results WHERE subject='Geography' AND admission='$admission'  AND period='$period'";
                         $exe = mysqli_query($obj->con, $sql);
                         $get_geo = mysqli_fetch_array($exe);
                         $geography = $get_geo['points'];
                         $geo = $get_geo['total'];

                         //history
                         $sql = "SELECT points,total FROM results WHERE subject='History' AND admission='$admission'  AND period='$period'";
                         $exe = mysqli_query($obj->con, $sql);
                         $get_hist = mysqli_fetch_array($exe);
                         $history = $get_hist['points'];
                         $his = $get_hist['total'];

                         //cre
                         $sql = "SELECT points,total FROM results WHERE subject='CRE' AND admission='$admission'  AND period='$period'";
                         $exe = mysqli_query($obj->con, $sql);
                         $get_cre = mysqli_fetch_array($exe);
                         $cre = $get_cre['points'];
                         $cr = $get_cre['total'];

                         if($get_chem && $get_bio && $get_phy && ($get_bus || $get_agri))
                         {
                             if($get_bus)
                             {
                                 $technical = $business;
                                 $technical_mark = $bus;
                             }
                             if($get_agri)
                             {
                                 $technical = $agriculture;
                                 $technical_mark = $agri;
                             }

                             $lowest_science = min($chemistry,$physics,$biology,$technical);
                             $lowest_science_mark = min($chem,$phy,$bio,$technical_mark);
                             $points = $points - $lowest_science;
                             $total_mark = $total_mark - $lowest_science_mark;

                         }
                         if($get_cre || $get_geo || $get_hist)
                         {
                             $points = $points - $history - $cre - $geography;
                             $total_mark  = $total_mark  - $his - $cr - $geo;
                             $points = $points + max($history,$cre,$geography);
                             $total_mark = $total_mark + max($his,$cr,$geo);

                         }
                     }

                     $count = $count - 1;
                     $average_points = $points / $minimum_subjects;
                     $average = $total_mark / $minimum_subjects;

                     //fetch grade
                     $sql = "SELECT * FROM $grading_system WHERE upper_limit>=$points AND lower_limit<=$points";
                     $execute = mysqli_query($obj->con, $sql);

                     if ($execute) {

                         while ($get_grades = mysqli_fetch_assoc($execute)) {
                             $grade = $get_grades['grade'];
                             $avpoints = $get_grades['points'];
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
                         'points'=>$avpoints,
                         'total_points'=>$points,
                         'average_points'=>$average_points,
                         'remarks'=>$remarks


                     );

                     if($obj->update_record('final_result',$where,$data))
                     {
                         if($cat>0)
                         {
                             //update cycle one
                             //fetch some records from the final results table
                             $where=array('admission'=>$obj->con->real_escape_string($_POST['delete']),
                                 'class' => $_SESSION['edit-results-class'],
                                 'period'=>$_SESSION['edit-results-term']);

                             $fetch_cycle_one=$obj->fetch_records('cycle_one',$where);

                             if($fetch_cycle_one){

                                 $count = $row['count'];

                             }


                             //if this was the only result in the cycle one results table, we delete it

                             if($count > 1)
                             {
                                 //recompute the total, average and grades
                                 //grading algorithm
                                 $sql = "SELECT sum(cat_points),sum(cat_total) FROM results WHERE admission='$admission'  AND period = '$period'";
                                 $exe = mysqli_query($obj->con,$sql);
                                 $get_points = mysqli_fetch_array($exe);
                                 $points = $get_points['sum(cat_points)'];
                                 $total_mark = $get_points['sum(cat_total)'];

                                 if(($form>=3 || ($form==2 && $term==3)))
                                 {
                                     //biology
                                     $sql = "SELECT cat_points,cat FROM results WHERE subject='Biology' AND admission='$admission' AND period='$period'";
                                     $exe = mysqli_query($obj->con, $sql);
                                     $get_bio = mysqli_fetch_array($exe);
                                     $biology = $get_bio['cat_points'];
                                     $bio = $get_bio['cat'];

                                     //physics
                                     $sql = "SELECT cat_points,cat FROM results WHERE subject='Physics' AND admission='$admission'  AND period='$period'";
                                     $exe = mysqli_query($obj->con, $sql);
                                     $get_phy = mysqli_fetch_array($exe);
                                     $physics = $get_phy['cat_points'];
                                     $phy = $get_phy['cat'];

                                     //chemistry
                                     $sql = "SELECT cat_points,cat FROM results WHERE subject='Chemistry' AND admission='$admission'  AND period='$period'";
                                     $exe = mysqli_query($obj->con, $sql);
                                     $get_chem = mysqli_fetch_array($exe);
                                     $chemistry = $get_chem['cat_points'];
                                     $chem = $get_chem['cat'];


                                     //agriculture
                                     $sql = "SELECT cat_points,cat FROM results WHERE subject='Agriculture' AND admission='$admission'  AND period='$period'";
                                     $exe = mysqli_query($obj->con, $sql);
                                     $get_agri = mysqli_fetch_array($exe);
                                     $agriculture = $get_agri['cat_points'];
                                     $agri = $get_agri['cat'];

                                     //business
                                     $sql = "SELECT cat_points,cat FROM results WHERE subject='Business' AND admission='$admission'  AND period='$period'";
                                     $exe = mysqli_query($obj->con, $sql);
                                     $get_bus = mysqli_fetch_array($exe);
                                     $business = $get_bus['cat_points'];
                                     $bus = $get_bus['cat'];

                                     //geography
                                     $sql = "SELECT cat_points,cat FROM results WHERE subject='Geography' AND admission='$admission'  AND period='$period'";
                                     $exe = mysqli_query($obj->con, $sql);
                                     $get_geo = mysqli_fetch_array($exe);
                                     $geography = $get_geo['cat_points'];
                                     $geo = $get_geo['cat'];

                                     //history
                                     $sql = "SELECT cat_points,cat FROM results WHERE subject='History' AND admission='$admission'  AND period='$period'";
                                     $exe = mysqli_query($obj->con, $sql);
                                     $get_hist = mysqli_fetch_array($exe);
                                     $history = $get_hist['cat_points'];
                                     $his = $get_hist['cat'];

                                     //cre
                                     $sql = "SELECT cat_points,cat FROM results WHERE subject='CRE' AND admission='$admission'  AND period='$period'";
                                     $exe = mysqli_query($obj->con, $sql);
                                     $get_cre = mysqli_fetch_array($exe);
                                     $cre = $get_cre['cat_points'];
                                     $cr = $get_cre['cat'];

                                     if($get_chem && $get_bio && $get_phy && ($get_bus || $get_agri))
                                     {
                                         if($get_bus)
                                         {
                                             $technical = $business;
                                             $technical_mark = $bus;
                                         }
                                         if($get_agri)
                                         {
                                             $technical = $agriculture;
                                             $technical_mark = $agri;
                                         }

                                         $lowest_science = min($chemistry,$physics,$biology,$technical);
                                         $lowest_science_mark = min($chem,$phy,$bio,$technical_mark);
                                         $points = $points - $lowest_science;
                                         $total_mark = $total_mark - $lowest_science_mark;

                                     }
                                     if($get_cre || $get_geo || $get_hist)
                                     {
                                         $points = $points - $history - $cre - $geography;
                                         $total_mark  = $total_mark  - $his - $cr - $geo;
                                         $points = $points + max($history,$cre,$geography);
                                         $total_mark = $total_mark + max($his,$cr,$geo);

                                     }
                                 }

                                 $count = $count - 1;
                                 $average_points = $points / $minimum_subjects;
                                 $average = $total_mark / $minimum_subjects;

                                 //fetch grade
                                 $sql = "SELECT * FROM $grading_system WHERE upper_limit>=$points AND lower_limit<=$points";
                                 $execute = mysqli_query($obj->con, $sql);

                                 if ($execute) {

                                     while ($get_grades = mysqli_fetch_assoc($execute)) {
                                         $grade = $get_grades['grade'];
                                         $avpoints = $get_grades['points'];
                                         $remarks = $get_grades['remarks'];
                                     }
                                 }

                                 //update cycle one results table
                                 $data = array(

                                     $_SESSION['edit-results-subject']=>'',
                                     'total'=>$total,
                                     'count'=>$count,
                                     'average'=>$average,
                                     'grade'=>$grade,
                                     'points'=>$avpoints,
                                     'total_points'=>$points,
                                     'average_points'=>$average_points,
                                     'remarks'=>$remarks


                                 );

                                 if($obj->update_record('cycle_one',$where,$data))
                                 {
                                     $success = "Deletion was successful";
                                 }
                                 else{
                                     $error = "Error deleting results";
                                 }


                             }



                         }
                         if($mid > 0)
                         {
                             //update cycle two
                             //fetch some records from cycle two results table
                             $where=array('admission'=>$obj->con->real_escape_string($_POST['delete']),
                                 'class' => $_SESSION['edit-results-class'],
                                 'period'=>$_SESSION['edit-results-term']);

                             $fetch_cycle_two=$obj->fetch_records('cycle_two',$where);

                             if($fetch_cycle_two){
                                 $mark = $row[$_SESSION['edit-results-subject']];
                                 $total = $row['total'];
                                 $count = $row['count'];
                                 $average = $row['average'];
                                 $form = $row['form'];
                             }


                             //if this was the only result in the cycle one results table, we delete it
                             if($count > 1)
                             {
                                 //recompute the total, average and grades
                                 //grading algorithm
                                 $sql = "SELECT sum(mid_points),sum(mid) FROM results WHERE admission='$admission'  AND period = '$period'";
                                 $exe = mysqli_query($obj->con,$sql);
                                 $get_points = mysqli_fetch_array($exe);
                                 $points = $get_points['sum(mid_points)'];
                                 $total_mark = $get_points['sum(mid)'];

                                 if(($form>=3 || ($form==2 && $term==3)))
                                 {
                                     //biology
                                     $sql = "SELECT mid_points,mid FROM results WHERE subject='Biology' AND admission='$admission' AND period='$period'";
                                     $exe = mysqli_query($obj->con, $sql);
                                     $get_bio = mysqli_fetch_array($exe);
                                     $biology = $get_bio['mid_points'];
                                     $bio = $get_bio['mid'];

                                     //physics
                                     $sql = "SELECT mid_points,mid FROM results WHERE subject='Physics' AND admission='$admission'  AND period='$period'";
                                     $exe = mysqli_query($obj->con, $sql);
                                     $get_phy = mysqli_fetch_array($exe);
                                     $physics = $get_phy['mid_points'];
                                     $phy = $get_phy['mid'];

                                     //chemistry
                                     $sql = "SELECT mid_points,mid FROM results WHERE subject='Chemistry' AND admission='$admission'  AND period='$period'";
                                     $exe = mysqli_query($obj->con, $sql);
                                     $get_chem = mysqli_fetch_array($exe);
                                     $chemistry = $get_chem['mid_points'];
                                     $chem = $get_chem['mid'];


                                     //agriculture
                                     $sql = "SELECT mid_points,mid FROM results WHERE subject='Agriculture' AND admission='$admission'  AND period='$period'";
                                     $exe = mysqli_query($obj->con, $sql);
                                     $get_agri = mysqli_fetch_array($exe);
                                     $agriculture = $get_agri['mid_points'];
                                     $agri = $get_agri['mid'];

                                     //business
                                     $sql = "SELECT mid_points,mid FROM results WHERE subject='Business' AND admission='$admission'  AND period='$period'";
                                     $exe = mysqli_query($obj->con, $sql);
                                     $get_bus = mysqli_fetch_array($exe);
                                     $business = $get_bus['mid_points'];
                                     $bus = $get_bus['mid'];

                                     //geography
                                     $sql = "SELECT mid_points,mid FROM results WHERE subject='Geography' AND admission='$admission'  AND period='$period'";
                                     $exe = mysqli_query($obj->con, $sql);
                                     $get_geo = mysqli_fetch_array($exe);
                                     $geography = $get_geo['mid_points'];
                                     $geo = $get_geo['mid'];

                                     //history
                                     $sql = "SELECT mid_points,mid FROM results WHERE subject='History' AND admission='$admission'  AND period='$period'";
                                     $exe = mysqli_query($obj->con, $sql);
                                     $get_hist = mysqli_fetch_array($exe);
                                     $history = $get_hist['mid_points'];
                                     $his = $get_hist['mid'];
                                     //cre
                                     $sql = "SELECT mid_points,mid FROM results WHERE subject='CRE' AND admission='$admission'  AND period='$period'";
                                     $exe = mysqli_query($obj->con, $sql);
                                     $get_cre = mysqli_fetch_array($exe);
                                     $cre = $get_cre['mid_points'];
                                     $cr = $get_cre['mid'];

                                     if($get_chem && $get_bio && $get_phy && ($get_bus || $get_agri))
                                     {
                                         if($get_bus)
                                         {
                                             $technical = $business;
                                             $technical_mark = $bus;
                                         }
                                         if($get_agri)
                                         {
                                             $technical = $agriculture;
                                             $technical_mark = $agri;
                                         }

                                         $lowest_science = min($chemistry,$physics,$biology,$technical);
                                         $lowest_science_mark = min($chem,$phy,$bio,$technical_mark);
                                         $points = $points - $lowest_science;
                                         $total_mark = $total_mark - $lowest_science_mark;

                                     }
                                     if($get_cre || $get_geo || $get_hist)
                                     {
                                         $points = $points - $history - $cre - $geography;
                                         $total_mark  = $total_mark  - $his - $cr - $geo;
                                         $points = $points + max($history,$cre,$geography);
                                         $total_mark = $total_mark + max($his,$cr,$geo);

                                     }
                                 }

                                 $count = $count - 1;
                                 $average_points = $points / $minimum_subjects;
                                 $average = $total_mark / $minimum_subjects;

                                 //fetch grade
                                 $sql = "SELECT * FROM $grading_system WHERE upper_limit>=$points AND lower_limit<=$points";
                                 $execute = mysqli_query($obj->con, $sql);

                                 if ($execute) {

                                     while ($get_grades = mysqli_fetch_assoc($execute)) {
                                         $grade = $get_grades['grade'];
                                         $avpoints = $get_grades['points'];
                                         $remarks = $get_grades['remarks'];
                                     }
                                 }

                                 //update cycle one results table
                                 $data = array(

                                     $_SESSION['edit-results-subject']=>'',
                                     'total'=>$total,
                                     'count'=>$count,
                                     'average'=>$average,
                                     'grade'=>$grade,
                                     'points'=>$avpoints,
                                     'total_points'=>$points,
                                     'average_points'=>$average_points,
                                     'remarks'=>$remarks


                                 );

                                 if($obj->update_record('cycle_two',$where,$data))
                                 {
                                     $success = "Deletion was successful";
                                 }
                                 else{
                                     $error = "Error deleting results";
                                 }


                             }
                         }
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
            $cat_one = $obj->con->real_escape_string(htmlentities($_POST['cat_one']));
            $mid_one = $obj->con->real_escape_string(htmlentities($_POST['mid_one']));

            if($cat>100){

                $error = "CYCLE ONE results can not be more than 100";

            }
            else if($mid>100){

                $error = "CYCLE TWO results cannot be more than 100";

            }
            else
            {
                $where=array('admission'=>$adm,
                    'class' => $_SESSION['edit-results-class'],
                    'period'=>$_SESSION['edit-results-term'],
                    'subject'=>$_SESSION['edit-results-subject']);

                //fetch new grade
                $total = $cat + $mid;
                $sql = "SELECT * FROM $grading_system WHERE upper_limit>=round($total) AND lower_limit<=round($total)";
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
                        $sql = "SELECT * FROM $grading_system WHERE upper_limit>=round($average) AND lower_limit<=round($average)";
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
                          <th>Cycle 1/100</th>
                          <th>Cycle 2/100</th>
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
                                               <input type="hidden" value="<?=$row['cat']?>" name="cat"/>
                                               <input type="hidden" value="<?=$row['mid']?>" name="mid"/>
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
                                               <input type="hidden"  value="<?=$row['cat']?>" name="cat_one">
                                               <label>Exam results</label>
                                               <input type="number" class="form-control" value="<?=$row['mid']?>" required="required" name="mid">
                                               <input type="hidden"  value="<?=$row['mid']?>" name="mid_one">
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
