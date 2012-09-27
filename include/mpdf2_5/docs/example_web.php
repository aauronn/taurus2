<?php


// $url = "http://www.ltg.ed.ac.uk/~richard/unicode-sample-3-2.html"; 	// ALTERNATIVE Unicode Sample page

set_time_limit(600);


define('_MPDF_PATH','../');

include("../mpdf.php");

if ($_REQUEST['url']) { $url = $_REQUEST['url']; }
else { $url = "http://www.php.net/manual/en/function.iconv.php"; }

    $html = '';	
    if (ini_get('allow_url_fopen')) {
	$html = file_get_contents($url);
    }
    else {
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt ( $ch , CURLOPT_RETURNTRANSFER , 1 );
	$html = curl_exec($ch);
	curl_close($ch);
    }

//==============================================================
//==============================================================
//==============================================================
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

$mpdf=new mPDF('UTF-8','A4','','',0,0,0,0,5,5); 

	$mpdf->SetDefaultFont('DejaVuSansCondensed');
	$mpdf->SetDefaultFontSize('10');
	$mpdf->ignore_invalid_utf8 = true;
	$mpdf->allow_charset_conversion=true;

	// php.net has illegal nesting of tags therefore need to disable optional tags in mpdf
	$mpdf->allow_html_optional_endtags = false;

	$mpdf->SetDisplayMode('fullpage');

	$mpdf->setBasePath($url);

	if ($_REQUEST['screen']) { $mpdf->disablePrintCSS = true; }

	$mpdf->AddPage();

	$mpdf->WriteHTML($html);

	$mpdf->Output('mpdf.pdf','I');
	exit;
//==============================================================
//==============================================================


?>