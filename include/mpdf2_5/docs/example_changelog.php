<?php



$html = '
<h1>mPDF</h1>
<h2>ChangeLog</h2>
<div style="border:1px solid #555555; background-color: #DDDDDD; padding: 1em; font-size:9pt; font-family:mono;">
';

$text = file_get_contents('../CHANGELOG.txt');

$html .= '<pre>'.$text.'</pre>';
$html .= '</div>';

//==============================================================
//==============================================================

define('_MPDF_PATH','../');
include("../mpdf.php");

$mpdf=new mPDF(); 

$mpdf->tabSpaces = 6;
$mpdf->use_embeddedfonts_1252 = true;

$mpdf->allow_charset_conversion=true;
$mpdf->charset_in='windows-1252';

$mpdf->WriteHTML($html);


$mpdf->Output('mpdf.pdf','I');
exit;
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================


?>