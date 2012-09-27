<?php

//
$timeo_start = microtime(true);
//


$html = '
<h1>mPDF</h1>
<h2>Basic Example</h2>

<div style="border:2px dashed #880000; padding:3px; margin-left:20pt;margin-right:20pt; margin-bottom:10pt; text-align:center; background-color:#eeddcc;"><p>This is <s>strikethrough</s> in <b><s>block</s></b> and <small>small <s>strikethrough</s> in <i>small span</i></small> and <big>big <s>strikethrough</s> in big span</big> and then <u>underline</u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</p>
<p>This is a <font color="#008800">green reference<sup>32-47</sup></font> and <u>underlined reference<sup>32-47</sup></u> then reference<sub>32-47</sub> and <u>underlined reference<sub>32-47</sub></u> then <s>Strikethrough reference<sup>32-47</sup></s> and <s>strikethrough reference<sub>32-47</sub></s></p> 
<p style="font-size:28pt">Repeated in <u>BIG</u>: This is reference<sup>32-47</sup> and <u>underlined reference<sup>32-47</sup></u> then reference<sub>32-47</sub> and <u>underlined reference<sub>32-47</sub></u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</p> 
<p style="font-size:6pt">Repeated in small: This is reference<sup>32-47</sup> and <u>underlined reference<sup>32-47</sup></u> then reference<sub>32-47</sub> and <u>underlined reference<sub>32-47</sub></u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text
</p></div>

<p>The above repeated, but starting with a paragraph with font-size specified (7pt)</p>
<p style="font-size:7pt;">This is <s>strikethrough</s> in block and <big>big <s>strikethrough</s> in big span</big> and then <u>underline</u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</p>


Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. <a href="http://www.bpm1.com">Maecenas consectetuer eros quis massa</a>. Mauris semper velit vehicula purus. Duis lacus. Aenean pretium consectetuer mauris. <span style="font-family:monospace; font-size:7pt; color:#880000; font-weight:bold; background-color:#FFDDCC;">Ut purus sem, consequat ut, fermentum sit amet, ornare sit amet, ipsum. Donec non nunc.</span> Maecenas fringilla. Curabitur libero. In dui massa, malesuada sit amet, hendrerit vitae, viverra nec, tortor. Donec varius. Ut ut dolor et tellus adipiscing

<!-- Try this to show that when using $mpdf->kwt i.e. Keep-with-table, it goes wrong when enclosed by a DIV with border or background 
<div style="border: 1px solid #000000; background-color: #EEEEFF;">
-->
<h4>Automatic Column Width</h4>
<table border="1" style="font-size:9pt; line-height:1.5; padding:8px; background-color:#CCDDFF; text-align:justify"><tbody>
<tr>
<td>Causes</td>
<td>Ut magna ipsum, tempus in, condimentum at, rutrum et, nisl. Vestibulum interdum luctus sapien. Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. <a href="http://www.bpm1.com">Maecenas consectetuer eros quis massa</a>. Mauris semper velit vehicula purus. Duis lacus. Aenean pretium consectetuer mauris. <span style="font-family:monospace; font-size:7pt; color:#880000; font-weight:bold; background-color:#FFDDCC;">Ut purus sem, consequat ut, fermentum sit amet, ornare sit amet, ipsum. Donec non nunc.</span> Maecenas fringilla. Curabitur libero. In dui massa, malesuada sit amet, hendrerit vitae, viverra nec, tortor. Donec varius. Ut ut dolor et tellus adipiscing.</td>
</tr>
<tr>
<td>Mechanisms</td>
<td>
<p>This is <s>strikethrough</s> in <b><s>block</s></b> and <small>small <s>strikethrough</s> in <i>small span</i></small> and <big>big <s>strikethrough</s> in big span</big> and then <u>underline</u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</p>
<p>This is a <font color="#008800">green reference<sup>32-47</sup></font> and <u>underlined reference<sup>32-47</sup></u> then reference<sub>32-47</sub> and <u>underlined reference<sub>32-47</sub></u> then <s>Strikethrough reference<sup>32-47</sup></s> and <s>strikethrough reference<sub>32-47</sub></s></p> 
<p><big>Repeated in <u>BIG</u>: This is reference<sup>32-47</sup> and <u>underlined reference<sup>32-47</sup></u> then reference<sub>32-47</sub> and <u>underlined reference<sub>32-47</sub></u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</big></p> 
<p><small>Repeated in small: This is reference<sup>32-47</sup> and <u>underlined reference<sup>32-47</sup></u> then reference<sub>32-47</sub> and <u>underlined reference<sub>32-47</sub></u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</small>
</p>

