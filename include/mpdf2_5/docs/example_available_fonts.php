<?php


define('_MPDF_PATH','../');
include("../mpdf.php");


$html = '
<style>
p { margin: 0 0 2mm 0; }
</style>
<h4>Available Fonts in mPDF</h4>
<h5>Supplied Fonts</h5>
<p style="font-family: freesans">FreeSans - The quick, sly fox jumped over the lazy brown dog.</p>
<p style="font-family: freemono">FreeMono - The quick, sly fox jumped over the lazy brown dog.</p>
<p style="font-family: freeserif">FreeSerif - The quick, sly fox jumped over the lazy brown dog.</p>
<p></p>
<p style="font-family: dejavusans">DejaVuSans - The quick, sly fox jumped over the lazy brown dog.</p>
<p style="font-family: dejavusanscondensed">DejaVuSansCondensed - The quick, sly fox jumped over the lazy brown dog.</p>
<p style="font-family: dejavusansmono">DejaVuSansMono - The quick, sly fox jumped over the lazy brown dog.</p>
<p style="font-family: dejavuserif">DejaVuSerif - The quick, sly fox jumped over the lazy brown dog.</p>
<p style="font-family: dejavuserifcondensed">DejaVuSerifCondensed - The quick, sly fox jumped over the lazy brown dog.</p>

<h5>Built-in Fonts</h5>
<p style="font-family: helvetica">Helvetica - The quick, sly fox jumped over the lazy brown dog.</p>
<p style="font-family: times">Times - The quick, sly fox jumped over the lazy brown dog.</p>
<p style="font-family: courier">Courier - The quick, sly fox jumped over the lazy brown dog.</p>
<p>Symbol - <span style="font-family: symbol">The quick, sly fox jumped over the lazy brown dog.</style> </p>
<p>Zapfdingbats - <span style="font-family: zapfdingbats">The quick, sly fox jumped over the lazy brown dog.</style> </p>

<h5>Unicode only</h5>
<p style="font-family: scheherazade; dir: rtl;">&laquo;Scheherazade&raquo; - Arabic, Persian (Farsi), Urdu and Pashto - '."\xd8\xa7\xd9\x84\xd8\xb3\xd8\xb1\xd9\x8a\xd8\xb9 \xd8\x8c \xd8\xab\xd8\xb9\xd9\x84\xd8\xa8 \xd9\x85\xd8\xa7\xd9\x83\xd8\xb1 \xd9\x88\xd9\x82\xd9\x81\xd8\xb2 \xd9\x81\xd9\x88\xd9\x82 \xd8\xa7\xd9\x84\xd9\x83\xd9\x84\xd8\xa8 \xd8\xa7\xd9\x84\xd9\x83\xd8\xb3\xd9\x88\xd9\x84 \xd8\xa7\xd9\x84\xd8\xa8\xd9\x86\xd9\x8a".'.</p>
<p style="font-family: garuda">&#39;Garuda&#39; Thai (sans-serif) - '."\xe0\xb8\x97\xe0\xb8\xb5\xe0\xb9\x88\xe0\xb8\xa3\xe0\xb8\xa7\xe0\xb8\x94\xe0\xb9\x80\xe0\xb8\xa3\xe0\xb9\x87\xe0\xb8\xa7, \xe0\xb8\x9b\xe0\xb8\xb2\xe0\xb8\x81\xe0\xb8\xa7\xe0\xb9\x88\xe0\xb8\xb2\xe0\xb8\x95\xe0\xb8\xb2\xe0\xb8\x82\xe0\xb8\xa2\xe0\xb8\xb4\xe0\xb8\x9a\xe0\xb8\xab\xe0\xb8\xa1\xe0\xb8\xb2\xe0\xb8\x88\xe0\xb8\xb4\xe0\xb9\x89\xe0\xb8\x87\xe0\xb8\x88\xe0\xb8\xad\xe0\xb8\x81 jumped \xe0\xb9\x80\xe0\xb8\xab\xe0\xb8\x99\xe0\xb8\xb7\xe0\xb8\xad\xe0\xb8\xaa\xe0\xb8\xb1\xe0\xb8\x99\xe0\xb8\xab\xe0\xb8\xa5\xe0\xb8\xb1\xe0\xb8\x87\xe0\xb8\xa2\xe0\xb8\xb2\xe0\xb8\xa7\xe0\xb8\x99\xe0\xb9\x89\xe0\xb8\xb3\xe0\xb8\x95\xe0\xb8\xb2\xe0\xb8\xa5\xe0\xb8\xaa\xe0\xb8\xb8\xe0\xb8\x99\xe0\xb8\xb1\xe0\xb8\x82".'.</p>
<p style="font-family: norasi">&#39;Norasi&#39; Thai (serif) - '."\xe0\xb8\x97\xe0\xb8\xb5\xe0\xb9\x88\xe0\xb8\xa3\xe0\xb8\xa7\xe0\xb8\x94\xe0\xb9\x80\xe0\xb8\xa3\xe0\xb9\x87\xe0\xb8\xa7, \xe0\xb8\x9b\xe0\xb8\xb2\xe0\xb8\x81\xe0\xb8\xa7\xe0\xb9\x88\xe0\xb8\xb2\xe0\xb8\x95\xe0\xb8\xb2\xe0\xb8\x82\xe0\xb8\xa2\xe0\xb8\xb4\xe0\xb8\x9a\xe0\xb8\xab\xe0\xb8\xa1\xe0\xb8\xb2\xe0\xb8\x88\xe0\xb8\xb4\xe0\xb9\x89\xe0\xb8\x87\xe0\xb8\x88\xe0\xb8\xad\xe0\xb8\x81 jumped \xe0\xb9\x80\xe0\xb8\xab\xe0\xb8\x99\xe0\xb8\xb7\xe0\xb8\xad\xe0\xb8\xaa\xe0\xb8\xb1\xe0\xb8\x99\xe0\xb8\xab\xe0\xb8\xa5\xe0\xb8\xb1\xe0\xb8\x87\xe0\xb8\xa2\xe0\xb8\xb2\xe0\xb8\xa7\xe0\xb8\x99\xe0\xb9\x89\xe0\xb8\xb3\xe0\xb8\x95\xe0\xb8\xb2\xe0\xb8\xa5\xe0\xb8\xaa\xe0\xb8\xb8\xe0\xb8\x99\xe0\xb8\xb1\xe0\xb8\x82".'.</p>

