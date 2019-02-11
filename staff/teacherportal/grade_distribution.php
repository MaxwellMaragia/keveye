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


$pdf = new FPDF('L','mm','A4');

$pdf->AddPage();

//set font to arial,bold,14pt
$pdf->SetFont('Arial','B','18');

//cella(width,height,text,border,endline,[align])

//headers


$pdf->SetX(110);
//LOGO
$pdf->Image("functions/fpdf/keveye.png",80,2,30,30);
$pdf->Cell(130  ,1,'FRIENDS SCHOOL KEVEYE GIRLS',0,1);

$pdf->SetFont('Arial','b','14');
$pdf->SetX(110);
$pdf->Cell(130 ,14,'P.o Box 856-50300',0,1);
$pdf->SetX(110);
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
$pdf->SetX(17);
$pdf->SetFont('Arial','b','12');
$pdf->Cell(17  ,6,'101',1,0);
$pdf->Cell(17  ,6,'102',1,0);
$pdf->Cell(17  ,6,'121',1,0);
$pdf->Cell(17  ,6,'231',1,0);
$pdf->Cell(17  ,6,'232',1,0);
$pdf->Cell(17  ,6,'233',1,0);
$pdf->Cell(17  ,6,'311',1,0);
$pdf->Cell(17  ,6,'312',1,0);
$pdf->Cell(17  ,6,'313',1,0);
$pdf->Cell(17  ,6,'441',1,0);
$pdf->Cell(17  ,6,'442',1,0);
$pdf->Cell(17  ,6,'443',1,0);
$pdf->Cell(17  ,6,'451',1,0);
$pdf->Cell(17  ,6,'501',1,0);
$pdf->Cell(17  ,6,'511',1,0);
$pdf->Cell(17  ,6,'565',1,0);
$pdf->Cell(17  ,6,'',0,0);
$pdf->Cell(17  ,6,'',0,0);

$pdf->SetFont('Arial','b','12');
$pdf->SetY(54);
$pdf->SetX(5);
$pdf->Cell(12  ,6,'GRD',1,0);
$pdf->Cell(17  ,6,'ENG',1,0);
$pdf->Cell(17  ,6,'KIS',1,0);
$pdf->Cell(17  ,6,'MAT',1,0);
$pdf->Cell(17  ,6,'BIO',1,0);
$pdf->Cell(17  ,6,'PHY',1,0);
$pdf->Cell(17  ,6,'CHE',1,0);
$pdf->Cell(17  ,6,'HIS',1,0);
$pdf->Cell(17  ,6,'GEO',1,0);
$pdf->Cell(17  ,6,'CRE',1,0);
$pdf->Cell(17  ,6,'HSC',1,0);
$pdf->Cell(17  ,6,'AD',1,0);
$pdf->Cell(17  ,6,'AGR',1,0);
$pdf->Cell(17  ,6,'CPS',1,0);
$pdf->Cell(17  ,6,'FRE',1,0);
$pdf->Cell(17  ,6,'MUS',1,0);
$pdf->Cell(17  ,6,'BST',1,0);

//english grade distribution
$grade=array('A','A-','B+','B','B-','C+','C','C-','D+','D','D-','E');
$i=0;
$eng=array();
$kis=array();
$mat=array();
$bio=array();
$phy=array();
$che=array();
$his=array();
$geo=array();
$cre=array();
$hsc=array();
$ad_=array();
$agr=array();
$cps=array();
$fre=array();
$mus=array();
$bst=array();

//english
for($i = 0; $i < 12; $i++){
	$query="SELECT COUNT(grade) AS count FROM results WHERE form='$form' AND term= '$periodt' AND subject='English' AND grade='$grade[$i]' ";
	$run=mysqli_query($obj->con,$query);
	while($disp=mysqli_fetch_array($run)){
  		$eng[]=$disp['count'];
	}
	
}

//kiswahili
for($i = 0; $i < 12; $i++){
	$query="SELECT COUNT(grade) AS count FROM results WHERE form='$form' AND term= '$periodt' AND subject='kiswahili' AND grade='$grade[$i]' ";
	$run=mysqli_query($obj->con,$query);
	while($disp=mysqli_fetch_array($run)){
  		$kis[]=$disp['count'];
	}
	
}

