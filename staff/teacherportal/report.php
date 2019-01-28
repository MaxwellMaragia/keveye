<?php
ob_start();
session_start();
if(!$_SESSION['account'])
{
    header('location:../index.php');
}
if(!$_SESSION['term'])
{
    header('location:results.php');
}
require('functions/fpdf/fpdf.php');
require('functions/actions.php');

$obj=new DataOperations();
$admission=$_SESSION['student-result'];
$year_term=$_SESSION['term'];
$opening_date = '';
$closing_date = '';
//$fees='';
//$fee_overpayment='';
$student=array("AdmissionNumber"=>$admission);

//fetching student details
$fetch_student=$obj->fetch_records("student",$student);

foreach($fetch_student as $row)
{
    $names=$row['names'];
    $class=$row['class'];
    $form=$row['form'];
    $category=$row['category'];
    $kcpe = $row['kcpe'];
    $gender = $row['gender'];
}




//get class during that period
$where_class = array('period'=>$year_term,'admission'=>$admission);
$get_class = $obj->fetch_records('final_result',$where_class);
foreach($get_class as $row)
{
    $result_class = $row['class'];
}

//get total students
$sql_class="SELECT * FROM final_result WHERE class='$result_class' AND period='$year_term'";
$sql_form="SELECT * FROM final_result WHERE form='$result_class[0]' AND period='$year_term'";

$execute_class=mysqli_query($obj->con,$sql_class);
$total_in_class=mysqli_num_rows($execute_class);
$execute_form=mysqli_query($obj->con,$sql_form);
$total_in_form=mysqli_num_rows($execute_form);

//get class rank
$query = "SELECT admission, average FROM final_result WHERE class='$result_class' AND period='$year_term' ORDER BY average DESC ";
$exe = mysqli_query($obj->con,$query);

$rank = 0;
$student = array();
while($res = mysqli_fetch_array($exe)){
    ++$rank;
    $student[$res['admission']] = $rank;

}
if($rank !== 0){
    $class_position=$student[$admission];
}

//get form rank
$sql = "SELECT admission, average FROM final_result WHERE form='$result_class[0]' AND period='$year_term' ORDER BY average DESC ";
$execute = mysqli_query($obj->con,$sql);

$form_rank = 0;
$student = array();
while($res = mysqli_fetch_array($execute)){
    ++$form_rank;
    $student[$res['admission']] = $form_rank;
}
if($form_rank !== 0){
    $form_position=$student[$admission];
}


//fetch totals
$where=array("period"=>$_SESSION['term'],"admission"=>$admission);
$fetch_total=$obj->fetch_records("final_result",$where);
foreach($fetch_total as $row)
{
    $totalmarks=$row['total'];
    $average=$row['average'];
    $overall_grade=$row['grade'];
    $count=$row['count'];
    $points = $row['points'];
}



$pdf = new FPDF('P','mm','A4');
$pdf->AddPage();

//set font to arial,bold,14pt
$pdf->SetFont('Arial','B','12');

//cella(width,height,text,border,endline,[align])
//LOGO
$pdf->Image("functions/fpdf/keveye.png",8,2,30,30);


//headers


$pdf->SetX(37);
$pdf->Cell(130  ,1,'FRIENDS SCHOOL KEVEYE GIRLS',0,1);
$pdf->SetFont('Arial','i','9');
$pdf->SetX(37);
$pdf->Cell(130  ,10,'P.o Box 865 MARAGOLI.',0,1);
$pdf->SetY(20);
$pdf->SetX(37);
$pdf->Cell(15,2,'www.keveyeschool.sc.ke.',0,1);
$pdf->SetX(82);
$pdf->Cell(130,-11,'Tel:  0707174835',0,1);
$pdf->SetFont('Arial','b','9');
$pdf->SetX(37);
$pdf->Cell(130 ,32,'DETERMINED TO EXCEL',0,1);
$pdf->SetY(28);
$pdf->SetX(70);
$pdf->SetFont('Arial','b','11');
$pdf->Cell(130 ,16,'TERM '.$year_term[10].' ACADEMIC REPORT '.substr($year_term,0,4),0,1);


$pdf->SetFont('Arial','b','8');
$pdf->Rect(10,42,192,6,'D');
$pdf->Cell(15  ,2,'NAMES:',0,0);
$pdf->SetFont('Arial','','9');
$pdf->Cell(15  ,2,$names,0,0);
$pdf->SetFont('Arial','b','8');
$pdf->SetX(80);
$pdf->Cell(15  ,2,'ADM.NO:',0,0);
$pdf->SetFont('Arial','','9');
$pdf->Cell(15  ,2,$admission,0,0);
$pdf->SetFont('Arial','b','8');
$pdf->SetX(118);
$pdf->Cell(15  ,2,'FORM:',0,0);
$pdf->SetFont('Arial','','9');
$pdf->Cell(15  ,2,$result_class[0],0,0);
$pdf->SetX(160);
$pdf->SetFont('Arial','b','9');
$pdf->Cell(15  ,2,'HOUSE:',0,0);
$pdf->SetFont('Arial','','9');
$pdf->Cell(15  ,2,'elgon',0,0);



