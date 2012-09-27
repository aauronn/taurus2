<?php
//
$timeo_start = microtime(true);
//

$html = '
<h1>mPDF</h1>
<h2>Lists</h2>
';

$html .= '
<style>
ol, ul { text-align: justify; 
}

.lista { list-style-type: upper-roman; }
.listb{ list-style-type: decimal; font-family: sans-serif; color: blue; font-weight: bold; font-style: italic; font-size: 19pt; }
.listc{ list-style-type: upper-alpha; text-indent: 25mm; }
.listd{ list-style-type: lower-alpha; color: teal; line-height: 2; }
.liste{ list-style-type: disc; }
</style>



<div style="background-color:#ddccff; padding:0pt; border: 1px solid #555555;">
<ol class="lista">
<li>Text here lorem ipsum ibisque totum.</li>
<li><span style="color:green; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">Text here lorem ipsum ibisque totum.</span></li>
<li style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum. Text here lorem ipsum ibisque totum. Text here lorem ipsum ibisque totum. Text here lorem ipsum ibisque totum. Text here lorem ipsum ibisque totum. Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.
<ol class="listb">
<li>Text here lorem ipsum ibisque totum.</li>
<li><span style="color:green; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">Text here lorem ipsum ibisque totum.</span></li>
<li style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem <span style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">ipsum</span> ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">Text here lorem ipsum ibisque totum.
<ol class="listc">
<li>Big text indent 25mm: Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem <span style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">ipsum</span> ibisque totum.
</li>
<li>Text here lorem ipsum ibisque totum.
<ol class="listd">
<li>Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem <span style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">ipsum</span> ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.
<ol class="liste">
<li>Text here lorem ipsum ibisque totum.</li>
<li style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem <span style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">ipsum</span> ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
</ol>
</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem <span style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">ipsum</span> ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
</ol>
</li>
<li>Text here lorem ipsum ibisque totum.</li>
</ol>
</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem <span style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">ipsum</span> ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
</ol>
</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.
<ol class="listc">
<li>Big text indent 25mm: Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem <span style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">ipsum</span> ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.
<ol class="listd">
<li>Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem <span style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">ipsum</span> ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">Text here lorem ipsum ibisque totum.
<ol class="liste">
<li>Text here lorem ipsum ibisque totum.</li>
<li style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem <span style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">ipsum</span> ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
</ol>
</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem <span style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">ipsum</span> ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.
<ol>
<li>No class specified. Text here lorem ipsum ibisque totum.</li>
<li style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem <span style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">ipsum</span> ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
</ol>
</li>
</ol>
</li>
</ol>
</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem <span style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">ipsum</span> ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
</ol>
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

$mpdf=new mPDF(); 

$mpdf->use_embeddedfonts_1252 = true;	// false is default


$mpdf->WriteHTML($html);

$mpdf->list_align_style = 'L';	// Determines alignment of numbers in numbered lists
$mpdf->list_number_suffix = ')';

$mpdf->WriteHTML($html);

$mpdf->WriteHTML('<div class="infobox">Generated in '.sprintf('%.2f',(microtime(true) - $timeo_start)).' seconds</div>');

$mpdf->Output();

exit;
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================


?>