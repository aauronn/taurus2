<?php
//
$timeo_start = microtime(true);
//

$list = '
<ol>
<li>Item <b><u>1</u></b></li>
<li>Item 2<sup>32</sup></li>
<li><small>Item</small> 3</li>
<li>Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo. 
<ul style="font-size:8pt; font-weight:bold; color:#008800; font-style:normal; font-family:sans-serif;">
<li>Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo. </li>
<li>Subitem 2
<ul style="font-size:12pt; font-weight:normal; font-style:normal; color:#000088; background-color:#ccfafa;">
<li>
Level 3 subitem
</li>
</ul>
</li>
</ul>
</li>
<li>Item 5</li>
</ol>
';

$table = '
<table border="1"><thead>
<tr class="headerrow"><th>Col/Row Header</th>
<td>
<p>Second column header p</p>
</td>
<td>Third column header</td>
</tr>
</thead><tbody>
<tr class="oddrow"><th>Row header 1</th>
<td><p>Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Vestibulum id felis. Phasellus tincidunt ligula non pede. Morbi turpis. In vitae dui non erat placerat malesuada. Mauris adipiscing congue ante. Proin at erat. Aliquam mattis</p></td>
<td>This is data</td>
</tr>
<tr class="evenrow"><th>Row header 2</th>
<td>
<p>This is data p<hr />Text after HR</p>
</td>
<td>
<p>This is long data</p>
</td>
</tr>
<tr class="oddrow"><th>
<p>Row header 3</p>
</th>
<td><p>This is long data</p>
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
<td>A list
'.$list.'</td>
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
';


$html = '

<h1>mPDF</h1>
<h2>Basic Example</h2>

<div style="border:1px solid #880000; margin:10pt; text-align:justify; padding:14pt; background-color:#ffaacc;">
This is text in a div.
<div style="border:1px dotted #880000; margin:10pt; text-align:justify; padding:7pt; background-color:#ffffcc; font-family:serif; font-style:italic; color:#880000; ">
Text in a second level div.
<p style="text-indent:20px; margin:5pt; border:1px dashed #000088; padding:20pt; text-align:justify; background-color:#ddffff;"><b>This is a p inside the div.</b> Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Vestibulum id felis. Phasellus tincidunt ligula non pede. Morbi turpis. In vitae dui non erat placerat malesuada. Mauris adipiscing congue ante. Proin at erat. Aliquam mattis. </p>
<hr />
<i>Text again inside the div.</i> Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo. Cras tellus. Fusce aliquet. Curabitur tincidunt viverra ligula. Fusce eget erat. Donec pede. Vestibulum id felis. Phasellus tincidunt ligula non pede. Morbi turpis. In vitae dui non erat placerat malesuada. Mauris adipiscing congue ante. Proin at erat. Aliquam mattis. Then a list:
'.$list.'
followed by more text.
<p style="text-indent:20px; border:11px solid #008800; padding:10pt; text-align:right; background-color:#ffddff;"><b>Another P inside the div.</b> Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo. (newline)<br />
Cras tellus. Fusce aliquet. Curabitur tincidunt viverra ligula. Fusce eget erat. Donec pede. Vestibulum id felis. Phasellus tincidunt ligula non pede. Morbi turpis. In vitae dui non erat placerat malesuada. Mauris adipiscing congue ante. Proin at erat. Aliquam mattis. </p>
Finally some more text to end the div including a table:.
'.$table.'
followed by some text.
</div>
In the last level div.
</div>



<div style="margin:10pt; padding:10pt; border:1px solid #880000;">
Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. <a href="http://www.bpm1.com">Maecenas consectetuer eros quis massa</a>. Mauris semper velit vehicula purus. Duis lacus. Aenean pretium consectetuer mauris. <span style="font-family:monospace; font-size:7pt; color:#880000; font-weight:bold; background-color:#FFDDCC;">Ut purus sem, consequat ut, fermentum sit amet, ornare sit amet, ipsum. Donec non nunc.</span> Maecenas fringilla. Curabitur libero. In dui massa, malesuada sit amet, hendrerit vitae, viverra nec, tortor. Donec varius. Ut ut dolor et tellus adipiscing. 
</div>

<p><small>Repeated in small: This is reference<sup>32-47</sup> and <u>underlined reference<sup>32-47</sup></u> then reference<sub>32-47</sub> and <u>underlined reference<sub>32-47</sub></u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</small>
</p>


