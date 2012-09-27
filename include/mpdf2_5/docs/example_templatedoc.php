<?php
// just require TCPDF instead of FPDF
define('_MPDF_PATH','../');
include("../mpdf.php");
//===================================================
//===================================================
include(_MPDF_PATH."mpdfi/mpdfi.php");

// initiate PDF
$mpdf=new mPDFI('','','','',15,15,57,16,9,9); 

//===================================================

// Add First page
//$mpdf->AddPage();
//$mpdf->Image('sunset.gif',0,0,210,297,'gif','',true, false);	// e.g. the last "false" allows a full page picture

//$pagecount = $mpdf->SetSourceFile('z_logoheader.pdf');
//$tplIdx = $mpdf->ImportPage(1);
//$mpdf->SetPageTemplate($tplIdx);	// Used (in Header) for any subsequent pages   SetPageTemplate() to turn off

$mpdf->SetDocTemplate('z_logoheader.pdf',1);	// 1|0 to continue after end of document or not - used on matching page numbers

//===================================================
$mpdf->AddPage();


$mpdf->WriteHTML('Hallo World');



$mpdf->AddPage();

//===================================================


$mpdf->WriteHTML('Hallo World');
$mpdf->AddPage();

//===================================================


$mpdf->WriteHTML('Hallo World');
$mpdf->AddPage();

//===================================================


$mpdf->WriteHTML('Hallo World');




$mpdf->Output('newpdf.pdf', 'I');

exit;


?>