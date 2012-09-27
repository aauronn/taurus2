<?php
	ini_set('display_errors', true);
	
	if( isset($_POST["opcion"]) && $_POST["opcion"] == "DEBUG_LEVEL"){
	
		$_SESSION[$GLOBALS["sistema"]]['debug_level'] = $_POST["level"];
		$_SESSION[$GLOBALS["sistema"]]['display_errors'] = $_POST["display_errors"] == 1? true:false ;
		
		print "Cambios realizados correctamente";
	}
	
	$GLOBALS["nombreArchivoPoliza"] = "";
	$GLOBALS["sLine"] = "";
	
	//Debugging
	ini_set('display_errors', isset($_SESSION[$GLOBALS["sistema"]]['display_errors'])?$_SESSION[$GLOBALS["sistema"]]['display_errors']:false);	//show php errors
	define('DBG_LVL_0', 0);				// no debug
	define('DBG_LVL_1', 1);				// show technical error messages
	define('DBG_LVL_2', 2);				// show extra debug info messages, $_POST = $_GET;
	define('DBG_LVL_3', 4);				// result not encrypted
	
	$GLOBALS["RC4_KEY"] = "89ac8c9bf9ac580ca2a806d14f3cd530f8d092d95eafa206cf4d26097d10d62888ad42ae1d23fc7c4a0750cf49309d455214b397a1dd3ef140f124ca71f3ee41"; //sha-512 (hex) de de csweb_atn_ciudadana
	$GLOBALS['debug_level'] = isset($_SESSION[$GLOBALS["sistema"]]['debug_level'])?$_SESSION[$GLOBALS["sistema"]]['debug_level']:DBG_LVL_3;
	
	$GLOBALS["nombre_sistema"] = "TAP";
	$GLOBALS["nombre_sistema_completo"] = "Taurus, Administrador de Proyectos";
	
	
	$GLOBALS["sistema"] = "TAP";
	$GLOBALS["url_host"] = "http://127.0.0.1/taurus2/";
	$GLOBALS["path_absoluto"] = "C:\\xampplite\\htdocs\\taurus2\\";
	$GLOBALS["archivos_upload_dir"] = $GLOBALS["path_absoluto"]."archivos_upload\\";
	$GLOBALS["archivos_upload_url"] = $GLOBALS["url_host"]."archivos_upload/";
	$GLOBALS["temp_archivos_dir"] = $GLOBALS["path_absoluto"]."temp_archivos\\";
	$GLOBALS["temp_archivos_url"] = $GLOBALS["url_host"]."temp_archivos/";
	$GLOBALS["documentos_dir"] = $GLOBALS["path_absoluto"]."documentos\\";
	$GLOBALS["documentos_url"] = $GLOBALS["url_host"]."documentos/";
	/*
	$GLOBALS["formato_fecha_entrada_bdd"] = "aaaa/mm/dd";
	$GLOBALS["formato_fecha_salida_bdd"] = "dd/mm/aaaa"; 
	$GLOBALS["formato_fecha_salida_mostrar"] = "dd/mm/aaaa";
	$GLOBALS["separador_fecha"] = "/";
	*/
	
	$GLOBALS["formato_fecha_entrada_bdd"] = "aaaa-mm-dd";
	$GLOBALS["formato_fecha_salida_bdd"] = "aaaa-mm-dd"; 
	$GLOBALS["formato_fecha_salida_mostrar"] = "aaaa-mm-dd";
	$GLOBALS["separador_fecha"] = "-";
	
	
	//CORREO
	$GLOBALS["smtp_Host"] = "smtp.gmail.com";
	$GLOBALS["smtp_Port"] = 587;
	$GLOBALS["smtp_Username"] = "aauronn@gmail.com";
	$GLOBALS["smtp_Password"] = "tidal1wave";
	$GLOBALS["SMTPSecure"] = "tls";
	
	//CHAT
	
	$GLOBALS["rtmpAddressChat"]="rtmp://localhost/chatVirtual";
	/*$GLOBALS["jdbcURL"]="jdbc:postgresql://localhost:5432/tutorias_dgest";
	$GLOBALS["bandwidth"]="8192";
	$GLOBALS["quality"]="80";
	$GLOBALS["fps"]="15";
	$GLOBALS["kfi"]="10";
	$GLOBALS["width"]="120";
	$GLOBALS["height"]="90";
	$GLOBALS["prefixNombreSo"]="tutorias_dgest";	
	$GLOBALS["_videollamada"]="true";
	$GLOBALS["_asignar"]="true";
	*/
	//$GLOBALS["rtmp_address"] = "rtmp://www.csweb.com.mx:1935/convertirDocs";
?>