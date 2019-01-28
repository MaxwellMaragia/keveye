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

$student=array("AdmissionNumber"=>$admission);

//fetching student details
$fetch_student=$obj->fetch_records("student",$student);

foreach($fetch_student as $row)
{
    $names=$row['names'];
    $class=$row['class'];
    $form=$row['form'];

}

//get class during that period
$where_class = array('period'=>$year_term,'admission'=>$admission);
$get_class = $obj->fetch_records('cycle_two',$where_class);
foreach($get_class as $row)
{
    $result_class = $row['class'];
}
//get min subjects
$sql="SELECT * FROM minimum_subjects WHERE form=$form";
$execute=mysqli_query($obj->con,$sql);

if(mysqli_num_rows($execute)>0)
{
    $get_min=mysqli_fetch_assoc($execute);
    $min=$get_min['minimum'];

}
//get total students
$sql_class="SELECT * FROM cycle_two WHERE class='$result_class' AND period='$year_term'";
$sql_form="SELECT * FROM cycle_two WHERE form='$result_class[0]' AND period='$year_term'";

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
$sql = "SELECT admission, average FROM cycle_two WHERE form='$result_class[0]' AND period='$year_term' ORDER BY average DESC ";
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
$fetch_total=$obj->fetch_records("cycle_two",$where);
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

//headers

$pdf->SetX(38);
$pdf->Cell(130  ,1,'FRIENDS SCHOOL KEVEYE GIRLS',0,1,'C');

$pdf->SetFont('Arial','b','12');
$pdf->SetX(40);
$pdf->Cell(130 ,14,'P.o Box 856-50300',0,1,'C');
$pdf->SetY(28);
$pdf->SetX(4);
$pdf->SetFont('Arial','bu','11');
$pdf->Cell(130 ,12,'Students Results Slip',0,1);

$pdf->SetFont('Arial','b','11');
$pdf->SetX(5);
$pdf->Cell(15,2,'Examination:',0,0);

$pdf->SetFont('Arial','','11');
$pdf->SetX(45);
$pdf->Cell(24  ,2,'CYCLE TWO',0,0);
$pdf->SetFont('Arial','b','11');
$pdf->SetX(98);
$pdf->Cell(15  ,2,'Term:',0,0);
$pdf->SetFont('Arial','','9');
$pdf->Cell(15  ,2,$year_term[10],0,0);

$pdf->SetFont('Arial','b','11');
$pdf->SetX(150);
$pdf->Cell(15  ,2,'Year:',0,0);
$pdf->SetFont('Arial','','9');
$pdf->Cell(15  ,2,substr($year_term,0,4),0,1);

$pdf->Cell(15,1,'',0,1);
$pdf->SetX(5);
$pdf->Cell(180,0,'',1,1);
$pdf->Cell(15,2,'',0,1);

$pdf->SetFont('Arial','b','11');
$pdf->SetX(5);
$pdf->Cell(15,2,'Adm.No:',0,0);

$pdf->SetFont('Arial','','11');
$pdf->SetX(28);
$pdf->Cell(24  ,2,$admission,0,0);
$pdf->SetFont('Arial','b','11');
$pdf->SetX(48);
$pdf->Cell(15  ,2,'Name:',0,0);
$pdf->SetFont('Arial','','9');
$pdf->Cell(15  ,2,$names,0,0);

$pdf->SetFont('Arial','b','11');
$pdf->SetX(140);
$pdf->Cell(15  ,2,'Form:',0,0);
$pdf->SetFont('Arial','','9');
$pdf->Cell(15  ,2,$class[0],0,0);
$pdf->Cell(15  ,2,$class,0,1);

$pdf->Cell(15,1,'',0,1);
$pdf->SetX(5);
$pdf->Cell(180,0,'',1,1);
$pdf->Cell(15,1,'',0,1);

$pdf->SetFont('Arial','b','11');
$pdf->SetX(5);
$pdf->Cell(15,2,'Ov.Position:',0,0);

$pdf->SetFont('Arial','','11');
$pdf->SetX(32);
$pdf->Cell(24  ,2,$form_position,0,0);
$pdf->SetFont('Arial','b','11');
$pdf->SetX(48);
$pdf->Cell(15  ,2,'Cl.Position:',0,0);
$pdf->SetFont('Arial','','9');
$pdf->SetX(78);
$pdf->Cell(15  ,2,$class_position,0,0);

$pdf->SetFont('Arial','b','11');
$pdf->SetX(90);
$pdf->Cell(15  ,2,'T Marks:',0,0);
$pdf->SetFont('Arial','','9');
$pdf->SetX(108);
$pdf->Cell(15  ,2,$totalmarks,0,0);

$pdf->SetFont('Arial','b','11');
$pdf->SetX(120);
$pdf->Cell(15  ,2,'MMarks:',0,0);
$pdf->SetFont('Arial','','9');
$pdf->SetX(136);
$pdf->Cell(15  ,2,$average,0,0);

$pdf->SetFont('Arial','b','11');
$pdf->SetX(156);
$pdf->Cell(15  ,2,'Mgrade:',0,0);
$pdf->SetFont('Arial','','9');
$pdf->SetX(172);
$pdf->Cell(15  ,2,$overall_grade,0,1);


$pdf->Cell(15,1,'',0,1);
$pdf->SetX(5);
$pdf->Cell(180,0,'',1,1);
$pdf->Cell(5,1,'',0,1);


$pdf->SetFont('Arial','b','10');
$pdf->SetX(5);
$pdf->Cell(20  ,6,'Code',1,0);
$pdf->Cell(60  ,6,'Subject Name',1,0);
$pdf->Cell(40  ,6,'Percent',1,0);
$pdf->Cell(40  ,6,'Grade',1,0);
$pdf->Cell(20  ,6,'Position',1,1);

$pdf->SetFont('Arial','','9');
$get_subjects=$obj->fetch_all_records("subject");
foreach($get_subjects as $row) {
    $subject = $row['SubjectName'];
    $code = $row['SubjectKey'];
    $where = array("admission" => $admission, "period" => $year_term, "subject" => $row['SubjectName']);
    $fetch_results = $obj->fetch_records("results", $where);
    $cat='';
    $end='';
    $total='';
    $grade='';

    foreach ($fetch_results as $row) {
        $cat = $row['mid'];
        $grade=$row['mid_grade'];


    }
    $pdf->SetX(5);
    $pdf->SetFont('Arial', '', '9');
    $pdf->Cell(20, 5, $code, 1, 0);
    $pdf->Cell(60, 5, $subject, 1, 0);
    $pdf->Cell(40, 5, $cat, 1, 0);
    $pdf->Cell(40, 5, $grade, 1, 0);
    $pdf->Cell(20, 5, 'NA', 1, 1);




}
$pdf->Cell(5,1,'',0,1);
$pdf->SetFont('Arial','b','10');
$pdf->SetX(5);
$pdf->Cell(20  ,6,'Isuued Without any erasure or alteration.',0,0);
$pdf->SetFont('Arial','b','10');
$pdf->SetX(120);
$pdf->Cell(20  ,6,'Ranking Subjects:',0,0);
$pdf->SetFont('Arial','b','10');
$pdf->SetX(152);
$pdf->Cell(20  ,6,$min,0,0);





$pdf->Output();
?>