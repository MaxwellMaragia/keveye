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
                        $mark[$i] = $mark[$i] / 2;
                        $cycle_one[$i] = $mark[$i] * 2;

                        $where=array("admission"=>$admission[$i],"period"=>$period,"subject"=>$subject);
                        $fetch_results=$obj->fetch_records("results",$where);

                        //if results for selected subject exist in results table
                        if($fetch_results)
                        {
                            foreach($fetch_results as $row)
                            {
                                $total[$i] = $row['total'] + $mark[$i];
                                $temp[$i] =$row['total'];
                                $subject_points[$i]=$row['points'];



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
                                    $sql = "SELECT * FROM $grading_system WHERE upper_limit>=$cycle_one[$i] AND lower_limit<=$cycle_one[$i]";
                                    $execute = mysqli_query($obj->con, $sql);

                                    while ($get_grades = mysqli_fetch_assoc($execute)) {
                                        $cycle_one_grade[$i] = $get_grades['grade'];
                                    }

                                    //updating data to subject results table


                                    $data = array(

                                        "mid" => $cycle_one[$i],
                                        "mid_grade"=>$cycle_one_grade[$i],
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
                                        $get_final=$obj->fetch_records("final_result",$where);

                                        foreach($get_final as $row)
                                        {
                                            $total_mark[$i]=$row['total']+$mark[$i];
                                            $total_new[$i]=$total_mark[$i];
                                            $subject_total[$i]=$row[$subject]+$mark[$i];
                                            $count[$i] = $row['count'];
                                            $tpoints[$i] = $row['total_points']+$points[$i];

                                        }

                                        //deduct current subject points

                                        $sql = "SELECT * FROM $grading_system WHERE upper_limit>=floor($temp[$i]) AND lower_limit<=floor($temp[$i])";
                                        $execute = mysqli_query($obj->con, $sql);

                                        while ($get_grades = mysqli_fetch_assoc($execute)) {
                                            $tpoints[$i] = $tpoints[$i] - $get_grades['points'];
                                            $current_points[$i] = $get_grades['points'];
                                        }



                                        //updating in cycle 1 results table

                                        //getting from cycle 1 results table so as to update
                                        $where=array("admission"=>$admission[$i],"period"=>$period);
                                        $get_cycle_one=$obj->fetch_records("cycle_two",$where);


                                        //if results exist in cycle one table
                                        if($get_cycle_one) {
                                            foreach ($get_cycle_one as $row) {
                                                $total_mark_one[$i] = $row['total'] + $cycle_one[$i];
                                                $count[$i] = $row['count'];
                                                $points_one = $row['points'];

                                            }
                                        }

//                                      if($class[0]>=3)
//                                      {
//                                          $average[$i]=$total_mark[$i]/$count[$i];
//                                      }
//                                      else
//                                      {
//                                          $average[$i]=$total_mark[$i]/$min;
//                                      }
                                        if($form>=3 && $subject == 'Chemistry')
                                        {
                                            //check if physics or biology exist


                                            $where = array('subject'=>'Biology','admission'=>$admission[$i],'period'=>$period);
                                            $get_bio[$i] = $obj->fetch_records('results',$where);
                                            $where = array('subject'=>'Physics','admission'=>$admission[$i],'period'=>$period);
                                            $get_phy[$i] = $obj->fetch_records('results',$where);
                                            $where = array('subject'=>'Agriculture','admission'=>$admission[$i],'period'=>$period);
                                            $get_agric[$i] = $obj->fetch_records('results',$where);
                                            $where = array('subject'=>'Business','admission'=>$admission[$i],'period'=>$period);
                                            $get_bus[$i] = $obj->fetch_records('results',$where);


                                            //if both exist
                                            if($get_bio[$i] && $get_phy[$i])
                                            {
                                                foreach($get_phy[$i] as $row)
                                                {
                                                    $physics[$i] = $row['total'];
                                                    $physics_points[$i] = $row['points'];
                                                    $physics_one[$i] = $row['mid'];
                                                }
                                                foreach($get_bio[$i] as $row)
                                                {
                                                    $biology[$i] = $row['total'];
                                                    $biology_points[$i] = $row['points'];
                                                    $biology_one[$i]= $row['mid'];
                                                }

                                                //remove the existing from total if business and agric exist

                                                if($get_agric[$i] || $get_bus[$i])
                                                {
                                                //pick the 2 highest
                                                $sciences[$i] = array($biology[$i],$physics[$i],$temp[$i]);
                                                $sciences_one[$i] = array($biology_one[$i],$physics_one[$i],$cycle_one[$i]);
                                                rsort($sciences[$i]);
                                                rsort($sciences_one[$i]);
                                                $first[$i] = $sciences[$i][0];
                                                $first_one[$i] = $sciences_one[$i][0];
                                                $second[$i] = $sciences[$i][1];
                                                $second_one[$i] = $sciences_one[$i][1];
                                                //deduct the lowest

                                                $total_mark[$i] = $total_mark[$i] - $first[$i] - $second[$i] - $mark[$i];
                                                $total_mark_one[$i] = $total_mark_one[$i] - $first_one[$i] - $second_one[$i] - $cycle_one[$i];

                                                $highest[$i] = array($physics[$i],$biology[$i],$total[$i]);
                                                $highest_one[$i] = array($physics_one[$i],$biology_one[$i],$cycle_one[$i]);
                                                rsort($highest[$i]);
                                                rsort($highest_one[$i]);
                                                $first[$i] = $highest[$i][0];
                                                $first_one[$i] = $highest_one[$i][0];
                                                $second[$i] = $highest[$i][1];
                                                $second_one[$i] = $highest_one[$i][1];
                                                $lowest[$i] = $highest[$i][2];
                                                $lowest_one[$i] = $highest_one[$i][2];

                                                $total_mark[$i] = $total_mark[$i] + $first[$i] + $second[$i];
                                                $total_mark_one[$i] = $total_mark_one[$i] + $first_one[$i] + $second_one[$i];

                                                //calculate points
                                                $sci_points[$i] = array($biology_points[$i],$physics_points[$i],$current_points[$i]);
                                                rsort($sci_points[$i]);
                                                $lowest_point[$i] = $sci_points[$i][2];
                                                $tpoints[$i] = $tpoints[$i] - $lowest_point[$i];


                                                    if($get_agric[$i])
                                                    {
                                                        foreach($get_agric[$i] as $row)
                                                        {
                                                            $technical[$i] = $row['total'];
                                                            $technical_points[$i] = $row['points'];
                                                            $technical_one[$i] = $row['mid'];
                                                        }
                                                    }
                                                    if($get_bus[$i])
                                                    {
                                                        foreach($get_bus[$i] as $row)
                                                        {
                                                            $technical[$i] = $row['total'];
                                                            $technical_points[$i] = $row['points'];
                                                            $technical_one[$i] = $row['mid'];
                                                        }
                                                    }

                                                    if($lowest[$i] > $technical[$i])
                                                    {
                                                        $total_mark[$i] = $total_mark[$i] - $technical[$i] + $lowest[$i];
                                                        $tpoints[$i] = $tpoints[$i] - $technical_points[$i] + $lowest_point[$i];
                                                    }
                                                    if($lowest_one[$i] > $technical_one[$i])
                                                    {
                                                        $total_mark_one[$i] = $total_mark_one[$i] - $technical_one[$i] + $lowest_one[$i];
                                                    }
                                                }
                                            }
                                        }

                                        if($form>=3 && $subject == 'Biology')
                                        {
                                            //check if physics and chemistry exist

                                            $where = array('subject'=>'Chemistry','admission'=>$admission[$i],'period'=>$period);
                                            $get_chem[$i] = $obj->fetch_records('results',$where);
                                            $where = array('subject'=>'Physics','admission'=>$admission[$i],'period'=>$period);
                                            $get_phy[$i] = $obj->fetch_records('results',$where);
                                            $where = array('subject'=>'Agriculture','admission'=>$admission[$i],'period'=>$period);
                                            $get_agric[$i] = $obj->fetch_records('results',$where);
                                            $where = array('subject'=>'Business','admission'=>$admission[$i],'period'=>$period);
                                            $get_bus[$i] = $obj->fetch_records('results',$where);

                                            //if both exist
                                            if($get_chem[$i] && $get_phy[$i])
                                            {
                                                foreach($get_phy[$i] as $row)
                                                {
                                                    $physics[$i] = $row['total'];
                                                    $physics_points[$i] = $row['points'];
                                                    $physics_one[$i] = $row['mid'];
                                                }
                                                foreach($get_chem[$i] as $row)
                                                {
                                                    $chemistry[$i] = $row['total'];
                                                    $chemistry_points[$i] = $row['points'];
                                                    $chemistry_one[$i] = $row['mid'];
                                                }

                                                if($get_agric[$i] || $get_bus[$i])
                                                {


                                                    //pick the 2 highest
                                                $sciences[$i] = array($chemistry[$i],$physics[$i],$temp[$i]);
                                                $sciences_one[$i] = array($chemistry_one[$i],$physics_one[$i],$cycle_one[$i]);
                                                rsort($sciences[$i]);
                                                rsort($sciences_one[$i]);
                                                $first[$i] = $sciences[$i][0];
                                                $first_one[$i] = $sciences_one[$i][0];
                                                $second[$i] = $sciences[$i][1];
                                                $second_one[$i] = $sciences_one[$i][1];
                                                //deduct the lowest

                                                $total_mark[$i] = $total_mark[$i] - $first[$i] - $second[$i] - $mark[$i];
                                                $total_mark_one[$i] = $total_mark_one[$i] - $first_one[$i] - $second_one[$i] - $cycle_one[$i];

                                                $highest[$i] = array($physics[$i],$chemistry[$i],$total[$i]);
                                                $highest_one[$i] = array($physics_one[$i],$chemistry_one[$i],$cycle_one[$i]);
                                                rsort($highest[$i]);
                                                rsort($highest_one[$i]);
                                                $first[$i] = $highest[$i][0];
                                                $first_one[$i] = $highest_one[$i][0];
                                                $second[$i] = $highest[$i][1];
                                                $second_one[$i] = $highest_one[$i][1];
                                                $lowest[$i] = $highest[$i][2];
                                                $lowest_one[$i] = $highest_one[$i][2];

                                                $total_mark[$i] = $total_mark[$i] + $first[$i] + $second[$i];
                                                $total_mark_one[$i] = $total_mark_one[$i] + $first_one[$i] + $second_one[$i];

                                                $sci_points[$i] = array($chemistry_points[$i],$physics_points[$i],$current_points[$i]);
                                                rsort($sci_points[$i]);
                                                $lowest_point[$i] = $sci_points[$i][2];
                                                $tpoints[$i] = $tpoints[$i] - $lowest_point[$i];


                                                    if($get_agric[$i])
                                                    {
                                                        foreach($get_agric[$i] as $row)
                                                        {
                                                            $technical[$i] = $row['total'];
                                                            $technical_points[$i] = $row['points'];
                                                            $technical_one[$i] = $row['mid'];
                                                        }
                                                    }
                                                    if($get_bus[$i])
                                                    {
                                                        foreach($get_bus[$i] as $row)
                                                        {
                                                            $technical[$i] = $row['total'];
                                                            $technical_points[$i] = $row['points'];
                                                            $technical_one[$i] = $row['mid'];
                                                        }
                                                    }

                                                    if($lowest[$i] > $technical[$i])
                                                    {
                                                        $total_mark[$i] = $total_mark[$i] - $technical[$i] + $lowest[$i];
                                                        $tpoints[$i] = $tpoints[$i] - $technical_points[$i] + $lowest_point[$i];
                                                    }
                                                    if($lowest_one[$i] > $technical_one[$i])
                                                    {
                                                        $total_mark_one[$i] = $total_mark_one[$i] - $technical_one[$i] + $lowest_one[$i];
                                                    }
                                                }
                                            }
                                        }
                                        if($form>=3 && $subject == 'Physics')
                                        {
                                            //check if biology and chemistry exist

                                            $where = array('subject'=>'Biology','admission'=>$admission[$i],'period'=>$period);
                                            $get_bio[$i] = $obj->fetch_records('results',$where);
                                            $where = array('subject'=>'Chemistry','admission'=>$admission[$i],'period'=>$period);
                                            $get_chem[$i] = $obj->fetch_records('results',$where);
                                            $where = array('subject'=>'Agriculture','admission'=>$admission[$i],'period'=>$period);
                                            $get_agric[$i] = $obj->fetch_records('results',$where);
                                            $where = array('subject'=>'Business','admission'=>$admission[$i],'period'=>$period);
                                            $get_bus[$i] = $obj->fetch_records('results',$where);


                                            //if both exist
                                            if($get_bio[$i] && $get_chem[$i])
                                            {
                                                foreach($get_bio[$i] as $row)
                                                {
                                                    $biology[$i] = $row['total'];
                                                    $biology_one[$i] = $row['mid'];
                                                    $biology_points[$i] = $row['points'];
                                                }
                                                foreach($get_chem[$i] as $row)
                                                {
                                                    $chemistry[$i] = $row['total'];
                                                    $chemistry_points[$i] = $row['points'];
                                                    $chemistry_one[$i] = $row['mid'];
                                                }

                                                if($get_agric[$i] || $get_bus[$i])
                                                {

                                                    //pick the 2 highest
                                                    $sciences[$i] = array($biology[$i],$chemistry[$i],$temp[$i]);
                                                    $sciences_one[$i] = array($biology_one[$i],$chemistry_one[$i],$cycle_one[$i]);
                                                    rsort($sciences[$i]);
                                                    rsort($sciences_one[$i]);
                                                    $first[$i] = $sciences[$i][0];
                                                    $first_one[$i] = $sciences_one[$i][0];
                                                    $second[$i] = $sciences[$i][1];
                                                    $second_one[$i] = $sciences_one[$i][1];
                                                    //deduct the lowest

                                                    $total_mark[$i] = $total_mark[$i] - $first[$i] - $second[$i] - $mark[$i];
                                                    $total_mark_one[$i] = $total_mark_one[$i] - $first_one[$i] - $second_one[$i] - $cycle_one[$i];

                                                    $highest[$i] = array($chemistry[$i],$biology[$i],$total[$i]);
                                                    $highest_one[$i] = array($chemistry_one[$i],$biology_one[$i],$cycle_one[$i]);
                                                    rsort($highest[$i]);
                                                    rsort($highest_one[$i]);
                                                    $first[$i] = $highest[$i][0];
                                                    $first_one[$i] = $highest_one[$i][0];
                                                    $second[$i] = $highest[$i][1];
                                                    $second_one[$i] = $highest_one[$i][1];
                                                    $lowest[$i] = $highest[$i][2];
                                                    $lowest_one[$i] = $highest_one[$i][2];

                                                    $total_mark[$i] = $total_mark[$i] + $first[$i] + $second[$i];
                                                    $total_mark_one[$i] = $total_mark_one[$i] + $first_one[$i] + $second_one[$i];

                                                    $sci_points[$i] = array($chemistry_points[$i],$biology_points[$i],$current_points[$i]);
                                                    rsort($sci_points[$i]);
                                                    $lowest_point[$i] = $sci_points[$i][2];
                                                    $tpoints[$i] = $tpoints[$i] - $lowest_point[$i];

                                                    if($get_agric[$i])
                                                    {
                                                        foreach($get_agric[$i] as $row)
                                                        {
                                                            $technical[$i] = $row['total'];
                                                            $technical_points[$i] = $row['points'];
                                                            $technical_one[$i] = $row['mid'];
                                                        }
                                                    }
                                                    if($get_bus[$i])
                                                    {
                                                        foreach($get_bus[$i] as $row)
                                                        {
                                                            $technical[$i] = $row['total'];
                                                            $technical_points[$i] = $row['points'];
                                                            $technical_one[$i] = $row['mid'];
                                                        }
                                                    }

                                                    if($lowest[$i] > $technical[$i])
                                                    {
                                                        $total_mark[$i] = $total_mark[$i] - $technical[$i] + $lowest[$i];
                                                        $tpoints[$i] = $tpoints[$i] - $technical_points[$i] + $lowest_point[$i];
                                                    }
                                                    if($lowest_one[$i] > $technical_one[$i])
                                                    {
                                                        $total_mark_one[$i] = $total_mark_one[$i] - $technical_one[$i] + $lowest_one[$i];
                                                    }
                                                }

                                            }
                                        }

                                        if($form>=3 && $subject == 'History')
                                        {
                                            //check if cre and geog exist
                                            $where = array('subject'=>'CRE','admission'=>$admission[$i],'period'=>$period);
                                            $get_cre[$i] = $obj->fetch_records('results',$where);
                                            $where = array('subject'=>'Geography','admission'=>$admission[$i],'period'=>$period);
                                            $get_geo[$i] = $obj->fetch_records('results',$where);

                                            //if cre exists but not geography
                                            if($get_cre[$i] && !$get_geo[$i])
                                            {
                                                foreach($get_cre[$i] as $row)
                                                {
                                                    $cre[$i] = $row['total'];
                                                    $cre_points[$i] = $row['points'];
                                                    $cre_one[$i] = $row['mid'];
                                                }
                                                //swapping
                                                $humanities[$i] = array($cre[$i],$temp[$i]);
                                                $humanities_one[$i] = array($cre_one[$i],$cycle_one[$i]);
                                                $total_mark[$i] = $total_mark[$i] - max($humanities[$i]) - $mark[$i];
                                                $total_mark_one[$i] = $total_mark_one[$i] - max($humanities_ones[$i]) - $cycle_one[$i];
                                                //total points

                                                //pick highest after update
                                                $highest[$i] = array($cre[$i],$total[$i]);
                                                $highest_one[$i] = array($cre_one[$i],$cycle_one[$i]);
                                                $total_mark[$i] = $total_mark[$i] + max($highest[$i]);
                                                $total_mark_one[$i] = $total_mark_one[$i] + max($highest_one[$i]);

                                                if($cre[$i]<$total[$i])
                                                {
                                                    $tpoints[$i] = $tpoints[$i] + $current_points[$i] - $cre_points[$i];
                                                }
                                            }

                                            //if geography exists but not cre
                                            if($get_geo[$i] && !$get_cre[$i] )
                                            {
                                                foreach($get_geo[$i] as $row)
                                                {
                                                    $geo[$i] = $row['total'];
                                                    $geo_points[$i] = $row['points'];
                                                    $geo_one[$i] = $row['mid'];
                                                }
                                                //swapping
                                                $humanities[$i] = array($geo[$i],$temp[$i]);
                                                $humanities_one[$i] = array($geo_one[$i],$cycle_one[$i]);
                                                $total_mark[$i] = $total_mark[$i] - max($humanities[$i]) - $mark[$i];
                                                $total_mark_one[$i] = $total_mark_one[$i] - max($humanities_one[$i]) - $cycle_one[$i];

                                                //total points


                                                //pick highest after update
                                                $highest[$i] = array($geo[$i],$total[$i]);
                                                $highest_one[$i] = array($geo_one[$i],$cycle_one[$i]);
                                                $total_mark[$i] = $total_mark[$i] + max($highest[$i]);
                                                $total_mark_one[$i] = $total_mark_one[$i] + max($highest_one[$i]);

                                                if($geo[$i]<$total[$i])
                                                {
                                                    $tpoints[$i] = $tpoints[$i] + $current_points[$i] - $geo_points[$i];
                                                }
                                            }
                                            if($get_cre[$i] && $get_geo[$i])
                                            {
                                                foreach($get_geo[$i] as $row)
                                                {
                                                    $geo[$i] = $row['total'];
                                                    $geo_points[$i] = $row['points'];
                                                    $geo_one[$i] = $row['mid'];
                                                }
                                                foreach($get_cre[$i] as $row)
                                                {
                                                    $cre[$i] = $row['total'];
                                                    $cre_points[$i] = $row['points'];
                                                    $cre_one[$i] = $row['mid'];
                                                }

                                                //pick only the highest humanity and add to the total
                                                $geocre[$i] = array($cre[$i],$geo[$i],$temp[$i]);
                                                $geocre_one[$i] = array($cre_one[$i],$geo_one[$i],$cre_one[$i]);
                                                $total_mark[$i] = $total_mark[$i] - max($geocre[$i]) - $mark[$i];
                                                $total_mark_one[$i] = $total_mark_one[$i] - max($geocre_one[$i]) - $cycle_one[$i];
                                                $humanities[$i] = array($total[$i],$geo[$i],$cre[$i]);
                                                $humanities_one[$i] = array($cycle_one[$i],$geo_one[$i],$cre_one[$i]);
                                                $total_mark[$i] = $total_mark[$i] + max($humanities[$i]);
                                                $total_mark_one[$i] = $total_mark_one[$i] + max($humanities_one[$i]);

                                                $tpoints[$i] = $tpoints[$i]+$current_points[$i];
                                                $hum_points[$i] = array($geo_points[$i],$cre_points[$i],$current_points[$i]);
                                                rsort($hum_points[$i]);
                                                $tpoints[$i] = $tpoints[$i] - $hum_points[$i][0] - $hum_points[$i][1];
                                            }

                                        }
                                        if($form>=3 && $subject == 'Geography')
                                        {
                                            //check if cre and hist exist
                                            $where = array('subject'=>'CRE','admission'=>$admission[$i],'period'=>$period);
                                            $get_cre[$i] = $obj->fetch_records('results',$where);
                                            $where = array('subject'=>'History','admission'=>$admission[$i],'period'=>$period);
                                            $get_hist[$i] = $obj->fetch_records('results',$where);

                                            //if cre exists but not geography
                                            if($get_cre[$i] && !$get_hist[$i])
                                            {
                                                foreach($get_cre[$i] as $row)
                                                {
                                                    $cre[$i] = $row['total'];
                                                    $cre_points[$i] = $row['points'];
                                                    $cre_one[$i] = $row['mid'];
                                                }
                                                //swapping
                                                $humanities[$i] = array($cre[$i],$temp[$i]);
                                                $humanities_one[$i] = array($cre_one[$i],$cycle_one[$i]);
                                                $total_mark[$i] = $total_mark[$i] - max($humanities[$i]) - $mark[$i];
                                                $total_mark_one[$i] = $total_mark_one[$i] - max($humanities_one[$i]) - $cycle_one[$i];

                                                //total points


                                                //pick highest after update
                                                $highest[$i] = array($cre[$i],$total[$i]);
                                                $highest_one[$i] = array($cre_one[$i],$cycle_one[$i]);
                                                $total_mark[$i] = $total_mark[$i] + max($highest[$i]);
                                                $total_mark_one[$i] = $total_mark_one[$i] + max($highest_one[$i]);

                                                if($cre[$i]<$total[$i])
                                                {
                                                    $tpoints[$i] = $tpoints[$i] + $current_points[$i] - $cre_points[$i];
                                                }
                                            }

                                            //if geography exists but not cre
                                            if($get_hist[$i] && !$get_cre[$i] )
                                            {
                                                foreach($get_hist[$i] as $row)
                                                {
                                                    $his[$i] = $row['total'];
                                                    $his_points[$i] = $row['points'];
                                                    $his_one[$i] = $row['mid'];
                                                }
                                                //swapping
                                                $humanities[$i] = array($his[$i],$temp[$i]);
                                                $humanities_one[$i] = array($his_one[$i],$cycle_one[$i]);
                                                $total_mark[$i] = $total_mark[$i] - max($humanities[$i]) - $mark[$i];
                                                $total_mark_one[$i] = $total_mark_one[$i] - max($humanities_one[$i]) - $cycle_one[$i];

                                                //total points


                                                //pick highest after update
                                                $highest[$i] = array($his[$i],$total[$i]);
                                                $highest_one[$i] = array($his_one[$i],$cycle_one[$i]);
                                                $total_mark[$i] = $total_mark[$i] + max($highest[$i]);
                                                $total_mark_one[$i] = $total_mark_one[$i] + max($highest_one[$i]);

                                                if($his[$i]<$total[$i])
                                                {
                                                    $tpoints[$i] = $tpoints[$i] + $current_points[$i] - $his_points[$i];
                                                }
                                            }
                                            if($get_cre[$i] && $get_hist[$i])
                                            {
                                                foreach($get_hist[$i] as $row)
                                                {
                                                    $his[$i] = $row['total'];
                                                    $his_points[$i] = $row['points'];
                                                    $his_one[$i] = $row['mid'];
                                                }
                                                foreach($get_cre[$i] as $row)
                                                {
                                                    $cre[$i] = $row['total'];
                                                    $cre_points[$i] = $row['points'];
                                                    $cre_one[$i] = $row['mid'];
                                                }

                                                //pick only the highest humanity and add to the total
                                                $hiscre[$i] = array($cre[$i],$his[$i],$temp[$i]);
                                                $hiscre_one[$i] = array($cre_one[$i],$his_one[$i],$cycle_one[$i]);
                                                $total_mark[$i] = $total_mark[$i] - max($hiscre[$i]) - $mark[$i];
                                                $total_mark_one[$i] = $total_mark_one[$i] - max($hiscre_one[$i]) - $cycle_one[$i];
                                                $humanities[$i] = array($total[$i],$his[$i],$cre[$i]);
                                                $humanities_one[$i] = array($cycle_one[$i],$his_one[$i],$cre_one[$i]);
                                                $total_mark[$i] = $total_mark[$i] + max($humanities[$i]);
                                                $total_mark_one[$i] = $total_mark_one[$i] + max($humanities_one[$i]);

                                                $tpoints[$i] = $tpoints[$i] + $current_points[$i];
                                                $hum_points[$i] = array($his_points[$i],$cre_points[$i],$current_points[$i]);
                                                rsort($hum_points[$i]);
                                                $tpoints[$i] = $tpoints[$i] - $hum_points[$i][0] - $hum_points[$i][1];
                                            }
                                        }
                                        if($form>=3 && $subject == 'CRE')
                                        {
                                            //check if geo and hist exist
                                            $where = array('subject'=>'Geography','admission'=>$admission[$i],'period'=>$period);
                                            $get_geo[$i] = $obj->fetch_records('results',$where);
                                            $where = array('subject'=>'History','admission'=>$admission[$i],'period'=>$period);
                                            $get_hist[$i] = $obj->fetch_records('results',$where);

                                            //if geo exists but not his
                                            if($get_geo[$i] && !$get_hist[$i])
                                            {
                                                foreach($get_geo[$i] as $row)
                                                {
                                                    $geo[$i] = $row['total'];
                                                    $geo_points[$i] = $row['points'];
                                                    $geo_one[$i] = $row['mid'];
                                                }
                                                //swapping
                                                $humanities[$i] = array($geo[$i],$temp[$i]);
                                                $humanities_one[$i] = array($geo_one[$i],$cycle_one[$i]);
                                                $total_mark[$i] = $total_mark[$i] - max($humanities[$i]) - $mark[$i];
                                                $total_mark_one[$i] = $total_mark_one[$i] - max($humanities_one[$i]) - $cycle_one[$i];

                                                //total points


                                                //pick highest after update
                                                $highest[$i] = array($geo[$i],$total[$i]);
                                                $highest_one[$i] = array($geo_one[$i],$cycle_one[$i]);
                                                $total_mark[$i] = $total_mark[$i] + max($highest[$i]);
                                                $total_mark_one[$i] = $total_mark_one[$i] + max($highest_one[$i]);

                                                if($geo[$i]<$total[$i])
                                                {
                                                    $tpoints[$i] = $tpoints[$i] + $current_points[$i] - $geo_points[$i];
                                                }

                                            }

                                            //if geography exists but not cre
                                            if($get_hist[$i] && !$get_geo[$i] )
                                            {
                                                foreach($get_hist[$i] as $row)
                                                {
                                                    $his[$i] = $row['total'];
                                                    $his_points[$i] = $row['points'];
                                                    $his_one[$i] = $row['mid'];
                                                }
                                                //swapping
                                                $humanities[$i] = array($his[$i],$temp[$i]);
                                                $humanities_one[$i] = array($his_one[$i],$cycle_one[$i]);
                                                $total_mark[$i] = $total_mark[$i] - max($humanities[$i]) - $mark[$i];
                                                $total_mark_one[$i] = $total_mark_one[$i] - max($humanities_one[$i]) - $cycle_one[$i];

                                                //total points

                                                //pick highest after update
                                                $highest[$i] = array($his[$i],$total[$i]);
                                                $highest_one[$i] = array($his_one[$i],$cycle_one[$i]);
                                                $total_mark[$i] = $total_mark[$i] + max($highest[$i]);
                                                $total_mark_one[$i] = $total_mark_one[$i] + max($highest_one[$i]);

                                                if($his[$i]<$total[$i])
                                                {
                                                    $tpoints[$i] = $tpoints[$i] + $current_points[$i] - $his_points[$i];
                                                }
                                            }
                                            if($get_geo[$i] && $get_hist[$i])
                                            {
                                                foreach($get_hist[$i] as $row)
                                                {
                                                    $his[$i] = $row['total'];
                                                    $his_one[$i] = $row['mid'];
                                                    $his_points[$i] = $row['points'];
                                                }
                                                foreach($get_geo[$i] as $row)
                                                {
                                                    $geo[$i] = $row['total'];
                                                    $geo_one[$i] = $row['mid'];
                                                    $geo_points[$i] = $row['points'];
                                                }

                                                //pick only the highest humanity and add to the total
                                                $hisgeo[$i] = array($his[$i],$geo[$i],$temp[$i]);
                                                $hisgeo_one[$i] = array($his_one[$i],$geo_one[$i],$cycle_one[$i]);
                                                $total_mark[$i] = $total_mark[$i] - max($hiscre[$i]) - $mark[$i];
                                                $total_mark_one[$i] = $total_mark_one[$i] - max($hiscre_one[$i]) - $cycle_one[$i];
                                                $humanities[$i] = array($total[$i],$geo[$i],$his[$i]);
                                                $humanities_one[$i] = array($cycle_one[$i],$geo_one[$i],$his_one[$i]);
                                                $total_mark[$i] = $total_mark[$i] + max($humanities[$i]);
                                                $total_mark_one[$i] = $total_mark_one[$i] + max($humanities_one[$i]);

                                                $tpoints[$i] = $tpoints[$i] + $current_points[$i];
                                                $hum_points[$i] = array($his_points[$i],$geo_points[$i],$current_points[$i]);
                                                rsort($hum_points[$i]);
                                                $tpoints[$i] = $tpoints[$i] - $hum_points[$i][0] - $hum_points[$i][1];
                                            }
                                        }

                                        if($form>=3 && $subject == 'Agriculture')
                                        {
                                            //check if 3 sciences exist
                                            //check if physics and chemistry exist

                                            $where = array('subject'=>'Chemistry','admission'=>$admission[$i],'period'=>$period);
                                            $get_chem[$i] = $obj->fetch_records('results',$where);
                                            $where = array('subject'=>'Physics','admission'=>$admission[$i],'period'=>$period);
                                            $get_phy[$i] = $obj->fetch_records('results',$where);
                                            $where = array('subject'=>'Biology','admission'=>$admission[$i],'period'=>$period);
                                            $get_bio[$i] = $obj->fetch_records('results',$where);

                                            //if both exist
                                            if($get_chem[$i] && $get_phy[$i] && $get_bio[$i])
                                            {
                                                foreach($get_phy[$i] as $row)
                                                {
                                                    $physics[$i] = $row['total'];
                                                    $physics_one[$i] = $row['mid'];
                                                    $physics_points[$i] = $row['points'];
                                                }
                                                foreach($get_chem[$i] as $row)
                                                {
                                                    $chemistry[$i] = $row['total'];
                                                    $chemistry_one[$i] = $row['mid'];
                                                    $chemistry_points[$i] = $row['points'];
                                                }
                                                foreach($get_bio[$i] as $row)
                                                {
                                                    $biology[$i] = $row['total'];
                                                    $biology_one[$i] = $row['mid'];
                                                    $biology_points[$i] = $row['points'];
                                                }

                                                //check lowest science
                                                $sciences[$i] = array($chemistry[$i],$physics[$i],$biology[$i]);
                                                $sciences_one[$i] = array($chemistry_one[$i],$physics_one[$i],$biology_one[$i]);

                                                $sci_points[$i] = array($chemistry_points[$i],$biology_points[$i],$physics_points[$i]);
                                                rsort($sci_points[$i]);
                                                $lowest_point[$i] = min($sci_points[$i]);


                                                $lowest[$i] = min($sciences[$i]);
                                                $lowest_one[$i] = min($sciences_one[$i]);


                                                //compare lowest to agriculture
                                                if($lowest[$i] >= $temp[$i])
                                                {
                                                    $total_mark[$i] = $total_mark[$i] - $lowest[$i];


                                                    if($lowest[$i] > $total[$i])
                                                    {
                                                        $total_mark[$i] = $total_mark[$i] - $mark[$i];
                                                        $total_mark[$i] = $total_mark[$i] + $lowest[$i];
                                                    }
                                                    else{
                                                        $tpoints[$i] = $tpoints[$i] - $lowest_point[$i];
                                                        $tpoints[$i] = $tpoints[$i] + $current_points[$i];
                                                        $total_mark[$i] = $total_mark[$i] - $mark[$i];
                                                        $total_mark[$i] = $total_mark[$i] + $total[$i];
                                                    }
                                                }
                                                else if($lowest[$i] < $temp[$i])
                                                {
                                                    //deduct the lowest
                                                    $total_mark[$i] = $total_mark[$i] - $temp[$i];


                                                    if($lowest[$i] > $total[$i])
                                                    {
                                                        $total_mark[$i] = $total_mark[$i] - $mark[$i];
                                                        $total_mark[$i] = $total_mark[$i] + $lowest[$i];
                                                    }
                                                    else{
                                                        $tpoints[$i] = $tpoints[$i] - $lowest_point[$i];
                                                        $tpoints[$i] = $tpoints[$i] + $current_points[$i];
                                                        $total_mark[$i] = $total_mark[$i] - $mark[$i];
                                                        $total_mark[$i] = $total_mark[$i] + $total[$i];
                                                    }

                                                }

                                                if($lowest_one[$i] >= $cycle_one[$i])
                                                {
                                                    $total_mark_one[$i] = $total_mark_one[$i] - $cycle_one[$i];
                                                    $total_mark_one[$i] = $total_mark_one[$i] + $lowest_one[$i];
                                                }
                                                else if($lowest_one[$i] < $cycle_one[$i])
                                                {
                                                    $total_mark_one[$i] = $total_mark_one[$i] - $lowest_one[$i];
                                                }

                                            }

                                        }
                                        if($form>=3 && $subject == 'Business')
                                        {
                                            //check if 3 sciences exist

                                            $where = array('subject'=>'Chemistry','admission'=>$admission[$i],'period'=>$period);
                                            $get_chem[$i] = $obj->fetch_records('results',$where);
                                            $where = array('subject'=>'Physics','admission'=>$admission[$i],'period'=>$period);
                                            $get_phy[$i] = $obj->fetch_records('results',$where);
                                            $where = array('subject'=>'Biology','admission'=>$admission[$i],'period'=>$period);
                                            $get_bio[$i] = $obj->fetch_records('results',$where);

                                            //if both exist
                                            if($get_chem[$i] && $get_phy[$i] && $get_bio[$i])
                                            {
                                                foreach($get_phy[$i] as $row)
                                                {
                                                    $physics[$i] = $row['total'];
                                                    $physics_one[$i] = $row['mid'];
                                                    $physics_points = $row['points'];

                                                }
                                                foreach($get_chem[$i] as $row)
                                                {
                                                    $chemistry[$i] = $row['total'];
                                                    $chemistry_one[$i] = $row['mid'];
                                                    $chemistry_points[$i] = $row['points'];
                                                }
                                                foreach($get_bio[$i] as $row)
                                                {
                                                    $biology[$i] = $row['total'];
                                                    $biology_one[$i] = $row['mid'];
                                                    $biology_points[$i] = $row['points'];
                                                }

                                                //check lowest science
                                                $sciences[$i] = array($chemistry[$i],$physics[$i],$biology[$i]);
                                                $sciences_one[$i] = array($chemistry_one[$i],$physics_one[$i],$biology_one[$i]);

                                                $sci_points[$i] = array($chemistry_points[$i],$biology_points[$i],$physics_points[$i]);
                                                rsort($sci_points[$i]);
                                                $lowest_point[$i] = min($sci_points[$i]);

                                                $lowest[$i] = min($sciences[$i]);
                                                $lowest_one[$i] = min($sciences_one[$i]);

                                                //compare lowest to business
                                                if($lowest[$i] >= $temp[$i])
                                                {
                                                    $total_mark[$i] = $total_mark[$i] - $lowest[$i];


                                                    if($lowest[$i] > $total[$i])
                                                    {
                                                        $total_mark[$i] = $total_mark[$i] - $mark[$i];
                                                        $total_mark[$i] = $total_mark[$i] + $lowest[$i];
                                                    }
                                                    else{
                                                        $tpoints[$i] = $tpoints[$i] - $lowest_point[$i];
                                                        $tpoints[$i] = $tpoints[$i] + $current_points[$i];
                                                        $total_mark[$i] = $total_mark[$i] - $mark[$i];
                                                        $total_mark[$i] = $total_mark[$i] + $total[$i];
                                                    }
                                                }
                                                else if($lowest[$i] < $temp[$i])
                                                {
                                                    //deduct the lowest
                                                    $total_mark[$i] = $total_mark[$i] - $temp[$i];


                                                    if($lowest[$i] > $total[$i])
                                                    {
                                                        $total_mark[$i] = $total_mark[$i] - $mark[$i];
                                                        $total_mark[$i] = $total_mark[$i] + $lowest[$i];
                                                    }
                                                    else{
                                                        $tpoints[$i] = $tpoints[$i] - $lowest_point[$i];
                                                        $tpoints[$i] = $tpoints[$i] + $current_points[$i];
                                                        $total_mark[$i] = $total_mark[$i] - $mark[$i];
                                                        $total_mark[$i] = $total_mark[$i] + $total[$i];
                                                    }

                                                }


                                                if($lowest_one[$i] >= $cycle_one[$i])
                                                {
                                                    $total_mark_one[$i] = $total_mark_one[$i] - $cycle_one[$i];
                                                    $total_mark_one[$i] = $total_mark_one[$i] + $lowest_one[$i];
                                                }
                                                else if($lowest_one[$i] < $cycle_one[$i])
                                                {
                                                    $total_mark_one[$i] = $total_mark_one[$i] - $lowest_one[$i];
                                                }

                                            }

                                        }
                                        $average[$i]=$total_mark[$i]/$min;

                                        $average_points[$i] = $tpoints[$i]/$min;

                                        //get new grade
                                        $sql = "SELECT * FROM $grading_system WHERE upper_limit>=floor($average[$i]) AND lower_limit<=floor($average[$i])";
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
                                            "total_points"=>$tpoints[$i],
                                            "average_points"=>$average_points[$i],
                                            "remarks" => $remarks[$i]

                                        );
                                        $where = array("admission" => $admission[$i], "period" => $period);

                                        if ($obj->update_record("final_result", $where, $data)) {

                                            if($get_cycle_one)
                                            {
                                                $average_one[$i]=$total_mark_one[$i]/$min;
                                                //get new grade
                                                $sql = "SELECT * FROM $grading_system WHERE upper_limit>=floor($average_one[$i]) AND lower_limit<=floor($average_one[$i])";
                                                $execute = mysqli_query($obj->con, $sql);

                                                if ($execute) {

                                                    while ($get_grades = mysqli_fetch_assoc($execute)) {
                                                        $grade_one[$i] = $get_grades['grade'];
                                                        $points_one[$i] = $get_grades['points'];
                                                        $remarks_one[$i] = $get_grades['remarks'];
                                                    }
                                                }
                                                //data update in final results table

                                                //updating data to results table
                                                $data = array(

                                                    $subject => $cycle_one[$i],
                                                    "total" => $total_mark_one[$i],
                                                    "cumulative"=>$total_mark[$i],
                                                    "average" => $average_one[$i],
                                                    "grade" => $grade_one[$i],
                                                    "points" => $points_one[$i],
                                                    "remarks" => $remarks_one[$i]

                                                );
                                                $where = array("admission" => $admission[$i], "period" => $period);

                                                if ($obj->update_record("cycle_two", $where, $data)) {

                                                    $success = "Exam results for form $class in $period have been uploaded successfully";

                                                }

                                            }
                                            else{

                                                $average[$i]=$cycle_one[$i]/$min;

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
                                                    $subject => $cycle_one[$i],
                                                    "total" => $cycle_one[$i],
                                                    "cumulative"=>$cycle_one[$i],
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
                                    $subject_points[$i] = $get_grades['points'];
                                    $remarks[$i] = $get_grades['remarks'];
                                }

                                //getting grade for cycle 1
                                $sql = "SELECT * FROM $grading_system WHERE upper_limit>=$cycle_one[$i] AND lower_limit<=$cycle_one[$i]";
                                $execute = mysqli_query($obj->con, $sql);

                                while ($get_grades = mysqli_fetch_assoc($execute)) {
                                    $cycle_one_grade[$i] = $get_grades['grade'];
                                }

                                $data = array(
                                    "names" => $names[$i],
                                    "admission" => $admission[$i],
                                    "class" => $class,
                                    "form" => $form,
                                    "subject" => $subject,
                                    "mid" => $cycle_one[$i],
                                    "mid_grade"=>$cycle_one_grade[$i],
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
                                            $total_mark[$i]=$row['total']+$mark[$i];
                                            $total_new[$i]=$total_mark[$i];
                                            $subject_total[$i]=$row[$subject]+$mark[$i];
                                            $count[$i]=$row['count']+1;
                                            $tpoints[$i] = $row['total_points'] + $points[$i];

                                        }

                                        //updating in cycle 1 results table

                                        //getting from cycle 1 results table so as to update
                                        $where=array("admission"=>$admission[$i],"period"=>$period);
                                        $get_cycle_one=$obj->fetch_records("cycle_two",$where);


                                        //if results exist in cycle one table
                                        if($get_cycle_one) {
                                            foreach ($get_cycle_one as $row) {
                                                $total_mark_one[$i] = $row['total'] + $cycle_one[$i];
                                                $count[$i] = $row['count'];

                                            }
                                        }

//                                          if($class[0]>=3)
//                                          {
//                                              $average[$i]=$total_mark[$i]/$count[$i];
//                                          }
//                                          else
//                                          {
//                                              $average[$i]=$total_mark[$i]/$min;
//                                          }

                                        if($form>=3 && $subject == 'Chemistry')
                                        {
                                            //check if physics or biology exist

                                            $where = array('subject'=>'Biology','admission'=>$admission[$i],'period'=>$period);
                                            $get_bio[$i] = $obj->fetch_records('results',$where);
                                            $where = array('subject'=>'Physics','admission'=>$admission[$i],'period'=>$period);
                                            $get_phy[$i] = $obj->fetch_records('results',$where);
                                            $where = array('subject'=>'Agriculture','admission'=>$admission[$i],'period'=>$period);
                                            $get_agric[$i] = $obj->fetch_records('results',$where);
                                            $where = array('subject'=>'Business','admission'=>$admission[$i],'period'=>$period);
                                            $get_bus[$i] = $obj->fetch_records('results',$where);

                                            //if both exist
                                            if($get_bio[$i] && $get_phy[$i])
                                            {
                                                foreach($get_phy[$i] as $row)
                                                {
                                                    $physics[$i] = $row['total'];
                                                    $physics_points[$i] = $row['points'];
                                                    $physics_one[$i] = $row['mid'];
                                                }
                                                foreach($get_bio[$i] as $row)
                                                {
                                                    $biology[$i] = $row['total'];
                                                    $biology_points[$i] = $row['points'];
                                                    $biology_one[$i] = $row['mid'];
                                                }

                                                //pick the 2 highest
                                                $sciences[$i] = array($biology[$i],$physics[$i],$mark[$i]);
                                                $sciences_one[$i] = array($biology_one[$i],$physics_one[$i],$cycle_one[$i]);
                                                $sci_points[$i] = array($physics_points[$i],$biology_points[$i],$points[$i]);


                                                if($get_agric[$i] || $get_bus[$i])
                                                {
                                                    //deduct the lowest
                                                    $total_mark[$i] = $total_mark[$i] - min($sciences[$i]);
                                                    $total_mark_one[$i] = $total_mark_one[$i] - min($sciences_one[$i]);


                                                    if($get_agric[$i])
                                                    {
                                                        foreach($get_agric[$i] as $row)
                                                        {
                                                            $humanity[$i] = $row['total'];
                                                            $humanity_point[$i] = $row['points'];
                                                            $humanity_one[$i] = $row['mid'];
                                                        }
                                                    }
                                                    if($get_bus[$i])
                                                    {
                                                        foreach($get_bus[$i] as $row)
                                                        {
                                                            $humanity[$i] = $row['total'];
                                                            $humanity_point[$i] = $row['points'];
                                                            $humanity_one[$i] = $row['mid'];
                                                        }
                                                    }

                                                    if( min($sciences[$i]) > $humanity[$i])
                                                    {
                                                        $total_mark[$i] = $total_mark[$i] - $humanity[$i] +  min($sciences[$i]);
                                                        $tpoints[$i] = $tpoints[$i] - $humanity_point[$i] + min($sci_points[$i]);
                                                        $total_mark_one[$i] = $total_mark_one[$i] - $humanity_one[$i] +  min($sciences_one[$i]);
                                                    }


                                                }
                                            }
                                        }
                                        if($form>=3 && $subject == 'Biology')
                                        {
                                            //check if physics and chemistry exist

                                            $where = array('subject'=>'Chemistry','admission'=>$admission[$i],'period'=>$period);
                                            $get_chem[$i] = $obj->fetch_records('results',$where);
                                            $where = array('subject'=>'Physics','admission'=>$admission[$i],'period'=>$period);
                                            $get_phy[$i] = $obj->fetch_records('results',$where);
                                            $where = array('subject'=>'Agriculture','admission'=>$admission[$i],'period'=>$period);
                                            $get_agric[$i] = $obj->fetch_records('results',$where);
                                            $where = array('subject'=>'Business','admission'=>$admission[$i],'period'=>$period);
                                            $get_bus[$i] = $obj->fetch_records('results',$where);

                                            //if both exist
                                            if($get_chem[$i] && $get_phy[$i])
                                            {
                                                foreach($get_phy[$i] as $row)
                                                {
                                                    $physics[$i] = $row['total'];
                                                    $physics_points[$i] = $row['points'];
                                                    $physics_one[$i] = $row['mid'];
                                                }
                                                foreach($get_chem[$i] as $row)
                                                {
                                                    $chemistry[$i] = $row['total'];
                                                    $chemistry_points[$i] = $row['points'];
                                                    $chemistry_one[$i] = $row['mid'];
                                                }

                                                //pick the 2 highest
                                                $sciences[$i] = array($chemistry[$i],$physics[$i],$mark[$i]);
                                                $sciences_one[$i] = array($chemistry_one[$i],$physics_one[$i],$cycle_one[$i]);
                                                $sci_points[$i] = array($chemistry_points[$i],$physics_points[$i],$points[$i]);


                                                if($get_agric[$i] || $get_bus[$i])
                                                {
                                                    //deduct the lowest
                                                    $total_mark[$i] = $total_mark[$i] - min($sciences[$i]);
                                                    $total_mark_one[$i] = $total_mark_one[$i] - min($sciences_one[$i]);

                                                    if($get_agric[$i])
                                                    {
                                                        foreach($get_agric[$i] as $row)
                                                        {
                                                            $humanity[$i] = $row['total'];
                                                            $humanity_point[$i] = $row['points'];
                                                            $humanity_one[$i] = $row['mid'];
                                                        }
                                                    }
                                                    if($get_bus[$i])
                                                    {
                                                        foreach($get_bus[$i] as $row)
                                                        {
                                                            $humanity[$i] = $row['total'];
                                                            $humanity_point[$i] = $row['points'];
                                                            $humanity_one[$i] = $row['mid'];
                                                        }
                                                    }

                                                    if( min($sciences[$i]) > $humanity[$i])
                                                    {
                                                        $total_mark[$i] = $total_mark[$i] - $humanity[$i] +  min($sciences[$i]);
                                                        $tpoints[$i] = $tpoints[$i] - $humanity_point[$i] + min($sci_points[$i]);
                                                    }
                                                    if( min($sciences_one[$i]) > $humanity_one[$i])
                                                    {
                                                        $total_mark_one[$i] = $total_mark_one[$i] - $humanity_one[$i] +  min($sciences_one[$i]);
                                                    }


                                                }
                                            }
                                        }
                                        if($form>=3 && $subject == 'Physics')
                                        {
                                            //check if biology and chemistry exist

                                            $where = array('subject'=>'Biology','admission'=>$admission[$i],'period'=>$period);
                                            $get_bio[$i] = $obj->fetch_records('results',$where);
                                            $where = array('subject'=>'Chemistry','admission'=>$admission[$i],'period'=>$period);
                                            $get_chem[$i] = $obj->fetch_records('results',$where);
                                            $where = array('subject'=>'Agriculture','admission'=>$admission[$i],'period'=>$period);
                                            $get_agric[$i] = $obj->fetch_records('results',$where);
                                            $where = array('subject'=>'Business','admission'=>$admission[$i],'period'=>$period);
                                            $get_bus[$i] = $obj->fetch_records('results',$where);

                                            //if both exist
                                            if($get_bio[$i] && $get_chem[$i])
                                            {
                                                foreach($get_bio[$i] as $row)
                                                {
                                                    $biology[$i] = $row['total'];
                                                    $biology_points[$i] = $row['points'];
                                                    $biology_one[$i] = $row['mid'];
                                                }
                                                foreach($get_chem[$i] as $row)
                                                {
                                                    $chemistry[$i] = $row['total'];
                                                    $chemistry_points[$i] = $row['points'];
                                                    $chemistry_one[$i] = $row['mid'];
                                                }

                                                if($get_agric[$i] || $get_bus[$i])
                                                {



                                                 //pick the 2 highest
                                                $sciences[$i] = array($chemistry[$i],$biology[$i],$mark[$i]);
                                                $sciences_one[$i] = array($chemistry_one[$i],$biology_one[$i],$cycle_one[$i]);
                                                $sci_points[$i] = array($chemistry_points[$i],$biology_points[$i],$points[$i]);


                                                    //deduct the lowest
                                                    $total_mark[$i] = $total_mark[$i] - min($sciences[$i]);
                                                    $total_mark_one[$i] = $total_mark_one[$i] - min($sciences_one[$i]);

                                                    if($get_agric[$i])
                                                    {
                                                        foreach($get_agric[$i] as $row)
                                                        {
                                                            $humanity[$i] = $row['total'];
                                                            $humanity_point[$i] = $row['points'];
                                                            $humanity_one[$i] = $row['mid'];
                                                        }
                                                    }
                                                    if($get_bus[$i])
                                                    {
                                                        foreach($get_bus[$i] as $row)
                                                        {
                                                            $humanity[$i] = $row['total'];
                                                            $humanity_point[$i] = $row['points'];
                                                            $humanity_one[$i] = $row['mid'];
                                                        }
                                                    }

                                                    if( min($sciences[$i]) > $humanity[$i])
                                                    {
                                                        $total_mark[$i] = $total_mark[$i] - $humanity[$i] +  min($sciences[$i]);
                                                        $tpoints[$i] = $tpoints[$i] - $humanity_point[$i] + min($sci_points[$i]);

                                                    }
                                                    if( min($sciences_one[$i]) > $humanity_one[$i])
                                                    {
                                                        $total_mark_one[$i] = $total_mark_one[$i] - $humanity_one[$i] +  min($sciences_one[$i]);
                                                    }


                                                }
                                            }
                                        }
                                        if($form>=3 && $subject == 'Agriculture')
                                        {
                                            //check if 3 sciences exist


                                            $where = array('subject'=>'Chemistry','admission'=>$admission[$i],'period'=>$period);
                                            $get_chem[$i] = $obj->fetch_records('results',$where);
                                            $where = array('subject'=>'Physics','admission'=>$admission[$i],'period'=>$period);
                                            $get_phy[$i] = $obj->fetch_records('results',$where);
                                            $where = array('subject'=>'Biology','admission'=>$admission[$i],'period'=>$period);
                                            $get_bio[$i] = $obj->fetch_records('results',$where);

                                            //if both exist
                                            if($get_chem[$i] && $get_phy[$i] && $get_bio[$i])
                                            {
                                                foreach($get_phy[$i] as $row)
                                                {
                                                    $physics[$i] = $row['total'];
                                                    $physics_one[$i] = $row['mid'];
                                                    $physics_points[$i] = $row['points'];
                                                }
                                                foreach($get_chem[$i] as $row)
                                                {
                                                    $chemistry[$i] = $row['total'];
                                                    $chemistry_one[$i] = $row['mid'];
                                                    $chemistry_points[$i] = $row['points'];
                                                }
                                                foreach($get_bio[$i] as $row)
                                                {
                                                    $biology[$i] = $row['total'];
                                                    $biology_one[$i] = $row['mid'];
                                                    $biology_points[$i] = $row['points'];
                                                }

                                                //check lowest science
                                                $sciences[$i] = array($chemistry[$i],$physics[$i],$biology[$i]);
                                                $sciences_one[$i] = array($chemistry_one[$i],$physics_one[$i],$biology_one[$i]);

                                                $sci_points[$i] = array($chemistry_points[$i],$biology_points[$i],$physics_points[$i]);
                                                rsort($sci_points[$i]);
                                                $lowest_point[$i] = min($sci_points[$i]);

                                                $lowest[$i] = min($sciences[$i]);
                                                $lowest_one[$i] = min($sciences_one[$i]);

                                                //compare lowest to agriculture
                                                if($lowest[$i] > $mark[$i])
                                                {
                                                    //deduct the lowest
                                                    $total_mark[$i] = $total_mark[$i] - $mark[$i];
                                                    $tpoints[$i] = $tpoints[$i] - $points[$i];

                                                }
                                                else if($lowest[$i] <= $mark[$i])
                                                {
                                                    $total_mark[$i] = $total_mark[$i] - $lowest[$i];
                                                    $tpoints[$i] = $tpoints[$i] - min($sci_points[$i]);
                                                }

                                                //compare lowest to agriculture in cycle one
                                                if($lowest_one[$i] > $cycle_one[$i])
                                                {
                                                    //deduct the lowest
                                                    $total_mark_one[$i] = $total_mark_one[$i] - $cycle_one[$i];
                                                    $total_mark_one[$i] = $total_mark_one[$i] + $lowest_one[$i];

                                                }
                                                else if($lowest_one[$i] <= $cycle_one[$i])
                                                {
                                                    $total_mark_one[$i] = $total_mark_one[$i] - $lowest_one[$i];
                                                }


                                            }

                                        }
                                        if($form>=3 && $subject == 'Business')
                                        {
                                            //check if 3 sciences exist

                                            $where = array('subject'=>'Chemistry','admission'=>$admission[$i],'period'=>$period);
                                            $get_chem[$i] = $obj->fetch_records('results',$where);
                                            $where = array('subject'=>'Physics','admission'=>$admission[$i],'period'=>$period);
                                            $get_phy[$i] = $obj->fetch_records('results',$where);
                                            $where = array('subject'=>'Biology','admission'=>$admission[$i],'period'=>$period);
                                            $get_bio[$i] = $obj->fetch_records('results',$where);

                                            //if both exist
                                            if($get_chem[$i] && $get_phy[$i] && $get_bio[$i])
                                            {
                                                foreach($get_phy[$i] as $row)
                                                {
                                                    $physics[$i] = $row['total'];
                                                    $physics_points[$i] = $row['points'];
                                                    $physics_one[$i] = $row['mid'];
                                                }
                                                foreach($get_chem[$i] as $row)
                                                {
                                                    $chemistry[$i] = $row['total'];
                                                    $chemistry_points[$i] = $row['points'];
                                                    $chemistry_one[$i] = $row['mid'];
                                                }
                                                foreach($get_bio[$i] as $row)
                                                {
                                                    $biology[$i] = $row['total'];
                                                    $biology_points[$i] = $row['points'];
                                                    $biology_one[$i] = $row['mid'];
                                                }

                                                //check lowest science
                                                $sciences[$i] = array($chemistry[$i],$physics[$i],$biology[$i]);
                                                $sciences_one[$i] = array($chemistry_one[$i],$physics_one[$i],$biology_one[$i]);


                                                $lowest[$i] = min($sciences[$i]);
                                                $lowest_one[$i] = min($sciences_one[$i]);

                                                //compare lowest to agriculture
                                                if($lowest[$i] > $mark[$i])
                                                {
                                                    //deduct the lowest
                                                    $total_mark[$i] = $total_mark[$i] - $mark[$i];
                                                    $tpoints[$i] = $tpoints[$i] - $points[$i];

                                                }
                                                else if($lowest[$i] <= $mark[$i])
                                                {
                                                    $total_mark[$i] = $total_mark[$i] - $lowest[$i];
                                                    $tpoints[$i] = $tpoints[$i] - min($sci_points[$i]);
                                                }

                                                //compare lowest to business in cycle one
                                                if($lowest_one[$i] > $cycle_one[$i])
                                                {
                                                    //deduct the lowest
                                                    $total_mark_one[$i] = $total_mark_one[$i] - $cycle_one[$i];
                                                    $total_mark_one[$i] = $total_mark_one[$i] + $lowest_one[$i];

                                                }
                                                else if($lowest_one[$i] <= $cycle_one[$i])
                                                {
                                                    $total_mark_one[$i] = $total_mark_one[$i] - $lowest_one[$i];
                                                }

                                            }

                                        }

                                        if($form>=3 && $subject == 'History')
                                        {
                                            //check if cre and geog exist
                                            $where = array('subject'=>'CRE','admission'=>$admission[$i],'period'=>$period);
                                            $get_cre[$i] = $obj->fetch_records('results',$where);
                                            $where = array('subject'=>'Geography','admission'=>$admission[$i],'period'=>$period);
                                            $get_geo[$i] = $obj->fetch_records('results',$where);

                                            //if cre exists but not geography
                                            if($get_cre[$i] && !$get_geo[$i])
                                            {
                                                foreach($get_cre[$i] as $row)
                                                {
                                                    $cre[$i] = $row['total'];
                                                    $cre_points[$i] = $row['points'];
                                                    $cre_one[$i] = $row['mid'];
                                                }
                                                //swapping
                                                $humanities[$i] = array($cre[$i],$mark[$i]);
                                                $humanities_one[$i] = array($cre_one[$i],$cycle_one[$i]);
                                                $total_mark[$i] = $total_mark[$i] - min($humanities[$i]);
                                                $total_mark_one[$i] = $total_mark_one[$i] - min($humanities_one[$i]);

                                                $tpoints[$i] = $tpoints[$i] - min($cre_points[$i],$points[$i]);


                                            }

                                            //if geography exists but not cre
                                            if($get_geo[$i] && !$get_cre[$i] )
                                            {
                                                foreach($get_geo[$i] as $row)
                                                {
                                                    $geo[$i] = $row['total'];
                                                    $geo_points[$i] = $row['points'];
                                                    $geo_one[$i] = $row['mid'];
                                                }
                                                //swapping
                                                $humanities[$i] = array($geo[$i],$mark[$i]);
                                                $humanities_one[$i] = array($geo_one[$i],$cycle_one[$i]);
                                                $total_mark[$i] = $total_mark[$i] - min($humanities[$i]);
                                                $total_mark_one[$i] = $total_mark_one[$i] - min($humanities_one[$i]);

                                                $tpoints[$i] = $tpoints[$i] - min($cre_points[$i],$points[$i]);

                                            }
                                            if($get_cre[$i] && $get_geo[$i])
                                            {
                                                foreach($get_geo[$i] as $row)
                                                {
                                                    $geo[$i] = $row['total'];
                                                    $geo_one[$i] = $row['mid'];
                                                }
                                                foreach($get_cre[$i] as $row)
                                                {
                                                    $cre[$i] = $row['total'];
                                                    $cre_one[$i] = $row['mid'];
                                                }

                                                //pick only the highest humanity and add to the total
                                                $geocre[$i] = array($cre[$i],$geo[$i]);
                                                $geocre_one[$i] = array($cre_one[$i],$geo_one[$i]);
                                                $total_mark[$i] = $total_mark[$i] - max($geocre[$i]) - $mark[$i];
                                                $total_mark_one[$i] = $total_mark_one[$i] - max($geocre_one[$i]) - $cycle_one[$i];
                                                $humanities[$i] = array($mark[$i],$geo[$i],$cre[$i]);
                                                $humanities_one[$i] = array($cycle_one[$i],$geo_one[$i],$cre_one[$i]);
                                                $total_mark[$i] = $total_mark[$i] + max($humanities[$i]);
                                                $total_mark_one[$i] = $total_mark_one[$i] + max($humanities_one[$i]);

                                                $hum_points[$i] = array($geo_points[$i],$cre_points[$i],$points[$i]);
                                                rsort($hum_points[$i]);
                                                $tpoints[$i] = $tpoints[$i] - $hum_points[$i][0] - $hum_points[$i][1];
                                            }

                                        }
                                        if($form>=3 && $subject == 'Geography')
                                        {
                                            //check if cre and hist exist
                                            $where = array('subject'=>'CRE','admission'=>$admission[$i],'period'=>$period);
                                            $get_cre[$i] = $obj->fetch_records('results',$where);
                                            $where = array('subject'=>'History','admission'=>$admission[$i],'period'=>$period);
                                            $get_hist[$i] = $obj->fetch_records('results',$where);

                                            //if cre exists but not geography
                                            if($get_cre[$i] && !$get_hist[$i])
                                            {
                                                foreach($get_cre[$i] as $row)
                                                {
                                                    $cre[$i] = $row['total'];
                                                    $cre_one[$i] = $row['mid'];
                                                    $cre_points[$i] = $row['points'];
                                                }
                                                //swapping
                                                $humanities[$i] = array($cre[$i],$mark[$i]);
                                                $humanities_one[$i] = array($cre_one[$i],$cycle_one[$i]);
                                                $total_mark[$i] = $total_mark[$i] - min($humanities[$i]);
                                                $total_mark_one[$i] = $total_mark_one[$i] - min($humanities_one[$i]);

                                                $tpoints[$i] = $tpoints[$i] - min($cre_points[$i],$points[$i]);
                                            }

                                            //if geography exists but not cre
                                            if($get_hist[$i] && !$get_cre[$i] )
                                            {
                                                foreach($get_hist[$i] as $row)
                                                {
                                                    $his[$i] = $row['total'];
                                                    $his_one[$i] = $row['mid'];
                                                    $his_points[$i] = $row['points'];
                                                }
                                                //swapping
                                                $humanities[$i] = array($his[$i],$mark[$i]);
                                                $humanities_one[$i] = array($his_one[$i],$cycle_one[$i]);
                                                $total_mark[$i] = $total_mark[$i] - min($humanities[$i]);
                                                $total_mark_one[$i] = $total_mark_one[$i] - min($humanities_one[$i]);

                                                $tpoints[$i] = $tpoints[$i] - min($his_points[$i],$points[$i]);

                                            }
                                            if($get_cre[$i] && $get_hist[$i])
                                            {
                                                foreach($get_hist[$i] as $row)
                                                {
                                                    $his[$i] = $row['total'];
                                                    $his_one[$i] = $row['mid'];
                                                }
                                                foreach($get_cre[$i] as $row)
                                                {
                                                    $cre[$i] = $row['total'];
                                                    $cre_one[$i] = $row['mid'];
                                                }

                                                //pick only the highest humanity and add to the total
                                                $hiscre[$i] = array($cre[$i],$his[$i]);
                                                $hiscre_one[$i] = array($cre_one[$i],$his_one[$i]);
                                                $total_mark[$i] = $total_mark[$i] - max($hiscre[$i]) - $mark[$i];
                                                $total_mark_one[$i] = $total_mark_one[$i] - max($hiscre_one[$i]) - $cycle_one[$i];
                                                $humanities[$i] = array($mark[$i],$his[$i],$cre[$i]);
                                                $humanities_one[$i] = array($cycle_one[$i],$his_one[$i],$cre_one[$i]);
                                                $total_mark[$i] = $total_mark[$i] + max($humanities[$i]);
                                                $total_mark_one[$i] = $total_mark_one[$i] + max($humanities_one[$i]);

                                                $hum_points[$i] = array($his_points[$i],$cre_points[$i],$points[$i]);
                                                rsort($hum_points[$i]);
                                                $tpoints[$i] = $tpoints[$i] - $hum_points[$i][0] - $hum_points[$i][1];
                                            }
                                        }
                                        if($form>=3 && $subject == 'CRE')
                                        {
                                            //check if geo and hist exist
                                            $where = array('subject'=>'Geography','admission'=>$admission[$i],'period'=>$period);
                                            $get_geo[$i] = $obj->fetch_records('results',$where);
                                            $where = array('subject'=>'History','admission'=>$admission[$i],'period'=>$period);
                                            $get_hist[$i] = $obj->fetch_records('results',$where);

                                            //if geo exists but not his
                                            if($get_geo[$i] && !$get_hist[$i])
                                            {
                                                foreach($get_geo[$i] as $row)
                                                {
                                                    $geo[$i] = $row['total'];
                                                    $geo_one[$i] = $row['mid'];
                                                    $geo_points[$i] = $row['points'];
                                                }
                                                //swapping
                                                $humanities[$i] = array($geo[$i],$mark[$i]);
                                                $total_mark[$i] = $total_mark[$i] - min($humanities[$i]);
                                                $humanities_one[$i] = array($geo_one[$i],$cycle_one[$i]);
                                                $total_mark_one[$i] = $total_mark_one[$i] - min($humanities_one[$i]);

                                                $tpoints[$i] = $tpoints[$i] - min($geo_points[$i],$points[$i]);
                                            }

                                            //if geography exists but not cre
                                            if($get_hist[$i] && !$get_geo[$i] )
                                            {
                                                foreach($get_hist[$i] as $row)
                                                {
                                                    $his[$i] = $row['total'];
                                                    $his_one[$i] = $row['mid'];
                                                    $his_points[$i] = $row['points'];

                                                }
                                                //swapping
                                                $humanities[$i] = array($his[$i],$mark[$i]);
                                                $humanities_one[$i] = array($his_one[$i],$cycle_one[$i]);
                                                $total_mark[$i] = $total_mark[$i] - min($humanities[$i]);
                                                $total_mark_one[$i] = $total_mark_one[$i] - min($humanities_one[$i]);

                                                $tpoints[$i] = $tpoints[$i] - min($points[$i],$his_points[$i]);

                                            }

                                            if($get_geo[$i] && $get_hist[$i])
                                            {
                                                foreach($get_hist[$i] as $row)
                                                {
                                                    $his[$i] = $row['total'];
                                                    $his_one[$i] = $row['mid'];
                                                }
                                                foreach($get_geo[$i] as $row)
                                                {
                                                    $geo[$i] = $row['total'];
                                                    $geo_one[$i] = $row['mid'];
                                                }

                                                //pick only the highest humanity and add to the total
                                                $hisgeo[$i] = array($geo[$i],$his[$i]);
                                                $hisgeo_one[$i] = array($geo_one[$i],$his_one[$i]);
                                                $total_mark[$i] = $total_mark[$i] - max($hisgeo[$i]) - $mark[$i];
                                                $total_mark_one[$i] = $total_mark_one[$i] - max($hisgeo_one[$i]) - $cycle_one[$i];
                                                $humanities[$i] = array($mark[$i],$his[$i],$geo[$i]);
                                                $humanities_one[$i] = array($cycle_one[$i],$his_one[$i],$geo_one[$i]);
                                                $total_mark[$i] = $total_mark[$i] + max($humanities[$i]);
                                                $total_mark_one[$i] = $total_mark_one[$i] + max($humanities_one[$i]);

                                                $hum_points[$i] = array($his_points[$i],$geo_points[$i],$points[$i]);
                                                rsort($hum_points[$i]);
                                                $tpoints[$i] = $tpoints[$i] - $hum_points[$i][0] - $hum_points[$i][1];

                                            }
                                        }



                                        //calculate the grade
                                        $average[$i]=$total_mark[$i]/$min;
                                        $average_one[$i]=$total_mark_one[$i]/$min;
                                        $average_points[$i] = $tpoints[$i] / $min;


                                        $sql_grade = "SELECT * FROM $grading_system WHERE upper_limit>=round($average[$i]) AND lower_limit<=round($average[$i])";
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
                                            "total" => $total_mark[$i],
                                            "average" => $average[$i],
                                            "grade" => $grade[$i],
                                            "count" => $count[$i],
                                            "points" => $points[$i],
                                            "total_points"=> $tpoints[$i],
                                            "average_points"=>$average_points[$i],
                                            "remarks" => $remarks[$i]

                                        );
                                        $where = array("admission" => $admission[$i], "period" => $period);

                                        if ($obj->update_record("final_result", $where, $data)) {

                                            if($get_cycle_one)
                                            {
                                                //get new grade
                                                $sql = "SELECT * FROM $grading_system WHERE upper_limit>=round($average_one[$i]) AND lower_limit<=round($average_one[$i])";
                                                $execute = mysqli_query($obj->con, $sql);

                                                if ($execute) {

                                                    while ($get_grades = mysqli_fetch_assoc($execute)) {
                                                        $grade_one[$i] = $get_grades['grade'];
                                                        $points_one[$i] = $get_grades['points'];
                                                        $remarks_one[$i] = $get_grades['remarks'];
                                                    }
                                                }
                                                //data update in final results table

                                                //updating data to results table
                                                $data = array(

                                                    $subject => $cycle_one[$i],
                                                    "total" => $total_mark_one[$i],
                                                    "cumulative"=>$total_mark[$i],
                                                    "average" => $average_one[$i],
                                                    "grade" => $grade_one[$i],
                                                    "points" => $points_one[$i],
                                                    "remarks" => $remarks_one[$i]

                                                );
                                                $where = array("admission" => $admission[$i], "period" => $period);

                                                if ($obj->update_record("cycle_two", $where, $data)) {

                                                    $success = "Exam results for form $class in $period have been uploaded successfully";

                                                }
                                            }
                                            else{

                                                $average[$i]=$cycle_one[$i]/$min;

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
                                                    $subject => $cycle_one[$i],
                                                    "total" => $cycle_one[$i],
                                                    "cumulative"=>$cycle_one[$i],
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
                                            $average[$i]=$cycle_one[$i]/$min;

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
                                                $subject => $cycle_one[$i],
                                                "total" => $cycle_one[$i],
                                                "cumulative"=>$cycle_one[$i],
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