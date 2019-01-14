<?php


//check if physics exist and pick highest
$where=array("admission"=>$admission[$i],"period"=>$period,"subject"=>"PHYSICS");
$get_physics=$obj->fetch_records("results",$where);

//if chemistry results exist
if($get_physics)
{

    foreach($get_physics as $row) {

        $physics[$i] = $row['total'];

    }

    if($mark[$i]>$physics[$i])
    {

        $total_new[$i]=$total_mark[$i]-$physics[$i];
        $average[$i]=$total_new[$i]/$min;

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

                $subject => $mark[$i],
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
    else
    {

        $average[$i]=($total_mark[$i]-$mark[$i])/$min;

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

                $subject => $mark[$i],
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
}


else
{

    $average[$i]=$total_mark[$i]/$min;


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

            $subject => $mark[$i],
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