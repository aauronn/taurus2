<?php




define('_MPDF_PATH','../');
include("../mpdf.php");


$html = '
<form>


<b>Textarea</b>
<textarea name="authors" rows="5" cols="80" wrap="virtual">'."\xd7\xa9\xd7\x98 \xd7\x91\xd7\x99\xd7\x9d \xd7\x9e\xd7\x90\xd7\x95\xd7\x9b\xd7\x96\xd7\x91 \xd7\x95\xd7\x9c\xd7\xa4\xd7\xaa\xd7\xa2 \xd7\x9e\xd7\xa6\xd7\x90 \xd7\x9c\xd7\x9b\xd7\x9f \xd7\x97\xd7\x9b\xd7\x95 \xd7\x9c\xd7\x99 \xd7\xa0\xd7\x90\xd7\x9d \xd7\x99\xd7\x94\xd7\x95\xd7\x94 \xd7\x9c\xd7\x99\xd7\x95\xd7\x9d \xd7\xa7\xd7\x95\xd7\x9e\xd7\x99 \xd7\x9c\xd7\xa2\xd7\x93, \xd7\x9b\xd7\x99 \xd7\x9e\xd7\xa9\xd7\xa4\xd7\x98\xd7\x99 \xd7\x9c\xd7\x90\xd7\xa1\xd7\xa3 \xd7\x92\xd7\x95\xd7\x99\xd7\x9d \xd7\x9c\xd7\xa7\xd7\x91\xd7\xa6\xd7\x99 \xd7\x9e\xd7\x9e\xd7\x9c\xd7\x9b\xd7\x95\xd7\xaa, \xd7\x9c\xd7\xa9\xd7\xa4\xd7\x9a \xd7\xa2\xd7\x9c\xd7\x99\xd7\x94\xd7\x9d \xd7\x96\xd7\xa2\xd7\x9e\xd7\x99 \xd7\x9b\xd7\x9c \xd7\x97\xd7\xa8\xd7\x95\xd7\x9f \xd7\x90\xd7\xa4\xd7\x99, \xd7\x9b\xd7\x99 \xd7\x91\xd7\x90\xd7\xa9 \xd7\xa7\xd7\xa0\xd7\x90\xd7\xaa\xd7\x99 \xd7\xaa\xd7\x90\xd7\x9b\xd7\x9c \xd7\x9b\xd7\x9c \xd7\x94\xd7\x90\xd7\xa8\xd7\xa5 \xd9\x87\xd9\x84 \xd8\xb3\xd8\xaa\xd8\xb3\xd9\x81\xd8\xb1 \xd8\xa7\xd9\x84\xd8\xac\xd9\x87\xd9\x88\xd8\xaf \xd8\xa7\xd9\x84\xd8\xaf\xd8\xa8\xd9\x84\xd9\x88\xd9\x85\xd8\xa7\xd8\xb3\xd9\x8a\xd8\xa9 \xd8\xa7\xd9\x84\xd8\xac\xd8\xa7\xd8\xb1\xd9\x8a\xd8\xa9 \xd8\xb9\xd9\x86 \xd8\xad\xd9\x84\xd9\x88\xd9\x84\xd8\x9f \xd9\x88\xd9\x83\xd9\x8a\xd9\x81 \xd8\xaa\xd9\x86\xd8\xb8\xd8\xb1 \xd9\x84\xd9\x84\xd8\xa7\xd8\xaa\xd9\x87\xd8\xa7\xd9\x85\xd8\xa7\xd8\xaa \xd9\x84\xd8\xa8\xd8\xb9\xd8\xb6 \xd9\x87\xd8\xb0\xd9\x87 \xd8\xa7\xd9\x84\xd8\xaf\xd9\x88\xd9\x84 \xd8\xa8\xd8\xa7\xd9\x84\xd8\xaa\xd8\xaf\xd8\xae\xd9\x84 \xd9\x81\xd9\x8a \xd8\xa7\xd9\x84\xd8\xb4\xd8\xa3\xd9\x86 \xd8\xa7\xd9\x84\xd8\xb9\xd8\xb1\xd8\xa7\xd9\x82\xd9\x8a\xd8\x8c \xd9\x88\xd8\xa7\xd9\x84\xd8\xaa\xd9\x88\xd8\xb1\xd8\xb7 \xd9\x81\xd9\x8a \xd8\xaf\xd8\xb9\xd9\x85 \xd8\xb9\xd9\x85\xd9\x84\xd9\x8a\xd8\xa7\xd8\xaa \xd8\xa7\xd9\x84\xd8\xb9\xd9\x86\xd9\x81\xd8\x9f \xd9\x88\xd8\xa7\xd9\x84\xd9\x89 \xd8\xa7\xd9\x8a \xd9\x85\xd8\xaf\xd9\x89 \xd9\x8a\xd8\xa8\xd8\xaf\xd9\x88 \xd8\xa7\xd9\x84\xd9\x88\xd8\xb6\xd8\xb9 \xd9\x81\xd9\x8a \xd8\xa7\xd9\x84\xd8\xb9\xd8\xb1\xd8\xa7\xd9\x82 \xd8\xa7\xd9\x86\xd8\xb9\xd9\x83\xd8\xa7\xd8\xb3\xd8\xa7 \xd9\x84\xd9\x84\xd8\xb5\xd8\xb1\xd8\xa7\xd8\xb9\xd8\xa7\xd8\xaa \xd8\xa7\xd9\x84\xd8\xa5\xd9\x82\xd9\x84\xd9\x8a\xd9\x85\xd9\x8a\xd8\xa9 \xd9\x81\xd9\x8a \xd8\xa7\xd9\x84\xd9\x85\xd9\x86\xd8\xb7\xd9\x82\xd8\xa9\xd8\x9f \xd9\x87\xd9\x84 \xd8\xb3\xd8\xaa\xd8\xb3\xd9\x81\xd8\xb1 \xd8\xa7\xd9\x84\xd8\xac\xd9\x87\xd9\x88\xd8\xaf \xd8\xa7\xd9\x84\xd8\xaf\xd8\xa8\xd9\x84\xd9\x88\xd9\x85\xd8\xa7\xd8\xb3\xd9\x8a\xd8\xa9 \xd8\xa7\xd9\x84\xd8\xac\xd8\xa7\xd8\xb1\xd9\x8a\xd8\xa9 \xd8\xb9\xd9\x86 \xd8\xad\xd9\x84\xd9\x88\xd9\x84\xd8\x9f \xd9\x88\xd9\x83\xd9\x8a\xd9\x81 \xd8\xaa\xd9\x86\xd8\xb8\xd8\xb1 \xd9\x84\xd9\x84\xd8\xa7\xd8\xaa\xd9\x87\xd8\xa7\xd9\x85\xd8\xa7\xd8\xaa \xd9\x84\xd8\xa8\xd8\xb9\xd8\xb6 \xd9\x87\xd8\xb0\xd9\x87 \xd8\xa7\xd9\x84\xd8\xaf\xd9\x88\xd9\x84 \xd8\xa8\xd8\xa7\xd9\x84\xd8\xaa\xd8\xaf\xd8\xae\xd9\x84 \xd9\x81\xd9\x8a \xd8\xa7\xd9\x84\xd8\xb4\xd8\xa3\xd9\x86 \xd8\xa7\xd9\x84\xd8\xb9\xd8\xb1\xd8\xa7\xd9\x82\xd9\x8a\xd8\x8c \xd9\x88\xd8\xa7\xd9\x84\xd8\xaa\xd9\x88\xd8\xb1\xd8\xb7 \xd9\x81\xd9\x8a \xd8\xaf\xd8\xb9\xd9\x85 \xd8\xb9\xd9\x85\xd9\x84\xd9\x8a\xd8\xa7\xd8\xaa \xd8\xa7\xd9\x84\xd8\xb9\xd9\x86\xd9\x81\xd8\x9f \xd9\x88\xd8\xa7\xd9\x84\xd9\x89 \xd8\xa7\xd9\x8a \xd9\x85\xd8\xaf\xd9\x89 \xd9\x8a\xd8\xa8\xd8\xaf\xd9\x88 \xd8\xa7\xd9\x84\xd9\x88\xd8\xb6\xd8\xb9 \xd9\x81\xd9\x8a \xd8\xa7\xd9\x84\xd8\xb9\xd8\xb1\xd8\xa7\xd9\x82 \xd8\xa7\xd9\x86\xd8\xb9\xd9\x83\xd8\xa7\xd8\xb3\xd8\xa7 \xd9\x84\xd9\x84\xd8\xb5\xd8\xb1\xd8\xa7\xd8\xb9\xd8\xa7\xd8\xaa \xd8\xa7\xd9\x84\xd8\xa5\xd9\x82\xd9\x84\xd9\x8a\xd9\x85\xd9\x8a\xd8\xa9 \xd9\x81\xd9\x8a \xd8\xa7\xd9\x84\xd9\x85\xd9\x86\xd8\xb7\xd9\x82\xd8\xa9\xd8\x9f".'</textarea>
<br /><br />

