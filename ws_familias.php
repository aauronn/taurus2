<?php
	include "include/entorno.inc.php";
	include "include/funciones.inc.php";	

	//*************************************
	//		DATAPROVIDER CLIENTES
	//*************************************
	if($_POST["opcion"]=="CAT_FAMILIAS"){		
		$strsql = "SELECT * FROM ctfamilias";
		print getXML($strsql,$db);
	}
	
	// *******************************************************
	// 			FILTRA FAMILIAS
	// *******************************************************
	if($_POST["opcion"]=="FILTRA_REGISTROS"){		
		$strsql="SELECT *, f_sbu_to_string(sbu) as sbu2 FROM ctfamilias";
		
		paginacion_buscar($db," $strsql ",array("clave_familia","nombre_familia"),"idfamilia",false,true,2);		
		
	}
	
	//*******************************************************
	//			BORRAR FAMILIAS
	//*******************************************************
	
	if($_POST["opcion"]=="BORRAR_REGISTROS"){	
		paginacion_borrar($db,"DELETE FROM ctfamilias WHERE idfamilia=[id]",true);	
	}
	
	
	
	//*******************************************************
	//			CREA FAMILIA
	//*******************************************************
	if($_POST["opcion"]=="NUEVO_REGISTRO"){
		$clave_familia 			=	textToDB(strtoupper($_POST["clave_familia"]));
		$nombre_familia			=	textToDB(strtoupper($_POST["nombre_familia"]));
		$descripcion_familia 	=	textToDB(strtoupper($_POST["descripcion_familia"]));
		$sbu 					=	textToDB(strtoupper($_POST["sbu"]));
		
		if(trim($clave_familia)==""){
			print "La clave familia no puede estar vacía";
			exit;
		}
		if(trim(nombre_familia)==""){
			print "Nombre Familia no puede estar vacío";
			exit;
		}
		if(trim($descripcion_familia)==""){
			print "Descripcion Familia no puede estar vacío";
			exit;
		}
		if(trim($sbu)==""){
			$sbu = 0;
		}
				
		$strsql = "INSERT INTO ctfamilias (clave_familia, nombre_familia, descripcion_familia, sbu)
					VALUES ('$clave_familia', '$nombre_familia', '$descripcion_familia', $sbu)";
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
	
	
	//*******************************************************
	//			EDITA FAMILIA
	//*******************************************************
	if($_POST["opcion"]=="EDITAR_REGISTRO"){
		$idfamilia				=	textToDB(strtoupper($_POST["idfamilia"]));
		$clave_familia 			=	textToDB(strtoupper($_POST["clave_familia"]));
		$nombre_familia			=	textToDB(strtoupper($_POST["nombre_familia"]));
		$descripcion_familia 	=	textToDB(strtoupper($_POST["descripcion_familia"]));
		$sbu 					=	textToDB(strtoupper($_POST["sbu"]));
		
		if(trim($clave_familia)==""){
			print "La clave familia no puede estar vacía";
			exit;
		}
		if(trim(nombre_familia)==""){
			print "Nombre Familia no puede estar vacío";
			exit;
		}
		if(trim($descripcion_familia)==""){
			print "Descripcion Familia no puede estar vacío";
			exit;
		}
		if(trim($sbu)==""){
			print "El SBU no puede estar vacío";
			exit;
		}
		
		$strsql = "UPDATE ctfamilias SET clave_familia='$clave_familia', nombre_familia='$nombre_familia', 
					descripcion_familia='$descripcion_familia', sbu=$sbu
					WHERE idfamilia=$idfamilia";    
		//print $strsql;
		
		$db->starttrans();	
		
		if(($rs=$db->execute($strsql))){
			if($db->completetrans()){
				print "ok";
			}
			else{
				$db->rollbacktrans();
				print "E4: ".$db->errormsg();
			}
		}else{
			if(strpos(strtoupper($db->ErrorMsg()),strtoupper("ctplanteles_idplantel_key"))!=0){
				print "yaexiste";
			}
			else{
				print "E1: ".$db->errormsg();
			}
		}
	}
	
?>