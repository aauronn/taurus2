<?php

	include "include/entorno.inc.php";
	include "include/funciones.inc.php";
	
	//*************************************
	//		DATAPROVIDER FABRICANTES Y SBU
	//*************************************
	if($_POST["opcion"]=="CAT_FABRICANTES"){		
		$strsql = "SELECT * FROM ctfabricantes";
		print getXML($strsql,$db);
	}
	
	// --------------------------------
	// 		FABRICANTES PROYECTO
	// --------------------------------
	if($_POST["opcion"]=="TRAER_FABRICANTES_PROYECTO"){		
		$idproyecto = $_POST["idproyecto"];
		$strsql = "SELECT a.*, b.nombre_fabricante as fabricante, c.descripcion_sbu as nombre_sbu 
			FROM tbproyectos_fabricantes a
			LEFT JOIN ctfabricantes b on a.clave_fabricante=b.clave_fabricante 
			LEFT JOIN ctsbu c on a.sbu = c.clave_sbu
			WHERE idproyecto=$idproyecto";
		print getXML($strsql,$db);
	}
	// --------------------------------
	// 		FILTRA FABRICANTES
	// --------------------------------
	if($_POST["opcion"]=="FILTRA_REGISTROS"){		
		//paginacion_buscar($db, "SELECT * FROM usuarios_web", array("nombre_cliente"),"clave_cliente",false);
		
		$strsqlfiltrar="    SELECT a.idfabricante, a.clave_fabricante, a.nombre_fabricante, a.idusuario, b.usuario, 
			b.nombre ||' '|| b.apaterno ||' '|| b.amaterno as especialista 
		FROM ctfabricantes a left join ctusuarios b on a.idusuario=b.idusuario";
		
		paginacion_buscar($db,$strsqlfiltrar,
		array("idfabricante","clave_fabricante","nombre","idusuario", "especialista"),"idfabricante",true,true,2);		
		
	}
	
	//*******************************************************
	//				BORRAR FABRICANTE
	//*******************************************************
	
	if($_POST["opcion"]=="BORRAR_REGISTROS"){	
		paginacion_borrar($db,"DELETE FROM ctfabricantes WHERE idfabricante='[id]'",false);	
	}
	
	//*******************************************************
	//			CREA FABRICANTE NUEVO
	//*******************************************************
	if($_POST["opcion"]=="NUEVO_REGISTRO"){
		$clave_fabricante 	=	textToDB(strtoupper($_POST["clave_fabricante"]));
		$idusuario			=	textToDB(strtoupper($_POST["idusuario"]));
		$nombre_fabricante 	=	textToDB(strtoupper($_POST["nombre_fabricante"]));
		
		if(trim($clave_fabricante)==""){
			print "La clave cliente no puede estar vacía";
			exit;
		}
		if(trim($idusuario)==""){
			print "Nombre del cliente no puede estar vacío";
			exit;
		}
		if(trim($nombre_fabricante)==""){
			print "La clave ciudad no puede estar vacía";
			exit;
		}
		
		$strsql = "INSERT INTO ctfabricantes (clave_fabricante, idusuario, nombre_fabricante)
					VALUES ('$clave_fabricante',$idusuario, '$nombre_fabricante')";
		//hacerArchivo($strsql);
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
	//			EDITA FABRICANTE
	//*******************************************************
	if($_POST["opcion"]=="EDITAR_REGISTRO"){
		//clave_fabricante
		$idfabricante		=	textToDB(strtoupper($_POST["idfabricante"]));
		$clave_fabricante 	=	textToDB(strtoupper($_POST["clave_fabricante"]));
		$idusuario			=	textToDB(strtoupper($_POST["idusuario"]));
		$nombre_fabricante 	=	textToDB(strtoupper($_POST["nombre_fabricante"]));
		
		if(trim($clave_fabricante)==""){
			print "La clave cliente no puede estar vacía";
			exit;
		}
		if(trim($idusuario)==""){
			print "Nombre del cliente no puede estar vacío";
			exit;
		}
		if(trim($nombre_fabricante)==""){
			print "La clave ciudad no puede estar vacía";
			exit;
		}
		
		$strsql = "UPDATE ctfabricantes SET clave_fabricante='$clave_fabricante', idusuario=$idusuario, 
			nombre_fabricante='$nombre_fabricante' 
			WHERE idfabricante=$idfabricante";    

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