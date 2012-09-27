<?php
// just require TCPDF instead of FPDF
define('_MPDF_PATH','../');
include("../mpdf.php");
//===================================================
//===================================================
include(_MPDF_PATH."mpdfi/mpdfi.php");

// initiate PDF
$mpdf=new mPDFI('','','','',15,15,57,16,9,9); 

$mpdf->SetDisplayMode('fullpage');

//$mpdf->WriteHTML('Hallo World');
//===================================================
$mpdf->SetCompression(false);

// Add First page
$pagecount = $mpdf->SetSourceFile('example_basic.pdf');

$crop_x = 50;
$crop_y = 50;
$crop_w = 100;
$crop_h = 100;

$tplIdx = $mpdf->ImportPage(2, $crop_x, $crop_y, $crop_w, $crop_h);

$x = 50;
$y = 50;
$w = 100;
$h = 100;

$mpdf->UseTemplate($tplIdx, $x, $y, $w, $h);

$mpdf->Rect($x, $y, $w, $h);


//===================================================



$mpdf->Output('newpdf.pdf', 'I');

exit;


?>