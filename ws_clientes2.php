<?php

	include "include/entorno.inc.php";
	include "include/funciones.inc.php";
	
	//*************************************
	//		DATAPROVIDER CLIENTES
	//*************************************
	if($_POST["opcion"]=="CAT_CLIENTES"){		
		//$strsql = "SELECT clave_cliente, nombre FROM clientes";
		$strsql = "SELECT a.*, b.ciudad, b.estado FROM ctclientes_proyectos a LEFT JOIN ciudades b ON a.clave_ciudad=b.clave_ciudad";
		print getXML($strsql,$db);
	}
	
	
	// --------------------------------
	// 		FILTRA CLIENTES
	// --------------------------------
	if($_POST["opcion"]=="FILTRA_REGISTROS"){		
		//paginacion_buscar($db, "SELECT * FROM usuarios_web", array("nombre_cliente"),"clave_cliente",false);
		
		$strsql = "SELECT a.*,
		COALESCE(a.direccion_1,'')||' '|| COALESCE(a.direccion_2,'') as direccion_completa, 
		b.ciudad, 
		b.estado 
		FROM ctclientes_proyectos a LEFT JOIN ciudades b ON a.clave_ciudad=b.clave_ciudad";
		
		/*
		"    SELECT a.*
            ,COALESCE(a.direccion_1,'')||' '|| COALESCE(a.direccion_2,'') as direccion_completa
			,(SELECT ciudad FROM ciudades b WHERE b.clave_ciudad=a.clave_ciudad) as nombre_ciudad
			,(SELECT nombre FROM vendedores c WHERE c.clave_vendedor=a.clave_vendedor) as nombre_vendedor
			,(SELECT descripcion FROM zonas d WHERE d.clave_zona=a.clave_zona) as nombre_zona
			FROM clientes a"
		*/
		
		paginacion_buscar($db,$strsql,array("nombre","idcliente"),"idcliente",false,true,2);		
		
	}
	
	//*******************************************************
	//				BORRAR CLIENTE
	//*******************************************************
	
	if($_POST["opcion"]=="BORRAR_REGISTROS"){	
		paginacion_borrar($db,"DELETE FROM clientes WHERE clave_cliente='[id]'",false);	
	}
	
	//*******************************************************
	//			TRAER COTIZACIONES
	//*******************************************************
	if ($_POST["opcion"]=='TRAER_CONTACTOS') {
		$offset = $_POST["offset"]+1;
		$limit = $_POST["limit"];
		$clave_cliente = $_POST["clave_cliente"];
		//$familia = $_POST["familia"];
		
		$strsql = "SELECT * FROM contactos WHERE clave_cliente='$clave_cliente' ";
		//$strsql .= " * FROM contactos WHERE clave_cliente='$clave_cliente'";
		
		
		//print $strsql;
		
		print getXML($strsql, $db, $limit, $offset,1) ;
	}
	
	//*******************************************************
	//			TRAER COTIZACIONES
	//*******************************************************
	if ($_POST["opcion"]=='TRAER_ENCCOTIZACIONES') {
		$offset = $_POST["offset"]+1;
		$limit = $_POST["limit"];
		$clave_cliente = $_POST["clave_cliente"];
		//$familia = $_POST["familia"];
		
		//$strsql = "SELECT * FROM contactos WHERE clave_cliente='$clave_cliente'";
		$strsql = "SELECT a.* 
				,(SELECT SUM(b.precio) as cotiazado FROM det_cotizaciones b WHERE 1=1 AND b.clave_bodega=a.clave_bodega AND b.num_docto=a.num_docto) as cotizado
				FROM enc_cotizaciones a WHERE 1=1
				AND clave_cliente='$clave_cliente' ORDER BY fecha DESC";
		
		
		//print $strsql;
		
		print getXML($strsql, $db, $limit, $offset,2) ;
	}
	
	//*******************************************************
	//			CREA CLIENTE NUEVO
	//*******************************************************
	if($_POST["opcion"]=="CAPTURA_USUARIO"){
		$clave_cliente 	=	textToDB(strtoupper($_POST["clave_cliente"]));
		$nombre_cliente =	textToDB(strtoupper($_POST["nombre_cliente"]));
		$direccion1 	=	textToDB(strtoupper($_POST["direccion1"]));
		$direccion2 	=	textToDB(strtoupper($_POST["direccion2"]));
		$clave_ciudad 	=	textToDB(strtoupper($_POST["clave_ciudad"]));
		$cp 			=	textToDB(strtoupper($_POST["cp"]));
		$rfc 			=	textToDB(strtoupper($_POST["rfc"]));
		$limite_credito	=	textToDB(strtoupper($_POST["limite_credito"]));
		$status_credito =	textToDB(strtoupper($_POST["status_credito"]));
		$clave_zona		=	"01";//textToDB(strtoupper($_POST["clave_zona"]));
		$tipo			=	"CF";//textToDB(strtoupper($_POST["tipo"]));
		$sub_tipo		=	"IND";//textToDB(strtoupper($_POST["sub_tipo"]));
		$clave_vendedor	=	"004";//textToDB(strtoupper($_POST["clave_vendedor"]));
		
		if(trim($clave_cliente)==""){
			print "La clave cliente no puede estar vacía";
			exit;
		}
		if(trim($nombre_cliente)==""){
			print "Nombre del cliente no puede estar vacío";
			exit;
		}
		if(trim($clave_ciudad)==""){
			print "La clave ciudad no puede estar vacía";
			exit;
		}
		if(trim($clave_vendedor)==""){
			print "La clave vendedor no puede estar vacía";
			exit;
		}
		if(trim($tipo)==""){
			print "El Tipo no puede estar vacío";
			exit;
		}
		if(trim($sub_tipo)==""){
			print "El SubTipo no puede estar vacío";
			exit;
		}
		if(trim($rfc)==""){
			print "El RFC no puede estar vacío";
			exit;
		}
		
		$strsql = "INSERT INTO clientes (clave_cliente, nombre, direccion_1, direccion_2, clave_ciudad, cp, rfc, 
						limite_credito, status_credito, clave_zona, tipo, sub_tipo, clave_vendedor)
					VALUES ('$clave_cliente','$nombre_cliente', '$direccion1', '$direccion2', '$clave_ciudad', '$cp', 
						'$rfc', '$limite_credito','$status_credito', '$clave_zona', '$tipo', '$sub_tipo', '$clave_vendedor')";
		hacerArchivo($strsql);
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
	//			EDITA CLIENTE
	//*******************************************************
	if($_POST["opcion"]=="EDITA_REGISTRO"){
		
		$clave_cliente 	=	textToDB(strtoupper($_POST["clave_cliente"]));
		$nombre_cliente =	textToDB(strtoupper($_POST["nombre_cliente"]));
		$direccion1 	=	textToDB(strtoupper($_POST["direccion1"]));
		$direccion2 	=	textToDB(strtoupper($_POST["direccion2"]));
		$clave_ciudad 	=	textToDB(strtoupper($_POST["clave_ciudad"]));
		$cp 			=	textToDB(strtoupper($_POST["cp"]));
		$rfc 			=	textToDB(strtoupper($_POST["rfc"]));
		$limite_credito	=	textToDB(strtoupper($_POST["limite_credito"]));
		$status_credito =	textToDB(strtoupper($_POST["status_credito"]));
		$clave_zona		=	textToDB(strtoupper($_POST["clave_zona"]));
		$tipo			=	"CF";//textToDB(strtoupper($_POST["tipo"]));
		$sub_tipo		=	"IND";//textToDB(strtoupper($_POST["sub_tipo"]));
		$clave_vendedor	=	"004";//textToDB(strtoupper($_POST["clave_vendedor"]));
		
		if(trim($clave_cliente)==""){
			print "La clave cliente no puede estar vacía";
			exit;
		}
		if(trim($nombre_cliente)==""){
			print "Nombre del cliente no puede estar vacío";
			exit;
		}
		if(trim($clave_ciudad)==""){
			print "La clave ciudad no puede estar vacía";
			exit;
		}
		if(trim($clave_vendedor)==""){
			print "La clave vendedor no puede estar vacía";
			exit;
		}
		if(trim($tipo)==""){
			print "El Tipo no puede estar vacío";
			exit;
		}
		if(trim($sub_tipo)==""){
			print "El SubTipo no puede estar vacío";
			exit;
		}
		if(trim($rfc)==""){
			print "El RFC no puede estar vacío";
			exit;
		}
		
		$strsql = "UPDATE clientes SET clave_cliente='$clave_cliente', nombre='$nombre_cliente', direccion_1='$direccion1', 
						direccion_2='$direccion2', clave_ciudad='$clave_ciudad', cp='$cp', rfc='$rfc', 
						limite_credito=$limite_credito', status_credito='$status_credito', clave_zona='$clave_zona', 
						tipo='$tipo', sub_tipo='$sub_tipo', clave_vendedor='$clave_vendedor'";    
		
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