if($fetch_total)
{

    //get min subjects
    $sql="SELECT * FROM minimum_subjects WHERE form=$form";
    $execute=mysqli_query($obj->con,$sql);

    if(mysqli_num_rows($execute)>0)
    {
        $get_min=mysqli_fetch_assoc($execute);
        $min=$get_min['minimum'];

    }

    //calculate points
    $sql = "SELECT SUM(points) FROM results WHERE admission='$admission' AND period='$year_term'";

    $exe = mysqli_query($obj->con,$sql);

    while($get_total = mysqli_fetch_assoc($exe))
    {
        $tpoints = $get_total['SUM(points)'];
        $mpoints = $tpoints/$min;
    }

    $pdf->Rect(10,48,192,6,'D');
    $pdf->SetFont('Arial','b','8');
    $pdf->SetY(50);
    $pdf->Cell(15  ,2,'FORM POS:',0,0);
    $pdf->SetFont('Arial','','9');
    $pdf->SetX(28);
    $pdf->Cell(15  ,2,$form_position,0,0);
    $pdf->SetX(80);
    $pdf->SetFont('Arial','b','8');
    $pdf->Cell(15  ,2,'OUT OF:       ',0,0);
    $pdf->SetFont('Arial','','9');

    $pdf->Cell(20  ,2,$total_in_form,0,0);
    $pdf->SetX(118);
    $pdf->SetFont('Arial','b','8');
    $pdf->Cell(15  ,2,'CLASS POS:',0,0);
    $pdf->SetFont('Arial','','9');
    $pdf->SetX(137);
    $pdf->Cell(15  ,2,$class_position,0,0);
    $pdf->SetX(160);
    $pdf->SetFont('Arial','b','8');
    $pdf->Cell(15  ,2,'OUT OF:   ',0,0);
    $pdf->SetFont('Arial','','9');
    $pdf->Cell(15  ,2,$total_in_class,0,0);


    $pdf->Rect(10,54,192,6,'D');
    $pdf->SetFont('Arial','b','8');
    $pdf->SetY(56);
    $pdf->Cell(15  ,2,'T.MARKS:     ',0,0);
    $pdf->SetFont('Arial','','9');
    $pdf->Cell(15  ,2,$totalmarks,0,0);
    $pdf->SetX(80);
    $pdf->SetFont('Arial','b','8');
    $pdf->Cell(15  ,2,'M.MARKS:       ',0,0);
    $pdf->SetFont('Arial','','9');
    $pdf->Cell(20  ,2,$average,0,0);
    $pdf->SetX(118);
    $pdf->SetFont('Arial','b','8');
    $pdf->Cell(15  ,2,'T.POINTS:   ',0,0);
    $pdf->SetFont('Arial','','9');
    $pdf->Cell(15  ,2,$tpoints,0,0);
    $pdf->SetX(160);
    $pdf->SetFont('Arial','b','8');
    $pdf->Cell(15  ,2,'M.POINTS:   ',0,0);
    $pdf->SetFont('Arial','','9');
    $pdf->Cell(15  ,2,$mpoints,0,0);


    $pdf->SetFont('Arial','b','7');
    $pdf->Cell(5,8,'',0,1);
    $pdf->Cell(30  ,6,'SUBJECT',1,0);
    $pdf->Cell(17  ,6,'CYCLE 1 %',1,0);
    $pdf->Cell(12  ,6,'GRADE',1,0);
    $pdf->Cell(17  ,6,'CYCLE 2 %',1,0);
    $pdf->Cell(12  ,6,'GRADE',1,0);
    $pdf->Cell(17  ,6,'AV %',1,0);
    $pdf->Cell(12  ,6,'GRADE',1,0);
    $pdf->Cell(17  ,6,'POINTS',1,0);
    $pdf->Cell(41  ,6,'REMARKS',1,0);
    $pdf->Cell(17  ,6,'INITIALS',1,1);

    $get_subjects=$obj->fetch_all_records("subject");

    foreach($get_subjects as $row) {
        $subject = $row['SubjectName'];
        $where = array("admission" => $admission, "period" => $year_term, "subject" => $row['SubjectName']);
        $fetch_results = $obj->fetch_records("results", $where);
        $cat='';
        $cat_grade='';
        $end='';
        $end_grade='';
        $total='';
        $grade='';
        $points='';
        $remarks='';
        $initials='';

        foreach ($fetch_results as $row) {
            $cat = $row['cat'];
            $cat_grade = $row['cat_grade'];
            $end=$row['mid'];
            $end_grade = $row['mid_grade'];
            $total=$row['total'];
            $grade=$row['grade'];
            $points=$row['points'];
            $remarks=$row['remarks'];
            $initials=$row['initials'];

        }
        $pdf->SetFont('Arial', '', '8');
        $pdf->Cell(30, 5, $subject, 1, 0);
        $pdf->Cell(17, 5, $cat, 1, 0);
        $pdf->Cell(12, 5, $cat_grade, 1, 0);
        $pdf->Cell(17, 5, $end, 1, 0);
        $pdf->Cell(12, 5, $end_grade, 1, 0);
        $pdf->Cell(17, 5, $total, 1, 0);
        $pdf->Cell(12, 5, $grade, 1, 0);
        $pdf->Cell(17, 5, $points, 1, 0);
        $pdf->Cell(41, 5, $remarks, 1, 0);
        $pdf->Cell(17, 5, $initials, 1, 1);


    }
    $pdf->SetFont('Arial', 'b', '9');
    $pdf->Cell(40, 7, 'KCPE MARKS', 1, 0);
    $pdf->Cell(17, 7, '', 0, 0);
    $pdf->Cell(17, 7, '', 0, 0);
    $pdf->SetFont('Arial', '', '9');
    $pdf->Cell(17, 7, $totalmarks, 1, 0);
    $pdf->Cell(17, 7, $overall_grade, 1, 0);
    $pdf->Cell(17, 7, $tpoints, 1, 0);
    $pdf->Cell(40, 7, '', 0, 0);
    $pdf->Cell(17, 7, '', 0, 1);
    $pdf->SetFont('Arial', '', '9');
    $pdf->Cell(40, 7, $kcpe, 1, 0);

    $pdf->Cell(17, 7, '', 0, 0);
    $pdf->Cell(17, 7, '', 0, 0);
    $pdf->SetFont('Arial', 'b', '9');
    $pdf->Cell(17, 7, $min*100, 1, 0);
    $pdf->Cell(17, 7, 'A', 1, 0);
    $pdf->Cell(17, 7, $min*12, 1, 1);
    $pdf->Cell(17, 7,'', 0, 1);

    $f1t1p = '-';
    $f1t2p = '-';
    $f1t3p = '-';
    $f1t1g = '-';
    $f1t2g = '-';
    $f1t3g = '-';
    $f2t1p = '-';
    $f2t2p = '-';
    $f2t3p = '-';
    $f2t1g = '-';
    $f2t2g = '-';
    $f2t3g = '-';
    $f3t1p = '-';
    $f3t2p = '-';
    $f3t3p = '-';
    $f3t1g = '-';
    $f3t2g = '-';
    $f3t3g = '-';
    $f4t1p = '-';
    $f4t2p = '-';
    $f4t3p = '-';
    $f4t1g = '-';
    $f4t2g = '-';
    $f4t3g = '-';


    $f1t1r = '-';
    $f1t1t = '-';
    $f1t2r = '-';
    $f1t2t = '-';
    $f1t3r = '-';
    $f1t3t = '-';

    $f2t1r = '-';
    $f2t1t = '-';
    $f2t2r = '-';
    $f2t2t = '-';
    $f2t3r = '-';
    $f2t3t = '-';

    $f3t1r = '-';
    $f3t1t = '-';
    $f3t2r = '-';
    $f3t2t = '-';
    $f3t3r = '-';
    $f3t3t = '-';

    $f4t1r = '-';
    $f4t1t = '-';
    $f4t2r = '-';
    $f4t2t = '-';
    $f4t3r = '-';
    $f4t3t = '-';



    //form 1 term 1 results
    $where = array('form'=>1,'admission'=>$admission,'term'=>1);
    $fetch = $obj->fetch_records('final_result',$where);
    foreach($fetch as $row)
    {

        $f1t1g = $row['grade'];

        //calculate points
        $sql = "SELECT SUM(points) FROM results WHERE admission='$admission' AND form = 1 AND term=1";
        $exe = mysqli_query($obj->con,$sql);
        while($get_total = mysqli_fetch_assoc($exe))
        {
            $tpoints = $get_total['SUM(points)'];
            $f1t1p = $tpoints/$min;
        }

        //get form rank

        $sql = "SELECT admission, average FROM final_result WHERE form=1 AND term=1 ORDER BY average DESC ";
        $execute = mysqli_query($obj->con,$sql);

        $f3t1t = mysqli_num_rows($execute);


        $form_rank = 0;
        $student = array();
        while($res = mysqli_fetch_array($execute)){
            ++$form_rank;
            $student[$res['admission']] = $form_rank;
        }
        if($form_rank !== 0){
            $f1t1r=$student[$admission];
        }
    }

    //form 1 term 2 results
    $where = array('form'=>1,'admission'=>$admission,'term'=>2);
    $fetch = $obj->fetch_records('final_result',$where);
    foreach($fetch as $row)
    {

        $f1t2g = $row['grade'];

        //calculate points
        $sql = "SELECT SUM(points) FROM results WHERE admission='$admission' AND form = 1 AND term=2";
        $exe = mysqli_query($obj->con,$sql);
        while($get_total = mysqli_fetch_assoc($exe))
        {
            $tpoints = $get_total['SUM(points)'];
            $f1t2p = $tpoints/$min;
        }

        //get form rank

        $sql = "SELECT admission, average FROM final_result WHERE form=3 AND term=2 ORDER BY average DESC ";
        $execute = mysqli_query($obj->con,$sql);

        $f1t2t = mysqli_num_rows($execute);


        $form_rank = 0;
        $student = array();
        while($res = mysqli_fetch_array($execute)){
            ++$form_rank;
            $student[$res['admission']] = $form_rank;
        }
        if($form_rank !== 0){
            $f1t2r=$student[$admission];
        }
    }

    //form 1 term 3 results
    $where = array('form'=>1,'admission'=>$admission,'term'=>3);
    $fetch = $obj->fetch_records('final_result',$where);
    foreach($fetch as $row)
    {
        $f1t3g = $row['grade'];

        //calculate points
        $sql = "SELECT SUM(points) FROM results WHERE admission='$admission' AND form = 1 AND term=3";
        $exe = mysqli_query($obj->con,$sql);
        while($get_total = mysqli_fetch_assoc($exe))
        {
            $tpoints = $get_total['SUM(points)'];
            $f1t3p = $tpoints/$min;
        }

        //get form rank

        $sql = "SELECT admission, average FROM final_result WHERE form=3 AND term=2 ORDER BY average DESC ";
        $execute = mysqli_query($obj->con,$sql);

        $f1t3t = mysqli_num_rows($execute);


        $form_rank = 0;
        $student = array();
        while($res = mysqli_fetch_array($execute)){
            ++$form_rank;
            $student[$res['admission']] = $form_rank;
        }
        if($form_rank !== 0){
            $f1t3r=$student[$admission];
        }
    }

    //form 2 term 1 results
    $where = array('form'=>2,'admission'=>$admission,'term'=>1);
    $fetch = $obj->fetch_records('final_result',$where);
    foreach($fetch as $row)
    {
        $f2t1g = $row['grade'];

        //calculate points
        $sql = "SELECT SUM(points) FROM results WHERE admission='$admission' AND form = 2 AND term=1";
        $exe = mysqli_query($obj->con,$sql);
        while($get_total = mysqli_fetch_assoc($exe))
        {
            $tpoints = $get_total['SUM(points)'];
            $f2t1p = $tpoints/$min;
        }

        //get form rank

        $sql = "SELECT admission, average FROM final_result WHERE form=2 AND term=1 ORDER BY average DESC ";
        $execute = mysqli_query($obj->con,$sql);

        $f2t1t = mysqli_num_rows($execute);


        $form_rank = 0;
        $student = array();
        while($res = mysqli_fetch_array($execute)){
            ++$form_rank;
            $student[$res['admission']] = $form_rank;
        }
        if($form_rank !== 0){
            $f2t1r=$student[$admission];
        }
    }

    //form 2 term 2 results
    $where = array('form'=>2,'admission'=>$admission,'term'=>2);
    $fetch = $obj->fetch_records('final_result',$where);
    foreach($fetch as $row)
    {
        $f2t2g = $row['grade'];

        //calculate points
        $sql = "SELECT SUM(points) FROM results WHERE admission='$admission' AND form = 2 AND term=2";
        $exe = mysqli_query($obj->con,$sql);
        while($get_total = mysqli_fetch_assoc($exe))
        {
            $tpoints = $get_total['SUM(points)'];
            $f2t2p = $tpoints/$min;
        }

        //get form rank

        $sql = "SELECT admission, average FROM final_result WHERE form=2 AND term=2 ORDER BY average DESC ";
        $execute = mysqli_query($obj->con,$sql);

        $f2t2t = mysqli_num_rows($execute);


        $form_rank = 0;
        $student = array();
        while($res = mysqli_fetch_array($execute)){
            ++$form_rank;
            $student[$res['admission']] = $form_rank;
        }
        if($form_rank !== 0){
            $f2t2r=$student[$admission];
        }
    }

    //form 2 term 3 results
    $where = array('form'=>2,'admission'=>$admission,'term'=>3);
    $fetch = $obj->fetch_records('final_result',$where);
    foreach($fetch as $row)
    {
        $f2t3g = $row['grade'];

        //calculate points
        $sql = "SELECT SUM(points) FROM results WHERE admission='$admission' AND form = 2 AND term=3";
        $exe = mysqli_query($obj->con,$sql);
        while($get_total = mysqli_fetch_assoc($exe))
        {
            $tpoints = $get_total['SUM(points)'];
            $f2t3p = $tpoints/$min;
        }

        //get form rank

        $sql = "SELECT admission, average FROM final_result WHERE form=2 AND term=3 ORDER BY average DESC ";
        $execute = mysqli_query($obj->con,$sql);

        $f2t3t = mysqli_num_rows($execute);


        $form_rank = 0;
        $student = array();
        while($res = mysqli_fetch_array($execute)){
            ++$form_rank;
            $student[$res['admission']] = $form_rank;
        }
        if($form_rank !== 0){
            $f2t3r=$student[$admission];
        }
    }

    //form 3 term 1 results
    $where = array('form'=>3,'admission'=>$admission,'term'=>1);
    $fetch = $obj->fetch_records('final_result',$where);
    foreach($fetch as $row)
    {
        $f3t1g = $row['grade'];

        //calculate points
        $sql = "SELECT SUM(points) FROM results WHERE admission='$admission' AND form = 3 AND term=1";
        $exe = mysqli_query($obj->con,$sql);
        while($get_total = mysqli_fetch_assoc($exe))
        {
            $tpoints = $get_total['SUM(points)'];
            $f3t1p = $tpoints/$min;
        }

        //get form rank

        $sql = "SELECT admission, average FROM final_result WHERE form=3 AND term=1 ORDER BY average DESC ";
        $execute = mysqli_query($obj->con,$sql);

        $f3t1t = mysqli_num_rows($execute);


        $form_rank = 0;
        $student = array();
        while($res = mysqli_fetch_array($execute)){
            ++$form_rank;
            $student[$res['admission']] = $form_rank;
        }
        if($form_rank !== 0){
            $f3t1r=$student[$admission];
        }

    }

    //form 3 term 2 results
    $where = array('form'=>3,'admission'=>$admission,'term'=>2);
    $fetch = $obj->fetch_records('final_result',$where);
    foreach($fetch as $row)
    {
        $f3t2g = $row['grade'];

        //calculate points
        $sql = "SELECT SUM(points) FROM results WHERE admission='$admission' AND form = 3 AND term=2";
        $exe = mysqli_query($obj->con,$sql);
        while($get_total = mysqli_fetch_assoc($exe))
        {
            $tpoints = $get_total['SUM(points)'];
            $f3t2p = $tpoints/$min;
        }

        //get form rank

        $sql = "SELECT admission, average FROM final_result WHERE form=3 AND term=2 ORDER BY average DESC ";
        $execute = mysqli_query($obj->con,$sql);

        $f3t2t = mysqli_num_rows($execute);


        $form_rank = 0;
        $student = array();
        while($res = mysqli_fetch_array($execute)){
            ++$form_rank;
            $student[$res['admission']] = $form_rank;
        }
        if($form_rank !== 0){
            $f3t2r=$student[$admission];
        }
    }

    //form 3 term 3 results
    $where = array('form'=>3,'admission'=>$admission,'term'=>3);
    $fetch = $obj->fetch_records('final_result',$where);
    foreach($fetch as $row)
    {

        $f3t3g = $row['grade'];

        //calculate points
        $sql = "SELECT SUM(points) FROM results WHERE admission='$admission' AND form = 3 AND term=3";
        $exe = mysqli_query($obj->con,$sql);
        while($get_total = mysqli_fetch_assoc($exe))
        {
            $tpoints = $get_total['SUM(points)'];
            $f3t3p = $tpoints/$min;
        }

        //get form rank

        $sql = "SELECT admission, average FROM final_result WHERE form=3 AND term=3 ORDER BY average DESC ";
        $execute = mysqli_query($obj->con,$sql);

        $f3t1t = mysqli_num_rows($execute);


        $form_rank = 0;
        $student = array();
        while($res = mysqli_fetch_array($execute)){
            ++$form_rank;
            $student[$res['admission']] = $form_rank;
        }
        if($form_rank !== 0){
            $f3t3r=$student[$admission];
        }
    }

    //form 4 term 1 results
    $where = array('form'=>4,'admission'=>$admission,'term'=>1);
    $fetch = $obj->fetch_records('final_result',$where);
    foreach($fetch as $row)
    {
        $f4t1g = $row['grade'];

        //calculate points
        $sql = "SELECT SUM(points) FROM results WHERE admission='$admission' AND form = 4 AND term=1";
        $exe = mysqli_query($obj->con,$sql);
        while($get_total = mysqli_fetch_assoc($exe))
        {
            $tpoints = $get_total['SUM(points)'];
            $f4t1p = $tpoints/$min;
        }
    }

    //form 4 term 2 results
    $where = array('form'=>4,'admission'=>$admission,'term'=>2);
    $fetch = $obj->fetch_records('final_result',$where);
    foreach($fetch as $row)
    {
        $f4t2g = $row['grade'];

        //calculate points
        $sql = "SELECT SUM(points) FROM results WHERE admission='$admission' AND form = 4 AND term=2";
        $exe = mysqli_query($obj->con,$sql);
        while($get_total = mysqli_fetch_assoc($exe))
        {
            $tpoints = $get_total['SUM(points)'];
            $f4t2p = $tpoints/$min;
        }
    }

}

