<?php
	//Adodb connection
	$GLOBALS['tipo_base_datos'] = "mssql";
	$GLOBALS['host_base_datos'] = "172.16.0.88";
	//$GLOBALS['host_base_datos'] = "ABSACITRIX/COMPAC";
	$GLOBALS['usuario_base_datos'] = "nom2";
	$GLOBALS['clave_base_datos'] = "sql1sql1";
	$GLOBALS['nombre_base_datos'] = "ctnomabsaok2011";
	
	include "libs/adodb5/adodb.inc.php";
	
	//$dbNom = &ADONewConnection("mssql");
	//$dbNom->PConnect("172.16.0.88", "nom2", "sql1sql1", "ctnomabsaok2011");
	
//	$db = ADONewConnection($databasetype); 
//$db->debug = true; 
//$db->Connect($server, $user, $password, $database); 
	include "libs/adodb5/adodb.inc.php";
	
	$dbNom =&ADONewConnection('odbc');
	$dbNom->Connect('Nomina','nom2','sql1sql');
?>