//maths
for($i = 0; $i < 12; $i++){
	$query="SELECT COUNT(grade) AS count FROM results WHERE form='$form' AND term= '$periodt' AND subject='mathematics' AND grade='$grade[$i]' ";
	$run=mysqli_query($obj->con,$query);
	while($disp=mysqli_fetch_array($run)){
  		$mat[]=$disp['count'];
	}
	
}

//bio
for($i = 0; $i < 12; $i++){
	$query="SELECT COUNT(grade) AS count FROM results WHERE form='$form' AND term= '$periodt' AND subject='biology' AND grade='$grade[$i]' ";
	$run=mysqli_query($obj->con,$query);
	while($disp=mysqli_fetch_array($run)){
  		$bio[]=$disp['count'];
	}
	
}

//phy
for($i = 0; $i < 12; $i++){
	$query="SELECT COUNT(grade) AS count FROM results WHERE form='$form' AND term= '$periodt' AND subject='physics' AND grade='$grade[$i]' ";
	$run=mysqli_query($obj->con,$query);
	while($disp=mysqli_fetch_array($run)){
  		$phy[]=$disp['count'];
	}
	
}

//che
for($i = 0; $i < 12; $i++){
	$query="SELECT COUNT(grade) AS count FROM results WHERE form='$form' AND term= '$periodt' AND subject='chemistry' AND grade='$grade[$i]' ";
	$run=mysqli_query($obj->con,$query);
	while($disp=mysqli_fetch_array($run)){
  		$che[]=$disp['count'];
	}
	
}

//his
for($i = 0; $i < 12; $i++){
	$query="SELECT COUNT(grade) AS count FROM results WHERE form='$form' AND term= '$periodt' AND subject='history' AND grade='$grade[$i]' ";
	$run=mysqli_query($obj->con,$query);
	while($disp=mysqli_fetch_array($run)){
  		$his[]=$disp['count'];
	}
	
}


//geo
for($i = 0; $i < 12; $i++){
	$query="SELECT COUNT(grade) AS count FROM results WHERE form='$form' AND term= '$periodt' AND subject='geography' AND grade='$grade[$i]' ";
	$run=mysqli_query($obj->con,$query);
	while($disp=mysqli_fetch_array($run)){
  		$geo[]=$disp['count'];
	}
	
}

//cre
for($i = 0; $i < 12; $i++){
	$query="SELECT COUNT(grade) AS count FROM results WHERE form='$form' AND term= '$periodt' AND subject='CRE' AND grade='$grade[$i]' ";
	$run=mysqli_query($obj->con,$query);
	while($disp=mysqli_fetch_array($run)){
  		$cre[]=$disp['count'];
	}
	
}


//HSC
for($i = 0; $i < 12; $i++){
	$query="SELECT COUNT(grade) AS count FROM results WHERE form='$form' AND term= '$periodt' AND subject='hsc' AND grade='$grade[$i]' ";
	$run=mysqli_query($obj->con,$query);
	while($disp=mysqli_fetch_array($run)){
  		$hsc[]=$disp['count'];
	}
	
}

//AD
for($i = 0; $i < 12; $i++){
	$query="SELECT COUNT(grade) AS count FROM results WHERE form='$form' AND term= '$periodt' AND subject='	Art and design' AND grade='$grade[$i]' ";
	$run=mysqli_query($obj->con,$query);
	while($disp=mysqli_fetch_array($run)){
  		$ad_[]=$disp['count'];
	}
	
}

//agr
for($i = 0; $i < 12; $i++){
	$query="SELECT COUNT(grade) AS count FROM results WHERE form='$form' AND term= '$periodt' AND subject='agriculture' AND grade='$grade[$i]' ";
	$run=mysqli_query($obj->con,$query);
	while($disp=mysqli_fetch_array($run)){
  		$agr[]=$disp['count'];
	}
	
}

