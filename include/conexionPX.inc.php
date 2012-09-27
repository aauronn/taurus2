<?php
	include "libs/adodb5/adodb.inc.php";
	
	$dbPx =&ADONewConnection('odbc');
	$dbPx->PConnect('TestPdox','','');

?>	