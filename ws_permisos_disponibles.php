<?php

include "include/entorno.inc.php";
include "include/funciones.inc.php";



	// --------------------------------
	// 		CATALOGO DE CIUDADES (USADO PARA COMBOBOX)
	// --------------------------------
if($_POST["opcion"]=="CAT_PERMISOS_GRUPOS"){	
	$strsql = "SELECT * FROM tbpermisos_grupos order by clavepermisogrupo ";
	print getXML($strsql,$db);
}




	// --------------------------------
	// 		FILTRA PERMISOS
	// --------------------------------
if($_POST["opcion"]=="FILTRA_REGISTROS"){		
	paginacion_buscar($db,"SELECT * FROM tbpermisos_disponibles",array("accion","descripcion","modulo","clavepermiso","clavepermisogrupo"),"clavepermisogrupo",false);		
}







	// --------------------------------
	// 		BORRA PERMISOS
	// --------------------------------
if($_POST["opcion"]=="BORRA_REGISTRO"){	
	paginacion_borrar($db,"DELETE FROM tbpermisos_disponibles WHERE idpermiso=[id]");	
}







	// --------------------------------
	// 		NUEVO PERMISO
	// --------------------------------
if($_POST["opcion"]=="NUEVO_REGISTRO"){
	$accion      	   = textToDB($_POST["accion"]);
	$descripcion       = textToDB($_POST["descripcion"]);
	$modulo 		   = textToDB($_POST["modulo"]);
	$clavepermiso      = textToDB($_POST["clavepermiso"]);
	$clavepermisogrupo = textToDB($_POST["clavepermisogrupo"]);
	
	
	if(trim($accion)==""){
		print "La accion no puede estar vacía";
		exit;
	}
	if(trim($descripcion)==""){
		print "La descripcion no puede estar vacía";
		exit;
	}

	if(trim($modulo)==""){
		print "El modulo no puede estar vacío";
		exit;
	}
	
	if(trim($clavepermiso)==""){
		print "La clave permiso no puede estar acía";
		exit;
	}
	if(trim($clavepermisogrupo)==""){
		print "La clave permiso grupo no puede estar vacía";
		exit;
	}
	
	
	$strsql="INSERT INTO tbpermisos_disponibles (accion,descripcion,modulo,clavepermiso,clavepermisogrupo) VALUES ('$accion','$descripcion','$modulo','$clavepermiso','$clavepermisogrupo') ";
	
	$db->starttrans();	
	
	if(($rs=$db->execute($strsql))){
		if($db->completetrans()){
			print "ok";
		}
		else{
			$db->rollbacktrans();
			print "E2: ".$db->errormsg()." - ".$strsql;//dbg								
		}
	}
	else{
		if(strpos(strtoupper($db->ErrorMsg()),strtoupper("tbpermisos_disponibles_idpermiso_seq"))!=0){
			print "yaexiste";
		}
		else{
			print "E1: ".$db->errormsg();//dbg
		}
	}	
}





	// --------------------------------
	// 		EDITA ZONA
	// --------------------------------
if($_POST["opcion"]=="EDITA_REGISTRO"){	
	$idpermiso     	   = textToDB($_POST["id"]);
	$accion      	   = textToDB($_POST["accion"]);
	$descripcion       = textToDB($_POST["descripcion"]);
	$modulo 		   = textToDB($_POST["modulo"]);
	$clavepermiso      = textToDB($_POST["clavepermiso"]);
	$clavepermisogrupo = textToDB($_POST["clavepermisogrupo"]);
	
	
	if($idpermiso=="" || !is_numeric($idpermiso)){
		print "Error id permiso";
		exit;
	}
	if(trim($accion)==""){
		print "La accion no puede estar vacía";
		exit;
	}
	if(trim($descripcion)==""){
		print "La descripcion no puede estar vacía";
		exit;
	}

	if(trim($modulo)==""){
		print "El modulo no puede estar vacío";
		exit;
	}
	
	if(trim($clavepermiso)==""){
		print "La clave permiso no puede estar acía";
		exit;
	}
	if(trim($clavepermisogrupo)==""){
		print "La clave permiso grupo no puede estar vacía";
		exit;
	}
	
	
	$strsql="UPDATE tbpermisos_disponibles SET accion='$accion', descripcion='$descripcion', modulo='$modulo', clavepermiso='$clavepermiso', clavepermisogrupo='$clavepermisogrupo' WHERE idpermiso='$idpermiso' ";
	
	$db->starttrans();	
	if(($rs=$db->execute($strsql))){
		if($db->completetrans()){
			print "ok";
		}
		else{
			$db->rollbacktrans();
			print "E2: ".$db->errormsg();
		}
	}else{
		if(strpos(strtoupper($db->ErrorMsg()),strtoupper("tbpermisos_disponibles_idpermiso_seq"))!=0){
			print "yaexiste";
		}
		else{
			print "E1: ".$db->errormsg();
		}
	}
}







?>