//cps
for($i = 0; $i < 12; $i++){
	$query="SELECT COUNT(grade) AS count FROM results WHERE form='$form' AND term= '$periodt' AND subject='Computer Studies' AND grade='$grade[$i]' ";
	$run=mysqli_query($obj->con,$query);
	while($disp=mysqli_fetch_array($run)){
  		$cps[]=$disp['count'];
	}
	
}


//fre
for($i = 0; $i < 12; $i++){
	$query="SELECT COUNT(grade) AS count FROM results WHERE form='$form' AND term= '$periodt' AND subject='French' AND grade='$grade[$i]' ";
	$run=mysqli_query($obj->con,$query);
	while($disp=mysqli_fetch_array($run)){
  		$fre[]=$disp['count'];
	}
	
}

//mus
for($i = 0; $i < 12; $i++){
	$query="SELECT COUNT(grade) AS count FROM results WHERE form='$form' AND term= '$periodt' AND subject='mus' AND grade='$grade[$i]' ";
	$run=mysqli_query($obj->con,$query);
	while($disp=mysqli_fetch_array($run)){
  		$mus[]=$disp['count'];
	}
	
}

//bst
for($i = 0; $i < 12; $i++){
	$query="SELECT COUNT(grade) AS count FROM results WHERE form='$form' AND term= '$periodt' AND subject='Business' AND grade='$grade[$i]' ";
	$run=mysqli_query($obj->con,$query);
	while($disp=mysqli_fetch_array($run)){
  		$bst[]=$disp['count'];
	}
	
}

//0
$pdf->SetFont('Arial','','12');
$pdf->SetY(60);
$pdf->SetX(17);
$pdf->Cell(17  ,6,$eng[0],1,0);
$pdf->Cell(17  ,6,$kis[0],1,0);
$pdf->Cell(17  ,6,$mat[0],1,0);
$pdf->Cell(17  ,6,$bio[0],1,0);
$pdf->Cell(17  ,6,$phy[0],1,0);
$pdf->Cell(17  ,6,$che[0],1,0);
$pdf->Cell(17  ,6,$his[0],1,0);
$pdf->Cell(17  ,6,$geo[0],1,0);
$pdf->Cell(17  ,6,$cre[0],1,0);
$pdf->Cell(17  ,6,$hsc[0],1,0);
$pdf->Cell(17  ,6,$ad_[0],1,0);
$pdf->Cell(17  ,6,$agr[0],1,0);
$pdf->Cell(17  ,6,$cps[0],1,0);
$pdf->Cell(17  ,6,$fre[0],1,0);
$pdf->Cell(17  ,6,$mus[0],1,0);
$pdf->Cell(17  ,6,$bst[0],1,0);



//1

$pdf->SetFont('Arial','','12');
$pdf->SetY(66);
$pdf->SetX(17);
$pdf->Cell(17  ,6,$eng[1],1,0);
$pdf->Cell(17  ,6,$kis[1],1,0);
$pdf->Cell(17  ,6,$mat[1],1,0);
$pdf->Cell(17  ,6,$bio[1],1,0);
$pdf->Cell(17  ,6,$phy[1],1,0);
$pdf->Cell(17  ,6,$che[1],1,0);
$pdf->Cell(17  ,6,$his[1],1,0);
$pdf->Cell(17  ,6,$geo[1],1,0);
$pdf->Cell(17  ,6,$cre[1],1,0);
$pdf->Cell(17  ,6,$hsc[1],1,0);
$pdf->Cell(17  ,6,$ad_[1],1,0);
$pdf->Cell(17  ,6,$agr[1],1,0);
$pdf->Cell(17  ,6,$cps[1],1,0);
$pdf->Cell(17  ,6,$fre[1],1,0);
$pdf->Cell(17  ,6,$mus[1],1,0);
$pdf->Cell(17  ,6,$bst[1],1,0);

//2

