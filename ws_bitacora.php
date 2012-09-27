<?php
	include "include/entorno.inc.php";
	include "include/funciones.inc.php";	
	
	// *******************************************************
	// 			FILTRA MENSAGES BITACORA
	// *******************************************************
	if($_POST["opcion"]=="FILTRA_REGISTROS"){		
		$strsql="select a.*
		, (SELECT nombre_familia FROM ctfamilias b WHERE b.clave_familia = a.clave_familia) as nombre_familia
		from ctsbu a 
		";
		
		paginacion_buscar($db," $strsql ",array("clave_sbu","descripcion_sbu"),"idsbu",false,true,2);		
		
	}
	
	//*******************************************************
	//			BORRAR MENSAGES BITACORA
	//*******************************************************
	
	if($_POST["opcion"]=="BORRAR_REGISTROS"){	
		paginacion_borrar($db,"DELETE FROM ctsbu WHERE idsbu=[id]",true);	
	}
	
	
	//*******************************************************
	//			CREA MENSAGE NUEVO BITACORA
	//*******************************************************
	if($_POST["opcion"]=="NUEVO_REGISTRO"){
		$clave_sbu	 		=	textToDB(strtoupper($_POST["clave_sbu"]));
		$descripcion_sbu	=	textToDB(strtoupper($_POST["descripcion_sbu"]));
		$clave_familia		=	textToDB(strtoupper($_POST["clave_familia"]));
		
		if(trim($clave_sbu)==""){
			print "La clave SBU no puede estar vacía";
			exit;
		}
		if(trim($descripcion_sbu)==""){
			print "Descripcion SBU no puede estar vacío";
			exit;
		}
		if(trim($clave_familia)==""){
			print "La Clave Familia no puede estar vacia";
			exit;
		}
		
		
		$strsql = "INSERT INTO ctsbu (clave_sbu, descripcion_sbu, clave_familia)
					VALUES ('$clave_sbu', '$descripcion_sbu', '$clave_familia')";
		$db->starttrans();
		
		if(($rs=$db->execute($strsql))){
			if($db->completetrans()){
				print "ok";
			}
			else{
				$db->rollbacktrans();
				print "E3: ".$db->errormsg()." - ".$strsql;//dbg								
			}
		}
		else{
			if(strpos(strtoupper($db->ErrorMsg()),strtoupper("ctplanteles_idplantel_key"))!=0){
				print "yaexiste";
			}
			else{
				print "E1: ".$db->errormsg();//dbg
			}
		}	
		
	}
	
?>