<b>Select</b>
<select size="1" name="status"><option value="A">'."\xd7\xa9\xd7\x98 \xd7\x91\xd7\x99\xd7\x9d \xd7\x9e\xd7\x90\xd7\x95\xd7\x9b\xd7\x96\xd7\x91 \xd7\x95\xd7\x9c\xd7\xa4\xd7\xaa\xd7\xa2 \xd7\x9e\xd7\xa6\xd7\x90".'</option><option value="W" >'."\xd7\xa9\xd7\x98 \xd7\x91\xd7\x99\xd7\x9d \xd7\x9e\xd7\x90\xd7\x95\xd7\x9b\xd7\x96\xd7\x91 \xd7\x95\xd7\x9c\xd7\xa4\xd7\xaa\xd7\xa2 \xd7\x9e\xd7\xa6\xd7\x90".'</option><option value="I" selected="selected">'."\xd7\xa9\xd7\x98 \xd7\x91\xd7\x99\xd7\x9d \xd7\x9e\xd7\x90\xd7\x95\xd7\x9b\xd7\x96\xd7\x91 \xd7\x95\xd7\x9c\xd7\xa4\xd7\xaa\xd7\xa2 \xd7\x9e\xd7\xa6\xd7\x90".'</option><option value="X" >'."\xd7\xa9\xd7\x98 \xd7\x91\xd7\x99\xd7\x9d \xd7\x9e\xd7\x90\xd7\x95\xd7\x9b\xd7\x96\xd7\x91 \xd7\x95\xd7\x9c\xd7\xa4\xd7\xaa\xd7\xa2 \xd7\x9e\xd7\xa6\xd7\x90".'</option> </select>
<br /><br />



