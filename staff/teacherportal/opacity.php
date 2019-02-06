<?php
require('opacity_function.php');

$pdf = new AlphaPDF();
$pdf->AddPage();
//$pdf->SetLineWidth(1.5);



// set alpha to semi-transparency
$pdf->SetAlpha(0.2);



// draw jpeg image
$pdf->Image('functions/fpdf/keveye.png',5,33,200);


// restore full opacity
$pdf->SetAlpha(1);




?>
