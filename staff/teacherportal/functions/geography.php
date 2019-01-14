<?php



//check if physics exist and pick highest
$where=array("admission"=>$admission[$i],"period"=>$period,"subject"=>"History");
$get_history=$obj->fetch_records("results",$where);

//if chemistry results exist
if($get_history)
{

    foreach($get_history as $row) {

        $hist[$i] = $row['total'];

    }

    if($subject_total[$i]>$hist[$i])
    {

        $total_mark[$i]=$total_mark[$i]-$hist[$i];
        $total_mark[$i]=$total_mark[$i]+$subject_total[$i];
        $average[$i]=$total_mark[$i]/$min;

    }
    //if the student got higher marks in physics than in cre

    //fetching the new grade using average
    $sql_grade="SELECT * FROM grading_system WHERE upper_limit>=round($average[$i]) AND lower_limit<=round($average[$i])";
    $execute_grade=mysqli_query($obj->con,$sql_grade);
    if($execute_grade) {



        while ($get_grades = mysqli_fetch_assoc($execute_grade)) {
            $grade[$i] = $get_grades['grade'];
            $points[$i] = $get_grades['points'];
            $remarks[$i] = $get_grades['remarks'];
        }

        //updating data to results table
        $data = array(

            $subject => $subject_total[$i],
            "total" => $total_mark[$i],
            "average" => $average[$i],
            "grade" =>$grade[$i],
            "points"=>$points[$i],
            "remarks"=>$remarks[$i]

        );
        $where=array("admission"=>$admission[$i],"period"=>$period);

        if($obj->update_record("final_result",$where,$data))
        {
            $success="Exam results for form $class in $period have been uploaded successfully";
        }

    }
//                                                //end loop
}
//if no physics results exist
else
{
    $sql_grade="SELECT * FROM grading_system WHERE upper_limit>=round($average[$i]) AND lower_limit<=round($average[$i])";
    $execute_grade=mysqli_query($obj->con,$sql_grade);
    if($execute_grade) {

        while ($get_grades = mysqli_fetch_assoc($execute_grade)) {
            $grade[$i] = $get_grades['grade'];
            $points[$i] = $get_grades['points'];
            $remarks[$i] = $get_grades['remarks'];
        }

        //updating data to results table
        $data = array(

            $subject => $subject_total[$i],
            "total" => $total_mark[$i],
            "average" => $average[$i],
            "grade" =>$grade[$i],
            "points"=>$points[$i],
            "remarks"=>$remarks[$i]

        );
        $where=array("admission"=>$admission[$i],"period"=>$period);

        if($obj->update_record("final_result",$where,$data))
        {
            $success="Exam results for form $class in $period have been uploaded successfully";
        }

    }
}