<p style="text-align:justify; border:8px dotted #CCCCCC; background-color:#efcbfe;">Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. Suspendisse potenti. Ut a eros at ligula vehicula pretium. Integer consectetuer nulla non orci. Morbi feugiat pulvinar dolor. <span style="color:#880000; text-decoration:underline; font-family:serif; font-weight:bold; font-style:italic; font-size:7pt; background-color:#d2ff88; ">Border Border Border Border Border Border Border Border Border</span> feugiat pede vel risus. Nulla et lectus. Fusce eleifend neque sit amet erat. Cras odio. Donec mattis, nisi id euismod auctor, neque metus pellentesque risus, at eleifend lacus sapien et risus.</p>

<p>Integer sit <font face="mono" size="2" color="#888800">lectus ac aliquam molestie, leo lacus tincidunt turpis,</font> amet odio sit amet lectus luctus euismod. Donec et nulla. Sed quis orci. Phasellus metus. Phasellus feugiat, nisi id euismod auctor, vel aliquam quam odio et sapien. malesuada sed, nulla. <span style="color:#880000; font-family:serif; font-weight:bold; font-size:7pt; background-color:#d2ff88; ">Mauris ante pede, auctor ac, suscipit quis,</span>, suscipit quis.</p>

<p>Integer sit amet odio sit amet lectus luctus euismod. Donec et nulla. Sed quis orci. Phasellus metus. Phasellus feugiat, nisi id euismod auctor, vel aliquam quam odio et sapien. malesuada sed, nulla. <span style="color:#880000; text-decoration:underline; font-family:serif; font-weight:bold; font-style:italic; font-size:7pt; background-color:#d2ff88; ">Mauris ante pede, auctor ac, suscipit quis,</span></p>



<p style="text-indent:30pt; color:#880000; padding:20px; border:1px solid #000000; margin-left:20pt; margin-right:8pt; text-align:justify; background-color:#ffff88;">Sed bibendum. Nunc eleifend ornare velit. Padding Padding Padding Padding consectetuer urna in erat. Class aptent taciti sociosqu ad litora torquent <span style="background-color:#ffbbff">per conubia nostra, per inceptos hymenaeos. Mauris sodales semper metus. Maecenas justo libero, pretium at, malesuada eu, mollis et, arcu.</span> Ut suscipit pede in nulla. Praesent elementum, dolor ac fringilla posuere, elit libero rutrum massa, vel tincidunt dui tellus a ante. Sed aliquet euismod dolor. Vestibulum sed dui. Duis lobortis hendrerit quam. Donec tempus orci ut libero. Pellentesque suscipit malesuada nisi. </p>

<p style="font-family:serif; font-size:9pt; padding:20px; border-left:10px dashed #008800; border-top:10px dashed #008800; margin-bottom:10pt; border-bottom:10px dotted #008800; border-right:10px dotted #008800; background-color:#EECCDD;" >Integer feugiat venenatis metus. Integer lacinia ultrices ipsum. Proin et arcu. Quisque varius libero. Nullam id arcu. <code>Aenean justo quam, accumsan nec, luctus id, pellentesque molestie, mi.</code> Aliquam sollicitudin feugiat eros. Nunc nisi turpis, consequat id, aliquet et, semper a, augue. Integer nisl ipsum, blandit et, lobortis a, egestas nec, odio. Nulla dolor ligula, nonummy ac, vulputate a, sollicitudin id, orci. Donec laoreet nisl id magna. Curabitur mollis, quam eget fermentum malesuada, risus tortor ullamcorper dolor, nec placerat nisi urna non pede. Aliquam pretium, leo in interdum interdum, ipsum neque accumsan lectus, ac fringilla dui ipsum sed justo. In tincidunt risus convallis odio egestas luctus. Integer volutpat. Donec ultricies, leo in congue iaculis, dolor neque imperdiet nibh, vitae feugiat mi enim nec sapien. Aenean turpis lorem, consequat quis, varius in, posuere vel, eros. Nulla facilisi.</p>

<form>


<b>Textarea</b>
<textarea name="authors" rows="5" cols="80" wrap="virtual">Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. </textarea>
<br /><br />

<b>Select</b>
<select size="1" name="status"><option value="A">Active</option><option value="W" >New item from auto_manager: pending validation</option><option value="I" selected="selected">Incomplete record - pending</option><option value="X" >Flagged for Deletion</option> </select> followed by text
<br /><br />




<b>Input Radio</b>
<input type="radio" name="recommended" value="0" > No &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="recommended" value="1" > Keep &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="recommended" value="2"  checked="checked" > Choice 
<br /><br />


<b>Input Text</b>
<input type="text" size="180" name="doi" value="10.1258/jrsm.100.5.211"> 
<br /><br />