//form 4 term 3 results
$where = array('form'=>4,'admission'=>$admission,'term'=>3);
$fetch = $obj->fetch_records('final_result',$where);
foreach($fetch as $row)
{
    $f4t3g = $row['grade'];

    //calculate points
    $sql = "SELECT SUM(points) FROM results WHERE admission='$admission' AND form = 4 AND term=3";
    $exe = mysqli_query($obj->con,$sql);
    while($get_total = mysqli_fetch_assoc($exe))
    {
        $tpoints = $get_total['SUM(points)'];
        $f4t3p = $tpoints/$min;
    }
}




$pdf->Cell(39,-5,'',0,1);
$pdf->Cell(48,6,'Form 1',1,0);
$pdf->Cell(48,6,'Form 2',1,0);
$pdf->Cell(48,6,'Form 3',1,0);
$pdf->Cell(48,6,'Form 4',1,1);
$pdf->SetFont('Arial','','6');
$pdf->Cell(8,6,'Term',1,0);
$pdf->Cell(10,6,'pos',1,0);
$pdf->Cell(10,6,'Out of',1,0);
$pdf->Cell(12,6,'MPTS',1,0);
$pdf->Cell(8,6,'grade',1,0);
$pdf->Cell(8,6,'Term',1,0);
$pdf->Cell(10,6,'pos',1,0);
$pdf->Cell(10,6,'Out of',1,0);
$pdf->Cell(12,6,'MPTS',1,0);
$pdf->Cell(8,6,'grade',1,0);
$pdf->Cell(8,6,'Term',1,0);
$pdf->Cell(10,6,'pos',1,0);
$pdf->Cell(10,6,'Out of',1,0);
$pdf->Cell(12,6,'MPTS',1,0);
$pdf->Cell(8,6,'grade',1,0);
$pdf->Cell(8,6,'Term',1,0);
$pdf->Cell(10,6,'pos',1,0);
$pdf->Cell(10,6,'Out of',1,0);
$pdf->Cell(12,6,'MPTS',1,0);
$pdf->Cell(8,6,'grade',1,1);
$pdf->Cell(8,6,'1',1,0);
$pdf->Cell(10,6,$f1t1r,1,0);
$pdf->Cell(10,6,$f1t1t,1,0);
$pdf->Cell(12,6,$f1t1p,1,0);
$pdf->Cell(8,6,$f1t1g,1,0);
$pdf->Cell(8,6,'1',1,0);
$pdf->Cell(10,6,$f2t1r,1,0);
$pdf->Cell(10,6,$f2t1t,1,0);
$pdf->Cell(12,6,$f2t1p,1,0);
$pdf->Cell(8,6,$f2t1g,1,0);
$pdf->Cell(8,6,'1',1,0);
$pdf->Cell(10,6,$f3t1r,1,0);
$pdf->Cell(10,6,$f3t1t,1,0);
$pdf->Cell(12,6,$f3t1p,1,0);
$pdf->Cell(8,6,$f3t1g,1,0);
$pdf->Cell(8,6,'1',1,0);
$pdf->Cell(10,6,$f4t1r,1,0);
$pdf->Cell(10,6,$f4t1t,1,0);
$pdf->Cell(12,6,$f4t1p,1,0);
$pdf->Cell(8,6,$f4t1g,1,1);
$pdf->Cell(8,6,'2',1,0);
$pdf->Cell(10,6,$f1t2r,1,0);
$pdf->Cell(10,6,$f1t2t,1,0);
$pdf->Cell(12,6,$f1t2p,1,0);
$pdf->Cell(8,6,$f1t2g,1,0);
$pdf->Cell(8,6,'2',1,0);
$pdf->Cell(10,6,$f2t2r,1,0);
$pdf->Cell(10,6,$f2t2t,1,0);
$pdf->Cell(12,6,$f2t2p,1,0);
$pdf->Cell(8,6,$f2t2g,1,0);
$pdf->Cell(8,6,'2',1,0);
$pdf->Cell(10,6,$f3t2r,1,0);
$pdf->Cell(10,6,$f3t2t,1,0);
$pdf->Cell(12,6,$f3t2p,1,0);
$pdf->Cell(8,6,$f3t2g,1,0);
$pdf->Cell(8,6,'2',1,0);
$pdf->Cell(10,6,$f4t2r,1,0);
$pdf->Cell(10,6,$f4t2t,1,0);
$pdf->Cell(12,6,$f4t2p,1,0);
$pdf->Cell(8,6,$f4t2g,1,1);
$pdf->Cell(8,6,'3',1,0);
$pdf->Cell(10,6,$f1t3r,1,0);
$pdf->Cell(10,6,$f1t3t,1,0);
$pdf->Cell(12,6,$f1t3p,1,0);
$pdf->Cell(8,6,$f1t3g,1,0);
$pdf->Cell(8,6,'3',1,0);
$pdf->Cell(10,6,$f2t3r,1,0);
$pdf->Cell(10,6,$f2t3t,1,0);
$pdf->Cell(12,6,$f2t3p,1,0);
$pdf->Cell(8,6,$f2t3g,1,0);
$pdf->Cell(8,6,'3',1,0);
$pdf->Cell(10,6,$f3t3r,1,0);
$pdf->Cell(10,6,$f3t3t,1,0);
$pdf->Cell(12,6,$f3t3p,1,0);
$pdf->Cell(8,6,$f3t3g,1,0);
$pdf->Cell(8,6,'3',1,0);
$pdf->Cell(10,6,$f4t3r,1,0);
$pdf->Cell(10,6,$f4t3t,1,0);
$pdf->Cell(12,6,$f4t3p,1,0);
$pdf->Cell(8,6,$f4t3g,1,1);
$pdf->Cell(113,2,'',0,0);
$pdf->Cell(70,2,'',0,1);
$pdf->Cell(114,2,'',0,0);


