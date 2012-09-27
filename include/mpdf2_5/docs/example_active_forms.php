<?php



$html = '
<a name="top"></a>
<h1>mPDF - Active Forms</h1>

<div>


<h2>Form 1 - MyForm (POST)</h2>
<form id="myform" action="./data.php" method="post">
<p>This is just to remind me that <a href="#top">internal links</a> and <a href="http://mpdf.bpm1.com">external links</a> are disabled when using active forms - but more to the point they srew up the Form if they are anywhere other than inside the form tags, but before any form elements!</p>

<b>Textarea</b>
<textarea style="font-family:mono; color: #008800; font-size: 11pt;" title="Write whatever you like" name="authors" rows="5" cols="60" wrap="virtual">Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. </textarea>
<br /><br />

<b>Select</b>
<select size="1" name="status" style="color: #008800; font-size: 10pt;" title="select an option here"><option value="A">Active</option><option value="W" >New item pending validation</option><option value="I" selected="selected">Incomplete record - pending</option><option value="X" >Flagged for Deletion</option> </select> followed by text
<br /><br />

<b>Select (multiple lines)</b>
<SELECT multiple="multiple" size="6" name="component-select">
      <OPTION selected="selected" value="Component_1_a">Component_1</OPTION>
      <OPTION value="Component_1_b">Component_2</OPTION>
      <OPTION selected="selected" >Component_3</OPTION>
      <OPTION>Component_4</OPTION>
      <OPTION>Component_5</OPTION>
      <OPTION>Component_6</OPTION>
      <OPTION>Component_7</OPTION>
   </SELECT>
<br /><br />


<b>Input Radio</b>
<input type="radio" name="pre_publication" value="No" > No &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" checked="checked" name="pre_publication" value="Yes" > Yes 
<br /><br />

<b>Input Radio</b>
<input type="radio" name="recommended" value="No" > No &nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="recommended" value="Keep" > Keep &nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="recommended" value="Choice" checked="checked"> Choice 
<br /><br />

<b>Input Text</b>
<input type="text" size="190" style="font-family:serif; color: #008800;font-size: 12pt;"  name="doi" title="Document Object Identifier" value="10.1258/jrsm.100.5.211" /> 
<br /><br />

<b>Input Text - readonly</b>
<input type="text" style="font-family:mono" size="80" name="readonlytest" alt="Document Object Identifier" readonly="readonly" value="10.1258/jrsm.100.5.211" /> 
<br /><br />

<b>Input Text - maxlength(5)</b>
<input type="text" size="70" name="maxtest" maxlength="5" alt="Document Object Identifier"  value="" /> 
<br /><br />

<b>Input Text - disabled</b>
<input type="text" style="font-family: Times" size="60" name="distest" disabled="disabled" value="10.1258/jrsm.100.5.211" /> 
<br /><br />

<b>Input Password</b>
<input type="password" size="40" style="color:#FF0000" name="password" value="secret" /> 
<br /><br />


<input type="checkbox" name="QPC" value="QPC-ON" > Checkboxes<br>
<input type="checkbox" name="QPA" value="QPA-ON" > Not selected<br>
<input type="checkbox" name="QLY" value="QPL-ON" checked="checked" style="color:#880000;"  > Selected<br>
<input type="checkbox" name="QPG" value="QPG-ON" disabled="disabled"> Disabled<br>
<input type="checkbox" name="QPL" value="QPL-ON" readonly="readonly" checked="checked" > Readonly<br>
<br /><br />

<input type="reset">

<input type="image" name="goimage" value="Go" src="goto.gif" />

<input type="button" name="gobutton" value="Go" />

<input type="submit" name="submit" value="Submit" /><br /><br />

<input type="hidden" name="hiddenfield" value="hiddenvalue" />


</form>

<hr style="width:80%;" />

<h2>Form 2 - MyForm2 (GET)</h2>

<form id="myform2" action="data.php" method="get">

<table border="1" style="padding:2px;" >

<tr><td valign="top" align="right"><b>Textarea</b></td><td><textarea name="title" rows="5" cols="20" wrap="virtual">TEXTINTABLE Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. </textarea></td></tr>




