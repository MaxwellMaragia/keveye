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

$pdf = new FPDF('L','mm',array(400,400));
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
$pdf->Cell(130 ,12,$form,0,0);

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
$pdf->SetX(101);
$pdf->SetFont('Arial','b','9');
$pdf->Cell(12  ,6,'101',1,0);
$pdf->Cell(12  ,6,'102',1,0);
$pdf->Cell(12  ,6,'121',1,0);
$pdf->Cell(12  ,6,'231',1,0);
$pdf->Cell(12  ,6,'232',1,0);
$pdf->Cell(12  ,6,'233',1,0);
$pdf->Cell(12  ,6,'311',1,0);
$pdf->Cell(12  ,6,'312',1,0);
$pdf->Cell(12  ,6,'313',1,0);
$pdf->Cell(12  ,6,'441',1,0);
$pdf->Cell(12  ,6,'442',1,0);
$pdf->Cell(12  ,6,'443',1,0);
$pdf->Cell(12  ,6,'451',1,0);
$pdf->Cell(12  ,6,'501',1,0);
$pdf->Cell(12  ,6,'511',1,0);
$pdf->Cell(12  ,6,'565',1,0);
$pdf->Cell(10  ,6,'',1,0);
$pdf->Cell(10  ,6,'',1,1);

$pdf->SetFont('Arial','b','9');
$pdf->SetX(5);
$pdf->Cell(12  ,6,'Ad.No',1,0);
$pdf->Cell(60  ,6,'Name',1,0);
$pdf->Cell(12  ,6,'Class',1,0);
$pdf->Cell(12  ,6,'KCPE',1,0);
$pdf->Cell(12  ,6,'ENG',1,0);
$pdf->Cell(12  ,6,'KIS',1,0);
$pdf->Cell(12  ,6,'MAT',1,0);
$pdf->Cell(12  ,6,'BIO',1,0);
$pdf->Cell(12  ,6,'PHY',1,0);
$pdf->Cell(12  ,6,'CHE',1,0);
$pdf->Cell(12  ,6,'HIS',1,0);
$pdf->Cell(12  ,6,'GEO',1,0);
$pdf->Cell(12  ,6,'CRE',1,0);
$pdf->Cell(12  ,6,'HSC',1,0);
$pdf->Cell(12  ,6,'AD',1,0);
$pdf->Cell(12  ,6,'AGR',1,0);
$pdf->Cell(12  ,6,'CPS',1,0);
$pdf->Cell(12  ,6,'FRE',1,0);
$pdf->Cell(12  ,6,'MSU',1,0);
$pdf->Cell(12  ,6,'BST',1,0);
$pdf->Cell(10  ,6,'',1,0);
$pdf->Cell(10  ,6,'',1,0);
$pdf->Cell(10  ,6,'S.Ent',1,0);
$pdf->Cell(10  ,6,'T.P',1,0);
$pdf->Cell(14  ,6,'MPts',1,0);
$pdf->Cell(10  ,6,'MG',1,0);
$pdf->Cell(10  ,6,'C.P',1,0);
$pdf->Cell(10  ,6,'O.P',1,0);
$pdf->Cell(14  ,6,'VAP',1,1);

//fetch results
$where = array("class"=>$form);
$get_data = $obj->fetch_records("final_result",$where);
foreach ($get_data as $row) 
{
	$adm = $row['admission'];
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

	//
	$engg=$adg=$cpsg=$freg=$bstg=$agrg=$creg=$hisg=$geog=$biog=$phyg=$cheg=$kisg=$matg="";

	if($ad!=null){
		$ad=$ad;
		$sql = "SELECT * FROM results WHERE admission='$adm' AND subject='Art and design' LIMIT 1";
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
		$sql = "SELECT * FROM results WHERE admission='$adm' AND subject='Computer Studies' LIMIT 1";
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
		$sql = "SELECT * FROM results WHERE admission='$adm' AND subject='French' LIMIT 1";
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
		$sql = "SELECT * FROM results WHERE admission='$adm' AND subject='Business' LIMIT 1";
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
		$sql = "SELECT * FROM results WHERE admission='$adm' AND subject='Agriculture' LIMIT 1";
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
		$sql = "SELECT * FROM results WHERE admission='$adm' AND subject='CRE' LIMIT 1";
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
		$sql = "SELECT * FROM results WHERE admission='$adm' AND subject='History' LIMIT 1";
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
		$sql = "SELECT * FROM results WHERE admission='$adm' AND subject='Geography' LIMIT 1";
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
		$sql = "SELECT * FROM results WHERE admission='$adm' AND subject='Biology' LIMIT 1";
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
		$sql = "SELECT * FROM results WHERE admission='$adm' AND subject='Physics' LIMIT 1";
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
		$sql = "SELECT * FROM results WHERE admission='$adm' AND subject='Chemistry' LIMIT 1";
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
		$sql = "SELECT * FROM results WHERE admission='$adm' AND subject='Kiswahili' LIMIT 1";
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
		$sql = "SELECT * FROM results WHERE admission='$adm' AND subject='English' LIMIT 1";
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
		$sql = "SELECT * FROM results WHERE admission='$adm' AND subject='Mathematics' LIMIT 1";
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



$pdf->SetFont('Arial','','9');
$pdf->SetX(5);
$pdf->Cell(12  ,6,$adm,1,0);
$pdf->Cell(60  ,6,$name,1,0);
$pdf->Cell(12  ,6,$cls,1,0);
$pdf->Cell(12  ,6,$kcpe,1,0);
$pdf->Cell(12  ,6,$eng.' '.$engg,1,0);
$pdf->Cell(12  ,6,$kis.' '.$kisg,1,0);
$pdf->Cell(12  ,6,$mat.' '.$matg,1,0);
$pdf->Cell(12  ,6,$bio.' '.$biog,1,0);
$pdf->Cell(12  ,6,$phy.' '.$phyg,1,0);
$pdf->Cell(12  ,6,$che.' '.$cheg,1,0);
$pdf->Cell(12  ,6,$his.' '.$hisg,1,0);
$pdf->Cell(12  ,6,$geo.' '.$geog,1,0);
$pdf->Cell(12  ,6,$cre.' '.$creg,1,0);
$pdf->Cell(12  ,6,'NA',1,0);
$pdf->Cell(12  ,6,$ad.' '.$adg,1,0);
$pdf->Cell(12  ,6,$agr.' '.$agrg,1,0);
$pdf->Cell(12  ,6,$cps.' '.$cpsg,1,0);
$pdf->Cell(12  ,6,$fre.' '.$freg,1,0);
$pdf->Cell(12  ,6,'NA',1,0);
$pdf->Cell(12  ,6,$bst.' '.$bstg,1,0);
$pdf->Cell(10  ,6,'',1,0);
$pdf->Cell(10  ,6,'',1,0);
$pdf->Cell(10  ,6,$count,1,0);
$pdf->Cell(10  ,6,'T.P',1,0);
$pdf->Cell(14  ,6,'MPts',1,0);
$pdf->Cell(10  ,6,$mg,1,0);
$pdf->Cell(10  ,6,'C.P',1,0);
$pdf->Cell(10  ,6,'O.P',1,0);
$pdf->Cell(14  ,6,'VAP',1,1);
}


$pdf->Output();
}
?>