//validating deviation input
if($f1t1p==0){
    $f1t1p=0;
}else{
    $f1t1p=$f1t1p;
}

if($f1t2p==0){
    $f1t2p=0;
}else{
    $f1t2p=$f1t2p;
}

if($f1t3p==0){
    $f1t3p=0;
}else{
    $f1t3p=$f1t3p;
}

if($f2t1p==0){
    $f2t1p=0;
}else{
    $f2t1p=$f2t1p;
}

if($f2t2p==0){
    $f2t2p=0;
}else{
    $f2t2p=$f2t2p;
}

if($f2t3p==0){
    $f2t3p=0;
}else{
    $f2t3p=$f2t3p;
}

if($f3t1p==0){
    $f3t1p=0;
}else{
    $f3t1p=$f3t1p;
}

if($f3t2p==0){
    $f3t2p=0;
}else{
    $f3t2p=$f3t2p;
}

if($f3t3p==0){
    $f3t3p=0;
}else{
    $f3t3p=$f3t3p;
}

if($f4t1p==0){
    $f4t1p=0;
}else{
    $f4t1p=$f4t1p;
}

if($f4t2p==0){
    $f4t2p=0;
}else{
    $f4t2p=$f4t2p;
}

if($f4t3p==0){
    $f4t3p=0;
}else{
    $f4t3p=$f4t3p;
}

