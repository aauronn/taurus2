<?php



$html = '
<style>
img { margin: 0.3em; }
table { border-collapse: collapse; }
</style>
<h1>mPDF</h1>
<h2>Images</h2>

<p>When printing in-line images, the whole line is vertically aligned to to the biggest image. This includes the top/bottom margin of the image. In the original mPDF v1.x this was set to 0.2em; by chance this neatly aligned the text to look as though it was perfectly inline with the bottom or top of the image. The default setting in v2 is 0.5em and the alignment is not so good.<br />
This example sets a CSS style of img { margin: 0.3em; }
</p>

Image out of block
<img src="sunset.jpg" width="226" />
Followed by a line.

<p>Image in a separate DIV</p>
<div align="center">Some text before the image <img src="sunset.jpg" width="226" /> and text after it</div>
<p>Followed by a line.</p>

<div align="center">Image in-line in a DIV<img style="vertical-align: top" src="sunset.jpg" width="226px" />Followed by a line</div>

Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. <a href="http://www.bpm1.com">Maecenas consectetuer eros quis massa</a>. Mauris semper velit vehicula purus. Duis lacus. Aenean pretium consectetuer mauris. <span style="font-family:monospace; font-size:7pt; color:#880000; font-weight:bold; background-color:#FFDDCC;">Ut purus sem, consequat ut, fermentum sit amet, ornare sit amet, ipsum. Donec non nunc.</span> Maecenas fringilla. Curabitur libero. In dui massa, malesuada sit amet, hendrerit vitae, viverra nec, tortor. Donec varius. Ut ut dolor et tellus adipiscing

<h4>Automatic Column Width</h4>
<table border="1" style="font-size:9pt; line-height:1.5; padding:8px; background-color:#CCDDFF; text-align:justify"><tbody>
<tr>
<td>Causes</td>
<td>Ut magna ipsum, tempus in, condimentum at, rutrum et, nisl. Vestibulum interdum luctus sapien. Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. <a href="http://www.bpm1.com">Maecenas consectetuer eros quis massa</a>. Mauris semper velit vehicula purus. Duis lacus. Aenean pretium consectetuer mauris. <span style="font-family:monospace; font-size:7pt; color:#880000; font-weight:bold; background-color:#FFDDCC;">Ut purus sem, consequat ut, fermentum sit amet, ornare sit amet, ipsum. Donec non nunc.</span> Maecenas fringilla. Curabitur libero. In dui massa, malesuada sit amet, hendrerit vitae, viverra nec, tortor. Donec varius. Ut ut dolor et tellus adipiscing.</td>
</tr>
<tr>
<td>Image</td>
<td><img src="sunset.jpg" width="226" /></td>
</tr>
<tr>
<td>Image</td>
<td><img src="sunset.jpg" width="226" /> <img src="sunset.jpg" width="226" /><br />
<img src="sunset.jpg" width="226" /> <img src="sunset.jpg" width="226" /></td>
</tr>
</tbody></table>





<p style="text-indent:30px;">Indented paragraph<br />
followed by a new line</p>

<p style="background-color:#FFCCEE; border:7px solid #880000; margin:0px; padding:10px;">Text here <img src="sunset.jpg" width="113" alt="NO-IMAGE" style="vertical-align:bottom; "/>Text here <img src="sunset.jpg" width="170" style="border:3px solid #44FF44; vertical-align:middle; " alt="NO-IMAGE"/>Text here Text here Text here <br />

<a href="http://www.bpm1.com"><img src="sunset.jpg" width="226" style="border:22px solid #44FF44;" /></a>
Text here <br />

Text here <br />
Text here <img src="sunset.jpg" height="94" />
Text here by <img src="sunset.jpg" height="94" />Text
<img src="sunset.jpg" width="170" style="border:3px solid #44FF44; vertical-align:top; " alt="NO-IMAGE"/>SS

Text here <br />
Text here <img src="sunset.jpg" height="94" />
Text here by <img src="sunset.jpg" height="94" />Text here
<img src="sunset.jpg" width="170" style="border:3px solid #44FF44; vertical-align:top; " alt="NO-IMAGE"/>SS
</p>


<hr />
<hr />
<br />
<br />
<br />
<br />

<p style="text-align:justify; background-color:#FFCCEE; border:7px dashed #880000; margin:0px; padding:10px;">Text here <img src="sunset.jpg" width="100" alt="NO-IMAGE" style="vertical-align:bottom; "/>Text here <img src="sunset.jpg" width="200" style="border:3px solid #44FF44; vertical-align:middle; " alt="NO-IMAGE"/>Text here Text here Text here 

<a href="http://www.bpm1.com"><img src="sunset.jpg" width="226" style="border:22px solid #44FF44;" /></a>
Text here 

Text here 
Text here<img src="sunset.jpg" height="94" />
Text here by <img src="sunset.jpg" height="94" />Text
<img src="sunset.jpg" width="170" style="border:3px solid #44FF44; vertical-align:top; " alt="NO-IMAGE"/>SS

Text here 
Text here <img src="sunset.jpg" height="94" />
Text here by <img src="sunset.jpg" height="94" />Text here
<img src="sunset.jpg" width="170" style="border:3px solid #44FF44; vertical-align:top; " alt="NO-IMAGE"/>SS
</p>

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

$mpdf=new mPDF(); 

$mpdf->use_embeddedfonts_1252 = true;	// false is default
$mpdf->use_kwt = true;	// Keeps the heading with the table on the 2nd page (kwt = keep-with-table)

$mpdf->WriteHTML($html);

$mpdf->Output('mpdf.pdf','I');
exit;
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================


?>