<?php
	include "include/entorno.inc.php";
	include "include/funciones.inc.php";
	
	//********************************************************
	// 			FILTRA CLIENTES DE BURO
	//********************************************************
	if($_POST["opcion"]=="FILTRA_REGISTROS"){		
		//paginacion_buscar($db, "SELECT * FROM usuarios_web", array("nombre_cliente"),"clave_cliente",false);
		
		paginacion_buscar($db,"     SELECT * FROM temp_clientes_buro",
		array("nombre","clave_cliente","tipo_persona"),"clave_cliente",false,true,2);		
		
	}
	
	//*******************************************************
	//			BORRAR CLIENTE DE BURO
	//*******************************************************
	
	if($_POST["opcion"]=="BORRAR_REGISTROS"){	
		paginacion_borrar($db,"DELETE FROM temp_clientes_buro WHERE clave_cliente='[id]'",false);	
	}
	
	//*******************************************************
	//			CREA CLIENTE NUEVO
	//*******************************************************
	if($_POST["opcion"]=="GUARDA_REGISTRO"){
		$clave_cliente 	=	textToDB(strtoupper($_POST["clave_cliente"]));
		$nombre_cliente =	textToDB(strtoupper($_POST["nombre_cliente"]));
		$tipo_persona	=	textToDB(strtoupper($_POST["tipo_persona"]));
		
		if(trim($clave_cliente)==""){
			print "La clave cliente no puede estar vacía";
			exit;
		}
		if(trim($nombre_cliente)==""){
			print "El Nombre del Cliente no puede estar vacío";
			exit;
		}
		
		$strsql = "INSERT INTO clientes (clave_cliente, nombre, tipo_persona)
					VALUES ('$clave_cliente','$nombre_cliente','$tipo_persona')";
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
		//print "Entre";
		$clave_cliente 	=	textToDB(strtoupper($_POST["clave_cliente"]));
		$nombre_cliente =	textToDB(strtoupper($_POST["nombre_cliente"]));
		$tipo_persona	=	textToDB(strtoupper($_POST["tipo_persona"]));
		
		if(trim($nombre_cliente)==""){
			print "El Nombre del Cliente no puede estar vacío";
			exit;
		}
		
		$strsql = "UPDATE temp_clientes_buro SET nombre='$nombre_cliente', tipo_persona='$tipo_persona' WHERE clave_cliente='$clave_cliente'";    
		
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
	
	/*
	SELECT ventas.clientes.clave_cliente AS clave_cliente,    
       		f_dias_vencimiento_buro_credito( datediff(day, ( 
				ventas.enc_facturas.fecha +  ventas.enc_facturas.plazo) ,ymd
				('2011','09','14')) ) as max ,
         	ventas.enc_facturas.moneda AS moneda,   
         	sum(f_conv_moneda(ventas.enc_facturas.saldo,ventas.enc_facturas.moneda, ventas.enc_facturas.tipo_cambio, 'MN')) as saldo_acumulado ,   
         	count(ventas.enc_facturas.num_docto) AS num_docto,   
         	UCASE( ventas.clientes.nombre) AS nombre,   
         	(ventas.clientes.direccion_1 ||' '||ventas.clientes.direccion_2) AS direccion_completa,    
         	UCASE(ventas.ciudades.cve_estado_sepomex) AS cve_estado_sepomex,   
         	UCASE(ventas.ciudades.cve_pais_sepomex) AS cve_pais_sepomex,   
         	UCASE(ventas.clientes.rfc) AS rfc,
               	  ventas.clientes.cp  AS cp,
         	UCASE(ventas.ciudades.ciudad) AS ciudad,
         	UCASE( ventas.ciudades.estado) AS estado
            //,(SELECT FIRST l.fecha FROM enc_facturas l WHERE l.clave_cliente=ventas.clientes.clave_cliente ORDER BY l.fecha ASC ) AS fecha_apertura 
            //,(SELECT FIRST y.fecha AS fecha_pago FROM enc_facturas x INNER JOIN pagos_cxc y
              //  ON x.num_docto=y.num_docto WHERE x.clave_cliente=ventas.clientes.clave_cliente AND x.clave_bodega=y.clave_bodega ORDER BY fecha_pago DESC) AS fecha_ultimo_pago  
    	FROM ventas.clientes,   
        	 ventas.enc_facturas,   
         	 ventas.ciudades
   		WHERE ( ventas.enc_facturas.clave_cliente = ventas.clientes.clave_cliente ) and  
         	( ventas.clientes.clave_ciudad = ventas.ciudades.clave_ciudad ) and  
         	( ( ventas.enc_facturas.fecha < ymd('2011','09','14') ) AND  
         	( ventas.enc_facturas.saldo > 0 ) )   and ventas.enc_facturas.clave_cliente 
		IN (SELECT clave_cliente FROM temp_clientes_buro)
		GROUP BY ventas.clientes.clave_cliente,   
        	ventas.enc_facturas.moneda,   
                               max,
         	ventas.ciudades.cve_estado_sepomex,   
         	ventas.ciudades.cve_pais_sepomex,   
         	ventas.clientes.nombre,   
         	direccion_completa,
         	ventas.clientes.rfc  , ventas.clientes.cp ,
         	ventas.ciudades.ciudad,ventas.ciudades.estado
         ORDER BY ventas.clientes.clave_cliente ASC,   
         	ventas.enc_facturas.moneda ASC,   
                                max ASC
	*/
?>