<p>The above repeated, but starting with a paragraph with font-size specified (7pt):</p>
<p style="font-size:7pt;">This is <s>strikethrough</s> in block and <big>big <s>strikethrough</s> in big span</big> and then <u>underline</u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</p>
</td>
</tr>
</tbody></table>

<!-- Try this to show that when using $mpdf->kwt i.e. Keep-with-table, it goes wrong when enclosed by a DIV with border or background 
</div>
-->

<p style="text-align:justify; padding: 5px; border:8px dotted #CCCCCC; background-color:#efcbfe;">Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. Suspendisse potenti. Ut a eros at ligula vehicula pretium. Integer consectetuer nulla non orci. Morbi feugiat pulvinar dolor. <span style="color:#880000; text-decoration:underline; font-family:serif; font-weight:bold; font-style:italic; font-size:7pt; background-color:#d2ff88; ">Border Border Border Border Border Border Border Border Border</span> feugiat pede vel risus. Nulla et lectus. Fusce eleifend neque sit amet erat. Cras odio. Donec mattis, nisi id euismod auctor, neque metus pellentesque risus, at eleifend lacus sapien et risus.</p>

<p>Integer sit <font face="mono" size="5" color="#888800">lectus ac aliquam molestie, leo lacus tincidunt turpis,</font> amet odio sit amet lectus luctus euismod. Donec et nulla. Sed quis orci. Phasellus metus. Phasellus feugiat, nisi id euismod auctor, vel aliquam quam odio et sapien. malesuada sed, nulla. <span style="color:#880000; font-family:serif; font-weight:bold; font-size:7pt; background-color:#d2ff88; ">Mauris ante pede, auctor ac, suscipit quis,</span>, suscipit quis.</p>

<p style="text-indent:2em;">Indented paragraph .. Integer sit amet odio sit amet lectus luctus euismod. Donec et nulla. Sed quis orci. Phasellus metus. Phasellus feugiat, nisi id euismod auctor, vel aliquam quam odio et sapien. malesuada sed, nulla. Mauris ante pede, auctor ac, suscipit quis feugiat pede vel risus. Nulla et lectus. Fusce eleifend neque sit amet erat. Cras odio. Donec mattis, nisi id euismod auctor, neque metus pellentesque risus, at eleifend lacus sapien et risus.</p>

<p style="text-indent:-2em;">Hanging indent .. Integer sit amet odio sit amet lectus luctus euismod. Donec et nulla. Sed quis orci. Phasellus metus. Phasellus feugiat, nisi id euismod auctor, vel aliquam quam odio et sapien. malesuada sed, nulla. Mauris ante pede, auctor ac, suscipit quis feugiat pede vel risus. Nulla et lectus. Fusce eleifend neque sit amet erat. Cras odio. Donec mattis, nisi id euismod auctor, neque metus pellentesque risus, at eleifend lacus sapien et risus.</p>

