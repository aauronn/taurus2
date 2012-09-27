<?php
	include "include/entorno.inc.php";
	include "include/funciones.inc.php";
	
	//-----------------------------------------
	//			DATA PROVIDERS
	//-----------------------------------------
	
	if($_POST["opcion"]=="CAT_CLIENTES"){	
		$strsql = "SELECT * FROM clientes ORDER BY clave_cliente";
		print getXML($strsql,$db);
	}
	
	
	
	//-----------------------------------------
	//			OPERACIONES
	//-----------------------------------------
	
	
	if ($_POST["opcion"]=='TRAER_CLIENTES') {
		$offset = $_POST["offset"];
		$limit = $_POST["limit"];
		//$familia = $_POST["familia"];
		
		$strsql = " a.*, 
			COALESCE(a.direccion_1,'')||' '|| COALESCE(a.direccion_2,'') as direccion_completa
			,(SELECT ciudad FROM ctciudades b WHERE b.clave_ciudad=a.clave_ciudad) as nombre_ciudad
			,(SELECT nombre FROM ctvendedores c WHERE c.clave_vendedor=a.clave_vendedor) as nombre_vendedor
			,(SELECT descripcion FROM ctzonas d WHERE d.clave_zona=a.clave_zona) as nombre_zona
			FROM ctclientes a order by clave_cliente";
		
		
		//$strsql = " SELECT * FROM clientes a order by clave_cliente";
		
		
		print $strsql;
		
		print getXML($strsql, $db, $limit, $offset,2) ;
	}
	
	if ($_POST["opcion"]=='TRAER_CONTACTOS') {
		$offset = $_POST["offset"]+1;
		$limit = $_POST["limit"];
		$clave_cliente = $_POST["clave_cliente"];
		//$familia = $_POST["familia"];
		
		//$strsql = "SELECT * FROM contactos WHERE clave_cliente='$clave_cliente'";
		$strsql = " * FROM contactos WHERE clave_cliente='$clave_cliente'";
		
		
		//print $strsql;
		
		print getXML($strsql, $db, $limit, $offset,1) ;
	}
	
	if ($_POST["opcion"]=='TRAER_ENCCOTIZACIONES') {
		$offset = $_POST["offset"]+1;
		$limit = $_POST["limit"];
		$clave_cliente = $_POST["clave_cliente"];
		//$familia = $_POST["familia"];
		
		//$strsql = "SELECT * FROM contactos WHERE clave_cliente='$clave_cliente'";
		$strsql = " a.* 
				,(SELECT SUM(b.precio) as cotiazado FROM det_cotizaciones b WHERE 1=1 AND b.clave_bodega=a.clave_bodega AND b.num_docto=a.num_docto) as cotizado
				FROM enc_cotizaciones a WHERE 1=1
				AND clave_cliente='$clave_cliente'";
		
		
		//print $strsql;
		
		print getXML($strsql, $db, $limit, $offset,1) ;
	}
?>