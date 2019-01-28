<?php
require('linegraph.php');

$pdf = new PDF_LineGraph();
$pdf->SetFont('Arial','',8);

$data = array(
	'Group' => array(
		'F1T1' => 10,
		'F1T2' => 8,
		'F1T3' => 6,
		'F2T1' => 4,
		'F2T2' => 9,
		'F2T3' => 7.5,
		'F3T1' => 11,
		'F3T2' => 10.5,
		'F3T3' => 9,
		'F4T1' => 8,
		'F4T2' => 10,
		'F4T3' => 10
	)
);
$colors = array(
	'Group' => array(114,171,237),
	
);

$pdf->AddPage();

$pdf->LineGraph(160,60,$data,'VHgBdB',null,12,6);

$pdf1->Output();

?>