<input type="checkbox" name="QPC" value="ON" > Checkboxes<br>
<input type="checkbox" name="QPA" value="ON" > Not selected<br>
<input type="checkbox" name="QLY" value="ON" checked="checked" > Selected
<br /><br />

<input type="submit" name="submit" value="Submit" /><br /><br />

</form>


<dl>
<dt><a href="#links">Test pages for Unicode ranges</a></dt><dd>Lists of Unicode characters that you can use to test the Unicode support of your Web browser and fonts.</dd>
<dt><a href="search.html">Search for a Unicode character</a></dt><dd>Search the test pages to find any character that you want to use.</dd>
<dt><a href="fontsbyrange.html">Fonts for each Unicode range</a></dt><dd>A list of Unicode ranges and the fonts that support them.</dd>
<dt>Unicode fonts</dt><dd>Lists of fonts for <a href="fonts.html">Windows</a>, <a href="fonts_mac.html">Mac OS 9</a>, <a href="fonts_macosx.html">Mac OS X 10</a> and <a href="fonts_unix.html">Unix</a>, with the Unicode ranges they support, and where to obtain them.</dd>
<dt><a href="macbrowsers.html">Browsers for Apple Macintosh computers</a></dt><dd>How to enable Unicode support in Web browsers under Mac OS 9.</dd>
<dt><a href="explorer.html">Internet Explorer for Windows</a></dt><dd>How to enable Unicode support in IE 4, IE 5, IE 5.5 and IE 6.</dd>
<dt><a href="netscape.html">Netscape for Windows</a></dt><dd>How to enable Unicode support in Netscape Communicator 4.x and 6.x.</dd>
<dt>Editors and word processors</dt><dd>Applications for <a href="utilities_editors.html">Windows</a>, <a href="utilities_editors_mac.html">Mac OS 9</a>, <a href="utilities_editors_macosx.html">Mac OS X 10</a> and <a href="utilities_editors_unix.html">Unix</a> that can produce Unicode text, HTML and word processor documents.</dd>
<dt>File conversion, font and keyboard utilities</dt><dd>Utilities for <a href="utilities_fonts_mac.html">Mac OS 9</a>, <a href="utilities_fonts_macosx.html">Mac OS X 10</a>, <a href="utilities_fonts.html">Windows</a> and <a href="utilities_fonts_unix.html">Unix</a> that can convert files to and from Unicode, view the characters in Unicode fonts, or re-map your keyboard to type Unicode characters.</dd>
<dt><a href="htmlunicode.html">Creating multilingual Web pages</a></dt><dd>HTML code, fonts and editors to help you produce Web pages with multiple scripts and languages</dd>
</dl>
<br /><br />


<div style="border:1px dashed #880000; margin-left:20pt;margin-right:20pt; margin-bottom:10pt; text-align:justify; background-color:#eeddcc;">Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo. Cras tellus. Fusce aliquet. Curabitur tincidunt viverra ligula. Fusce eget erat. Donec pede. Vestibulum id felis. Phasellus tincidunt ligula non pede. Morbi turpis. In vitae dui non erat placerat malesuada. Mauris adipiscing congue ante. Proin at erat. Aliquam mattis. </div>


<div style="border:1px dotted #880000; margin-left:20pt;margin-right:20pt; margin-bottom:10pt; text-align:justify; background-color:#ddccee;">Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. 
</div>
<p style="border:1px dashed #008800; padding:5pt; margin-left:10pt;margin-right:5pt; margin-bottom:10pt; text-align:justify; background-color:#ddeecc;">Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo. Cras tellus. Fusce aliquet. Curabitur tincidunt viverra ligula. Fusce eget erat. Donec pede. Vestibulum id felis. Phasellus tincidunt ligula non pede. Morbi turpis. In vitae dui non erat placerat malesuada. Mauris adipiscing congue ante. Proin at erat. Aliquam mattis. </p>
<div>
Cras euismod tellus vel leo. Cras tellus. Fusce aliquet. Curabitur tincidunt viverra ligula. Fusce eget erat. Donec pede. Vestibulum id felis. Phasellus tincidunt ligula non pede. Morbi turpis. In vitae dui non erat placerat malesuada. Mauris adipiscing congue ante. Proin at erat. Aliquam mattis. </div>

<div style="border:5px dashed #880000; margin-left:20pt;margin-right:20pt; text-align:justify; background-color:#ddffee;">Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo. Cras tellus. Fusce aliquet. Curabitur tincidunt viverra ligula. Fusce eget erat. Donec pede. Vestibulum id felis. Phasellus tincidunt ligula non pede. Morbi turpis. In vitae dui non erat placerat malesuada. Mauris adipiscing congue ante. Proin at erat. Aliquam mattis. </div>

