<?php




define('_MPDF_PATH','../');
include("../mpdf.php");
//
$timeo_start = microtime(true);
//


$html = '
<h1>mPDF</h1>
<h2>Table of Contents (ToC)</h2>

<h4>Heading 4</h4>
';
//==============================================================
$lorem = "<p>Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. Suspendisse potenti. Ut a eros at ligula vehicula pretium. Maecenas feugiat pede vel risus. Nulla et lectus. Fusce eleifend neque sit amet erat. Integer consectetuer nulla non orci. Morbi feugiat pulvinar dolor. Cras odio. Donec mattis, nisi id euismod auctor, neque metus pellentesque risus, at eleifend lacus sapien et risus. Phasellus metus. Phasellus feugiat, lectus ac aliquam molestie, leo lacus tincidunt turpis, vel aliquam quam odio et sapien. Mauris ante pede, auctor ac, suscipit quis, malesuada sed, nulla. Integer sit amet odio sit amet lectus luctus euismod. Donec et nulla. Sed quis orci. </p><p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Proin vel sem at odio varius pretium. Maecenas sed orci. Maecenas varius. Ut magna ipsum, tempus in, condimentum at, rutrum et, nisl. Vestibulum interdum luctus sapien. Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. Maecenas consectetuer eros quis massa. Mauris semper velit vehicula purus. Duis lacus. Aenean pretium consectetuer mauris. Ut purus sem, consequat ut, fermentum sit amet, ornare sit amet, ipsum. Donec non nunc. Maecenas fringilla. Curabitur libero. In dui massa, malesuada sit amet, hendrerit vitae, viverra nec, tortor. Donec varius. Ut ut dolor et tellus adipiscing adipiscing. </p><p>Proin aliquet lorem id felis. Curabitur vel libero at mauris nonummy tincidunt. Donec imperdiet. Vestibulum sem sem, lacinia vel, molestie et, laoreet eget, urna. Curabitur viverra faucibus pede. Morbi lobortis. Donec dapibus. Donec tempus. Ut arcu enim, rhoncus ac, venenatis eu, porttitor mollis, dui. Sed vitae risus. In elementum sem placerat dui. Nam tristique eros in nisl. Nulla cursus sapien non quam porta porttitor. Quisque dictum ipsum ornare tortor. Fusce ornare tempus enim. </p><p>Maecenas arcu justo, malesuada eu, dapibus ac, adipiscing vitae, turpis. Fusce mollis. Aliquam egestas. In purus dolor, facilisis at, fermentum nec, molestie et, metus. Vestibulum feugiat, orci at imperdiet tincidunt, mauris erat facilisis urna, sagittis ultricies dui nisl et lectus. Sed lacinia, lectus vitae dictum sodales, elit ipsum ultrices orci, non euismod arcu diam non metus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. In suscipit turpis vitae odio. Integer convallis dui at metus. Fusce magna. Sed sed lectus vitae enim tempor cursus. Cras eu erat vel libero sodales congue. Sed erat est, interdum nec, elementum eleifend, pretium at, nibh. Praesent massa diam, adipiscing id, mollis sed, posuere et, urna. Quisque ut leo. Aliquam interdum hendrerit tortor. Vestibulum elit. Vestibulum et arcu at diam mattis commodo. Nam ipsum sem, ultricies at, rutrum sit amet, posuere nec, velit. Sed molestie mollis dui. </p>";
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
//==============================================================
//==============================================================

$mpdf=new mPDF('en-GB','A4','','',32,25,27,25,16,13); 

	$mpdf->use_embeddedfonts_1252 = true;	// false is default

	$mpdf->useOddEven = 1;	// Use different Odd/Even headers and footers and mirror margins (1 or 0)

	$mpdf->SetDisplayMode('fullpage','two');

	$mpdf->AddPage();

//==============================================================
	// Set Header and Footer
	$h = array (
  'odd' => 
  array (
    'R' => 
    array (
      'content' => '{PAGENO}',
      'font-size' => 8,
      'font-style' => 'B',
      'font-family' => 'DejaVuSansCondensed',
    ),
    'line' => 1,
  ),
  'even' => 
  array (
    'L' => 
    array (
      'content' => '{PAGENO}',
      'font-size' => 8,
      'font-style' => 'B',
      'font-family' => 'DejaVuSansCondensed',
    ),
    'line' => 1,
  ),
);

	$f = array (
  'odd' => 
  array (
    'L' => 
    array (
      'content' => '{DATE Y-m-d}',
      'font-size' => 8,
      'font-style' => 'BI',
      'font-family' => 'DejaVuSansCondensed',
    ),
    'C' => 
    array (
      'content' => '- {PAGENO} -',
      'font-size' => 8,
      'font-style' => '',
      'font-family' => '',
    ),
    'R' => 
    array (
      'content' => 'My Handbook',
      'font-size' => 8,
      'font-style' => 'BI',
      'font-family' => 'DejaVuSansCondensed',
    ),
    'line' => 1,
  ),
  'even' => 
  array (
    'L' => 
    array (
      'content' => 'My Handbook',
      'font-size' => 8,
      'font-style' => 'BI',
      'font-family' => 'DejaVuSansCondensed',
    ),
    'C' => 
    array (
      'content' => '- {PAGENO} -',
      'font-size' => 8,
      'font-style' => '',
      'font-family' => '',
    ),
    'R' => 
    array (
      'content' => '{DATE Y-m-d}',
      'font-size' => 8,
      'font-style' => 'BI',
      'font-family' => 'DejaVuSansCondensed',
    ),
    'line' => 0,
  ),
);

