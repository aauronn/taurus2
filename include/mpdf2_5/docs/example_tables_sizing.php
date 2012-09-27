<?php


/* Example of Basic HTML Tags */

define('_MPDF_PATH','../');
include("../mpdf.php");
//
$timeo_start = microtime(true);
//

$html = '
<div style="border: 1px solid #000088; background-color: #DDDDFF; padding: 0mm; margin: 0;">

Table width:1080px
<table border="1" style="width:1080px">
<tr>
<td style="width:75%">width:75%</td>
<td>not set</td>
</tr>
</table>

Table width:1080px
<table border="1" style="width:1080px">
<tr>
<td style="width:810px">width:810px</td>
<td>not set</td>
</tr>
</table>

Table width:1080px
<table border="1" style="width:1080px">
<tr>
<td style="width:50%">width:50%</td>
<td>not set</td>
</tr>
</table>

Table width:1080px
<table border="1" style="width:1080px">
<tr>
<td>not set</td>
<td style="width:50%">width:50%</td>
</tr>
</table>

Table width:1080px
<table border="1" style="width:1080px">
<tr>
<td style="width:540px">width:540px</td>
<td>not set</td>
</tr>
</table>

Table width:1080px
<table border="1" style="width:1080px">
<tr>
<td style="width:37%">width:37%</td>
<td>not set</td>
</tr>
</table>

Table width:1080px
<table border="1" style="width:1080px">
<tr>
<td style="width:400px">width:400px</td>
<td>not set</td>
</tr>
</table>

Table width:not set
<table border="1">
<tr>
<td style="width:20%">width:20%</td>
<td>not set</td>
<td>not set</td>
</tr>
</table>

Table width:not set
<table border="1">
<tr>
<td style="width:80%">width:80%</td>
<td>not&nbsp;set</td>
<td>not&nbsp;set</td>
</tr>
</table>

Table width:not set
<table border="1">
<tr>
<td style="width:80%">width:80%</td>
<td>not set</td>
<td>not set</td>
</tr>
</table>

Table width:500px
<table border="1" style="width:500px">
<tr>
<td style="width:50%">width:50%</td>
<td>Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. Suspendisse potenti. Ut a eros at ligula vehicula pretium. Maecenas feugiat pede vel risus. Nulla et lectus. Fusce eleifend neque sit amet erat. Integer consectetuer nulla non orci. Morbi feugiat pulvinar dolor. Cras odio. Donec et nulla. Sed quis orci</td>
<td style="width:120px">width:120px</td>
</tr>
</table>

Table width:500px
<table border="1" style="width:500px">
<tr>
<td>not set</td>
<td style="width:50%">width:50%</td>
</tr>
</table>

Table width:500px
<table border="1" style="width:500px">
<tr>
<td style="width:250px">width:250px</td>
<td>not set</td>
</tr>
</table>

Table width:500px
<table border="1" style="width:500px">
<tr>
<td style="width:250px">width:250px</td>
<td style="width:250px">width:250px</td>
</tr>
</table>

Table width:500px
<table border="1" style="width:500px">
<tr>
<td>not set</td>
<td style="width:67%">width:67%</td>
</tr>
</table>

Table width:500px
<table border="1" style="width:500px">
<tr>
<td style="width:33%">width:33%</td>
<td>not set</td>
</tr>
</table>

Table width:500px
<table border="1" style="width:500px">
<tr>
<td style="width:165px">width:165px</td>
<td>not set</td>
</tr>
</table>

Table width:500px
<table border="1" style="width:500px">
<tr>
<td style="width:67%">width:67%</td>
<td>not set</td>
</tr>
</table>

Table width:500px
<table border="1" style="width:500px">
<tr>
<td>not set</td>
<td style="width:33%">width:33%</td>
</tr>
</table>

Table width:500px
<table border="1" style="width:500px">
<tr>
<td>not set</td>
<td style="width:165px">width:165px</td>
</tr>
</table>







<table style="width:1080px"><tr><td>

Table width:1080px

</td></tr><tr><td>

<table border="1" width="100%">
<tr>
<td style="width:75%">width:75%</td>
<td>not set</td>
</tr>
</table>

</td></tr><tr><td>

<table border="1" width="100%">
<tr>
<td style="width:810px">width:810px</td>
<td>not set</td>
</tr>
</table>

</td></tr><tr><td>

<table border="1" width="100%">
<tr>
<td style="width:50%">width:50%</td>
<td>not set</td>
</tr>
</table>

</td></tr><tr><td>

<table border="1" width="100%">
<tr>
<td>not set</td>
<td style="width:50%">width:50%</td>
</tr>
</table>

</td></tr><tr><td>

<table border="1" width="100%">
<tr>
<td style="width:540px">width:540px</td>
<td>not set</td>
</tr>
</table>

</td></tr><tr><td>

<table border="1" width="100%">
<tr>
<td style="width:37%">width:37%</td>
<td>not set</td>
</tr>
</table>

</td></tr><tr><td>

<table border="1" width="100%">
<tr>
<td style="width:400px">width:400px</td>
<td>not set</td>
</tr>
</table>