<b>Input Radio</b>
<input type="radio" name="pre_publication" value="0" checked="checked" > '."\xd7\xa9\xd7\x98 \xd7\x91\xd7\x99\xd7\x9d \xd7\x9e\xd7\x90\xd7\x95\xd7\x9b\xd7\x96\xd7\x91 \xd7\x95\xd7\x9c\xd7\xa4\xd7\xaa\xd7\xa2 \xd7\x9e\xd7\xa6\xd7\x90".' &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="pre_publication" value="1" > '."\xd7\xa9\xd7\x98 \xd7\x91\xd7\x99\xd7\x9d \xd7\x9e\xd7\x90\xd7\x95\xd7\x9b\xd7\x96\xd7\x91 \xd7\x95\xd7\x9c\xd7\xa4\xd7\xaa\xd7\xa2 \xd7\x9e\xd7\xa6\xd7\x90".' 
<br /><br />


<b>Input Radio</b>
<input type="radio" name="recommended" value="0" > '."\xd7\xa9\xd7\x98 \xd7\x91\xd7\x99\xd7\x9d \xd7\x9e\xd7\x90\xd7\x95\xd7\x9b\xd7\x96\xd7\x91 \xd7\x95\xd7\x9c\xd7\xa4\xd7\xaa\xd7\xa2 \xd7\x9e\xd7\xa6\xd7\x90".' &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="recommended" value="1" > '."\xd7\xa9\xd7\x98 \xd7\x91\xd7\x99\xd7\x9d \xd7\x9e\xd7\x90\xd7\x95\xd7\x9b\xd7\x96\xd7\x91 \xd7\x95\xd7\x9c\xd7\xa4\xd7\xaa\xd7\xa2 \xd7\x9e\xd7\xa6\xd7\x90".' &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="recommended" value="2"  checked="checked" > '."\xd7\xa9\xd7\x98 \xd7\x91\xd7\x99\xd7\x9d \xd7\x9e\xd7\x90\xd7\x95\xd7\x9b\xd7\x96\xd7\x91 \xd7\x95\xd7\x9c\xd7\xa4\xd7\xaa\xd7\xa2 \xd7\x9e\xd7\xa6\xd7\x90".' 
<br /><br />


