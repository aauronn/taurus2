<?php

define('_MPDF_PATH','../');
include("../mpdf.php");
//===================================================
//===================================================
include(_MPDF_PATH."mpdfi/mpdfi.php");

// initiate PDF
$mpdf=new mPDFI('win-1252'); 


//===================================================

$mpdf->Thumbnail('z_orientation2.pdf', 4, 5);	// number per row	// spacing in mm

$mpdf->WriteHTML('<pagebreak /><div>Now with rotated pages</div>');

$mpdf->Thumbnail('z_orientation3.pdf', 4);	// number per row	// spacing in mm


//===================================================
//===================================================


$mpdf->Output();

exit;


?>