//principal's comment
function principalComment($grade){
    switch($grade){
        case 'A':
            $comment="exellent work, keep it up";
            break;
        case 'A-':
            $comment="Good job, keep it up";
            break;
        case 'B+':
            $comment="exemplary perfomance, keep the psych";
            break;
        case 'B':
            $comment="good";
            break;
        case 'B-':
            $comment="good";
            break;
        case 'C+':
            $comment="fair";
            break;
        case 'C':
            $comment="fair";
            break;
        case 'C-':
            $comment="work hard";
            break;
        case 'D+':
            $comment="you can do better";
            break;
        case 'D':
            $comment="do better";
            break;
        case 'D-':
            $comment="you can do better";
            break;
        default:
            $comment="not a good perfomance";
            break;
    }
    return $comment;
}


function teachersComment($grade){
    switch($grade){
        case 'A':
            $comment="exellent work, keep it up";
            break;
        case 'A-':
            $comment="Good job, keep it up";
            break;
        case 'B+':
            $comment="exemplary perfomance, keep the psych";
            break;
        case 'B':
            $comment="good";
            break;
        case 'B-':
            $comment="good";
            break;
        case 'C+':
            $comment="fair";
            break;
        case 'C':
            $comment="fair";
            break;
        case 'C-':
            $comment="work hard";
            break;
        case 'D+':
            $comment="you can do better";
            break;
        case 'D':
            $comment="do better";
            break;
        case 'D-':
            $comment="you can do better";
            break;
        default:
            $comment="not a good perfomance";
            break;
    }
    return $comment;
}

