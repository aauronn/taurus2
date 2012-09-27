<?php



$html = '
<h1>mPDF</h1>
<h2>Colors</h2>
<p>Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. Suspendisse potenti. Ut a eros at ligula vehicula pretium. Maecenas feugiat pede vel risus. Nulla et lectus. Fusce eleifend neque sit amet erat. Integer consectetuer nulla non orci. Morbi feugiat pulvinar dolor. Cras odio. Donec mattis, nisi id euismod auctor, neque metus pellentesque risus, at eleifend lacus sapien et risus. Phasellus metus. Phasellus feugiat, lectus ac aliquam molestie, leo lacus tincidunt turpis, vel aliquam quam odio et sapien. Mauris ante pede, auctor ac, suscipit quis, malesuada sed, nulla. Integer sit amet odio sit amet lectus luctus euismod. Donec et nulla. Sed quis orci. </p>

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

$mpdf=new mPDF('win-1252','A4-L'); 

$mpdf->use_embeddedfonts_1252 = true;	// false is default
$mpdf->SetDisplayMode('fullpage');

$mpdf->SetFontSize(6);

//$mpdf->WriteHTML($html);

$pm = 8;///page margin
$w = 20;
$h = 10;
$m = 5;

for($k=0;$k<=8;$k++) {	// Black - page group
 for($y=0;$y<=10;$y++) {	// Yellow - page group
  $mpdf->AddPage();
  for($i=0;$i<=10;$i++) {	// Rows (Magenta)
    for($j=0; $j<=10; $j++) {	// Cols (Cyan)
	$mpdf->SetXY($pm+($j*($w+$m)),$pm+($i*($h+$m)));
	$mpdf->SetFillColor(($j*10),($i*10),($y*10),($k*10));
	$mpdf->Cell($w,$h,'',0,0,'L',1);
	$mpdf->SetXY($pm+($j*($w+$m)),$h+$pm+($i*($h+$m)));
	$txt = 'C:'.($j*10).' M:'.($i*10).' Y:'.($y*10).' K:'.($k*10);
	$mpdf->Cell($w,4,$txt,0,0,'L');

    }
  }
 }
}

$mpdf->Output('mpdf.pdf','I');

exit;
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================


?>