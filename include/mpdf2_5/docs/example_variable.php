<?php

define('_MPDF_PATH','../');
include("../mpdf.php");

//==============================================================
//==============================================================
$html = '
<p><a name="top"></a>P: Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. Suspendisse potenti. Ut a eros at ligula vehicula pretium. Maecenas feugiat pede vel risus. <span title="Nulla is marked by a span">Nulla</span> et lectus. Fusce eleifend neque sit amet erat. Integer <span style="color: #DD0000">XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX</span> consectetuer nulla non orci. Morbi feugiat pulvinar dolor. Cras odio. Donec mattis, nisi id euismod auctor, neque metus pellentesque risus, XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX at eleifend lacus sapien et risus. Phasellus metus. Phasellus feugiat, lectus ac aliquam molestie, leo lacus tincidunt turpis, vel aliquam quam odio et sapien. Mauris ante pede, auctor ac, suscipit quis, malesuada sed, nulla. Integer sit amet odio sit amet lectus luctus euismod. Donec et nulla. Sed quis orci. </p>
<p style="color: #BB0000">XXXXXXXXXXXXXXXXXXXXXXXXXXXXXZZZZZZZZ</p>
';
$mpdf=new mPDF(); 
$mpdf->use_embeddedfonts_1252 = true;
//$mpdf->SetProtection(array(),'','bread');
$mpdf->WriteHTML($html);
$mpdf->AddPage();
$mpdf->WriteHTML($html);

$mpdf->Output('test.pdf','F'); 
unset( $mpdf );
//==============================================================
//==============================================================

//
// Must set codepage,  protection (including a password - otherwise generates a random password) and compression exactly as original file
//
//===================================================
include(_MPDF_PATH."mpdfi/mpdfi.php");

// initiate PDF
$mpdf=new mPDFI(''); 	// rest of parameters do nothing in this case


//$mpdf->SetProtection(array(),'','bread'); 



// An array of one or more patterns to replace (or can just be string)
// if utf-8 mode, the patterns must not be in text with justified alignment
// use only ASCII characters
$search = array(
		'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX', 
		'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXZZZZZZZZ'
);

// An array of same number of replacement strings (or can just be string)
// Can contain any utf-8 encoded chars
$replacement = array(
		"personalised for Jos\xc3\xa9 Bloggs",
		"COPYRIGHT: Licensed to Jos\xc3\xa9 Bloggs"
);
//==============================================================
$mpdf->OverWrite('test.pdf', $search, $replacement, 'I', 'mpdf.pdf' ) ;
//==============================================================


//==============================================================
//==============================================================
//==============================================================
//==============================================================
exit;
//==============================================================
//==============================================================
//==============================================================
//==============================================================


?>