Integer sit <font face="mono" size="6" color="#888800">lectus ac aliquam molestie, leo lacus tincidunt turpis,</font> amet odio sit amet lectus luctus euismod. Donec et nulla. Sed quis orci. Phasellus metus. Phasellus feugiat, nisi id euismod auctor, vel aliquam quam odio et sapien. malesuada sed, nulla. <span style="color:#880000; font-family:serif; font-weight:bold; font-size:7pt; background-color:#d2ff88; ">Mauris ante pede, auctor ac, suscipit quis,</span>, suscipit quis.
<br />
Integer sit amet odio sit amet lectus luctus euismod. Donec et nulla. Sed quis orci. Phasellus metus. Phasellus feugiat, nisi id euismod auctor, vel aliquam quam odio et sapien. malesuada sed, nulla. <span style="color:#880000; text-decoration:underline; font-family:serif; font-weight:bold; font-style:italic; font-size:7pt; background-color:#d2ff88; ">Mauris ante pede, auctor ac, suscipit quis,</span> <br />

Hallo World!


<p style="text-indent:30pt; color:#880000; padding:20px; border:1px solid #000000; margin-left:20pt; margin-right:8pt; text-align:justify; background-color:#ffff88;">Sed bibendum. Nunc eleifend ornare velit. Padding Padding Padding Padding consectetuer urna in erat. Class aptent taciti sociosqu ad litora torquent <span style="background-color:#ffbbff">per conubia nostra, per inceptos hymenaeos. Mauris sodales semper metus. Maecenas justo libero, pretium at, malesuada eu, mollis et, arcu.</span> Ut suscipit pede in nulla. Praesent elementum, dolor ac fringilla posuere, elit libero rutrum massa, vel tincidunt dui tellus a ante. Sed aliquet euismod dolor. Vestibulum sed dui. Duis lobortis hendrerit quam. Donec tempus orci ut libero. Pellentesque suscipit malesuada nisi. </p>

<p style="padding:20px; border-left:10px dashed #008800; border-top:10px dashed #008800; margin-bottom:10pt; border-bottom:10px dotted #008800; border-right:10px dotted #008800; background-color:#EECCDD;" >Integer feugiat venenatis metus. Integer lacinia ultrices ipsum. Proin et arcu. Quisque varius libero. Nullam id arcu. <code>Aenean justo quam, accumsan nec, luctus id, pellentesque molestie, mi.</code> Aliquam sollicitudin feugiat eros. Nunc nisi turpis, consequat id, aliquet et, semper a, augue. Integer nisl ipsum, blandit et, lobortis a, egestas nec, odio. Nulla dolor ligula, nonummy ac, vulputate a, sollicitudin id, orci. Donec laoreet nisl id magna. Curabitur mollis, quam eget fermentum malesuada, risus tortor ullamcorper dolor, nec placerat nisi urna non pede. Aliquam pretium, leo in interdum interdum, ipsum neque accumsan lectus, ac fringilla dui ipsum sed justo. In tincidunt risus convallis odio egestas luctus. Integer volutpat. Donec ultricies, leo in congue iaculis, dolor neque imperdiet nibh, vitae feugiat mi enim nec sapien. Aenean turpis lorem, consequat quis, varius in, posuere vel, eros. Nulla facilisi.</p>


<div style="border:2px dashed #880000; padding:3px; margin-left:20pt;margin-right:20pt; margin-bottom:10pt; text-align:right; background-color:#eeddcc;">Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo. Cras tellus. Fusce aliquet. Curabitur tincidunt viverra ligula. Fusce eget erat. Donec pede. Vestibulum id felis. Phasellus tincidunt ligula non pede. Morbi turpis. In vitae dui non erat placerat malesuada. Mauris adipiscing congue ante. Proin at erat. Aliquam mattis. </div>


