<?php
	//session_start(); //no usar antes de session_id($id);
	
	if(isset($_GET['SSID']) && $_GET['SSID']!="" && $_GET['SSID']!="no"){
		session_id($_GET['SSID']); //Reconstruccion de sesiones
		session_start();
	}
	else{
		session_start();
	}
	
	//Copy superglobals for debugging purposes
	if(($GLOBALS["debug_level"] & DBG_LVL_2) > 0){
		if (!empty($_POST)){
			$_GET = $_POST;
		}		
		if (!empty($_GET)){
			$_POST = $_GET;
		}
	}

	//Enable GZIP compression
	ob_start("ob_gzhandler");	

	//include "config.inc.php";
	include "config.inc.php";
	include "conexionbd.inc.php";		//Conexion a SQLAnywhere
	include "conexionContpaq.inc.php";  //Conexion a MSServer
	include "conexionAccess.inc.php";	//Conexion a tmpCheqpaq en Access
	$db->SetFetchMode(ADODB_FETCH_ASSOC);
	$dbNom->SetFetchMode(ADODB_FETCH_ASSOC);
	$dbAcs->SetFetchMode(ADODB_FETCH_ASSOC);
	
?>