</td></tr></table><table><tr><td>

Table width:not set

</td></tr><tr><td>

<table border="1" width="100%">
<tr>
<td style="width:20%">width:20%</td>
<td>not set</td>
<td>not set</td>
</tr>
</table>

</td></tr><tr><td>

<table border="1" width="100%">
<tr>
<td style="width:80%">width:80%</td>
<td>not&nbsp;set</td>
<td>not&nbsp;set</td>
</tr>
</table>

</td></tr><tr><td>

<table border="1" width="100%">
<tr>
<td style="width:80%">width:80%</td>
<td>not set</td>
<td>not set</td>
</tr>
</table>

</td></tr></table><table style="width:500px"><tr><td>

Table width:500px

</td></tr><tr><td>

<table border="1" width="100%">
<tr>
<td style="width:50%">width:50%</td>
<td>Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. Suspendisse potenti. Ut a eros at ligula vehicula pretium. Maecenas feugiat pede vel risus. Nulla et lectus. Fusce eleifend neque sit amet erat. Integer consectetuer nulla non orci. Morbi feugiat pulvinar dolor. Cras odio. Donec et nulla. Sed quis orci</td>
<td style="width:120px">width:120px</td>
</tr>
</table>

</td></tr><tr><td>

<table border="1" width="100%">
<tr>
<td>not set</td>
<td style="width:50%">width:50%</td>
</tr>
</table>

</td></tr><tr><td>

<table border="1" width="100%">
<tr>
<td style="width:250px">width:250px</td>
<td>not set</td>
</tr>
</table>

</td></tr><tr><td>

<table border="1" width="100%">
<tr>
<td style="width:250px">width:250px</td>
<td style="width:250px">width:250px</td>
</tr>
</table>

</td></tr><tr><td>

<table border="1" width="100%">
<tr>
<td>not set</td>
<td style="width:67%">width:67%</td>
</tr>
</table>

</td></tr><tr><td>

<table border="1" width="100%">
<tr>
<td style="width:33%">width:33%</td>
<td>not set</td>
</tr>
</table>

</td></tr><tr><td>

<table border="1" width="100%">
<tr>
<td style="width:165px">width:165px</td>
<td>not set</td>
</tr>
</table>

</td></tr><tr><td>

<table border="1" width="100%">
<tr>
<td style="width:67%">width:67%</td>
<td>not set</td>
</tr>
</table>

</td></tr><tr><td>

<table border="1" width="100%">
<tr>
<td>not set</td>
<td style="width:33%">width:33%</td>
</tr>
</table>

</td></tr><tr><td>

<table border="1" width="100%">
<tr>
<td>not set</td>
<td style="width:165px">width:165px</td>
</tr>
</table>



</td></tr>
</table>




</div>
';


$style = '
<style>
table {
	border-collapse: separate;
	border: 4px solid #880000;
	padding: 4px;
	margin: 0px 20px 0px 20px;
	empty-cells: hide;
	background-color:#FFFFCC;
}
table.outer2 {
	border-collapse: separate;
	border: 4px solid #088000;
	padding: 3px;
	margin: 10px 0px;
	empty-cells: hide;
	background-color: yellow;
}
table.outer2 td {
	font-family: Times;
}
table.inner {
	border-collapse: collapse;
	border: 2px solid #000088;
	padding: 3px;
	margin: 5px;
	empty-cells: show;
	background-color:#FFCCFF;
}
td {
	border: 1px solid #008800;
	padding: 0px;

	background-color:#ECFFDF;
}
table.inner td {
	border: 1px solid #000088;
	padding: 0px;
	font-family: monospace;
	font-style: italic;
	font-weight: bold;
	color: #880000;
	background-color:#FFECDF;
}
table.collapsed {
	border-collapse: collapse;
}
table.collapsed td {
	background-color:#EDFCFF;
}
table td.selected {
	font-weight: bold;
	color: #880000;
	background-color:#AAAAFF;
}


</style>
';

$html2 = '
<h1>mPDF</h1>
<h2>Tables - Nested</h2>


<div style="border: 2px solid #000088; background-color: #DDDDFF; padding: 2mm;">

<p>Text before table</p>

<table cellSpacing="2" class="outer2" autosize="3" style="page-break-inside:avoid">
<tbody>
<tr>
<td class="selected" style="width:20%">w=20%</td>
<td>Row 2</td>
<td>

<table cellSpacing="2" class="inner">
<tbody>
<tr>
<td>Row A</td>
<td>A2</td>
<td>A3</td>
<td>A4</td>
</tr>

<tr>
<td>Row B</td>
<td>B2</td>
<td>B3</td>
<td>B4</td>
</tr>

<tr>
<td>Row C</td>
<td>C2</td>
<td>C3</td>
<td>C4</td>
</tr>

<tr>
<td>Row D</td>
<td>D2</td>
<td>D3</td>
<td>D4</td>
</tr>

</tbody></table>


</td>
<td>This is data</td>
</tr>

<tr>
<td>Row 2</td>
<td>This is data</td>
<td>This is data</td>
<td>This is data</td>
</tr>

