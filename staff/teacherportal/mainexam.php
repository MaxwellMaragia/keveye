<?php
session_start();
$_SESSION['student-result']='';


if($_SESSION['account'])
{
    $_SESSION['exam']='CAT 2';
    //checking if teacher had selected correct details
    if(($_SESSION['year'] && $_SESSION['term'] && $_SESSION['class'] && $_SESSION['subject'] && $_SESSION['exam'] && $_SESSION['initials'] ))
    {
        include 'functions/actions.php';

        $obj = new DataOperations();

        $year = $term = $class = $subject = $exam = $error = $success = $marks = $message = '';

        $year = $_SESSION['year'];
        $term = $_SESSION['term'];
        $class = $_SESSION['class'];
        $form = $class[0];
        $initials = $_SESSION['initials'];
        $subject = $_SESSION['subject'];
        $exam = $_SESSION['exam'];
        $limit = 100;

        $grading_system = "grading_system$form";



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
                $mark[$i]=$obj->con->real_escape_string(htmlentities($_POST['exam'][$i]));
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


                       if($term != 3)
                       {
                           $mark[$i] = $mark[$i] / 2;
                           $cycle_two[$i] = $mark[$i] * 2;
                       }
                       else
                       {
                           $cycle_two[$i] = $mark[$i];
                       }


                        $where=array("admission"=>$admission[$i],"period"=>$period,"subject"=>$subject);
                        $fetch_results=$obj->fetch_records("results",$where);

                        //if results for selected subject exist in results table
                        if($fetch_results)
                        {
                            foreach($fetch_results as $row)
                            {
                                $total[$i] = $row['total'] + $mark[$i];

                                //getting grade for updating

                                $sql = "SELECT * FROM $grading_system WHERE upper_limit>=floor($total[$i]) AND lower_limit<=floor($total[$i])";
                                $execute = mysqli_query($obj->con, $sql);

                                if ($execute) {


                                    while ($get_grades = mysqli_fetch_assoc($execute)) {
                                        $grade[$i] = $get_grades['grade'];
                                        $points[$i] = $get_grades['points'];
                                        $remarks[$i] = $get_grades['remarks'];
                                    }

                                    //updating data to subject results table


                                    //getting grade for cycle 1
                                    $sql = "SELECT * FROM $grading_system WHERE upper_limit>=$cycle_two[$i] AND lower_limit<=$cycle_two[$i]";
                                    $execute = mysqli_query($obj->con, $sql);

                                    while ($get_grades = mysqli_fetch_assoc($execute)) {
                                        $cycle_two_grade[$i] = $get_grades['grade'];
                                        $cycle_two_points[$i] = $get_grades['points'];
                                    }

                                    //updating data to subject results table


                                    $data = array(

                                        "mid" => $cycle_two[$i],
                                        "mid_grade"=>$cycle_two_grade[$i],
                                        "mid_points"=>$cycle_two_points[$i],
                                        "total" => $total[$i],
                                        "grade" => $grade[$i],
                                        "points" => $points[$i],
                                        "initials"=>$initials,
                                        "term"=>$term,
                                        "remarks" => $remarks[$i],
                                        "exam_entered" => '1'


                                    );

                                    if($obj->update_record("results",$where,$data))
                                    {

                                        //updating in final results table

                                        //getting from final results table so as to update
                                        $where=array("admission"=>$admission[$i],"period"=>$period);

                                        $where=array("admission"=>$admission[$i],"period"=>$period);
                                        $get_cycle_two=$obj->fetch_records("cycle_two",$where);

                                        //if results exist, we update the current
                                        $fetch_total=$obj->fetch_records("final_result",$where,$data);
                                        if($fetch_total)
                                        {
                                            foreach($fetch_total as $row)
                                            {
                                                $subject_total[$i]=$row[$subject]+$mark[$i];
                                            }
                                        }


                                        //grading algorithm
                                        $sql = "SELECT sum(points),sum(total) FROM results WHERE admission='$admission[$i]'  AND period = '$period'";
                                        $exe = mysqli_query($obj->con,$sql);
                                        $get_points = mysqli_fetch_array($exe);
                                        $points[$i] = $get_points['sum(points)'];
                                        $total_mark[$i] = $get_points['sum(total)'];

                                        if(($form>=3 || ($form==2 && $term==3)))
                                        {
                                            //biology
                                            $sql = "SELECT points,total FROM results WHERE subject='Biology' AND admission='$admission[$i]' AND period='$period'";
                                            $exe = mysqli_query($obj->con, $sql);
                                            $get_bio = mysqli_fetch_array($exe);
                                            $biology[$i] = $get_bio['points'];
                                            $bio[$i] = $get_bio['total'];

                                            //physics
                                            $sql = "SELECT points,total FROM results WHERE subject='Physics' AND admission='$admission[$i]'  AND period='$period'";
                                            $exe = mysqli_query($obj->con, $sql);
                                            $get_phy = mysqli_fetch_array($exe);
                                            $physics[$i] = $get_phy['points'];
                                            $phy[$i] = $get_phy['total'];

                                            //chemistry
                                            $sql = "SELECT points,total FROM results WHERE subject='Chemistry' AND admission='$admission[$i]'  AND period='$period'";
                                            $exe = mysqli_query($obj->con, $sql);
                                            $get_chem = mysqli_fetch_array($exe);
                                            $chemistry[$i] = $get_chem['points'];
                                            $chem[$i] = $get_chem['total'];


                                            //agriculture
                                            $sql = "SELECT points,total FROM results WHERE subject='Agriculture' AND admission='$admission[$i]'  AND period='$period'";
                                            $exe = mysqli_query($obj->con, $sql);
                                            $get_agri = mysqli_fetch_array($exe);
                                            $agriculture[$i] = $get_agri['points'];
                                            $agri[$i] = $get_agri['total'];

                                            //business
                                            $sql = "SELECT points,total FROM results WHERE subject='Business' AND admission='$admission[$i]'  AND period='$period'";
                                            $exe = mysqli_query($obj->con, $sql);
                                            $get_bus = mysqli_fetch_array($exe);
                                            $business[$i] = $get_bus['points'];
                                            $bus[$i] = $get_bus['total'];

                                            //geography
                                            $sql = "SELECT points,total FROM results WHERE subject='Geography' AND admission='$admission[$i]'  AND period='$period'";
                                            $exe = mysqli_query($obj->con, $sql);
                                            $get_geo = mysqli_fetch_array($exe);
                                            $geography[$i] = $get_geo['points'];
                                            $geo[$i] = $get_geo['total'];

                                            //history
                                            $sql = "SELECT points,total FROM results WHERE subject='History' AND admission='$admission[$i]'  AND period='$period'";
                                            $exe = mysqli_query($obj->con, $sql);
                                            $get_hist = mysqli_fetch_array($exe);
                                            $history[$i] = $get_hist['points'];
                                            $his[$i] = $get_hist['total'];

                                            //cre
                                            $sql = "SELECT points,total FROM results WHERE subject='CRE' AND admission='$admission[$i]'  AND period='$period'";
                                            $exe = mysqli_query($obj->con, $sql);
                                            $get_cre = mysqli_fetch_array($exe);
                                            $cre[$i] = $get_cre['points'];
                                            $cr[$i] = $get_cre['total'];

                                            if($get_chem && $get_bio && $get_phy && ($get_bus || $get_agri))
                                            {
                                                if($get_bus)
                                                {
                                                    $technical[$i] = $business[$i];
                                                    $technical_mark[$i] = $bus[$i];
                                                }
                                                if($get_agri)
                                                {
                                                    $technical[$i] = $agriculture[$i];
                                                    $technical_mark[$i] = $agri[$i];
                                                }

                                                $lowest_science[$i] = min($chemistry[$i],$physics[$i],$biology[$i],$technical[$i]);
                                                $lowest_science_mark[$i] = min($chem[$i],$phy[$i],$bio[$i],$technical_mark[$i]);
                                                $points[$i] = $points[$i] - $lowest_science[$i];
                                                $total_mark[$i] = $total_mark[$i]  - $lowest_science_mark[$i];

                                            }
                                            if($get_cre || $get_geo || $get_hist)
                                            {
                                                $points[$i] = $points[$i] - $history[$i] - $cre[$i] - $geography[$i];
                                                $total_mark[$i]  = $total_mark[$i]  - $his[$i] - $cr[$i] - $geo[$i];
                                                $points[$i] = $points[$i] + max($history[$i],$cre[$i],$geography[$i]);
                                                $total_mark[$i] = $total_mark[$i] + max($his[$i],$cr[$i],$geo[$i]);

                                            }
                                        }


                                        $average_points[$i] = $points[$i]/$min;
                                        $average[$i] = $total_mark[$i]/$min;

                                        //get new grade
                                        $sql = "SELECT * FROM $grading_system WHERE upper_limit>=$points[$i] AND lower_limit<=$points[$i]";
                                        $execute = mysqli_query($obj->con, $sql);

                                        if ($execute) {

                                            while ($get_grades = mysqli_fetch_assoc($execute)) {
                                                $grade[$i] = $get_grades['grade'];
                                                $avpoints[$i] = $get_grades['points'];
                                                $remarks[$i] = $get_grades['remarks'];
                                            }
                                        }
                                        //data update in final results table



                                        //updating data to results table
                                        $data = array(

                                            $subject => $subject_total[$i],
                                            "total" => $total_mark[$i],
                                            "average" => $average[$i],
                                            "grade" => $grade[$i],
                                            "points" => $avpoints[$i],
                                            "total_points"=>$points[$i],
                                            "average_points"=>$average_points[$i],
                                            "remarks" => $remarks[$i]

                                        );
                                        $where = array("admission" => $admission[$i], "period" => $period);

                                        if ($obj->update_record("final_result", $where, $data)) {

                                            if($get_cycle_two)
                                            {
                                                //grading algorithm
                                                $sql = "SELECT sum(mid_points),sum(mid) FROM results WHERE admission='$admission[$i]'  AND period = '$period'";
                                                $exe = mysqli_query($obj->con,$sql);
                                                $get_points = mysqli_fetch_array($exe);
                                                $points[$i] = $get_points['sum(mid_points)'];
                                                $total_mark[$i] = $get_points['sum(mid)'];

                                                if(($form>=3 || ($form==2 && $term==3)))
                                                {
                                                    //biology
                                                    $sql = "SELECT mid_points,mid FROM results WHERE subject='Biology' AND admission='$admission[$i]' AND period='$period'";
                                                    $exe = mysqli_query($obj->con, $sql);
                                                    $get_bio = mysqli_fetch_array($exe);
                                                    $biology[$i] = $get_bio['mid_points'];
                                                    $bio[$i] = $get_bio['mid'];
                                                    //physics
                                                    $sql = "SELECT mid_points,mid FROM results WHERE subject='Physics' AND admission='$admission[$i]'  AND period='$period'";
                                                    $exe = mysqli_query($obj->con, $sql);
                                                    $get_phy = mysqli_fetch_array($exe);
                                                    $physics[$i] = $get_phy['mid_points'];
                                                    $phy[$i] = $get_phy['mid'];

                                                    //chemistry
                                                    $sql = "SELECT mid_points,mid FROM results WHERE subject='Chemistry' AND admission='$admission[$i]'  AND period='$period'";
                                                    $exe = mysqli_query($obj->con, $sql);
                                                    $get_chem = mysqli_fetch_array($exe);
                                                    $chemistry[$i] = $get_chem['mid_points'];
                                                    $chem[$i] = $get_chem['mid'];


                                                    //agriculture
                                                    $sql = "SELECT mid_points,mid FROM results WHERE subject='Agriculture' AND admission='$admission[$i]'  AND period='$period'";
                                                    $exe = mysqli_query($obj->con, $sql);
                                                    $get_agri = mysqli_fetch_array($exe);
                                                    $agriculture[$i] = $get_agri['mid_points'];
                                                    $agri[$i] = $get_agri['mid'];

                                                    //business
                                                    $sql = "SELECT mid_points,mid FROM results WHERE subject='Business' AND admission='$admission[$i]'  AND period='$period'";
                                                    $exe = mysqli_query($obj->con, $sql);
                                                    $get_bus = mysqli_fetch_array($exe);
                                                    $business[$i] = $get_bus['mid_points'];
                                                    $bus[$i] = $get_bus['mid'];

                                                    //geography
                                                    $sql = "SELECT mid_points,mid FROM results WHERE subject='Geography' AND admission='$admission[$i]'  AND period='$period'";
                                                    $exe = mysqli_query($obj->con, $sql);
                                                    $get_geo = mysqli_fetch_array($exe);
                                                    $geography[$i] = $get_geo['mid_points'];
                                                    $geo[$i] = $get_geo['mid'];

                                                    //history
                                                    $sql = "SELECT mid_points,mid FROM results WHERE subject='History' AND admission='$admission[$i]'  AND period='$period'";
                                                    $exe = mysqli_query($obj->con, $sql);
                                                    $get_hist = mysqli_fetch_array($exe);
                                                    $history[$i] = $get_hist['mid_points'];
                                                    $his[$i] = $get_hist['mid'];

                                                    //cre
                                                    $sql = "SELECT mid_points,mid FROM results WHERE subject='CRE' AND admission='$admission[$i]'  AND period='$period'";
                                                    $exe = mysqli_query($obj->con, $sql);
                                                    $get_cre = mysqli_fetch_array($exe);
                                                    $cre[$i] = $get_cre['mid_points'];
                                                    $cr[$i] = $get_cre['mid'];

                                                    if($get_chem && $get_bio && $get_phy && ($get_bus || $get_agri))
                                                    {
                                                        if($get_bus)
                                                        {
                                                            $technical[$i] = $business[$i];
                                                            $technical_mark[$i] = $bus[$i];
                                                        }
                                                        if($get_agri)
                                                        {
                                                            $technical[$i] = $agriculture[$i];
                                                            $technical_mark[$i] = $agri[$i];
                                                        }

                                                        $lowest_science[$i] = min($chemistry[$i],$physics[$i],$biology[$i],$technical[$i]);
                                                        $lowest_science_mark[$i] = min($chem[$i],$phy[$i],$bio[$i],$technical_mark[$i]);
                                                        $points[$i] = $points[$i] - $lowest_science[$i];
                                                        $total_mark[$i] = $total_mark[$i]  - $lowest_science_mark[$i];

                                                    }
                                                    if($get_cre || $get_geo || $get_hist)
                                                    {
                                                        $points[$i] = $points[$i] - $history[$i] - $cre[$i] - $geography[$i];
                                                        $total_mark[$i]  = $total_mark[$i]  - $his[$i] - $cr[$i] - $geo[$i];
                                                        $points[$i] = $points[$i] + max($history[$i],$cre[$i],$geography[$i]);
                                                        $total_mark[$i] = $total_mark[$i] + max($his[$i],$cr[$i],$geo[$i]);

                                                    }
                                                }

                                                //calculate the grade
                                                $average[$i]=$total_mark[$i]/$min;
                                                $average_points[$i] = $points[$i] / $min;

                                                $sql = "SELECT * FROM $grading_system WHERE upper_limit>=$points[$i] AND lower_limit<=$points[$i]";
                                                $execute = mysqli_query($obj->con, $sql);

                                                if ($execute) {

                                                    while ($get_grades = mysqli_fetch_assoc($execute)) {
                                                        $grade[$i] = $get_grades['grade'];
                                                        $avpoints[$i] = $get_grades['points'];
                                                        $remarks[$i] = $get_grades['remarks'];
                                                    }
                                                }
                                                //data update in final results table

                                                //updating data to results table
                                                $data = array(

                                                    $subject => $cycle_two[$i],
                                                    "total" => $total_mark[$i],
                                                    "average" => $average[$i],
                                                    "grade" => $grade[$i],
                                                    "points" => $avpoints[$i],
                                                    "total_points"=> $points[$i],
                                                    "average_points"=>$average_points[$i],
                                                    "remarks" => $remarks[$i]

                                                );
                                                $where = array("admission" => $admission[$i], "period" => $period);

                                                if ($obj->update_record("cycle_two", $where, $data)) {

                                                    $success = "Exam results for form $class in $period have been uploaded successfully";

                                                }
                                            }
                                            else{

                                                $average[$i]=$cycle_two[$i]/$min;

                                                $sql_grade = "SELECT * FROM $grading_system WHERE upper_limit>=round($average[$i]) AND lower_limit<=round($average[$i])";
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
                                                    $subject => $cycle_two[$i],
                                                    "total" => $cycle_two[$i],
                                                    "cumulative"=>$cycle_two[$i],
                                                    "count" => 1,
                                                    "average" => $average[$i],
                                                    "grade" =>$grade[$i],
                                                    "points"=>$points[$i],
                                                    "remarks"=>$remarks[$i],
                                                    "term"=>$term

                                                );
                                                if ($obj->insert_record("cycle_two", $data)) {

                                                    $success = "Exam results for form $class in $period have been uploaded successfully";

                                                }
                                            }
                                        }
                                    }

                                }

                            }

                        }
                        //if no result exist in results table
                        else
                        {
                            //fetching the grade for grading

                            $sql_grade="SELECT * FROM $grading_system WHERE upper_limit>=floor($mark[$i]) AND lower_limit<=floor($mark[$i])";
                            $execute_grade=mysqli_query($obj->con,$sql_grade);

                            if($execute_grade) {

                                while ($get_grades = mysqli_fetch_assoc($execute_grade)) {
                                    $grade[$i] = $get_grades['grade'];
                                    $points[$i] = $get_grades['points'];
                                    $subject_points[$i]= $points[$i];
                                    $remarks[$i] = $get_grades['remarks'];
                                }

                                //getting grade for cycle 1
                                $sql = "SELECT * FROM $grading_system WHERE upper_limit>=$cycle_two[$i] AND lower_limit<=$cycle_two[$i]";
                                $execute = mysqli_query($obj->con, $sql);

                                while ($get_grades = mysqli_fetch_assoc($execute)) {
                                    $cycle_two_grade[$i] = $get_grades['grade'];
                                    $cycle_two_points[$i] = $get_grades['points'];
                                }

                                $data = array(
                                    "names" => $names[$i],
                                    "admission" => $admission[$i],
                                    "class" => $class,
                                    "form" => $form,
                                    "subject" => $subject,
                                    "mid" => $cycle_two[$i],
                                    "mid_grade"=>$cycle_two_grade[$i],
                                    "mid_points"=>$cycle_two_points[$i],
                                    "total" => $mark[$i],
                                    "grade" =>$grade[$i],
                                    "points"=>$points[$i],
                                    "remarks"=>$remarks[$i],
                                    "period" => $period,
                                    "term" =>$term,
                                    "cat_entered" => 0,
                                    "exam_entered" => 1,
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
                                            $count[$i]=$row['count']+1;
                                            $subject_total[$i]=$row[$subject]+$mark[$i];
                                        }

                                        //updating in cycle 1 results table

                                        //getting from cycle 1 results table so as to update
                                        $where=array("admission"=>$admission[$i],"period"=>$period);
                                        $get_cycle_two=$obj->fetch_records("cycle_two",$where);
                                        if($get_cycle_two)
                                        {
                                            foreach($get_cycle_two as $row)
                                            {
                                                $count_one[$i] = $row['count']+1;
                                            }
                                        }


                                        //grading algorithm
                                        $sql = "SELECT sum(points),sum(total) FROM results WHERE admission='$admission[$i]'  AND period = '$period'";
                                        $exe = mysqli_query($obj->con,$sql);
                                        $get_points = mysqli_fetch_array($exe);
                                        $points[$i] = $get_points['sum(points)'];
                                        $total_mark[$i] = $get_points['sum(total)'];

                                        if(($form>=3 || ($form==2 && $term==3)))
                                        {
                                            //biology
                                            $sql = "SELECT points,total FROM results WHERE subject='Biology' AND admission='$admission[$i]' AND period='$period'";
                                            $exe = mysqli_query($obj->con, $sql);
                                            $get_bio = mysqli_fetch_array($exe);
                                            $biology[$i] = $get_bio['points'];
                                            $bio[$i] = $get_bio['total'];

                                            //physics
                                            $sql = "SELECT points,total FROM results WHERE subject='Physics' AND admission='$admission[$i]'  AND period='$period'";
                                            $exe = mysqli_query($obj->con, $sql);
                                            $get_phy = mysqli_fetch_array($exe);
                                            $physics[$i] = $get_phy['points'];
                                            $phy[$i] = $get_phy['total'];

                                            //chemistry
                                            $sql = "SELECT points,total FROM results WHERE subject='Chemistry' AND admission='$admission[$i]'  AND period='$period'";
                                            $exe = mysqli_query($obj->con, $sql);
                                            $get_chem = mysqli_fetch_array($exe);
                                            $chemistry[$i] = $get_chem['points'];
                                            $chem[$i] = $get_chem['total'];


                                            //agriculture
                                            $sql = "SELECT points,total FROM results WHERE subject='Agriculture' AND admission='$admission[$i]'  AND period='$period'";
                                            $exe = mysqli_query($obj->con, $sql);
                                            $get_agri = mysqli_fetch_array($exe);
                                            $agriculture[$i] = $get_agri['points'];
                                            $agri[$i] = $get_agri['total'];

                                            //business
                                            $sql = "SELECT points,total FROM results WHERE subject='Business' AND admission='$admission[$i]'  AND period='$period'";
                                            $exe = mysqli_query($obj->con, $sql);
                                            $get_bus = mysqli_fetch_array($exe);
                                            $business[$i] = $get_bus['points'];
                                            $bus[$i] = $get_bus['total'];

                                            //geography
                                            $sql = "SELECT points,total FROM results WHERE subject='Geography' AND admission='$admission[$i]'  AND period='$period'";
                                            $exe = mysqli_query($obj->con, $sql);
                                            $get_geo = mysqli_fetch_array($exe);
                                            $geography[$i] = $get_geo['points'];
                                            $geo[$i] = $get_geo['total'];

                                            //history
                                            $sql = "SELECT points,total FROM results WHERE subject='History' AND admission='$admission[$i]'  AND period='$period'";
                                            $exe = mysqli_query($obj->con, $sql);
                                            $get_hist = mysqli_fetch_array($exe);
                                            $history[$i] = $get_hist['points'];
                                            $his[$i] = $get_hist['total'];

                                            //cre
                                            $sql = "SELECT points,total FROM results WHERE subject='CRE' AND admission='$admission[$i]'  AND period='$period'";
                                            $exe = mysqli_query($obj->con, $sql);
                                            $get_cre = mysqli_fetch_array($exe);
                                            $cre[$i] = $get_cre['points'];
                                            $cr[$i] = $get_cre['total'];

                                            if($get_chem && $get_bio && $get_phy && ($get_bus || $get_agri))
                                            {
                                                if($get_bus)
                                                {
                                                    $technical[$i] = $business[$i];
                                                    $technical_mark[$i] = $bus[$i];
                                                }
                                                if($get_agri)
                                                {
                                                    $technical[$i] = $agriculture[$i];
                                                    $technical_mark[$i] = $agri[$i];
                                                }

                                                $lowest_science[$i] = min($chemistry[$i],$physics[$i],$biology[$i],$technical[$i]);
                                                $lowest_science_mark[$i] = min($chem[$i],$phy[$i],$bio[$i],$technical_mark[$i]);
                                                $points[$i] = $points[$i] - $lowest_science[$i];
                                                $total_mark[$i] = $total_mark[$i] - $lowest_science_mark[$i];

                                            }
                                            if($get_cre || $get_geo || $get_hist)
                                            {
                                                $points[$i] = $points[$i] - $history[$i] - $cre[$i] - $geography[$i];
                                                $total_mark[$i]  = $total_mark[$i]  - $his[$i] - $cr[$i] - $geo[$i];
                                                $points[$i] = $points[$i] + max($history[$i],$cre[$i],$geography[$i]);
                                                $total_mark[$i] = $total_mark[$i] + max($his[$i],$cr[$i],$geo[$i]);

                                            }
                                        }

                                        //calculate the grade
                                        $average[$i]=$total_mark[$i]/$min;
                                        $average_points[$i] = $points[$i] / $min;


                                        $sql_grade = "SELECT * FROM $grading_system WHERE upper_limit>=$points[$i] AND lower_limit<=$points[$i]";
                                        $execute_grade = mysqli_query($obj->con, $sql_grade);

                                        if ($execute_grade) {

                                            while ($get_grades = mysqli_fetch_assoc($execute_grade)) {
                                                $grade[$i] = $get_grades['grade'];
                                                $avpoints[$i] = $get_grades['points'];
                                                $remarks[$i] = $get_grades['remarks'];
                                            }
                                        }

                                        //data to be updated


                                        //updating data to results table
                                        $data = array(

                                            $subject => $subject_total[$i],
                                            "total" => $total_mark[$i],
                                            "average" => $average[$i],
                                            "grade" => $grade[$i],
                                            "count" => $count[$i],
                                            "points" => $avpoints[$i],
                                            "total_points"=> $points[$i],
                                            "average_points"=>$average_points[$i],
                                            "remarks" => $remarks[$i]

                                        );
                                        $where = array("admission" => $admission[$i], "period" => $period);

                                        if ($obj->update_record("final_result", $where, $data)) {

                                            if($get_cycle_two)
                                            {

                                                //grading algorithm
                                                $sql = "SELECT sum(mid_points),sum(mid) FROM results WHERE admission='$admission[$i]'  AND period = '$period'";
                                                $exe = mysqli_query($obj->con,$sql);
                                                $get_points = mysqli_fetch_array($exe);
                                                $points[$i] = $get_points['sum(mid_points)'];
                                                $total_mark[$i] = $get_points['sum(mid)'];

                                                if(($form>=3 || ($form==2 && $term==3)))
                                                {
                                                    //biology
                                                    $sql = "SELECT mid_points,mid FROM results WHERE subject='Biology' AND admission='$admission[$i]' AND period='$period'";
                                                    $exe = mysqli_query($obj->con, $sql);
                                                    $get_bio = mysqli_fetch_array($exe);
                                                    $biology[$i] = $get_bio['mid_points'];
                                                    $bio[$i] = $get_bio['mid'];
                                                    //physics
                                                    $sql = "SELECT mid_points,mid FROM results WHERE subject='Physics' AND admission='$admission[$i]'  AND period='$period'";
                                                    $exe = mysqli_query($obj->con, $sql);
                                                    $get_phy = mysqli_fetch_array($exe);
                                                    $physics[$i] = $get_phy['mid_points'];
                                                    $phy[$i] = $get_phy['mid'];

                                                    //chemistry
                                                    $sql = "SELECT mid_points,mid FROM results WHERE subject='Chemistry' AND admission='$admission[$i]'  AND period='$period'";
                                                    $exe = mysqli_query($obj->con, $sql);
                                                    $get_chem = mysqli_fetch_array($exe);
                                                    $chemistry[$i] = $get_chem['mid_points'];
                                                    $chem[$i] = $get_chem['mid'];


                                                    //agriculture
                                                    $sql = "SELECT mid_points,mid FROM results WHERE subject='Agriculture' AND admission='$admission[$i]'  AND period='$period'";
                                                    $exe = mysqli_query($obj->con, $sql);
                                                    $get_agri = mysqli_fetch_array($exe);
                                                    $agriculture[$i] = $get_agri['mid_points'];
                                                    $agri[$i] = $get_agri['mid'];

                                                    //business
                                                    $sql = "SELECT mid_points,mid FROM results WHERE subject='Business' AND admission='$admission[$i]'  AND period='$period'";
                                                    $exe = mysqli_query($obj->con, $sql);
                                                    $get_bus = mysqli_fetch_array($exe);
                                                    $business[$i] = $get_bus['mid_points'];
                                                    $bus[$i] = $get_bus['mid'];

                                                    //geography
                                                    $sql = "SELECT mid_points,mid FROM results WHERE subject='Geography' AND admission='$admission[$i]'  AND period='$period'";
                                                    $exe = mysqli_query($obj->con, $sql);
                                                    $get_geo = mysqli_fetch_array($exe);
                                                    $geography[$i] = $get_geo['mid_points'];
                                                    $geo[$i] = $get_geo['mid'];

                                                    //history
                                                    $sql = "SELECT mid_points,mid FROM results WHERE subject='History' AND admission='$admission[$i]'  AND period='$period'";
                                                    $exe = mysqli_query($obj->con, $sql);
                                                    $get_hist = mysqli_fetch_array($exe);
                                                    $history[$i] = $get_hist['mid_points'];
                                                    $his[$i] = $get_hist['mid'];

                                                    //cre
                                                    $sql = "SELECT mid_points,mid FROM results WHERE subject='CRE' AND admission='$admission[$i]'  AND period='$period'";
                                                    $exe = mysqli_query($obj->con, $sql);
                                                    $get_cre = mysqli_fetch_array($exe);
                                                    $cre[$i] = $get_cre['mid_points'];
                                                    $cr[$i] = $get_cre['mid'];

                                                    if($get_chem && $get_bio && $get_phy && ($get_bus || $get_agri))
                                                    {
                                                        if($get_bus)
                                                        {
                                                            $technical[$i] = $business[$i];
                                                            $technical_mark[$i] = $bus[$i];
                                                        }
                                                        if($get_agri)
                                                        {
                                                            $technical[$i] = $agriculture[$i];
                                                            $technical_mark[$i] = $agri[$i];
                                                        }

                                                        $lowest_science[$i] = min($chemistry[$i],$physics[$i],$biology[$i],$technical[$i]);
                                                        $lowest_science_mark[$i] = min($chem[$i],$phy[$i],$bio[$i],$technical_mark[$i]);
                                                        $points[$i] = $points[$i] - $lowest_science[$i];
                                                        $total_mark[$i] = $total_mark[$i]  - $lowest_science_mark[$i];

                                                    }
                                                    if($get_cre || $get_geo || $get_hist)
                                                    {
                                                        $points[$i] = $points[$i] - $history[$i] - $cre[$i] - $geography[$i];
                                                        $total_mark[$i]  = $total_mark[$i]  - $his[$i] - $cr[$i] - $geo[$i];
                                                        $points[$i] = $points[$i] + max($history[$i],$cre[$i],$geography[$i]);
                                                        $total_mark[$i] = $total_mark[$i] + max($his[$i],$cr[$i],$geo[$i]);

                                                    }
                                                }

                                                //calculate the grade
                                                $average[$i]=$total_mark[$i]/$min;
                                                $average_points[$i] = $points[$i] / $min;

                                                $sql = "SELECT * FROM $grading_system WHERE upper_limit>=$points[$i] AND lower_limit<=$points[$i]";
                                                $execute = mysqli_query($obj->con, $sql);

                                                if ($execute) {

                                                    while ($get_grades = mysqli_fetch_assoc($execute)) {
                                                        $grade[$i] = $get_grades['grade'];
                                                        $avpoints[$i] = $get_grades['points'];
                                                        $remarks[$i] = $get_grades['remarks'];
                                                    }
                                                }
                                                //data update in final results table

                                                //updating data to results table
                                                $data = array(

                                                    $subject => $cycle_two[$i],
                                                    "total" => $total_mark[$i],
                                                    "average" => $average[$i],
                                                    "grade" => $grade[$i],
                                                    "points" => $avpoints[$i],
                                                    "total_points"=> $points[$i],
                                                    "average_points"=>$average_points[$i],
                                                    "count" => $count_one[$i],
                                                    "remarks" => $remarks[$i]

                                                );
                                                $where = array("admission" => $admission[$i], "period" => $period);

                                                if ($obj->update_record("cycle_two", $where, $data)) {

                                                    $success = "Exam results for form $class in $period have been uploaded successfully";

                                                }
                                            }
                                            else{

                                                $average[$i]=$cycle_two[$i]/$min;

                                                $sql_grade = "SELECT * FROM $grading_system WHERE upper_limit>=round($average[$i]) AND lower_limit<=round($average[$i])";
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
                                                    $subject => $cycle_two[$i],
                                                    "total" => $cycle_two[$i],
                                                    "cumulative"=>$cycle_two[$i],
                                                    "count" => 1,
                                                    "average" => $average[$i],
                                                    "grade" =>$grade[$i],
                                                    "points"=>$points[$i],
                                                    "remarks"=>$remarks[$i],
                                                    "term"=>$term

                                                );
                                                if ($obj->insert_record("cycle_two", $data)) {

                                                    $success = "Exam results for form $class in $period have been uploaded successfully";

                                                }

                                            }

                                        }



                                    }
                                    //if results do not exist
                                    else
                                    {

                                        //entering new data

                                        //calculate the grade

                                        $average[$i] = $mark[$i] / $min;


                                        $sql_grade = "SELECT * FROM $grading_system WHERE upper_limit>=round($average[$i]) AND lower_limit<=round($average[$i])";
                                        $execute_grade = mysqli_query($obj->con, $sql_grade);

                                        if ($execute_grade) {

                                            while ($get_grades = mysqli_fetch_assoc($execute_grade)) {
                                                $grade[$i] = $get_grades['grade'];
                                                $points[$i] = $get_grades['points'];
                                                $remarks[$i] = $get_grades['remarks'];
                                            }
                                        }

                                        $average_points[$i] =  $subject_points[$i] / $min;


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
                                            "total_points"=> $subject_points[$i],
                                            "average_points"=>$average_points[$i],
                                            "term"=>$term,
                                            "remarks"=>$remarks[$i]

                                        );

                                        if($obj->insert_record("final_result",$data))
                                        {
                                            $average[$i]=$cycle_two[$i]/$min;

                                            $sql_grade = "SELECT * FROM $grading_system WHERE upper_limit>=round($average[$i]) AND lower_limit<=round($average[$i])";
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
                                                $subject => $cycle_two[$i],
                                                "total" => $cycle_two[$i],
                                                "cumulative"=>$cycle_two[$i],
                                                "count" => 1,
                                                "average" => $average[$i],
                                                "grade" =>$grade[$i],
                                                "points"=>$points[$i],
                                                "remarks"=>$remarks[$i],
                                                "term"=>$term

                                            );
                                            if ($obj->insert_record("cycle_two", $data)) {

                                                $success = "Exam results for form $class in $period have been uploaded successfully";

                                            }
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
    <title>Enter exam results for <?=$class?></title>
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
                        Form <?=$class?> CYCLE 2 exam results (<?=$subject?>) for <?=$period?>
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
                                    <li>Marks entered MUST be numerical and less than or equal to <?=$limit?>, fields with other values will not be saved</li>
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
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Admission number</th>
                                        <th>Student names</th>
                                        <th>Exam result field (X/
                                            <?php
                                            echo +$limit;
                                            ?>
                                            )
                                        </th>
                                    </tr>

                                    </thead>
                                    <tbody>
                                    <?php
                                    $sql = "SELECT names,AdmissionNumber FROM student WHERE class='$class' AND AdmissionNumber NOT IN (SELECT admission FROM results WHERE period='$period' AND subject='$subject' AND exam_entered=1)";
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
                                                <td><input type="number" class="form-control" name="exam[]"

                                                        /></td>
                                                </td>
                                            </tr>
                                        <?php

                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                                <button type="submit" name="submit" class="btn btn-primary"><span
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