<b>Input Text</b>
<input type="text" size="240" name="doi" value="'."\xd7\xa9\xd7\x98 \xd7\x91\xd7\x99\xd7\x9d \xd7\x9e\xd7\x90\xd7\x95\xd7\x9b\xd7\x96\xd7\x91 \xd7\x95\xd7\x9c\xd7\xa4\xd7\xaa\xd7\xa2 \xd7\x9e\xd7\xa6\xd7\x90".'"> 
<br /><br />


<input type="checkbox" name="QPC" value="ON" > '."\xd7\xa9\xd7\x98 \xd7\x91\xd7\x99\xd7\x9d \xd7\x9e\xd7\x90\xd7\x95\xd7\x9b\xd7\x96\xd7\x91 \xd7\x95\xd7\x9c\xd7\xa4\xd7\xaa\xd7\xa2 \xd7\x9e\xd7\xa6\xd7\x90".'<br>
<input type="checkbox" name="QPA" value="ON" > '."\xd7\xa9\xd7\x98 \xd7\x91\xd7\x99\xd7\x9d \xd7\x9e\xd7\x90\xd7\x95\xd7\x9b\xd7\x96\xd7\x91 \xd7\x95\xd7\x9c\xd7\xa4\xd7\xaa\xd7\xa2 \xd7\x9e\xd7\xa6\xd7\x90".'<br>
<input type="checkbox" name="QLY" value="ON" checked="checked" > '."\xd7\xa9\xd7\x98 \xd7\x91\xd7\x99\xd7\x9d \xd7\x9e\xd7\x90\xd7\x95\xd7\x9b\xd7\x96\xd7\x91 \xd7\x95\xd7\x9c\xd7\xa4\xd7\xaa\xd7\xa2 \xd7\x9e\xd7\xa6\xd7\x90".'
<br /><br />

<input type="submit" name="submit" value="'."\xd7\xa9\xd7\x98 \xd7\x91\xd7\x99\xd7\x9d \xd7\x9e\xd7\x90\xd7\x95\xd7\x9b\xd7\x96\xd7\x91 \xd7\x95\xd7\x9c\xd7\xa4\xd7\xaa\xd7\xa2 \xd7\x9e\xd7\xa6\xd7\x90".'" /><br /><br />[ <a href="index.php?mod=articles&sop=show&task=&post_num=20&searchterms=&ISSN=&rec=1&sort=&selcat=&navrow=0&navpage=1&task=">Cancel</a> ]

</form>



';

//==============================================================
//==============================================================
//==============================================================
if ($_REQUEST['html']) { echo '<html><head><style>'.file_get_contents('mpdfstyletables.css').'</style></head><body dir="rtl">'.$html.'</body></html>'; exit; }
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

$mpdf=new mPDF('ar','A4','','',32,25,27,25,16,13); 


	// consider reducing lineheight when using columns - especially if vAligned justify
	$mpdf->default_lineheight_correction = 1.2;

	// LOAD a stylesheet
	$stylesheet = file_get_contents('mpdfstyletables.css');
	$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text


	$mpdf->WriteHTML($html,2);




	$mpdf->Output('mpdf.pdf','I');
	exit;
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================


?>