$pdf->SetFont('Arial','','12');
$pdf->SetY(72);
$pdf->SetX(17);
$pdf->Cell(17  ,6,$eng[2],1,0);
$pdf->Cell(17  ,6,$kis[2],1,0);
$pdf->Cell(17  ,6,$mat[2],1,0);
$pdf->Cell(17  ,6,$bio[2],1,0);
$pdf->Cell(17  ,6,$phy[2],1,0);
$pdf->Cell(17  ,6,$che[2],1,0);
$pdf->Cell(17  ,6,$his[2],1,0);
$pdf->Cell(17  ,6,$geo[2],1,0);
$pdf->Cell(17  ,6,$cre[2],1,0);
$pdf->Cell(17  ,6,$hsc[2],1,0);
$pdf->Cell(17  ,6,$ad_[2],1,0);
$pdf->Cell(17  ,6,$agr[2],1,0);
$pdf->Cell(17  ,6,$cps[2],1,0);
$pdf->Cell(17  ,6,$fre[2],1,0);
$pdf->Cell(17  ,6,$mus[2],1,0);
$pdf->Cell(17  ,6,$bst[2],1,0);


//3
$pdf->SetFont('Arial','','12');
$pdf->SetY(78);
$pdf->SetX(17);
$pdf->Cell(17  ,6,$eng[3],1,0);
$pdf->Cell(17  ,6,$kis[3],1,0);
$pdf->Cell(17  ,6,$mat[3],1,0);
$pdf->Cell(17  ,6,$bio[3],1,0);
$pdf->Cell(17  ,6,$phy[3],1,0);
$pdf->Cell(17  ,6,$che[3],1,0);
$pdf->Cell(17  ,6,$his[3],1,0);
$pdf->Cell(17  ,6,$geo[3],1,0);
$pdf->Cell(17  ,6,$cre[3],1,0);
$pdf->Cell(17  ,6,$hsc[3],1,0);
$pdf->Cell(17  ,6,$ad_[3],1,0);
$pdf->Cell(17  ,6,$agr[3],1,0);
$pdf->Cell(17  ,6,$cps[3],1,0);
$pdf->Cell(17  ,6,$fre[3],1,0);
$pdf->Cell(17  ,6,$mus[3],1,0);
$pdf->Cell(17  ,6,$bst[3],1,0);


//4
$pdf->SetFont('Arial','','12');
$pdf->SetY(84);
$pdf->SetX(17);
$pdf->Cell(17  ,6,$eng[4],1,0);
$pdf->Cell(17  ,6,$kis[4],1,0);
$pdf->Cell(17  ,6,$mat[4],1,0);
$pdf->Cell(17  ,6,$bio[4],1,0);
$pdf->Cell(17  ,6,$phy[4],1,0);
$pdf->Cell(17  ,6,$che[4],1,0);
$pdf->Cell(17  ,6,$his[4],1,0);
$pdf->Cell(17  ,6,$geo[4],1,0);
$pdf->Cell(17  ,6,$cre[4],1,0);
$pdf->Cell(17  ,6,$hsc[4],1,0);
$pdf->Cell(17  ,6,$ad_[4],1,0);
$pdf->Cell(17  ,6,$agr[4],1,0);
$pdf->Cell(17  ,6,$cps[4],1,0);
$pdf->Cell(17  ,6,$fre[4],1,0);
$pdf->Cell(17  ,6,$mus[4],1,0);
$pdf->Cell(17  ,6,$bst[4],1,0);


//5
$pdf->SetFont('Arial','','12');
$pdf->SetY(90);
$pdf->SetX(17);
$pdf->Cell(17  ,6,$eng[5],1,0);
$pdf->Cell(17  ,6,$kis[5],1,0);
$pdf->Cell(17  ,6,$mat[5],1,0);
$pdf->Cell(17  ,6,$bio[5],1,0);
$pdf->Cell(17  ,6,$phy[5],1,0);
$pdf->Cell(17  ,6,$che[5],1,0);
$pdf->Cell(17  ,6,$his[5],1,0);
$pdf->Cell(17  ,6,$geo[5],1,0);
$pdf->Cell(17  ,6,$cre[5],1,0);
$pdf->Cell(17  ,6,$hsc[5],1,0);
$pdf->Cell(17  ,6,$ad_[5],1,0);
$pdf->Cell(17  ,6,$agr[5],1,0);
$pdf->Cell(17  ,6,$cps[5],1,0);
$pdf->Cell(17  ,6,$fre[5],1,0);
$pdf->Cell(17  ,6,$mus[5],1,0);
$pdf->Cell(17  ,6,$bst[5],1,0);


