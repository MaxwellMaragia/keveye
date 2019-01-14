<?php
require('fpdf/fpdf.php');


$pdf = new FPDF('P','mm','A4');
$pdf->AddPage();

//set font to arial,bold,14pt
$pdf->SetFont('Arial','B','12');

//cella(width,height,text,border,endline,[align])
//LOGO
$pdf->Image("keveye.png",8,2,25,25);

//headers


$pdf->SetX(34);
$pdf->Cell(130  ,1,'FRIENDS SCHOOL KEVEYE GIRLS',0,1);
$pdf->SetFont('Arial','BI','9');
$pdf->SetX(34);
$pdf->Cell(130  ,16,'P.o Box 865 MARAGOLI.',0,1);
$pdf->SetY(22);
$pdf->SetX(34);
$pdf->Cell(15,2,'keveyegirls@yahoo.com.',0,1);
$pdf->SetX(90);
$pdf->Cell(130,-11,'Tel:  0707174835',0,1);
$pdf->SetFont('Arial','b','12');
$pdf->SetX(72);
$pdf->Cell(130 ,32,'DETERMINED TO EXCEL',0,1);
$pdf->SetY(28);
$pdf->SetX(58);
$pdf->Cell(130 ,16,'TERM TWO ACADEMIC REPORT 2018',0,1);

$pdf->SetFont('Arial','b','8');
$pdf->Rect(5,42,200,6,'D');
$pdf->Cell(15  ,2,'NAMES:',0,0);
$pdf->SetFont('Arial','','9');
$pdf->Cell(15  ,2,'Jean Naholi Adhiambo',0,0);
$pdf->SetFont('Arial','b','8');
$pdf->SetX(60);
$pdf->Cell(15  ,2,'ADM.NO:',0,0);
$pdf->SetFont('Arial','','9');
$pdf->Cell(15  ,2,'4098',0,0);
$pdf->Rect(5,48,200,6,'D');
$pdf->SetFont('Arial','b','8');
$pdf->SetY(50);
$pdf->Cell(15  ,2,'NAMES:',0,0);
$pdf->SetFont('Arial','','9');
$pdf->Cell(15  ,2,'Jean Naholi Adhiambo',0,0);







$pdf->Output();
?>