<?php
//require('functions/fpdf/fpdf.php');

//$pdf = new FPDF('P','mm','A4'); //use new class
//$pdf->AddPage();

//			chart properties
//position
$chartX=10;
$chartY=188;

//dimension
$chartWidth=110;
$chartHeight=60;

//padding
$chartTopPadding=7;
$chartLeftPadding=10;
$chartBottomPadding=10;
$chartRightPadding=5;

//chart box
$chartBoxX=$chartX+$chartLeftPadding;
$chartBoxY=$chartY+$chartTopPadding;
$chartBoxWidth=$chartWidth-$chartLeftPadding-$chartRightPadding;
$chartBoxHeight=$chartHeight-$chartBottomPadding-$chartTopPadding;

//bar width
$barWidth=5;


//validating graph input
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
//validating graph input


//chart data
$data=Array(
	
	'f1t1'=>[
		'color'=>[255,0,0],
		'value'=> $f1t1p ],
	'f1t2'=>[
		'color'=>[255,255,0],
		'value'=> $f1t2p],
	'f1t3'=>[
		'color'=>[50,0,255],
		'value'=> $f1t3p],
	'f2t1'=>[
		'color'=>[255,0,255],
		'value'=>$f2t1p],
	'f2t2'=>[
		'color'=>[0,255,0],
		'value'=>$f2t2p],
	'f2t3'=>[
		'color'=>[0,255,0],
		'value'=>$f2t3p],
	'f3t1'=>[
		'color'=>[0,255,0],
		'value'=>$f3t1p],
	'f3t2'=>[
		'color'=>[0,255,0],
		'value'=>$f3t2p],
	'f3t3'=>[
		'color'=>[0,255,0],
		'value'=>$f3t3p],
	'f4t1'=>[
		'color'=>[0,255,0],
		'value'=>$f4t1p],
	'f4t2'=>[
		'color'=>[0,255,0],
		'value'=>$f4t2p],
	'f4t3'=>[
		'color'=>[0,255,0],
		'value'=>$f4t3p],
	'max'=>[
		'color'=>[255,5,5],
		'value'=> 12 ]						
	);

//$dataMax
$dataMax=0;
foreach($data as $item){
	if($item['value']>$dataMax)$dataMax=$item['value'];
}

//data step
$dataStep=1;

//set font, line width and color
$pdf->SetFont('Arial','',7);
$pdf->SetLineWidth(0.2);
$pdf->SetDrawColor(0);

//chart boundary
$pdf->Rect($chartX,$chartY,$chartWidth,$chartHeight);

//vertical axis line
$pdf->Line(
	$chartBoxX ,
	$chartBoxY , 
	$chartBoxX , 
	($chartBoxY+$chartBoxHeight)
	);
//horizontal axis line
$pdf->Line(
	$chartBoxX-2 , 
	($chartBoxY+$chartBoxHeight) , 
	$chartBoxX+($chartBoxWidth) , 
	($chartBoxY+$chartBoxHeight)
	);

///vertical axis
//calculate chart's y axis scale unit
$yAxisUnits=$chartBoxHeight/$dataMax;

//draw the vertical (y) axis labels
for($i=0 ; $i<=$dataMax ; $i+=$dataStep){
	//y position
	$yAxisPos=$chartBoxY+($yAxisUnits*$i);
	//draw y axis line
	$pdf->Line(
		$chartBoxX+100 ,
		$yAxisPos ,
		$chartBoxX ,
		$yAxisPos
	);
	$pdf->Line(
		$chartBoxX-5 ,
		$yAxisPos ,
		$chartBoxX ,
		$yAxisPos
	);
	//set cell position for y axis labels
	$pdf->SetXY($chartBoxX-$chartLeftPadding , $yAxisPos-2);
	//$pdf->Cell($chartLeftPadding-4 , 5 , $dataMax-$i , 1);---------------
	$pdf->Cell($chartLeftPadding-4 , 5 , $dataMax-$i, 0 , 0 , 'R');
}

///horizontal axis
//set cells position
$pdf->SetXY($chartBoxX , $chartBoxY+$chartBoxHeight);

//cell's width
$xLabelWidth=$chartBoxWidth / count($data);

//$pdf->Cell($xLabelWidth , 5 , $itemName , 1 , 0 , 'C');-------------
//loop horizontal axis and draw the bar
$barXPos=0;
foreach($data as $itemName=>$item){
	//print the label
	//$pdf->Cell($xLabelWidth , 5 , $itemName , 1 , 0 , 'C');--------------
	$pdf->Cell($xLabelWidth , 5 , $itemName , 0 , 0 , 'C');
	
	///drawing the bar
	//bar color
	$pdf->SetFillColor($item['color'][0],$item['color'][1],$item['color'][2]);
	//bar height
	$barHeight=$yAxisUnits*$item['value'];
	//bar x position
	$barX=($xLabelWidth/2)+($xLabelWidth*$barXPos);
	$barX=$barX-($barWidth/2);
	$barX=$barX+$chartBoxX;
	//bar y position
	$barY=$chartBoxHeight-$barHeight;
	$barY=$barY+$chartBoxY;
	//draw the bar
	$pdf->Rect($barX,$barY,$barWidth,$barHeight,'DF');
	//increase x position (next series)
	$barXPos++;
}

//axis labels
$pdf->SetFont('Arial','B',9);
$pdf->SetXY($chartX,$chartY);
$pdf->Cell(100,10,"Pts",0);
$pdf->SetXY(($chartWidth/2)-50+$chartX,$chartY+$chartHeight-($chartBottomPadding/2));
$pdf->Cell(100,5,"Term",0,0,'C');
$pdf->SetFont('Arial','',7);
$pdf->Cell(-6,1,"points",0,0,'C');
//$pdf->Cell(-100,15,"KEY->POINTS[GRADE] 12[A], 11[A-] , 10[B+] , 9[B] , 8[B-] , 7[C+] , 6[C] , 5[C-] , 4[D+] , 3[D] , 2[D-] , 1[E]",0,0,'C');


//$pdf->Output();