//6
$pdf->SetFont('Arial','','12');
$pdf->SetY(96);
$pdf->SetX(17);
$pdf->Cell(17  ,6,$eng[6],1,0);
$pdf->Cell(17  ,6,$kis[6],1,0);
$pdf->Cell(17  ,6,$mat[6],1,0);
$pdf->Cell(17  ,6,$bio[6],1,0);
$pdf->Cell(17  ,6,$phy[6],1,0);
$pdf->Cell(17  ,6,$che[6],1,0);
$pdf->Cell(17  ,6,$his[6],1,0);
$pdf->Cell(17  ,6,$geo[6],1,0);
$pdf->Cell(17  ,6,$cre[6],1,0);
$pdf->Cell(17  ,6,$hsc[6],1,0);
$pdf->Cell(17  ,6,$ad_[6],1,0);
$pdf->Cell(17  ,6,$agr[6],1,0);
$pdf->Cell(17  ,6,$cps[6],1,0);
$pdf->Cell(17  ,6,$fre[6],1,0);
$pdf->Cell(17  ,6,$mus[6],1,0);
$pdf->Cell(17  ,6,$bst[6],1,0);

//7
$pdf->SetFont('Arial','','12');
$pdf->SetY(102);
$pdf->SetX(17);
$pdf->Cell(17  ,6,$eng[7],1,0);
$pdf->Cell(17  ,6,$kis[7],1,0);
$pdf->Cell(17  ,6,$mat[7],1,0);
$pdf->Cell(17  ,6,$bio[7],1,0);
$pdf->Cell(17  ,6,$phy[7],1,0);
$pdf->Cell(17  ,6,$che[7],1,0);
$pdf->Cell(17  ,6,$his[7],1,0);
$pdf->Cell(17  ,6,$geo[7],1,0);
$pdf->Cell(17  ,6,$cre[7],1,0);
$pdf->Cell(17  ,6,$hsc[7],1,0);
$pdf->Cell(17  ,6,$ad_[7],1,0);
$pdf->Cell(17  ,6,$agr[7],1,0);
$pdf->Cell(17  ,6,$cps[7],1,0);
$pdf->Cell(17  ,6,$fre[7],1,0);
$pdf->Cell(17  ,6,$mus[7],1,0);
$pdf->Cell(17  ,6,$bst[7],1,0);

//8
$pdf->SetFont('Arial','','12');
$pdf->SetY(108);
$pdf->SetX(17);
$pdf->Cell(17  ,6,$eng[8],1,0);
$pdf->Cell(17  ,6,$kis[8],1,0);
$pdf->Cell(17  ,6,$mat[8],1,0);
$pdf->Cell(17  ,6,$bio[8],1,0);
$pdf->Cell(17  ,6,$phy[8],1,0);
$pdf->Cell(17  ,6,$che[8],1,0);
$pdf->Cell(17  ,6,$his[8],1,0);
$pdf->Cell(17  ,6,$geo[8],1,0);
$pdf->Cell(17  ,6,$cre[8],1,0);
$pdf->Cell(17  ,6,$hsc[8],1,0);
$pdf->Cell(17  ,6,$ad_[8],1,0);
$pdf->Cell(17  ,6,$agr[8],1,0);
$pdf->Cell(17  ,6,$cps[8],1,0);
$pdf->Cell(17  ,6,$fre[8],1,0);
$pdf->Cell(17  ,6,$mus[8],1,0);
$pdf->Cell(17  ,6,$bst[8],1,0);