//validating deviation input

if(($result_class[0] == 1) && ($year_term[10]==1))
{

    $previous_mean = 0;
    $current_mean = $f1t1p;
    $deviation = $current_mean - $previous_mean;
    $principalC=principalComment($f1t1g);
    $teacherC=teachersComment($f1t1g);
}

if(($result_class[0] == 1) && ($year_term[10]==2))
{
    $previous_mean = $f1t1p;
    $current_mean = $f1t2p;
    $deviation = $current_mean - $previous_mean;
    $principalC=principalComment($f1t2g);
    $teacherC=teachersComment($f1t2g);
}

if(($result_class[0] == 1) && ($year_term[10]==3))
{
    $previous_mean = $f1t2p;
    $current_mean = $f1t3p;
    $deviation = $current_mean - $previous_mean;
    $principalC=principalComment($f1t3g);
    $teacherC=teachersComment($f1t3g);
}


if(($result_class[0] == 2) && ($year_term[10]==1))
{
    $previous_mean = $f1t3p;
    $current_mean = $f2t1p;
    $deviation = $current_mean - $previous_mean;
    $principalC=principalComment($f2t1g);
    $teacherC=teachersComment($f2t1g);
}

if(($result_class[0] == 2) && ($year_term[10]==2))
{
    $previous_mean = $f2t1p;
    $current_mean = $f2t2p;
    $deviation = $current_mean - $previous_mean;
    $principalC=principalComment($f2t2g);
    $teacherC=teachersComment($f2t2g);
}

