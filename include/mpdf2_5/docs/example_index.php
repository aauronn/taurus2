<?php




define('_MPDF_PATH','../');
include("../mpdf.php");
//
$timeo_start = microtime(true);
//


$html = '
<h1>mPDF</h1>
<h2>Index</h2>

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

	$mpdf->SetDisplayMode('fullpage');

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

	$mpdf->startPageNums();	// Required for TOC use after AddPage(), and to use Headers and Footers
	$mpdf->setHeader($h);
	$mpdf->setFooter($f);

	// LOAD a stylesheet
	$stylesheet = file_get_contents('mpdfstyleA4.css');
	$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

	$mpdf->WriteHTML('<h1>mPDF</h1><h2>Index</h2>',2);


// INDEX +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	// USE EITHER:
   for ($j = 1; $j<20; $j++) {

	$html = '';
	// Split $lorem into words
	$words = preg_split('/([\s,\.]+)/',$lorem,-1,PREG_SPLIT_DELIM_CAPTURE);
	foreach($words as $i => $e) {

	   if($i%2==0) {
			//TEXT
		$x =  rand(1,10); 
		if (preg_match('/^[a-zA-Z]{4,99}$/',$e) && ($x > 8)) {
			// If it is just a word use it as an index entry
			$content = ucfirst(trim($e));

			//$html .= '<indexentry content="'.$content.'" />';	// OR
			$mpdf->Reference($content);
			$html .= '<i>'.$e . '</i>';

		}
		else { $html .= $e; }
	   }
	   else { $html .= $e; }
	}


	$mpdf->WriteHTML($html,2);
   }


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

// Index - This should be inserted where it is intended to appear in the document
	$mpdf->AddPage('','E');	
	$mpdf->setHeader();		// clear/set header - before adding page
	$mpdf->AddPage();	
	$mpdf->setFooter();		// clear/set footer - after adding page
	$mpdf->WriteHTML('<h2>Index</h2>',2);
	$mpdf->CreateReference(2, '', '', 3, 1, '', 5, 'serif','sans-serif');

/* Parameters for CreateReference() (Index)
	index_columns - no. of columns
	index_font-size - blank = default font-size
	index_line-height	 - float value e.g. 1.1 - default = 1.0
	index_indent_margin - offset (in mm) for following lines - (uses hanging margin) 
	index_headings - 1 or 0 - Use First letters to divide entries
	index_heading_font-size in pts (blank = default = 1.8 x the entry font size)
	gap - gap between columns (in mm) - default = 5
	index_font font family for entries
	index_heading_font font for First letters used to divide entries
*/




	$mpdf->WriteHTML('<div class="infobox">Generated in '.sprintf('%.2f',(microtime(true) - $timeo_start)).' seconds</div>',2);


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