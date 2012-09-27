<?php


//==============================================================
$html = '
<style>
td {
	padding: 3mm;
	border: 1px solid #880000;
}
</style>

<p>This table has padding-top and -bottom set to 3mm i.e. padding within the cells. Also background-, border colour and style, font family and size are set by in-line <acronym>CSS</acronym>.</p>
<table rotate="90" align="right" style="background-color: #BBCCDD; font-family: serif; font-size:11pt ">
<tbody>
<tr>
<td>Row 1</td>
<td><img src="sunset.jpg" width="300" hspace="10" vspace="10" style="border:12px solid #44FF44;" /></td>
<td style="vertical-align:bottom; text-align: right;">Bottom right: <img src="sunset.jpg" width="87" alt="NO-IMAGE" style="vertical-align:bottom; "/></td>
</tr>
<tr>
<td>Some text first<br /><img src="sunset.jpg" width="208" style="border:3px solid #44FF44; vertical-align:middle; " alt="NO-IMAGE"/><br />and some after</td>
<td><p>This is data p</p></td>
<td><p><img src="sunset.jpg" width="380" /></p></td>
</tr>
<tr>
<td><p>Row 3</p></td>
<td><p><img src="sunset.jpg" width="284" style="border:3px solid #44FF44; vertical-align:top; " alt="NO-IMAGE"/></p></td>
<td>This is data</td>
</tr>
</tbody></table>
';

//==============================================================
//==============================================================
//==============================================================
if ($_REQUEST['html']) { echo $html; exit; }
if ($_REQUEST['source']) { 
	$file = __FILE__;
	header("Content-Type: text/plain");
	header("Content-Length: ". filesize($file));
	header("Content-Disposition: attachment; filename='".$file."'");
	readfile($file);
}
//==============================================================
//==============================================================
//==============================================================

define('_MPDF_PATH','../');
include("../mpdf.php");

$mpdf=new mPDF(); 

$mpdf->use_embeddedfonts_1252 = true;	// false is default

$mpdf->WriteHTML($html);

$mpdf->Output('mpdf.pdf','I');
exit;
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================


?>