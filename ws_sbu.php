<?php
	include "include/entorno.inc.php";
	include "include/funciones.inc.php";	

	//*************************************
	//		DATAPROVIDER CLIENTES
	//*************************************
	if($_POST["opcion"]=="GET_SBU"){		
		$strsql = "SELECT * FROM ctsbu";
		print getXML($strsql,$db);
	}
	
	// *******************************************************
	// 			FILTRA SBU
	// *******************************************************
	if($_POST["opcion"]=="FILTRA_REGISTROS"){		
		$strsql="select a.*
		, (SELECT nombre_familia FROM ctfamilias b WHERE b.clave_familia = a.clave_familia) as nombre_familia
		from ctsbu a 
		";
		
		paginacion_buscar($db," $strsql ",array("clave_sbu","descripcion_sbu"),"idsbu",false,true,2);		
		
	}
	
	//*******************************************************
	//			BORRAR SBU
	//*******************************************************
	
	if($_POST["opcion"]=="BORRAR_REGISTROS"){	
		paginacion_borrar($db,"DELETE FROM ctsbu WHERE idsbu=[id]",true);	
	}
	
	
	//*******************************************************
	//			CREA SBU NUEVO
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
	
	
	//*******************************************************
	//			EDITA SBU
	//*******************************************************
	if($_POST["opcion"]=="EDITAR_REGISTRO"){
		$idsbu				=	textToDB(strtoupper($_POST["idsbu"]));
		$clave_sbu	 		=	textToDB(strtoupper($_POST["clave_sbu"]));
		$descripcion_sbu	=	textToDB(strtoupper($_POST["descripcion_sbu"]));
		$clave_familia		=	textToDB(strtoupper($_POST["clave_familia"]));
		
		if(trim($idsbu)==""){
			print "El ID SBU no puede estar vacío";
			exit;
		}
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
		
		$strsql = "UPDATE ctsbu SET clave_sbu='$clave_sbu', descripcion_sbu='$descripcion_sbu', 
					clave_familia='$clave_familia'
					WHERE idsbu=$idsbu";    
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