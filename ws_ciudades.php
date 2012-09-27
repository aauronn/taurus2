<?php
	include "include/entorno.inc.php";
	include "include/funciones.inc.php";
	
	//-----------------------------------------
	//			DATA PROVIDERS
	//-----------------------------------------
	
	//Para usarlo en produccion se usa ctciudades, para usarlo en desarrollo ciudades
	
	if($_POST["opcion"]=="CAT_CIUDADES"){	
		$strsql = "SELECT clave_ciudad, ciudad, estado, clave_estado, pais FROM ctciudades ORDER BY clave_ciudad";
		print getXML($strsql,$db);
	}
	
?>