<div style="border:1px dotted #880000; margin-left:20pt;margin-right:20pt; margin-bottom:10pt; text-align:justify; background-color:#ddccee;">Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. 
</div>
<p style="border:1px dashed #008800; padding:5pt; margin-left:10pt;margin-right:5pt; margin-bottom:10pt; text-align:center; background-color:#ddeecc;">Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate <span style="background-color:#ffbbff">per conubia nostra, per inceptos hymenaeos. Mauris sodales semper metus. Maecenas justo libero, pretium at, malesuada eu, mollis et, arcu</span>. Donec luctus. Cras euismod tellus vel leo. Cras tellus. Fusce aliquet. Curabitur tincidunt viverra ligula. Fusce eget erat. Donec pede. Vestibulum id felis. Phasellus tincidunt ligula non pede. Morbi turpis. In vitae dui non erat placerat malesuada. Mauris adipiscing congue ante. Proin at erat. Aliquam mattis. </p>
<div>
Cras euismod tellus vel leo. Cras tellus. Fusce aliquet. Curabitur tincidunt viverra ligula. Fusce eget erat. Donec pede. Vestibulum id felis. Phasellus tincidunt ligula non pede. Morbi turpis. In vitae dui non erat placerat malesuada. Mauris adipiscing congue ante. Proin at erat. Aliquam mattis. </div>

Hallo World!

<div style="border:5px dashed #880000; margin-left:20pt;margin-right:20pt; text-align:justify; background-color:#ddffee;">Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo. Cras tellus. Fusce aliquet. Curabitur tincidunt viverra ligula. Fusce eget erat. Donec pede. Vestibulum id felis. Phasellus tincidunt ligula non pede. Morbi turpis. In vitae dui non erat placerat malesuada. Mauris adipiscing congue ante. Proin at erat. Aliquam mattis. </div>

<div style="border:1px dashed #880000; text-align:justify; background-color:#ddffee;">
<p style="margin:10pt; text-indent:20px; border:1px dotted #880000; padding:20pt; text-align:justify; background-color:#ddffff;">Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo. Cras tellus. Fusce aliquet. Curabitur tincidunt viverra ligula. Fusce eget erat. Donec pede. Vestibulum id felis. Phasellus tincidunt ligula non pede. Morbi turpis. In vitae dui non erat placerat malesuada. Mauris adipiscing congue ante. Proin at erat. Aliquam mattis. </p>
<div style="margin:0pt; text-indent:20px; border:1px dotted #880000; text-align:justify; background-color:#ffddff;">Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo. Cras tellus. Fusce aliquet. Curabitur tincidunt viverra ligula. Fusce eget erat. Donec pede. Vestibulum id felis. Phasellus tincidunt ligula non pede. Morbi turpis. In vitae dui non erat placerat malesuada. Mauris adipiscing congue ante. Proin at erat. Aliquam mattis. </div>
<p style="text-indent:20px; border:1px solid #880000; padding:20pt; text-align:justify; background-color:#ddffff;">Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo. (newline)<br />
Cras tellus. Fusce aliquet. Curabitur tincidunt viverra ligula. Fusce eget erat. Donec pede. Vestibulum id felis. Phasellus tincidunt ligula non pede. Morbi turpis. In vitae dui non erat placerat malesuada. Mauris adipiscing congue ante. Proin at erat. Aliquam mattis. </p>
</div>

<p>This is <s>strikethrough</s> in <b><s>block</s></b> and <small>small <s>strikethrough</s> in <i>small span</i></small> and <big>big <s>strikethrough</s> in big span</big> and then <u>underline</u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</p>
<br /> 


<p style="color:#880000; font-weight:bold; font-size:52pt" ><span style="color:#AAFFFF; outline-width:medium; outline-color:invert; ">OUTLINE Text.</span> </p>

