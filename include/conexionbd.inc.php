<?php
	//Adodb connection
	/*$GLOBALS['tipo_base_datos'] = "postgres";
	$GLOBALS['host_base_datos'] = "localhost";
	$GLOBALS['usuario_base_datos'] = "luis";
	$GLOBALS['clave_base_datos'] = "abc123";
	$GLOBALS['nombre_base_datos'] = "taurus";
	*/
	$GLOBALS['tipo_base_datos'] = "sqlanywhere";
	$GLOBALS['host_base_datos'] = "127.0.0.1";
	$GLOBALS['usuario_base_datos'] = "dba";
	$GLOBALS['clave_base_datos'] = "sqlsql";
	$GLOBALS['nombre_base_datos'] = "taurus";
	
	
	
	// Configure connection parameters
	//GALAXYSERVER
//	$db_host  		= "172.16.0.85;PORT=2638"; 
//	$db_server_name = "GALAXYSERVER";
	
	//SERVERLOCAL
//	$db_host        = "localhost;PORT=2638";
//	$db_server_name = "serverluis";
	
	//SERVERTAURUS
//	$db_host        = "172.16.0.139;PORT=2638";
//	$db_server_name = "servertaurus";
	
	//SERVERTAURUS2
	$db_host		= "172.16.0.139;PORT=49152";
	$db_server_name = "servertaurus2";
	
	//SERVERTAURUS  ABSANORTE
//	$db_host        = "172.16.0.139;PORT=49152";
//	$db_server_name = "servertaurusnorte";
	
	$db_name        = "galaxy";
	
	$db_file        = 'c:\ABSA\respaldo_abc\abc.db';
	$db_conn_name   = "ServidorGalaxy";
	
	$db_user        = "dba";
	$db_pass        = "sqlsql";
	
	//================================================================
	//Driver={SYBASE SYSTEM 11};Srvr=myServerAddress;Uid=myUsername;Pwd=myPassword;Database=myDataBase;
	
	$connect_string = "Driver={SQL Anywhere 11};".
					  "CommLinks=tcpip(Host=$db_host);".
                      "ServerName=$db_server_name;".
                      //"DatabaseName=$db_name;".
                      //"DatabaseFile=$db_file;".
                      //"ConnectionName=$db_conn_name;".
                      "uid=$db_user;".
					  "pwd=$db_pass";
	
	include "libs/adodb5/adodb.inc.php";
	
	//$db = NewADOConnection($GLOBALS['tipo_base_datos']);
	//$db->Connect($GLOBALS['host_base_datos'], $GLOBALS['usuario_base_datos'], $GLOBALS['clave_base_datos'], $GLOBALS['nombre_base_datos']);
	
	$db =&ADONewConnection('sqlanywhere');
	$db->Connect($connect_string,'','');
	//$db =&ADONewConnection('odbc');
	//$db->PConnect('pdxPrueba','','');
	
	
?>