<tr><td valign="top" align="right"><b>Select</b></td><td><select size="1" name="status"><option value="A">Active</option><option value="W" >New item from auto_manager: pending validation</option><option value="I" selected="selected">Incomplete record - pending</option><option value="X" >Flagged for Deletion</option> </select> <input type="hidden" name="old_status" value="A" /> </td></tr>

<tr><td valign="top" align="right"><b>Select</b><br />(multiple lines)</td><td>
<SELECT multiple size="4" name="component-select">
      <OPTION selected value="Component_1_a">Component_1</OPTION>
      <OPTION selected value="Component_1_b">Component_2</OPTION>
      <OPTION>Component_3</OPTION>
      <OPTION>Component_4</OPTION>
      <OPTION>Component_5</OPTION>
      <OPTION>Component_6</OPTION>
      <OPTION>Component_7</OPTION>
   </SELECT>
 </td></tr>


<tr><td valign="bottom" align="right"><b>Input Radio</b></td><td><input type="radio" name="pre_publication" value="0"  checked > No &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="pre_publication" value="1" > Yes </td></tr>


<tr><td valign="top" align="right"><b>Input Radio</b></td><td><input type="radio" name="recommended" value="0" > No &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="recommended" value="1" > Keep &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="recommended" value="2"  checked="checked" > Choice </td></tr>

<tr><td valign="top" align="right"><b>Input Text</b></td><td><input type="text" size="40" name="doi" value="10.1258/jrsm.100.5.211"> </td>
</tr>
<tr><td valign="top" align="right"><b>Input Password</b></td><td><input type="password" size="40" name="password" value="secret"> </td>
</tr>
<tr><td valign="top" align="right"><b>Submit</b></td><td><input type="submit" name="submit" value="Submit" /></td>
</tr>



</table>
</form>

<h2>Form 3 - MyForm3 (EMAIL)</h2>

<form id="myform3" action="mailto:admin@mpdf.bpm1.com" method="get">
<table border="1" style="padding:8px;">

<tr><td valign="top" align="right"><b>Checkbox</b></td><td></td></tr>

<tr><td><input type="checkbox" name="QPC" value="ON" > Checkboxes<br></td><td><input type="checkbox" name="QSC" value="ON" > Gardening</td></tr>

<tr><td><input type="checkbox" name="QPA" value="ON" > Holidays<br></td><td><input type="checkbox" name="QPD" value="ON" > Motoring<br></td></tr>

<tr><td><input type="checkbox" name="QLY" value="ON" checked="checked" > Books</td><td><input type="checkbox" name="QCA" value="ON" > Theatre</td></tr>

<tr><td><input type="checkbox" name="QNU" value="ON" checked="checked" > Selected option</td><td><input type="checkbox" name="QET" value="ON" > Musicals</td></tr><tr><td><input type="checkbox" name="QBE" value="ON" > Eating out</td><td><input type="checkbox" name="QPY" value="ON" > Events</td></tr>



<tr><td>
<input type="submit" name="submit" value="Submit" /><br />
</td><td></td></tr></table>
</form>

</div>

';

//==============================================================
//==============================================================
//==============================================================
if ($_REQUEST['html']) { echo '<html><head><style>'.file_get_contents('mpdfstyleA4.css').'</style></head><body>'.$html.'</body></html>'; exit; }
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

$mpdf=new mPDF('utf-8'); 

//$mpdf=new mPDF('win-1252'); 
$mpdf->use_embeddedfonts_1252 = true;	// false is default


$mpdf->useActiveForms = true;
$mpdf->SetProtection(array('copy', 'print', 'modify', 'annot-forms' ),''); 	// ensure to set annot-forms if encrypting
$mpdf->setUserRights();	// essential to allow forms to be filled in and/or saved


// LOAD a stylesheet
$stylesheet = file_get_contents('mpdfstyleA4.css');
$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

//==============================================================

$mpdf->WriteHTML($html);

//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================






$mpdf->Output('example_active_forms.pdf','D');

exit;
//==============================================================
//==============================================================
//==============================================================


?>