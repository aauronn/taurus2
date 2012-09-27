<?php



$html = '

<h1>mPDF</h1>
<p>When using $mpdf-&gt;WriteHTML you should usually write complete block elements in one go. Any block elements (div, p etc) will be closed at the end of writeHTML segment.<br />
In the first example on this page, the html chunk is written in one go; in the second it is split...</p>
<p>On the second page, the initialisation and closing parameters of $mpdf-&gt;WriteHTML() are used to "carry over"...</p>
<div style="border:1px solid #880000; margin:10pt; text-align:justify; padding:14pt; background-color:#ffaacc;">
This is text in a div.
<div style="border:1px dotted #880000; margin:10pt; text-align:justify; padding:7pt; background-color:#ffffcc; font-family:serif; font-style:italic; color:#880000; ">
Text in a second level div.
<p style="text-indent:20px; margin:5pt; border:1px dashed #000088; padding:20pt; text-align:justify; background-color:#ddffff;"><b>This is a p inside the div.</b> Praesent pharetra nulla in turpis. Phasellus tincidunt ligula non pede. Morbi turpis. Aliquam mattis. </p>
<i>Text again inside the div.</i> Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo. Mauris adipiscing congue ante. Proin at erat. Aliquam mattis.
</div>
In the last level div.
</div>
And now here it is split...
<div style="border:1px solid #880000; margin:10pt; text-align:justify; padding:14pt; background-color:#ffaacc;">
This is text in a div.
<div style="border:1px dotted #880000; margin:10pt; text-align:justify; padding:7pt; background-color:#ffffcc; font-family:serif; font-style:italic; color:#880000; ">
Text in a second level div.
<p style="text-indent:20px; margin:5pt; border:1px dashed #000088; padding:20pt; text-align:justify; background-color:#ddffff;"><b>This is a p inside the div.</b> Praesent pharetra nulla in turpis.
';

$html2 = 'Phasellus tincidunt ligula non pede. Morbi turpis. Aliquam mattis. </p>
<i>Text again inside the div.</i> Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo. Mauris adipiscing congue ante. Proin at erat. Aliquam mattis.
</div>
In the last level div.
</div>

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
	exit; 
}
//==============================================================
//==============================================================
//==============================================================

define('_MPDF_PATH','../');
include("../mpdf.php");

	$mpdf=new mPDF('en-GB','A4','','',32,25,27,25,16,13); 

	$mpdf->SetDisplayMode('fullpage');
	$mpdf->use_embeddedfonts_1252 = true;

	$mpdf->useOddEven = 1;	// Use different Odd/Even headers and footers and mirror margins (1 or 0)

	$mpdf->WriteHTML($html,2);
	$mpdf->WriteHTML($html2,2);

	$mpdf->WriteHTML($html,2,true,false);
	$mpdf->WriteHTML($html2,2,false, true);

	$mpdf->Output('mpdf.pdf','I');
	exit;
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================


?>