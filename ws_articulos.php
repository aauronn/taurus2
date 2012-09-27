<?php
	include "include/entorno.inc.php";
	include "include/funciones.inc.php";
	
	//-----------------------------------------
	//			DATA PROVIDERS
	//-----------------------------------------
	
	if($_POST["opcion"]=="CAT_FAMILIAS"){	
		$strsql = "SELECT DISTINCT familia FROM familia_subfamilia order by familia";
		print getXML($strsql,$db);
	}
	
	if($_POST["opcion"]=="CAT_SUBFAMILIAS"){	
		$strsql = "SELECT DISTINCT sub_familia FROM familia_subfamilia order by sub_familia";
		print getXML($strsql,$db);
	}
	
	
	//-----------------------------------------
	//			OPERACIONES
	//-----------------------------------------
	if($_POST["opcion"]=="FILTRA_REGISTROS"){		
		//paginacion_buscar($db, "SELECT * FROM usuarios_web", array("nombre_cliente"),"clave_cliente",false);
		hacerArchivo($strsql);
		paginacion_buscar($db,"    SELECT a.*
				, a.sub_familia as clave_familia_subfamilia
				FROM articulos a ",array("familia","sub_familia","descripcion","codigo_articulo"),"codigo_articulo",false,true,2);		
		
	}
	
	if ($_POST["opcion"]=='TRAER_FAMILIA_SUBFAMILIA') {
		$offset = $_POST["offset"]+1;
		$limit = $_POST["limit"];
		$familia = $_POST["familia"];
		$subfamilia = $_POST["subfamilia"];
		$articulo = $_POST["articulo"];
		/*
		$strsql = "SELECT a.*
		, (SELECT b.nombre FROM proveedores b WHERE a.clave_proveedor=b.clave_proveedor) as proveedor 
		, a.sub_familia as clave_familia_subfamilia
		FROM familia_subfamilia a WHERE 1=1";
		*/
		
		$strsql=//"SELECT TOP $limit 
				//	START AT $offset
		$strsql="a.*
				, a.sub_familia as clave_familia_subfamilia
				FROM articulos a 
				WHERE 1=1";
		
		if ($familia!="") {
			if ($familia!='Todos') {
				$strsql.= " AND familia='$familia'";
			}
		}
		if ($subfamilia!="") {
			if ($subfamilia!='Todos') {
				$strsql.= " AND sub_familia='$subfamilia'";
			}
		}
		if ($articulo!="") {
			$strsql.= " AND descripcion  like '%$articulo%'";
		}
		$strsql.=" ORDER BY familia";
		
		print $subfamilia."\n";
		//print $strsql;
		
		print getXML($strsql, $db,$limit,$offset,1) ;
	}
	
	

	
	/*
	
	if ($_POST["opcion"]=='TRAER_FAMILIA_SUBFAMILIA') {
		
		$strsql = "SELECT a.*, 
		(SELECT b.nombre FROM proveedores b WHERE a.clave_proveedor=b.clave_proveedor) as proveedor 
		FROM ctfamilia_subfamilia a WHERE 1=1";

		
		//print $strsql;
		
		print getXML($strsql, $db, 1000) ;
	}
	*/
	
	//----------------------------------------------
	//			FAMILIAS ARBOLES
	//----------------------------------------------
	
	
	
	// --------------------------------
	// 		PERMISOS USUARIO
	// --------------------------------	
	if($_REQUEST["opcion"]=="FAMILIAS_PROYECTO"){
		//print "entre";
		$clave_proyecto = $_REQUEST["clave_proyecto"];
		
		$productos_asignados=array();
		
		$strsql="SELECT * FROM productos_asignados WHERE clave_proyecto=$clave_proyecto";
		$rs=$db->Execute($strsql);
		if($rs){
			while($row=$rs->fetchRow()){
				array_push($productos_asignados,$row);
			}
			//print_r($productos_asignados);
			print getXML($strsql,$db);
		}

	}
	
	

	
?>