//==============================================================

	// LOAD a stylesheet
	$stylesheet = file_get_contents('mpdfstyleA4.css');
	$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

	$mpdf->WriteHTML('<h1>mPDF</h1><h2>Table of Contents & Bookmarks</h2>',2);


// TOC TABLE OF CONTENTS +++++++++++++++++++++++++++++++++++++++++++++
	// USE EITHER:
/*
// This should be inserted where it is intended to appear in the document
	$mpdf->AddPage('','E');		// Add a page if required to make current page an Even one
	$mpdf->setHeader($h);		// Set the header you want to appear FOLLOWING the ToC
	$mpdf->TOC('','',5,1);	// font-family (blank for default font), font-size (pts) blank for default, indent each level (mm) default 5
					// Last parameters set pagenumbering details from the new page onwards
	$mpdf->setFooter($f);		// Set the footer you want to appear FOLLOWING the ToC
	
// If you are not using Odd and Even pages, use:
//	$mpdf->setHeader($h);		// Set the header you want to appear FOLLOWING the ToC
//	$mpdf->AddPage('','',1);	// This time set any pagenumbering parameters on the AddPage
//	$mpdf->TOC('','',5);
//	$mpdf->setFooter($f);		// Set the footer you want to appear FOLLOWING the ToC


   for ($i = 1; $i<21; $i++) {
	$mpdf->Bookmark('Section '.$i,0,-1);	
	$mpdf->WriteHTML('<h4>Section '.$i.'</h4>',2);
	$mpdf->TOC_Entry('Section '.$i,0);
	$mpdf->WriteHTML($lorem,2);
   }
*/
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	// OR USE:

	$mpdf->WriteHTML('<pagebreak type="E" />');
	$mpdf->setHeader($h);		// Set the header you want to appear FOLLOWING the ToC
	$mpdf->WriteHTML('<TOC font="" font-size="" indent="5" resetpagenum="1" />');
	$mpdf->setFooter($f);		// Set the footer you want to appear FOLLOWING the ToC

	$mpdf->WriteHTML('<tocpagebreak name="Figures" toc-preHTML="&lt;h2&gt;FIGURES&lt;/h2&gt;" />');

	$mpdf->WriteHTML('This is text to go between the ToCs');

	$mpdf->WriteHTML('<tocpagebreak name="Graphs" toc-preHTML="&lt;h2&gt;GRAPHS&lt;/h2&gt;" />');

   for ($i = 1; $i<21; $i++) {
	$mpdf->WriteHTML('<h4>Section '.$i.'<bookmark content="Section '.$i.'" level="0" /><tocentry content="Section '.$i.'" level="0" /></h4>',2);
	if ($i % 2) { $mpdf->WriteHTML('<tocentry name="Figures" content="Figure '.$i.'" level="0" />',2); }
	if ($i % 3) { $mpdf->WriteHTML('<tocentry name="Graphs" content="Graph '.$i.'" level="0" />',2); }
	if ($i % 5) { $mpdf->WriteHTML('<tocentry name="ALL" content="Global '.$i.'" level="0" />',2); }
	$mpdf->WriteHTML($lorem,2);
   }
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


// This can be entered anytime before the document is output
	$mpdf->TOCheader = array();	// array as for setting header/footer
	$mpdf->TOCfooter = array();	// array as for setting header/footer
	$mpdf->TOCpreHTML = '<h2>Contents</h2>';	// HTML text to appear before table of contents
	$mpdf->TOCpostHTML = '';	// HTML text to appear after table of contents
	$mpdf->TOCbookmarkText = 'Content list';	// Text as it will appear in the Bookmarks (leave blank for none)

	$mpdf->WriteHTML('<div class="infobox">Generated in '.sprintf('%.2f',(microtime(true) - $timeo_start)).' seconds</div>',2);


	$mpdf->Output('mpdf.pdf','I');
	exit;
//==============================================================
//==============================================================


?>