//9
$pdf->SetFont('Arial','','12');
$pdf->SetY(114);
$pdf->SetX(17);
$pdf->Cell(17  ,6,$eng[9],1,0);
$pdf->Cell(17  ,6,$kis[9],1,0);
$pdf->Cell(17  ,6,$mat[9],1,0);
$pdf->Cell(17  ,6,$bio[9],1,0);
$pdf->Cell(17  ,6,$phy[9],1,0);
$pdf->Cell(17  ,6,$che[9],1,0);
$pdf->Cell(17  ,6,$his[9],1,0);
$pdf->Cell(17  ,6,$geo[9],1,0);
$pdf->Cell(17  ,6,$cre[9],1,0);
$pdf->Cell(17  ,6,$hsc[9],1,0);
$pdf->Cell(17  ,6,$ad_[9],1,0);
$pdf->Cell(17  ,6,$agr[9],1,0);
$pdf->Cell(17  ,6,$cps[9],1,0);
$pdf->Cell(17  ,6,$fre[9],1,0);
$pdf->Cell(17  ,6,$mus[9],1,0);
$pdf->Cell(17  ,6,$bst[9],1,0);


//10
$pdf->SetFont('Arial','','12');
$pdf->SetY(120);
$pdf->SetX(17);
$pdf->Cell(17  ,6,$eng[10],1,0);
$pdf->Cell(17  ,6,$kis[10],1,0);
$pdf->Cell(17  ,6,$mat[10],1,0);
$pdf->Cell(17  ,6,$bio[10],1,0);
$pdf->Cell(17  ,6,$phy[10],1,0);
$pdf->Cell(17  ,6,$che[10],1,0);
$pdf->Cell(17  ,6,$his[10],1,0);
$pdf->Cell(17  ,6,$geo[10],1,0);
$pdf->Cell(17  ,6,$cre[10],1,0);
$pdf->Cell(17  ,6,$hsc[10],1,0);
$pdf->Cell(17  ,6,$ad_[10],1,0);
$pdf->Cell(17  ,6,$agr[10],1,0);
$pdf->Cell(17  ,6,$cps[10],1,0);
$pdf->Cell(17  ,6,$fre[10],1,0);
$pdf->Cell(17  ,6,$mus[10],1,0);
$pdf->Cell(17  ,6,$bst[10],1,0);


//11
$pdf->SetFont('Arial','','12');
$pdf->SetY(126);
$pdf->SetX(17);
$pdf->Cell(17  ,6,$eng[11],1,0);
$pdf->Cell(17  ,6,$kis[11],1,0);
$pdf->Cell(17  ,6,$mat[11],1,0);
$pdf->Cell(17  ,6,$bio[11],1,0);
$pdf->Cell(17  ,6,$phy[11],1,0);
$pdf->Cell(17  ,6,$che[11],1,0);
$pdf->Cell(17  ,6,$his[11],1,0);
$pdf->Cell(17  ,6,$geo[11],1,0);
$pdf->Cell(17  ,6,$cre[11],1,0);
$pdf->Cell(17  ,6,$hsc[11],1,0);
$pdf->Cell(17  ,6,$ad_[11],1,0);
$pdf->Cell(17  ,6,$agr[11],1,0);
$pdf->Cell(17  ,6,$cps[11],1,0);
$pdf->Cell(17  ,6,$fre[11],1,0);
$pdf->Cell(17  ,6,$mus[11],1,0);
$pdf->Cell(17  ,6,$bst[11],1,0);





$pdf->SetFont('Arial','B','12');
$pdf->SetY(60);
$pdf->SetX(5);
$pdf->Cell(12  ,6,'A',1,1);

$pdf->SetX(5);
$pdf->Cell(12  ,6,'A-',1,1);

$pdf->SetX(5);
$pdf->Cell(12  ,6,'B+',1,1);

$pdf->SetX(5);
$pdf->Cell(12  ,6,'B',1,1);

$pdf->SetX(5);
$pdf->Cell(12  ,6,'B-',1,1);

$pdf->SetX(5);
$pdf->Cell(12  ,6,'C+',1,1);

$pdf->SetX(5);
$pdf->Cell(12  ,6,'C',1,1);

$pdf->SetX(5);
$pdf->Cell(12  ,6,'C-',1,1);

$pdf->SetX(5);
$pdf->Cell(12  ,6,'D+',1,1);

$pdf->SetX(5);
$pdf->Cell(12  ,6,'D',1,1);

$pdf->SetX(5);
$pdf->Cell(12  ,6,'D-',1,1);

$pdf->SetX(5);
$pdf->Cell(12  ,6,'E',1,1);





$pdf->Output();
}
?>


