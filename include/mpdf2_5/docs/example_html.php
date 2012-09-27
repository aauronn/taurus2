<?php


/* Example of Basic HTML Tags */

define('_MPDF_PATH','../');
include("../mpdf.php");
//
$timeo_start = microtime(true);
//


$html = '
<h1>mPDF</h1>
<h2>HTML Tags</h2>
<h3>Heading 3</h3>
<h4>Heading 4</h4>
<h5>Heading 5</h5>
<h6>Heading 6</h6>

<h4>Lists</h4>
<h5>Ordered list</h5>
<p>Ordinary text</p>
<ol>
<li>Item 1</li>
<li>Item 2
<ul>
<li>Subitem of ordered list</li>
<li>Subitem 2
<ul>
<li>Level 3 subitem</li>
</ul>
</li>
</ul>
</li>
<li>Item 3</li>
</ol>
<h5>Unordered list</h5>
<ul>
<li>Item 1</li>
<li>Item 2
<ul>
<li>Subitem of unordered list</li>
<li>Subitem 2
<ul>
<li>Level 3 subitem</li>
</ul>
</li>
</ul>
</li>
<li>Item 3</li>
</ul>
<h4>&lt;HR&gt;</h4>
<hr />

<h4>Tables</h4>
<p>bpmTopnTail<b>C</b> Class (centered) Odd and Even rows</p>
<table class="bpmTopnTailC"><thead>
<tr class="headerrow"><th>Col/Row Header</th>
<td>
<p>Second column header p</p>
</td>
<td>Third column header</td>
</tr>
</thead><tbody>
<tr class="oddrow"><th>Row header 1</th>
<td>This is data</td>
<td>This is data</td>
</tr>
<tr class="evenrow"><th>Row header 2</th>
<td>
<p>This is data p</p>
</td>
<td>
<p>This is data</p>
</td>
</tr>
<tr class="oddrow"><th>
<p>Row header 3</p>
</th>
<td>
<p>This is long data</p>
</td>
<td>This is data</td>
</tr>
<tr class="evenrow"><th>
<p>Row header 4</p>
<p>&lt;th&gt; cell acting as header</p>
</th>
<td>This is data</td>
<td>
<p>This is data</p>
</td>
</tr>
<tr class="oddrow"><th>Row header 5</th>
<td>Also data</td>
<td>Also data</td>
</tr>
<tr class="evenrow"><th>Row header 6</th>
<td>Also data</td>
<td>Also data</td>
</tr>
<tr class="oddrow"><th>Row header 7</th>
<td>Also data</td>
<td>Also data</td>
</tr>
<tr class="evenrow"><th>Row header 8</th>
<td>Also data</td>
<td>Also data</td>
</tr>
</tbody></table>
<p>&nbsp;</p>
<table class="bpmNoLines"><tbody>
<tr>
<td class="pmhTopLeft">Testing '."\xe2\x80\x98".'quotes'."\xe2\x80\x99".' and '."\xe2\x80\x9c".'double quotes'."\xe2\x80\x9d".' <b>bold</b> and <i>italic</i> and finally <b><i>bold and italic</i></b> fonts [in table]</td>
</tr>
</tbody></table>
<p>Testing '."\xe2\x80\x98".'quotes'."\xe2\x80\x99".' and '."\xe2\x80\x9c".'double quotes'."\xe2\x80\x9d".'
<b>bold</b> and <i>italic</i> and finally <b><i>bold and italic</i></b> fonts in [&lt;p&gt;]</p>
<p>This is to test<sup>32</sup> sup and sub<sub>3</sub> script <u>text</u> This is to test<sup>32</sup> sup and sub<sub><u>3</u></sub> script <u>text</u></p>
<p>Table with pmhNolines<sup>32</sup> specified (in &lt;p&gt;)</p>
<p>Paragraph immediately above table</p>
<p>
<table id="subs93" class="bpmTopic"><tbody>
<tr>
<td class="pmhTopCenter"><b>Baseline Creatinine<br />
Clearance (ml/min)</b></td>
<td class="pmhTopCenter"><b>Zoledronic Acid<br />
Recommended Dose</b></td>
<td><b>mL of concentrate</b></td>
</tr>
<tr>
<td class="pmhTopCenter">&ge; 60</td>
<td class="pmhTopCenter">4.0 mg</td>
<td class="pmhTopCenter">5.0 mL</td>
</tr>
<tr>
<td class="pmhTopCenter">50 '."\xe2\x80\x93".' 60</td>
<td class="pmhTopCenter">3.5 mg</td>
<td class="pmhTopCenter">4.4 mL</td>
</tr>
<tr>
<td class="pmhTopCenter">40 '."\xe2\x80\x93".' 49</td>
<td class="pmhTopCenter">3.3 mg</td>
<td class="pmhTopCenter">4.1 mL</td>
</tr>
<tr>
<td class="pmhTopCenter">30 - 39</td>
<td class="pmhTopCenter">3.0 mg</td>
<td class="pmhTopCenter">3.8 mL</td>
</tr>
</tbody></table>
</p>
<p>Paragraph immediately below table to look for table-bottom-margin</p>
<p>Paragraph immediately above list</p>
<ul>
<li>First item</li>
<li>Second item</li>
<li>Third item</li>
<li>Fourth item</li>
<li>Fifth item</li>
</ul>
<p>Paragraph immediately below list to look for list-bottom-margin</p>

<h4>LINK</h4>
<p><a href="http://www-950.ibm.com/software/globalization/icu/demo/converters?s=ALL">http://www-950.ibm.com/software/globalization/icu/demo/converters?s=ALL</a></p>