if(($result_class[0] == 2) && ($year_term[10]==3))
{
    $previous_mean = $f2t2p;
    $current_mean = $f2t3p;
    $deviation = $current_mean - $previous_mean;
    $principalC=principalComment($f2t3g);
    $teacherC=teachersComment($f2t3g);
}

if(($result_class[0] == 3) && ($year_term[10]==1))
{
    $previous_mean = $f2t3p;
    $current_mean = $f3t1p;
    $deviation = $current_mean - $previous_mean;
    $principalC=principalComment($f3t1g);
    $teacherC=teachersComment($f3t1g);
}

if(($result_class[0] == 3) && ($year_term[10]==2))
{
    $previous_mean = $f3t1p;
    $current_mean = $f3t2p;
    $deviation = $current_mean - $previous_mean;
    $principalC=principalComment($f3t2g);
    $teacherC=teachersComment($f3t2g);
}

if(($result_class[0] == 3) && ($year_term[10]==3))
{
    $previous_mean = $f3t2p;
    $current_mean = $f3t3p;
    $deviation = $current_mean - $previous_mean;
    $principalC=principalComment($f3t3g);
    $teacherC=teachersComment($f3t3g);
}

if(($result_class[0] == 4) && ($year_term[10]==1))
{
    $previous_mean = $f3t3p;
    $current_mean = $f4t1p;
    $deviation = $current_mean - $previous_mean;
    $principalC=principalComment($f4t1g);
    $teacherC=teachersComment($f4t1g);
}

