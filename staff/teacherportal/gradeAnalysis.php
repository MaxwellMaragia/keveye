<?php
session_start();
if(!$_SESSION['account'])
{
    header('location:../index.php');
}
else
{
require('functions/fpdf/fpdf.php');
require('functions/actions.php');
$obj=new DataOperations();
$class=$_SESSION['view_results_class'];
$period=$_SESSION['view_results_term'];
$periodt = substr($period, -1);
$periody = substr($period, 0,4);
$form = substr($class, -2,1);




//dev
if($periodt==1)
{
 $prevt = $periodt+2;
 $t = substr($period, 4,6);
 $ny = substr($period, 0,4);
 $py = $ny-1;
 $prevp = $py.' '.$t.' '.$prevt;
}
$prevt = $periodt-1;
$yt = substr($period, 0,9);
$prevp = $yt.' '.$prevt;

$pdf = new FPDF('L','mm','A4');
$pdf->AddPage();

//set font to arial,bold,14pt
$pdf->SetFont('Arial','B','18');

//cella(width,height,text,border,endline,[align])

//headers

$pdf->SetX(24);
$pdf->Cell(130  ,1,'FRIENDS SCHOOL KEVEYE GIRLS',0,1);

$pdf->SetFont('Arial','b','14');
$pdf->SetX(24);
$pdf->Cell(130 ,14,'P.o Box 856-50300',0,1);
$pdf->SetX(24);
$pdf->Cell(130 ,1,'Tel: 0707174835',0,1);
$pdf->SetY(32);
$pdf->SetX(2);
$pdf->SetFont('Arial','b','14');
$pdf->Cell(130 ,12,'Form:',0,0);
$pdf->SetX(18);
$pdf->SetFont('Arial','b','14');
$pdf->Cell(130 ,12,$class,0,0);

$pdf->SetFont('Arial','b','16');
$pdf->SetX(26);
$pdf->Cell(15,12,'Subject Percentage/Grades Analysis',0,1);

$pdf->SetY(39);
$pdf->SetX(2);
$pdf->SetFont('Arial','b','14');
$pdf->Cell(130 ,12,'Term:',0,0);
$pdf->SetX(18);
$pdf->SetFont('Arial','b','14');
$pdf->Cell(130 ,12,$periodt,0,0);

$pdf->SetFont('Arial','b','14');
$pdf->SetX(26);
$pdf->Cell(15,12,'Year: ',0,0);
$pdf->SetFont('Arial','b','14');
$pdf->SetX(44);
$pdf->Cell(15,12,$periody,0,1);

$pdf->SetY(48);
$pdf->SetX(70);
$pdf->SetFont('Arial','b','9');
$pdf->Cell(10  ,6,'101',1,0);
$pdf->Cell(10  ,6,'102',1,0);
$pdf->Cell(10  ,6,'121',1,0);
$pdf->Cell(10  ,6,'231',1,0);
$pdf->Cell(10  ,6,'232',1,0);
$pdf->Cell(10  ,6,'233',1,0);
$pdf->Cell(10  ,6,'311',1,0);
$pdf->Cell(10  ,6,'312',1,0);
$pdf->Cell(10  ,6,'313',1,0);
$pdf->Cell(10  ,6,'441',1,0);
$pdf->Cell(10  ,6,'442',1,0);
$pdf->Cell(10  ,6,'443',1,0);
$pdf->Cell(10  ,6,'451',1,0);
$pdf->Cell(10  ,6,'501',1,0);
$pdf->Cell(10  ,6,'511',1,0);
$pdf->Cell(10  ,6,'565',1,0);
// $pdf->Cell(10  ,6,'',1,0);
$pdf->Cell(3  ,6,'',1,1);

$pdf->SetFont('Arial','b','8');
$pdf->SetX(2);
$pdf->Cell(10  ,6,'Ad.No',1,0);
$pdf->Cell(39  ,6,'Name',1,0);
$pdf->Cell(10  ,6,'Class',1,0);
$pdf->Cell(9  ,6,'KCPE',1,0);
$pdf->Cell(10  ,6,'ENG',1,0);
$pdf->Cell(10  ,6,'KIS',1,0);
$pdf->Cell(10  ,6,'MAT',1,0);
$pdf->Cell(10  ,6,'BIO',1,0);
$pdf->Cell(10  ,6,'PHY',1,0);
$pdf->Cell(10  ,6,'CHE',1,0);
$pdf->Cell(10  ,6,'HIS',1,0);
$pdf->Cell(10  ,6,'GEO',1,0);
$pdf->Cell(10  ,6,'CRE',1,0);
$pdf->Cell(10  ,6,'HSC',1,0);
$pdf->Cell(10  ,6,'AD',1,0);
$pdf->Cell(10  ,6,'AGR',1,0);
$pdf->Cell(10  ,6,'CPS',1,0);
$pdf->Cell(10  ,6,'FRE',1,0);
$pdf->Cell(10  ,6,'MSU',1,0);
$pdf->Cell(10  ,6,'BST',1,0);
// $pdf->Cell(10  ,6,'',1,0);
$pdf->Cell(3  ,6,'',1,0);
$pdf->Cell(10  ,6,'S.Ent',1,0);
$pdf->Cell(6  ,6,'T.P',1,0);
$pdf->Cell(12  ,6,'MPts',1,0);
$pdf->Cell(6  ,6,'MG',1,0);
$pdf->Cell(6  ,6,'C.P',1,0);
$pdf->Cell(6  ,6,'O.P',1,0);
$pdf->Cell(12.5  ,6,'VAP',1,1);

//fetch results

$qry = "SELECT * FROM final_result WHERE class='$class' AND period='$period' ORDER BY average DESC";
$run = mysqli_query($obj->con,$qry);
while ($row = mysqli_fetch_array($run)) 
{
	$adm = $row['admission'];
	$admission=$adm;
	$name = $row['names'];
	$cls = $row['class'];
	$ad = $row['Art and design'];
	$cps = $row['Computer Studies'];
	$fre = $row['French'];
	$bst = $row['Business'];
	$agr = $row['Agriculture'];
	$cre = $row['CRE'];
	$his = $row['History'];
	$geo = $row['Geography'];
	$bio = $row['Biology'];
	$phy = $row['Physics'];
	$che = $row['Chemistry'];
	$kis = $row['Kiswahili'];
	$eng = $row['English'];
	$mat = $row['Mathematics'];
	$count = $row['count'];
	$mg = $row['grade'];
	$tp = $row['total_points'];
	$mp = $row['average_points'];

	//get total students
$sql_class="SELECT * FROM final_result WHERE class='$class' AND period='$period'";
$sql_form="SELECT * FROM final_result WHERE form='$form' AND period='$period'";

$execute_class=mysqli_query($obj->con,$sql_class);
$total_in_class=mysqli_num_rows($execute_class);
$execute_form=mysqli_query($obj->con,$sql_form);
$total_in_form=mysqli_num_rows($execute_form);

//get class rank
$query = "SELECT admission, average FROM final_result WHERE class='$class' AND period='$period' ORDER BY average DESC ";
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
$sql = "SELECT admission, average FROM final_result WHERE form='$form' AND period='$period' ORDER BY average DESC ";
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

	//
	$engg=$adg=$cpsg=$freg=$bstg=$agrg=$creg=$hisg=$geog=$biog=$phyg=$cheg=$kisg=$matg="";

	if($ad!=null){
		$ad=$ad;
		$sql = "SELECT * FROM results WHERE admission='$adm' AND subject='Art and design' AND period='$period' LIMIT 1";
		$res = mysqli_query($obj->con,$sql);
		while ($d = mysqli_fetch_array($res))
		 {
			$adg = $d['grade'];
		}
	}
	else
	{
		$ad="--";
	}
	if($cps!=null){
		$cps=$cps;
		$sql = "SELECT * FROM results WHERE admission='$adm' AND subject='Computer Studies' AND period='$period' LIMIT 1";
		$res = mysqli_query($obj->con,$sql);
		while ($d = mysqli_fetch_array($res))
		 {
			$cpsg = $d['grade'];
		}
	}
	else
	{
		$cps="--";
	}
	if($fre!=null){
		$fre=$fre;
		$sql = "SELECT * FROM results WHERE admission='$adm' AND subject='French' AND period='$period' LIMIT 1";
		$res = mysqli_query($obj->con,$sql);
		while ($d = mysqli_fetch_array($res))
		 {
			$freg = $d['grade'];
		}
	}
	else
	{
		$fre="--";
	}
	if($bst!=null){
		$bst=$bst;
		$sql = "SELECT * FROM results WHERE admission='$adm' AND subject='Business' AND period='$period' LIMIT 1";
		$res = mysqli_query($obj->con,$sql);
		while ($d = mysqli_fetch_array($res))
		 {
			$bstg = $d['grade'];
		}
	}
	else
	{
		$bst="--";
	}
	if($agr!=null){
		$agr=$agr;
		$sql = "SELECT * FROM results WHERE admission='$adm' AND subject='Agriculture' AND period='$period' LIMIT 1";
		$res = mysqli_query($obj->con,$sql);
		while ($d = mysqli_fetch_array($res))
		 {
			$agrg = $d['grade'];
		}
	}
	else
	{
		$agr="--";
	}
	if($cre!=null){
		$cre=$cre;
		$sql = "SELECT * FROM results WHERE admission='$adm' AND subject='CRE' AND period='$period' LIMIT 1";
		$res = mysqli_query($obj->con,$sql);
		while ($d = mysqli_fetch_array($res))
		 {
			$creg = $d['grade'];
		}
	}
	else
	{
		$cre="--";
	}
	if($his!=null){
		$his=$his;
		$sql = "SELECT * FROM results WHERE admission='$adm' AND subject='History' AND period='$period' LIMIT 1";
		$res = mysqli_query($obj->con,$sql);
		while ($d = mysqli_fetch_array($res))
		 {
			$hisg = $d['grade'];
		}
	}
	else
	{
		$his="--";
	}
	if($geo!=null){
		$geo=$geo;
		$sql = "SELECT * FROM results WHERE admission='$adm' AND subject='Geography' AND period='$period' LIMIT 1";
		$res = mysqli_query($obj->con,$sql);
		while ($d = mysqli_fetch_array($res))
		 {
			$geog = $d['grade'];
		}
	}
	else
	{
		$geo="--";
	}
	if($bio!=null){
		$bio=$bio;
		$sql = "SELECT * FROM results WHERE admission='$adm' AND subject='Biology' AND period='$period' LIMIT 1";
		$res = mysqli_query($obj->con,$sql);
		while ($d = mysqli_fetch_array($res))
		 {
			$biog = $d['grade'];
		}
	}
	else
	{
		$bio="--";
	}
	if($phy!=null){
		$phy=$phy;
		$sql = "SELECT * FROM results WHERE admission='$adm' AND subject='Physics' AND period='$period' LIMIT 1";
		$res = mysqli_query($obj->con,$sql);
		while ($d = mysqli_fetch_array($res))
		 {
			$phyg = $d['grade'];
		}
	}
	else
	{
		$phy="--";
	}
	if($che!=null){
		$che=$che;
		$sql = "SELECT * FROM results WHERE admission='$adm' AND subject='Chemistry' AND period='$period' LIMIT 1";
		$res = mysqli_query($obj->con,$sql);
		while ($d = mysqli_fetch_array($res))
		 {
			$cheg = $d['grade'];
		}
	}
	else
	{
		$che="--";
	}
	if($kis!=null){
		$kis=$kis;
		$sql = "SELECT * FROM results WHERE admission='$adm' AND subject='Kiswahili' AND period='$period' LIMIT 1";
		$res = mysqli_query($obj->con,$sql);
		while ($d = mysqli_fetch_array($res))
		 {
			$kisg = $d['grade'];
		}
	}
	else
	{
		$kis="--";
	}
	if($eng!=null){
		$eng=$eng;
		$sql = "SELECT * FROM results WHERE admission='$adm' AND subject='English' AND period='$period' LIMIT 1";
		$res = mysqli_query($obj->con,$sql);
		while ($d = mysqli_fetch_array($res))
		 {
			$engg = $d['grade'];
		}
	}
	else
	{
		$eng="--";
	}
	if($mat!=null){
		$mat=$mat;
		$sql = "SELECT * FROM results WHERE admission='$adm' AND subject='Mathematics' AND period='$period' LIMIT 1";
		$res = mysqli_query($obj->con,$sql);
		while ($d = mysqli_fetch_array($res))
		 {
			$matg = $d['grade'];
		}
	}
	else
	{
		$mat="--";
	}

	//fetch kcpe marks
	$where1 = array("AdmissionNumber"=>$adm);
	$get_kcpe = $obj->fetch_records("student",$where1);
	foreach ($get_kcpe as $row) 
	{
		$kcpe = $row['kcpe'];
	}

	//fetch previous mpts
    $q = "SELECT average_points FROM final_result WHERE admission='$adm' AND period='$prevp' ";
    $ans = mysqli_query($obj->con,$q);
    $r = mysqli_fetch_array($ans);
    $prevAv = $r[0];
    $dev = $mp-$prevAv;

$pdf->SetFont('Arial','','8');
$pdf->SetX(2);
$pdf->Cell(10  ,6,$adm,1,0);
$pdf->Cell(39  ,6,$name,1,0);
$pdf->Cell(10  ,6,$cls,1,0);
$pdf->Cell(9  ,6,$kcpe,1,0);
$pdf->Cell(10  ,6,$eng.' '.$engg,1,0);
$pdf->Cell(10  ,6,$kis.' '.$kisg,1,0);
$pdf->Cell(10  ,6,$mat.' '.$matg,1,0);
$pdf->Cell(10  ,6,$bio.' '.$biog,1,0);
$pdf->Cell(10  ,6,$phy.' '.$phyg,1,0);
$pdf->Cell(10  ,6,$che.' '.$cheg,1,0);
$pdf->Cell(10  ,6,$his.' '.$hisg,1,0);
$pdf->Cell(10  ,6,$geo.' '.$geog,1,0);
$pdf->Cell(10  ,6,$cre.' '.$creg,1,0);
$pdf->Cell(10  ,6,'NA',1,0);
$pdf->Cell(10  ,6,$ad.' '.$adg,1,0);
$pdf->Cell(10  ,6,$agr.' '.$agrg,1,0);
$pdf->Cell(10  ,6,$cps.' '.$cpsg,1,0);
$pdf->Cell(10  ,6,$fre.' '.$freg,1,0);
$pdf->Cell(10  ,6,'NA',1,0);
$pdf->Cell(10  ,6,$bst.' '.$bstg,1,0);
// $pdf->Cell(10  ,6,'',1,0);
$pdf->Cell(3  ,6,'',1,0);
$pdf->Cell(10  ,6,$count,1,0);
$pdf->Cell(6  ,6,$tp,1,0);
$pdf->Cell(12  ,6,$mp,1,0);
$pdf->Cell(6  ,6,$mg,1,0);
$pdf->Cell(6  ,6,$class_position,1,0);
$pdf->Cell(6  ,6,$form_position,1,0);
$pdf->Cell(12.5  ,6,$dev,1,1);

}




$pdf->Output();
}
?>