<h5>Periodic Table</h5>
<table style="background-color:#CCFFEE; border:23px solid #888888; padding-left:1mm; padding-right:1mm; text-align:center;">
<thead>
<tr><td>1A</td><th>2A</th><th>3B</th><th>4B</th><th>5B</th><th>6B</th><th>7B</th><th>8B</th><th>8B</th><th>8B</th><th>1B</th><th>2B</th><th>3A</th><th>4A</th><th>5A</th><th>6A</th><th>7A</th><th>8A</th></tr>
</thead>
<tbody>
<tr>
<td>H </td>
<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
<td>He </td>
</tr>
<tr>
<td>Li </td>
<td>Be</td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td>B </td>
<td>C </td>
<td>N </td>
<td>O </td>
<td>F </td>
<td>Ne </td>
</tr>
<tr>
<td>Na </td>
<td>Mg </td>
<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
<td>Al </td>
<td>Si </td>
<td>P </td>
<td>S </td>
<td>Cl </td>
<td>Ar </td>
</tr>
<tr>
<td>K </td>
<td>Ca </td>
<td>Sc </td>
<td>Ti </td>
<td>V </td>
<td>Cr </td>
<td>Mn </td>
<td>Fe </td>
<td>Co </td>
<td>Ni </td>
<td>Cu </td>
<td>Zn </td>
<td>Ga </td>
<td>Ge </td>
<td>As </td>
<td>Se </td>
<td>Br </td>
<td>Kr </td>
</tr>
<tr>
<td>Rb </td>
<td>Sr </td>
<td>Y </td>
<td>Zr </td>
<td>Nb </td>
<td>Mo </td>
<td>Tc </td>
<td>Ru </td>
<td>Rh </td>
<td>Pd </td>
<td>Ag </td>
<td>Cd </td>
<td>In </td>
<td>Sn </td>
<td>Sb </td>
<td>Te </td>
<td>I </td>
<td>Xe </td>
</tr>
<tr>
<td>Cs </td>
<td>Ba </td>
<td>La </td>
<td>Hf </td>
<td>Ta </td>
<td>W </td>
<td>Re </td>
<td>Os </td>
<td>Ir </td>
<td>Pt </td>
<td>Au </td>
<td>Hg </td>
<td>Tl </td>
<td>Pb </td>
<td>Bi </td>
<td>Po </td>
<td>At </td>
<td>Rn </td>
</tr>
<tr>
<td>Fr </td>
<td>Ra </td>
<td>Ac </td>
<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
</tr>
<tr>
<td></td><td></td><td></td>
<td>Ce </td>
<td>Pr </td>
<td>Nd </td>
<td>Pm </td>
<td>Sm </td>
<td>Eu </td>
<td>Gd </td>
<td>Tb </td>
<td>Dy </td>
<td>Ho </td>
<td>Er </td>
<td>Tm </td>
<td>Yb </td>
<td>Lu </td>
<td></td>
</tr>
<tr>
<td></td><td></td><td></td>
<td>Th </td>
<td>Pa </td>
<td>U</td>
<td>Np </td>
<td>Pu </td>
<td>Am </td>
<td>Cm </td>
<td>Bk </td>
<td>Cf </td>
<td>Es </td>
<td>Fm </td>
<td>Md </td>
<td>No </td>
<td>Lr </td>
<td></td>
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
	exit; 
}
//==============================================================
//==============================================================
//==============================================================

define('_MPDF_PATH','../');
include("../mpdf.php");

$mpdf=new mPDF(); 
$mpdf->use_kwt = true;	// Keeps the heading with the table on the 2nd page (kwt = keep-with-table)

$mpdf->use_embeddedfonts_1252 = true;

$mpdf->AddPage();

$mpdf->WriteHTML($html);

if ($_REQUEST['plussource']) { 
	$showcode = '<div style="border:1px solid #555555; background-color: #DDDDDD; padding: 1em; font-size:9pt; font-family:mono;">';
	$showcode .= '<h2>HTML Code</h2>';
	$showcode .= nl2br(htmlspecialchars($html));
	$showcode .= '</div>';
	$mpdf->WriteHTML($showcode,2);
}

$mpdf->WriteHTML('<div class="infobox">Generated in '.sprintf('%.2f',(microtime(true) - $timeo_start)).' seconds</div>',2);
$mpdf->Output('mpdf.pdf','I');
exit;
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================


?>