<h4>Justification</h4>
<h5>Single word should split</h5>
<p>http://www-950.ibm.com/software/globalization/icu/demo/converters?s=ALL&amp;snd=4356</p>
<h5>Should not split</h5>
<p>Maecenas feugiat pede vel risus. Nulla et lectus eleifend <i>verylongwordthatwontsplit</i> neque sit amet erat</p>
<p>Maecenas feugiat pede vel risus. Nulla et lectus eleifend et <i>verylongwordthatwontsplit</i> neque sit amet erat</p>

<h4>PRE-formatted text</h4>

<pre>This is text defined as <pre>. The CSS stylesheet determines the
font-family and font-size. It should leave all entities, such as
&amp; &#77; and &#xa3 unchanged, and print tags such as <p> as they
are written.
	Tabs can be used to indent, and are set at 8 spaces wide.
12345678Space before and after the <pre> can be set using CSS
stylesheets again (margin-top and margin-bottom).</pre>


<p>Paragraph before the &lt;pre&gt;</p>
<pre>This is text defined as <pre>. The CSS stylesheet determines the
font-family and font-size. It should leave all entities, such as
&amp; &#77; and &#xa3 unchanged, and print tags such as <p> as they
are written.
	Tabs can be used to indent, and are set at 8 spaces wide.
12345678Space before and after the <pre> can be set using CSS
stylesheets again (margin-top and margin-bottom).</pre>
<p>Paragraph following the &lt;pre&gt;</p>

<p style="font-size:15pt; color:#440066">Paragraph using the in-line style to determine the font-size (15pt) and colour</p>


<h4>Testing BIG, SMALL, UNDERLINE, STRIKETHROUGH, FONT color, ACRONYM, SUPERSCRIPT and SUBSCRIPT</h4>
<p>This is <s>strikethrough</s> in <b><s>block</s></b> and <small>small <s>strikethrough</s> in <i>small span</i></small> and <big>big <s>strikethrough</s> in big span</big> and then <u>underline</u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</p>
<p>This is a <font color="#008800">green reference<sup>32-47</sup></font> and <u>underlined reference<sup>32-47</sup></u> then reference<sub>32-47</sub> and <u>underlined reference<sub>32-47</sub></u> then <s>Strikethrough reference<sup>32-47</sup></s> and <s>strikethrough reference<sub>32-47</sub></s>
</p> 
<p><big>Repeated in <u>BIG</u>: This is reference<sup>32-47</sup> and <u>underlined reference<sup>32-47</sup></u> then reference<sub>32-47</sub> and <u>underlined reference<sub>32-47</sub></u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</big>
</p> 
<p><small>Repeated in small: This is reference<sup>32-47</sup> and <u>underlined reference<sup>32-47</sup></u> then reference<sub>32-47</sub> and <u>underlined reference<sub>32-47</sub></u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</small> 
</p>

<p>The above repeated, but starting with a paragraph with font-size specified (7pt)</p>
<p style="font-size:7pt;">This is <s>strikethrough</s> in block and <small>small <s>strikethrough</s> in small span</small> and then <u>underline</u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</p>
<p style="font-size:7pt;">This is <s>strikethrough</s> in block and <big>big <s>strikethrough</s> in big span</big> and then <u>underline</u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</p>
<p style="font-size:7pt;">This is reference<sup>32-47</sup> and <u>underlined reference<sup>32-47</sup></u> then reference<sub>32-47</sub> and <u>underlined reference<sub>32-47</sub></u> then <s>Strikethrough reference<sup>32-47</sup></s> and <s>strikethrough reference<sub>32-47</sub></s>
<br /> 
<br />
<small>Repeated in small: This is reference<sup>32-47</sup> and <u>underlined reference<sup>32-47</sup></u> then reference<sub>32-47</sub> and <u>underlined reference<sub>32-47</sub></u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</small></p> 
<p></p>
<p style="font-size:7pt;">
<big>Repeated in BIG: This is reference<sup>32-47</sup> and <u>underlined reference<sup>32-47</sup></u> then reference<sub>32-47</sub> and <u>underlined reference<sub>32-47</sub></u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</big>
</p>

<h4>Testing Word wrap with Orphans</h4>
This is <s>strikethrough</s> OUT OF BLOCK<sup>2</sup> and <small>small <s>strikethrough</s> in small span</small> and <big>big <s>strikethrough</s> in big span</big> and then <u>underline</u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text

';
//==============================================================
//==============================================================
//==============================================================
if ($_REQUEST['html']) { echo '<html><head><style>'.file_get_contents('mpdfstyletables.css').'</style></head><body>'.$html.'</body></html>'; exit; }
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

	// Can alternatively use body {} in Stylesheet but this does not cover the ShadedBox before WriteHTML()
	$mpdf->SetDefaultFont('DejaVuSansCondensed');
	$mpdf->SetDefaultFontSize('11');

	$mpdf->SetDisplayMode('fullpage');

	$mpdf->SetTitle('mPDF Example - HTML');

	$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list

//==============================================================

	// LOAD a stylesheet
	$stylesheet = file_get_contents('mpdfstyletables.css');
	$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

	$mpdf->WriteHTML($html);

	$mpdf->WriteHTML('<div class="infobox">Generated in '.sprintf('%.2f',(microtime(true) - $timeo_start)).' seconds</div>',2);

	$mpdf->Output('mpdf.pdf','I');
	exit;


?>