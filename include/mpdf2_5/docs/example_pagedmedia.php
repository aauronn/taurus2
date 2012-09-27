<?php




define('_MPDF_PATH','../');
include("../mpdf.php");

$headerhtml = '
<htmlpageheader name="myHTMLHeaderOdd" style="display:none">
<div style="background-color:#BBEEFF" align="center"><b>{PAGENO}</b></div>
</htmlpageheader>
<htmlpageheader name="myHTMLHeaderEven" style="display:none">
<div style="background-color:#EFFBBE" align="center"><b><i>{PAGENO}</i></b></div>
</htmlpageheader>
<htmlpagefooter name="myHTMLFooterOdd" style="display:none">
<div style="background-color:#CFFFFC" align="center"><b>{PAGENO}</b></div>
</htmlpagefooter>
<htmlpagefooter name="myHTMLFooterEven" style="display:none">
<div style="background-color:#FFCCFF" align="center"><b><i>{PAGENO}</i></b></div>
</htmlpagefooter>


<pageheader name="myHeader2Odd" content-left="My Book Title" content-center="myHeader2Odd" content-right="{PAGENO}" header-style="font-family:sans-serif; font-size:8pt; font-weight:bold; color:#008800;" header-style-left="" line="on" />

<pagefooter name="myFooter2Even" content-left="{PAGENO}" content-center="myFooter2Even" content-right="{DATE j-m-Y}" footer-style="font-family:sans-serif; font-size:10pt; color:#000880;" footer-style-left="font-weight:bold; " line="on" />

';


$html2 = "<p>Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. Suspendisse potenti. Ut a eros at ligula vehicula pretium. Maecenas feugiat pede vel risus. Nulla et lectus. Fusce eleifend neque sit amet erat. Integer consectetuer nulla non orci. Morbi feugiat pulvinar dolor. Cras odio. Donec mattis, nisi id euismod auctor, neque metus pellentesque risus, at eleifend lacus sapien et risus. Phasellus metus. Phasellus feugiat, lectus ac aliquam molestie, leo lacus tincidunt turpis, vel aliquam quam odio et sapien. Mauris ante pede, auctor ac, suscipit quis, malesuada sed, nulla. Integer sit amet odio sit amet lectus luctus euismod. Donec et nulla. Sed quis orci. </p><p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Proin vel sem at odio varius pretium. Maecenas sed orci. Maecenas varius. Ut magna ipsum, tempus in, condimentum at, rutrum et, nisl. Vestibulum interdum luctus sapien. Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. Maecenas consectetuer eros quis massa. Mauris semper velit vehicula purus. Duis lacus. Aenean pretium consectetuer mauris. Ut purus sem, consequat ut, fermentum sit amet, ornare sit amet, ipsum. Donec non nunc. Maecenas fringilla. Curabitur libero. In dui massa, malesuada sit amet, hendrerit vitae, viverra nec, tortor. Donec varius. Ut ut dolor et tellus adipiscing adipiscing. </p><p>Proin aliquet lorem id felis. Curabitur vel libero at mauris nonummy tincidunt. Donec imperdiet. Vestibulum sem sem, lacinia vel, molestie et, laoreet eget, urna. Curabitur viverra faucibus pede. Morbi lobortis. Donec dapibus. Donec tempus. Ut arcu enim, rhoncus ac, venenatis eu, porttitor mollis, dui. Sed vitae risus. In elementum sem placerat dui. Nam tristique eros in nisl. Nulla cursus sapien non quam porta porttitor. Quisque dictum ipsum ornare tortor. Fusce ornare tempus enim. </p><p>Maecenas arcu justo, malesuada eu, dapibus ac, adipiscing vitae, turpis. Fusce mollis. Aliquam egestas. In purus dolor, facilisis at, fermentum nec, molestie et, metus. Vestibulum feugiat, orci at imperdiet tincidunt, mauris erat facilisis urna, sagittis ultricies dui nisl et lectus. Sed lacinia, lectus vitae dictum sodales, elit ipsum ultrices orci, non euismod arcu diam non metus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. In suscipit turpis vitae odio. Integer convallis dui at metus. Fusce magna. Sed sed lectus vitae enim tempor cursus. Cras eu erat vel libero sodales congue. Sed erat est, interdum nec, elementum eleifend, pretium at, nibh. Praesent massa diam, adipiscing id, mollis sed, posuere et, urna. Quisque ut leo. Aliquam interdum hendrerit tortor. Vestibulum elit. Vestibulum et arcu at diam mattis commodo. Nam ipsum sem, ultricies at, rutrum sit amet, posuere nec, velit. Sed molestie mollis dui. </p>";
//==============================================================


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

	$mpdf=new mPDF('en-GB','A4','','',5,5,5,5,0,0); 

	$mpdf->use_embeddedfonts_1252 = true;	// false is default

	$mpdf->useOddEven = 1;	// Use different Odd/Even headers and footers and mirror margins (1 or 0)

	$mpdf->SetDisplayMode('fullpage','two');


//==============================================================

	// LOAD a stylesheet
	$stylesheet = file_get_contents('mpdfstylePaged.css');
	$s = '';

//	$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text
	$s .= '<style>'.$stylesheet.'</style>';

//	$mpdf->WriteHTML($headerhtml );	// Sets the Headers and Footers from HTML code
	$s .= $headerhtml;
//==============================================================

for ($j = 1; $j<7; $j++) { 
  $s .= '<h1 class="heading'.$j.'">mPDF '.$j.'</h1><h2>Paged Media using CSS</h2>';
   for ($x = 1; $x<3; $x++) {
	$s .= $html2;
   }
}

    $mpdf->WriteHTML($s);

	$mpdf->Output('mpdf.pdf','I');
	exit;
//==============================================================
//==============================================================


?>