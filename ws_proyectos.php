<?php
	
	include "include/entorno.inc.php";
	include "include/funciones.inc.php";
	include("include/phpmailer/class.phpmailer.php");
	include("include/phpmailer/class.smtp.php");
	
	//**************************************************************************
	//		TRAER VENDEDORES(1) Y ESPECIALITAS(9)
	//**************************************************************************
	if($_POST["opcion"]=="GET_VENDEDORES"){
		$strsql="         SELECT *, (a.nombre ||' '|| a.apaterno) as nombre_completo FROM ctusuarios a where idtipousuario=1 or idtipousuario=9";
		print getXML($strsql, $db);
	}
	
	if($_POST["opcion"]=="GET_VENDEDORES_ASIGNADOS"){
		$idproyecto=$_POST["idproyecto"];
		$strsql="         SELECT a.*,(b.nombre ||' '|| b.apaterno) as nombre_completo FROM ctVendedoresEspecialistas_asignados a, ctusuarios b where a.idproyecto=$idproyecto and b.idusuario=a.idusuario";
		
		print getXML($strsql, $db);
	}
	
	//**************************************************************************
	//	AGREAR O BORRAR VENDEDORES Y/O ESPECIALISTAS ASIGNADOS A PROYECTO
	//**************************************************************************
	if($_POST["opcion"]=="ACTUALIZAR_ASOCIADOS"){
		$idproyecto		 = $_POST["idproyecto"];
		$usuariosAgregar = $_POST["usuariosAgregar"];
		
		//Usuarios ya asignados
		$strsql="         SELECT * FROM VENTAS.ctVendedoresEspecialistas_asignados a where idproyecto=$idproyecto";
		$rs=$db->execute($strsql);
		if($rs){
			while($row=$rs->fetchRow()){
				array_push($asignados_arr_usuario,$row['idusuario']);
			}
		}
		
		if ($usuariosAgregar!=""){
			
		}
	}
	
	//**************************************************************************
	//		TRAER BITACORA
	//**************************************************************************
	if($_POST["opcion"]=="TRAER_BITACORA"){
		$idproyecto = $_POST["idproyecto"];
		$strsql="         SELECT * FROM tbbitacora_proyectos WHERE idproyecto=$idproyecto";
		print getXML($strsql, $db);
	}
	
	//**************************************************************************
	//		ULTIMO PROYECTO DEL VENDEDOR
	//**************************************************************************
	if($_POST["opcion"]=="GET_ULTIMOPROYECTO"){
		$strsql="         SELECT *";
	}
	
	// --------------------------------
	// 		FILTRA PROYECTOS
	// --------------------------------
	if($_POST["opcion"]=="FILTRA_REGISTROS"){		
		$idtipousuario = $_POST["idtipousuario"];
		$idusuario = $_POST["idusuario"];
		
		if ($idtipousuario==1|| $idtipousuario==2|| $idtipousuario==9) {
			//$entre = "Primer";
			$strsql = "     SELECT a.*         
						    ,(select b.nombre ||' '||b.apaterno from ctusuarios b where b.idusuario=a.idusuario) as nombre_vendedor 
						    ,(select c.nombre from ctclientes_proyectos c where c.idcliente=a.idcliente) as nombre_cliente
						    ,(case a.estado
							        when 0 then 'Perdido' 
									when 1 then 'Ganado'
									when 2 then 'Seguimiento'
					            END
						      ) as strEstado 
						FROM tbproyectos a
						WHERE idusuario=$idusuario";
		}
		if ($idtipousuario==3 || $idtipousuario==4) {
			//$entre = "Segundo";
			$strsql = "     SELECT a.*         
						    ,(select b.nombre ||' '||b.apaterno from ctusuarios b where b.idusuario=a.idusuario) as nombre_vendedor 
						    ,(select c.nombre from ctclientes_proyectos c where c.idcliente=a.idcliente) as nombre_cliente 
						    ,(case a.estado
							        when 0 then 'Perdido' 
									when 1 then 'Ganado'
									when 2 then 'Seguimiento'
					            END
						      ) as strEstado 
						FROM tbproyectos a";
		}
		//paginacion_buscar($db, "SELECT * FROM usuarios_web", array("nombre_cliente"),"clave_cliente",false);
		
		
		//hacerArchivo($strsql);
		paginacion_buscar($db,$strsql,array("nombre_proyecto","vendedor"),"idproyecto",false,true,2);		
		
	}
	
	//*******************************************************
	//			CREA PROYECTO NUEVO
	//*******************************************************
	if($_POST["opcion"]=="CAPTURA_PROYECTO"){
		$idcliente 				=	textToDB(strtoupper($_POST["clave_cliente"]));
		$nombre_proyecto		=	textToDB(strtoupper($_POST["nombre_proyecto"]));
		$monto_total_probable 	=	textToDB(strtoupper($_POST["monto_total_probable"]));
		$porcentaje_proyectado 	=	textToDB(strtoupper($_POST["porcentaje_proyectado"]));
		$fecha_decision		 	=	textToDB(strtoupper($_POST["fecha_decision"]));
		$idusuario 				=	textToDB(strtoupper($_POST["clave_usuario"]));
		$descripcion_proyecto	=	textToDB(strtoupper($_POST["descripcion_proyecto"]));
		
		$clave_proyecto 		=	textToDB(strtoupper($_POST["clave_proyecto"]));
		$fecha_creacion			=	textToDb(strtoupper($_POST["fecha_creacion"]));
		$estado					=	textToDB(strtoupper($_POST["estado"]));
		
		$flashvars = $_POST['bitacora'];
		if($flashvars!=""){
			if(get_magic_quotes_gpc()){
			    $flashvars = stripslashes($flashvars);
			}
			$bitacoraMsgs = object_to_array(unserialize(urldecode($flashvars)));
			//print_r($bitacoraMsgs);
		}
		
		$flashvars = $_POST['serializadoAgregados'];
		if($flashvars!=""){
			if(get_magic_quotes_gpc()){
				$flashvars = stripslashes($flashvars);
			}
			$userAgregado = object_to_array(unserialize(urldecode($flashvars)));
			//print_r($bitacoraMsgs);
		}
		
		$flashvars = $_POST['serializadoFabricantes'];
		if($flashvars!=""){
			if(get_magic_quotes_gpc()){
				$flashvars = stripslashes($flashvars);
			}
			$fabricantes = object_to_array(unserialize(urldecode($flashvars)));
			//print_r($bitacoraMsgs);
		}
		
		if(trim($idcliente)==""){
			print "El idcliente no puede estar vacía";
			exit;
		}
		if(trim($nombre_proyecto)==""){
			print "Nombre del proyecto no puede estar vacío";
			exit;
		}
		if(trim($monto_total_probable)==""){
			print "El monto total probable no puede estar vacío";
			exit;
		}
		if(trim($porcentaje_proyectado)==""){
			print "El porcentaje proyectado no puede estar vacío";
			exit;
		}
		if(trim($fecha_decision)==""){
			print "La fecha de decision no puede estar vacia";
			exit;
		}
		if(trim($idusuario)==""){
			print "El idusuario no puede estar vacio";
			exit;
		}
		if(trim($descripcion_proyecto)==""){
			print "La descripcion del proyecto no puede estar vacio";
			exit;
		}
		
		$nombre_usuario = textFromDB($_SESSION[$GLOBALS["sistema"]]['nombre']);
		$apaterno = textFromDB($_SESSION[$GLOBALS["sistema"]]['apaterno']);
		$amaterno = textFromDB($_SESSION[$GLOBALS["sistema"]]['amaterno']);
		
		
		$consecutivo = traerConsecutivoClaveProyecto($idusuario, $db);
		
		$clave_proyecto2= $clave_proyecto.$consecutivo;
	
		$strsql = "INSERT INTO tbproyectos (idcliente, nombre_proyecto, monto_total_probable, porcentaje_proyectado, 
					fecha_desicion, idusuario, descripcion_proyecto, clave_proyecto, fecha_creacion, estado)
					VALUES ('$idcliente', '$nombre_proyecto', $monto_total_probable, $porcentaje_proyectado, 
					'$fecha_decision', '$idusuario', '$descripcion_proyecto', '$clave_proyecto2','$fecha_creacion','$estado')";
		
		$db->starttrans();
		
		if($rs=$db->execute($strsql)){
			//hacerArchivo($strsql);
			$identidad=traerIdentity($db);
			$ultipoProyectoAgregado = ultimoProyectoAgregado($clave_proyecto2, $db);
			//print "$ultipoProyectoAgregado - $clave_proyecto2 ---";
			
			//USUARIOS AGREGADOS A PROYECTO
			$strUserAgregado ="";
			if ($userAgregado){
				foreach ($userAgregado as $agregado){
					//print_r($agregado);
					$strsql= "INSERT INTO ctVendedoresEspecialistas_asignados (idproyecto, idusuario) VALUES ($ultipoProyectoAgregado, ".$agregado[idusuario].");";
					$rs=$db->execute($strsql);

					mandarCorreo("aauronn@gmail.com", "lagutierrez@absason.com", "Asignado a proyecto: $nombre_proyecto", "Favor de revisar el proyecto: $nombre_proyecto, has sido asignado por $nombre_usuario $apaterno $amaterno", "");
				}
			}
			
			//$strFabricantes = "";
			if ($fabricantes){
				foreach ($fabricantes as $fabricante){
					$strsql = " INSERT INTO tbproyectos_fabricantes (idproyecto, clave_fabricante, sbu, porcentaje, monto_dlls) ";
					$strsql.= " VALUES ($ultipoProyectoAgregado, '$fabricante[clave_fabricante]', '$fabricante[clave_SBU]', $fabricante[porcentaje], $fabricante[monto_dlls])";
					
					$rs=$db->execute($strsql);
	//				hacerArchivo($strsql);
				}
			}
		
			//BITACORA
			//$strsqlBitacora="";
			if ($identidad){
				if ($bitacoraMsgs){
					foreach ($bitacoraMsgs as $msg) {
						$strsql="INSERT INTO tbbitacora_proyectos (idproyecto, fecha, idusuario, descripcion) VALUES ($identidad, now(), $idusuario, '".$msg[descripcion]."') ";
						$rs=$db->execute($strsql);
					}
				}
			}
		}
		if($db->completetrans()){
			print "ok";
			//print getXML("SELECT @@IDENTITY");
		}
		else{
			$db->rollbacktrans();
			print "E3: ".$db->errormsg()." - ".$strsql;//dbg								
		}
			
		
	}
	
	
	//*******************************************************
	//			EDITA PROYECTO
	//*******************************************************
	if($_POST["opcion"]=="EDITA_PROYECTO"){
		$idproyecto				=	textToDB(strtoupper($_POST["idproyecto"]));
		$idcliente 				=	textToDB(strtoupper($_POST["idcliente"]));
		$nombre_proyecto		=	textToDB(strtoupper($_POST["nombre_proyecto"]));
		$monto_total_probable 	=	textToDB(strtoupper($_POST["monto_total_probable"]));
		$porcentaje_proyectado 	=	textToDB(strtoupper($_POST["porcentaje_proyectado"]));
		$fecha_decision		 	=	textToDB(strtoupper($_POST["fecha_decision"]));
		$idusuario	 			=	textToDB(strtoupper($_POST["idusuario"]));
		$descripcion_proyecto	=	textToDB(strtoupper($_POST["descripcion_proyecto"]));
		 
		$flashvars = $_POST['bitacora'];
		if($flashvars!=""){
			if(get_magic_quotes_gpc()){
				$flashvars = stripslashes($flashvars);
			}
			$bitacoraMsgs = object_to_array(unserialize(urldecode($flashvars)));
			//print_r($bitacoraMsgs);
		}
		
		$flashvars = $_POST['serializadoAgregados'];
		if($flashvars!=""){
			if(get_magic_quotes_gpc()){
				$flashvars = stripslashes($flashvars);
			}
			$userAgregado = object_to_array(unserialize(urldecode($flashvars)));
			//print_r($bitacoraMsgs);
		}
		
		$flashvars = $_POST['serializadoRemovidos'];
		if($flashvars!=""){
			if(get_magic_quotes_gpc()){
				$flashvars = stripslashes($flashvars);
			}
			$userBorrado = object_to_array(unserialize(urldecode($flashvars)));
			//print_r($bitacoraMsgs);
		}
		
		$flashvars = $_POST['fabricantesserializadoAgregados'];
		if($flashvars!=""){
			if(get_magic_quotes_gpc()){
				$flashvars = stripslashes($flashvars);
			}
			$fabricanteAgregado = object_to_array(unserialize(urldecode($flashvars)));
			//print_r($bitacoraMsgs);
		}
		
		$flashvars = $_POST['fabricantesserializadoRemovidos'];
		if($flashvars!=""){
			if(get_magic_quotes_gpc()){
				$flashvars = stripslashes($flashvars);
			}
			$fabricanteBorrado = object_to_array(unserialize(urldecode($flashvars)));
			//print_r($bitacoraMsgs);
		}
		
		if(trim($idcliente)==""){
			print "La clave cliente no puede estar vacía";
			exit;
		}
		if(trim($nombre_proyecto)==""){
			print "Nombre del proyecto no puede estar vacío";
			exit;
		}
		if(trim($monto_total_probable)==""){
			print "El monto total probable no puede estar vacío";
			exit;
		}
		if(trim($porcentaje_proyectado)==""){
			print "El porcentaje proyectado no puede estar vacío";
			exit;
		}
		if(trim($fecha_decision)==""){
			print "La fecha de decision no puede estar vacia";
			exit;
		}
		if(trim($idusuario)==""){
			print "La clave usuario no puede estar vacio";
			exit;
		}
		if(trim($descripcion_proyecto)==""){
			print "La descripcion del proyecto no puede estar vacio";
			exit;
		}
		
		$strsql = "UPDATE tbproyectos SET idcliente='$idcliente', nombre_proyecto='$nombre_proyecto', 
					monto_total_probable=$monto_total_probable, porcentaje_proyectado=$porcentaje_proyectado, 
					fecha_desicion='$fecha_decision', descripcion_proyecto='$descripcion_proyecto'
					WHERE idproyecto=$idproyecto";    
	//	hacerArchivo($strsql);
		
		$db->starttrans();	
		
		$db->execute($strsql);
		
		//	AGREGAR USUARIOS A PROYECTO
		//$strUserAgregado ="";
		if ($userAgregado){
			foreach ($userAgregado as $agregado){
				//print_r($agregado);
				$strsql= "INSERT INTO ctVendedoresEspecialistas_asignados (idproyecto, idusuario) VALUES (".textToDB($idproyecto).", ".textToDB($agregado[idusuario]).") ";
					$db->execute($strsql);
					
					//mandarCorreo("aauronn@gmail.com", "lagutierrez@absason.com", "Asignado a proyecto: $nombre_proyecto", "Favor de revisar el proyecto: $nombre_proyecto, has sido asignado por $nombre_usuario $apaterno $amaterno", "");
					
					if (mandarCorreo("lual.gutierrez@gmail.com", "Luis Canseco", "aauronn@gmail.com", "Luis Gutierrez", "Alta Proyecto", "<h1>Bienvenido al proyecto</h1>")!= "OK|aauronn@gmail.com"){
						hacerArchivo("hubo Error");
					}
					
					
			}
			//hacerArchivo($strUserAgregado);
		}
		
		//	BORRAR USUARIOS DE PROYECTO
		
		if ($userBorrado){
			foreach ($userBorrado as $borrado){
				$strsql= "DELETE FROM ctVendedoresEspecialistas_asignados WHERE idproyecto = $idproyecto AND idusuario = ".$borrado[idusuario]."; ";
				$db->execute($strsql);
			}
			
		}
		
		//	AGREGAR FABRICANTES
		if($fabricanteAgregado){
			foreach ($fabricanteAgregado as $fabricante){
				$strsql = " INSERT INTO tbproyectos_fabricantes (idproyecto, clave_fabricante, sbu, porcentaje, monto_dlls) ";
				$strsql.= " VALUES ($idproyecto, '$fabricante[clave_fabricante]', '$fabricante[clave_SBU]', $fabricante[porcentaje], $fabricante[monto_dlls])";
					
				$rs=$db->execute($strsql);
			}
		}
		
		//	BORRAR FABRICANTE
		if($fabricanteBorrado){
			foreach ($fabricanteBorrado as $fabricanteB){
				$strsql = "DELETE FROM tbproyectos_fabricantes WHERE idpf = $fabricanteB[idpf]";
				$rs=$db->execute($strsql);
			}
		}
		
		//BITACORA
		if ($bitacoraMsgs){
			foreach ($bitacoraMsgs as $msg) {
				$strsql=" INSERT INTO tbbitacora_proyectos (idproyecto,fecha,idusuario,descripcion) VALUES ($idproyecto,now(),$idusuario,'$msg[descripcion]') ";
				$db->execute($strsql);
			}
			
		}
		
		
		if($db->completetrans()){
			print "ok";
			//print getXML("SELECT @@IDENTITY");
		}else{
			$db->rollbacktrans();
			print "E4: ".$db->errormsg()." - ".$strsql;//dbg								
		}
	}
	
	//*******************************************************
	//			BORRAR PROYECTO
	//*******************************************************
	
	if($_POST["opcion"]=="BORRAR_REGISTROS"){	
		paginacion_borrar_multiple($db, 
				array("DELETE FROM tbproyectos WHERE idproyecto=[id]",
						"DELETE FROM tbbitacora_proyectos WHERE idproyecto=[id]",
						"DELETE FROM ctVendedoresEspecialistas_asignados WHERE idproyecto=[id]")
				,true);
	//	paginacion_borrar($db,,true);
	//	paginacion_borrar($db,,true);

	}
	
	//********************************************************
	//			BORRAR CLIENTES
	//********************************************************
	if($_POST["opcion"]=="BORRAR_CLIENTE"){
		paginacion_borrar($db, "DELETE FROM ctclientes_proyectos WHERE idcliente=[id]");
	}
	
	
	// --------------------------------
	// 		TRAER_CLIENTES
	// --------------------------------
	if($_POST["opcion"]=="TRAER_CLIENTES"){
		$strsql = "SELECT a.*, b.ciudad, b.estado FROM ctclientes_proyectos a LEFT JOIN ctciudades b ON a.clave_ciudad=b.clave_ciudad";
		
		paginacion_buscar($db,$strsql,array("nombre","idcliente"),"idcliente",false,true,2);		
	}
	
	// --------------------------------
	// 		TRAER_CLIENTE_PROYECTO
	// --------------------------------
	if($_POST["opcion"]=="TRAER_CLIENTE_PROYECTO"){
		$idClientePost = $_POST["idcliente"];
		$strsql = "SELECT a.*, b.ciudad, b.estado FROM ctclientes_proyectos a LEFT JOIN ctciudades b ON a.clave_ciudad=b.clave_ciudad WHERE idcliente=$idClientePost";
		
		print getXML($strsql, $db);
		
		//paginacion_buscar($db,$strsql,array("nombre","idcliente"),"idcliente",false,true,2);		
	}
	
	// --------------------------------
	// 		GUARDAR CLIENTES
	// --------------------------------
	if($_POST["opcion"]=="AGREGAR_CLIENTE"){
		$nombre 			= $_POST["nombre"];
		$claveGalaxyCliente = $_POST["claveGalaxy"];
		$direccion_1		= $_POST["direccion_1"];
		$direccion_2 		= $_POST["direccion_2"];
		$claveCiudad		= $_POST["claveCiudad"];
		$cp 				= $_POST["cp"];
		$rfc				= $_POST["rfc"];
		$IdUsuario 			= $_POST["IdUsuario"];
		$claveGalaxyVen		= $_POST["claveGalaxyVen"];
		$nombreContacto 	= $_POST["nombreContacto"];
		$correoContacto		= $_POST["correoContacto"];
		$puestoContacto		= $_POST["puestoContacto"];
		$fechaAlta			= "now()";
		$contactoTelefonos	= $_POST["contactoTelefonos"];
		
		if(trim($nombre)==""){
			print "El cliente debe tener nombre";
			exit;
		}
		
		if(trim($rfc)==""){
			print "El campo RFC no puede estar vacio";
			exit;
		}
		
		if(trim($nombreContacto)==""){
			print "Debe haber un nombre de contacto";
			exit;
		}
		
		if(trim($correoContacto)==""){
			print "Debe de haber un correo de contacto";
			exit;
		}
		
		$strsql = "INSERT INTO ctclientes_proyectos (nombre, clave_cliente_galaxy, direccion_1, direccion_2, clave_ciudad, ";
		$strsql.= "cp, rfc, clave_vendedor, clave_vendedor_galaxy, contacto_nombre, contacto_correo, contacto_puesto, fecha_alta, contacto_telefonos)";
		$strsql.= "VALUES ('$nombre', '$claveGalaxyCliente', '$direccion_1', '$direccion_2', '$claveCiudad', ";
		$strsql.= "'$cp', '$rfc', $IdUsuario, '$claveGalaxyVen', '$nombreContacto', '$correoContacto', '$puestoContacto', $fechaAlta, '$contactoTelefonos')";
		
		
		$db->starttrans();	//INICIO LA TRANSACCION DE AGREGAR UN CLIENTE
		
		if ($rs=$db->execute($strsql)){ //AQUI EJECUTO EL QUERY PARA AGREGAR UN CLIENTE
			
		}
		
		
		if($db->completetrans()){	//SI LA TRANSACCION SE TERMINA
			print "ok";

		}else{
			$db->rollbacktrans();
			print "E4: ".$db->errormsg()." - ".$strsql;
		}
		
	}
	
	// --------------------------------
	// 		EDITAR CLIENTES
	// --------------------------------
	if($_POST["opcion"]=="EDITAR_CLIENTE"){
		$idCliente			= $_POST["idcliente"];
		$nombre 			= $_POST["nombre"];
		$claveGalaxyCliente = $_POST["claveGalaxy"];
		$direccion_1		= $_POST["direccion_1"];
		$direccion_2 		= $_POST["direccion_2"];
		$claveCiudad		= $_POST["claveCiudad"];
		$cp 				= $_POST["cp"];
		$rfc				= $_POST["rfc"];
		$IdUsuario 			= $_POST["IdUsuario"];
		$claveGalaxyVen		= $_POST["claveGalaxyVen"];
		$nombreContacto 	= $_POST["nombreContacto"];
		$correoContacto		= $_POST["correoContacto"];
		$puestoContacto		= $_POST["puestoContacto"];
		$fechaAlta			= "now()";
		$contactoTelefonos	= $_POST["contactoTelefonos"];
	
		if(trim($nombre)==""){
			print "El cliente debe tener nombre";
			exit;
		}
	
		if(trim($rfc)==""){
			print "El campo RFC no puede estar vacio";
			exit;
		}
	
		if(trim($nombreContacto)==""){
			print "Debe haber un nombre de contacto";
			exit;
		}
	
		if(trim($correoContacto)==""){
			print "Debe de haber un correo de contacto";
			exit;
		}
	
		$strsql = "UPDATE ctclientes_proyectos SET "; 
		$strsql.= "nombre = '$nombre', clave_cliente_galaxy = '$claveGalaxyCliente', direccion_1 = '$direccion_1', direccion_2 = '$direccion_2', ";
		$strsql.= "clave_ciudad = '$claveCiudad',  cp = '$cp', rfc = '$rfc', clave_vendedor = '$IdUsuario', clave_vendedor_galaxy = '$claveGalaxyVen', ";
		$strsql.= "contacto_nombre = '$nombreContacto', contacto_correo = '$correoContacto', contacto_puesto = '$puestoContacto', fecha_alta = $fechaAlta, ";
		$strsql.= "contacto_telefonos = '$contactoTelefonos' ";
		$strsql.= "WHERE idcliente = $idCliente ";
	
	
		$db->starttrans();	/*INICIO LA TRANSACCION DE AGREGAR UN CLIENTE*/
	
		if ($rs=$db->execute($strsql)){ //AQUI EJECUTO EL QUERY PARA AGREGAR UN CLIENTE
				
		}
	
	
		if($db->completetrans()){	//SI LA TRANSACCION SE TERMINA
			print "ok";
	
		}else{
			$db->rollbacktrans();
			print "E4: ".$db->errormsg()." - ".$strsql;
		}
	
	}
	
	
	function traerConsecutivoClaveProyecto($idusuario, &$db){
		$consecutivo=0;
		
		$strsqlClaveProyecto = "select first clave_proyecto from tbproyectos where idusuario=$idusuario order by clave_proyecto desc";
		//hacerArchivo($strsqlClaveProyecto);
		$rs=$db->Execute($strsqlClaveProyecto);
		if ($rs) {
			while ($row = $rs->fetchRow()) {
				$clave_proyecto=$row[clave_proyecto];
				
				//$consecutivo+1;
				//hacerArchivo($claves_Proyectos[0]."   ////    ".$consecutivo);
				//exit;
				
			}
		}
		$claves_Proyectos = explode("-",$clave_proyecto);
		$consecutivo=$claves_Proyectos[1]+1;
		//hacerArchivo($consecutivo);
		return $consecutivo;
	}
	
	function traerIdentity (&$db){
		$identidad="";
		$strsql = "SELECT @@IDENTITY;";
		$rs=$db->Execute($strsql);
		if($rs){
			while ($row = $rs->fetchRow()) {
				$identidad= $row["@@IDENTITY"];
			}
		}
		return $identidad;
	}
	
	//function mandarCorreoProyecto
	
?>