<p style="font-family: gb">&#39;GBK&#39; Chinese simplified (Adobe font) - '."\xe5\xbf\xab\xe9\x80\x9f\xef\xbc\x8c\xe7\x8b\xa1\xe7\x8c\xbe\xe7\x9a\x84\xe7\x8b\x90\xe7\x8b\xb8\xe8\xb7\xb3\xe8\xbf\x87\xe4\xba\x86\xe6\x87\x92\xe6\x83\xb0\xe7\x9a\x84\xe6\xa3\x95\xe8\x89\xb2\xe7\x8b\x97".'</p>

<p style="font-family: big5">&#39;BIG5&#39; Chinese Traditional (Adobe font) - '."\xe5\xbf\xab\xe9\x80\x9f\xef\xbc\x8c\xe7\x8b\xa1\xe7\x8c\xbe\xe7\x9a\x84\xe7\x8b\x90\xe7\x8b\xb8\xe8\xb7\xb3\xe9\x81\x8e\xe4\xba\x86\xe6\x87\xb6\xe6\x83\xb0\xe7\x9a\x84\xe6\xa3\x95\xe8\x89\xb2\xe7\x8b\x97".'</p>

<p style="font-family: sjis">&#39;SJIS&#39; Japanese (Adobe font) - '."\xe3\x82\xaf\xe3\x82\xa4\xe3\x83\x83\xe3\x82\xaf\xe3\x80\x81\xe6\xb5\xb7\xe5\x8d\x83\xe5\xb1\xb1\xe5\x8d\x83\xe3\x81\xae\xe4\xba\xba\xe9\x96\x93\xe3\x81\xae\xe6\x80\xa0\xe6\x83\xb0\xe3\x81\xaa\xe8\x8c\xb6\xe8\x89\xb2\xe3\x81\xae\xe7\x8a\xac\xe3\x82\x92\xe9\xa3\x9b\xe3\x81\xb3\xe8\xb6\x8a\xe3\x81\x88\xe3\x81\x9f".'</p>

<p style="font-family: uhc">&#39;UHC&#39; Korean (Adobe font) - '."\xeb\xb9\xa0\xeb\xa5\xb8, \xea\xb5\x90\xed\x99\x9c\xed\x95\x9c \xea\xb0\x88\xec\x83\x89 \xec\x97\xac\xec\x9a\xb0\xea\xb0\x80 \xea\xb2\x8c\xec\x9c\xbc\xeb\xa5\xb8 \xea\xb0\x9c\xeb\xa5\xbc \xeb\x9b\xb0\xec\x96\xb4 \xeb\x84\x98\xeb\x8a\x94".'</p>

<h5>Examples of others (not supplied with mPDF as copyright)</h5>
<p style="font-family: albertus">albertus - The quick, sly fox jumped over the lazy brown dog.</p>
<p style="font-family: verdana">verdana - The quick, sly fox jumped over the lazy brown dog.</p>
<p style="font-family: trebuchet">trebuchet - The quick, sly fox jumped over the lazy brown dog.</p>
<p style="font-family: calibri">calibri - The quick, sly fox jumped over the lazy brown dog.</p>
<p style="font-family: constantia">constantia - The quick, sly fox jumped over the lazy brown dog.</p>
<p style="font-family: franklin">franklin - The quick, sly fox jumped over the lazy brown dog.</p>
<p style="font-family: georgia">georgia - The quick, sly fox jumped over the lazy brown dog.</p>
<p style="font-family: tahoma">tahoma - The quick, sly fox jumped over the lazy brown dog.</p>


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

$mpdf=new mPDF('utf-8','A4','','',32,25,27,25,16,13); 

	$mpdf->use_embeddedfonts_1252 = false;	// false is default

// This is a fix to allow Scheherazade to show Arabic text which it would naturally ArabJoin to presentation forms 
// which don't show in Scheherazade
$mpdf->pregNonARABICchars = "\x{0600}-\x{06FF}\x{0750}-\x{077F}";

	$mpdf->SetDisplayMode('fullpage');

	$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list


	$mpdf->WriteHTML($html);

	$mpdf->Output('mpdf.pdf','I');
	exit;


?>