Hallo World!<br />

<div style="border:1px solid #880000; text-align:justify; background-color:#ddaaee;">
<p style="margin:5pt; text-indent:20px; border:1px dashed #880000; padding:20pt; text-align:justify; background-color:#ddffff; margin-collapse:1;">Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo. Cras tellus. Fusce aliquet. Curabitur tincidunt viverra ligula. Fusce eget erat. Donec pede. Vestibulum id felis. Phasellus tincidunt ligula non pede. Morbi turpis. In vitae dui non erat placerat malesuada. Mauris adipiscing congue ante. Proin at erat. Aliquam mattis. </p>
<div style="margin:5pt; text-indent:20px; border:1px dotted #880000; text-align:justify; background-color:#ffddff;">Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo. Cras tellus. Fusce aliquet. Curabitur tincidunt viverra ligula. Fusce eget erat. Donec pede. Vestibulum id felis. Phasellus tincidunt ligula non pede. Morbi turpis. In vitae dui non erat placerat malesuada. Mauris adipiscing congue ante. Proin at erat. Aliquam mattis. </div>
<p style="margin:5pt; text-indent:20px; border:1px dashed #880000; padding:20pt; text-align:justify; background-color:#ddffff;">Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo. (newline)<br />
Cras tellus. Fusce aliquet. Curabitur tincidunt viverra ligula. Fusce eget erat. Donec pede. Vestibulum id felis. Phasellus tincidunt ligula non pede. Morbi turpis. In vitae dui non erat placerat malesuada. Mauris adipiscing congue ante. Proin at erat. Aliquam mattis. </p>
</div>

<p>This is <s>strikethrough</s> in <b><s>block</s></b> and <small>small <s>strikethrough</s> in <i>small span</i></small> and <big>big <s>strikethrough</s> in big span</big> and then <u>underline</u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</p>
<br /> 


<p style="color:#880000; font-weight:bold; font-size:52pt" ><span style="color:#AAFFFF; outline-width:medium; outline-color:invert; ">OUTLINE Text.</span> </p>

Periodic Table
<div style="margin:16pt; background-color:#ddffaa; border:1px dashed #880000">
<table style="border:4mm dashed #888888; padding-left:1mm; padding-right:1mm; text-align:center;"><thead>
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
</div>

Hallo world

<div style="margin:10pt; background-color:#ddffaa; border:13px dashed #880000">
<table align="center" style="border:0.2mm solid #888888; padding-left:1mm; padding-right:1mm; text-align:center;"><thead>
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
</div>

';

$html2 = '
<div style="border:1px solid #880000; text-align:justify; background-color:#ddaaee;">
<div style="margin:5pt; text-indent:20px; border:1px dashed #880000; padding:20pt; text-align:justify; background-color:#ddffff; margin-collapse:1;">Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo. Cras tellus. Fusce aliquet. Curabitur tincidunt viverra ligula. Fusce eget erat. Donec pede. Vestibulum id felis. Phasellus tincidunt ligula non pede. Morbi turpis. In vitae dui non erat placerat malesuada. Mauris adipiscing congue ante. Proin at erat. Aliquam mattis.
<div style="margin:5pt; text-indent:20px; border:1px dotted #880000; text-align:justify; background-color:#ffddff;">Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo. Cras tellus. Fusce aliquet. Curabitur tincidunt viverra ligula. Fusce eget erat. Donec pede. Vestibulum id felis. Phasellus tincidunt ligula non pede. Morbi turpis. In vitae dui non erat placerat malesuada. Mauris adipiscing congue ante. Proin at erat. Aliquam mattis. </div>
Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo. (newline)<br />
Cras tellus. Fusce aliquet. Curabitur tincidunt viverra ligula. Fusce eget erat. Donec pede. Vestibulum id felis. Phasellus tincidunt ligula non pede. Morbi turpis. In vitae dui non erat placerat malesuada. Mauris adipiscing congue ante. Proin at erat. Aliquam mattis. </div>
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

	$mpdf->use_embeddedfonts_1252 = true;

	$mpdf->useOddEven = 1;	// Use different Odd/Even headers and footers and mirror margins (1 or 0)

	$mpdf->AddPage();

	$mpdf->WriteHTML($html,2);

	$mpdf->WriteHTML('<div class="infobox">Generated in '.sprintf('%.2f',(microtime(true) - $timeo_start)).' seconds</div>',2);

	$mpdf->Output('mpdf.pdf','I');
	exit;
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================


?>