if(($result_class[0] == 4) && ($year_term[10]==2))
{
    $previous_mean = $f4t1p;
    $current_mean = $f4t2p;
    $deviation = $current_mean - $previous_mean;
    $principalC=principalComment($f4t2g);
    $teacherC=teachersComment($f4t2g);
}

if(($result_class[0] == 4) && ($year_term[10]==3))
{
    $previous_mean = $f4t2p;
    $current_mean = $f4t3p;
    $deviation = $current_mean - $previous_mean;
    $principalC=principalComment($f4t3g);
    $teacherC=teachersComment($f4t3g);
}
$pdf->SetFont('Arial','','8');
$pdf->Cell(26,6,'Previous mean',1,0);
$pdf->Cell(26,6,'Current mean',1,0);
$pdf->Cell(26,6,'Deviation',1,1);
$pdf->Cell(114,6,'',0,0);
$pdf->Cell(26,6,$previous_mean,1,0);
$pdf->Cell(26,6,$current_mean,1,0);
$pdf->Cell(26,6,$deviation,1,1);
$pdf->Cell(114,6,'',0,1);
$pdf->Cell(114,6,'',0,0);

$pdf->Cell(7,6,'Grd',1,0);
$pdf->Cell(6,6,'A',1,0);
$pdf->Cell(6,6,'A-',1,0);
$pdf->Cell(6,6,'B+',1,0);
$pdf->Cell(6,6,'B',1,0);
$pdf->Cell(6,6,'B-',1,0);
$pdf->Cell(6,6,'C+',1,0);
$pdf->Cell(6,6,'C',1,0);
$pdf->Cell(6,6,'C-',1,0);
$pdf->Cell(6,6,'D+',1,0);
$pdf->Cell(6,6,'D',1,0);
$pdf->Cell(6,6,'D-',1,0);
$pdf->Cell(5,6,'E',1,1);

$pdf->Cell(114,6,'',0,0);


$pdf->Cell(7,6,'pts',1,0);
$pdf->Cell(6,6,'12',1,0);
$pdf->Cell(6,6,'11',1,0);
$pdf->Cell(6,6,'10',1,0);
$pdf->Cell(6,6,'9',1,0);
$pdf->Cell(6,6,'8',1,0);
$pdf->Cell(6,6,'7',1,0);
$pdf->Cell(6,6,'6',1,0);
$pdf->Cell(6,6,'5',1,0);
$pdf->Cell(6,6,'4',1,0);
$pdf->Cell(6,6,'3',1,0);
$pdf->Cell(6,6,'2',1,0);
$pdf->Cell(5,6,'1',1,1);


//get term
$where=array("state"=>"In progress");
$get_term=$obj->fetch_records("terms",$where);

foreach($get_term as $row)
{
    $term=$row['term'];
    $opening_date = $row['opening_date'];
    $closing_date = $row['closing_date'];
}


$pdf->Cell(114,6,'',0,1);
$pdf->Cell(114,6,'',0,0);
$pdf->SetFont('Arial', '', '9');
$pdf->Cell(39,6,'School closed on',1,0);
$pdf->SetFont('Arial', 'i', '9');
$pdf->Cell(39,6,$closing_date,1,1);
$pdf->Cell(114,6,'',0,0);
$pdf->SetFont('Arial', '', '9');
$pdf->Cell(39,6,'Next term begins on',1,0);
$pdf->SetFont('Arial', 'i', '9');
$pdf->Cell(39,6,$opening_date,1,1);







include 'chart.php';




$pdf->Cell(39,7,'',0,1);
$pdf->SetFont('Arial','b','9');
$pdf->Cell(39,5,"Principal's remarks",0,0);
$pdf->Cell(60,4,'',0,0);
$pdf->Cell(39,5,"Class teacher's remarks",0,0); //appeded priincipals remarks
$pdf->SetX(10);
$pdf->SetFont('Arial','I','8');
$pdf->Cell(95,16,$principalC,1,0);
$pdf->SetX(109);
$pdf->SetFont('Arial','I','8');
$pdf->Cell(93,16,$teacherC,1,0);
$pdf->Cell(1,25,'',0,1);
$pdf->SetX(10);
$pdf->Image("functions/fpdf/principal.png",37,270,20,6);
$pdf->Cell(10,1,"Principal's signature......................",0,0);
$pdf->SetX(109);
$pdf->Cell(200,1,"Date & stamp...............................................................................................",0,0);






$pdf->Output();
ob_end_flush();
?>