<tr>
<td>Row 3</td>
<td>

<table cellSpacing="2" class="inner">
<tbody>
<tr>
<td class="selected" style="width:25%">w=25%</td>
<td class="selected" style="width:2cm">w=2cm</td>
<td>A3</td>
<td>A4</td>
</tr>

<tr>
<td>Row B</td>
<td>B2</td>
<td style="text-align:center;"><img src="sunset.jpg" width="84" style="border:3px solid #44FF44; vertical-align:top; " /></td>
<td>B4</td>
</tr>

<tr>
<td>Row C</td>
<td>C2</td>
<td>

<table cellSpacing="2">
<tbody>
<tr>
<td>F1</td>
<td>F2</td>
</tr>
<tr>
<td>Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. Suspendisse potenti. Ut a eros at ligula vehicula pretium. Maecenas feugiat pede vel risus. Nulla et lectus. Fusce eleifend neque sit amet erat. Integer consectetuer nulla non orci. Morbi feugiat pulvinar dolor. Cras odio. Donec et nulla. Sed quis orci.</td>
<td>G2</td>
</tr>
</tbody></table>

</td>
<td>C4</td>
</tr>

<tr>
<td>Row D</td>
<td>D2</td>
<td>D3</td>
<td>D4</td>
</tr>

</tbody></table>


</td>
<td style="vertical-align: bottom; text-align: right;">
<table cellSpacing="2" class="inner">
<tbody>
<tr>
<td>Row A</td>
<td>A2</td>
<td>A3</td>
<td>A4</td>
</tr>

<tr>
<td>Row B</td>
<td>B2</td>
<td>B3</td>
<td>B4</td>
</tr>

<tr>
<td>Row C</td>
<td>C2</td>
<td>C3</td>
<td>C4</td>
</tr>

<tr>
<td>Row D</td>
<td>D2</td>
<td>D3</td>
<td>D4</td>
</tr>

</tbody></table>
</td>
<td>This is data</td>
</tr>

<tr>
<td>Row 4</td>
<td>This is data</td>
<td><table cellSpacing="2" class="inner">
<tbody>
<tr>
<td>Row A</td>
<td>A2</td>
<td>A3</td>
<td>A4</td>
</tr>

<tr>
<td>Row B</td>
<td>B2</td>
<td style="text-align:center;"><img src="sunset.jpg" width="84" style="border:3px solid #44FF44; vertical-align:top; " /></td>
<td>B4</td>
</tr>

<tr>
<td>Row C</td>
<td>C2</td>
<td>

<table cellSpacing="2">
<tbody>
<tr>
<td>F1</td>
<td>F2</td>
</tr>
<tr>
<td>Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. Suspendisse potenti. Ut a eros at ligula vehicula pretium. Maecenas feugiat pede vel risus. Nulla et lectus. Fusce eleifend neque sit amet erat. Integer consectetuer nulla non orci. Morbi feugiat pulvinar dolor. Cras odio. Donec et nulla. Sed quis orci.</td>
<td>G2</td>
</tr>
</tbody></table>

</td>
<td>C4</td>
</tr>

<tr>
<td>Row D</td>
<td>D2</td>
<td>D3</td>
<td>D4</td>
</tr>

</tbody></table>

</td>
<td>This is data</td>
</tr>


</tbody></table>


</div>
';




//==============================================================
//==============================================================
//==============================================================
if ($_REQUEST['html']) { echo '<html><head><style>'.file_get_contents('mpdfstyletables.css').'</style></head><body>'.$style.$html.$html2.'</body></html>'; exit; }

if ($_REQUEST['source']) { 
	$file = __FILE__;
	header("Content-Type: text/plain");
	header("Content-Length: ". filesize($file));
	header("Content-Disposition: attachment; filename='".$file."'");
	readfile($file);
	exit; 
}
//==============================================================
//==============================================================
//==============================================================

$mpdf=new mPDF('en-GB','A4','','',32,25,27,25,16,13); 

	$mpdf->use_embeddedfonts_1252 = true;	// false is default

	$mpdf->SetDisplayMode('fullpage');

	$mpdf->SetTitle('mPDF Example - HTML');

	$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list

	// LOAD a stylesheet
	$stylesheet = file_get_contents('mpdfstyletables.css');
	$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text
	$mpdf->WriteHTML($style);


	$mpdf->keep_table_proportions = true;
	$mpdf->WriteHTML($html);
	$mpdf->keep_table_proportions = false;
	$mpdf->WriteHTML($html);

	$mpdf->WriteHTML('<pagebreak />');

	$mpdf->keep_table_proportions = true;
	$mpdf->WriteHTML($html2);
	$mpdf->keep_table_proportions = false;
	$mpdf->WriteHTML($html2);
	$mpdf->ignore_table_widths = true;
	$mpdf->WriteHTML($html2);

	$mpdf->WriteHTML('<div class="infobox">Generated in '.sprintf('%.2f',(microtime(true) - $timeo_start)).' seconds</div>',2);

	$mpdf->Output('mpdf.pdf','I');
	exit;


?>