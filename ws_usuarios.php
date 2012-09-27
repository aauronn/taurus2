<?php

include "include/entorno.inc.php";
include "include/funciones.inc.php";

//*******************************************************************
//		ESPECIALISTAS
//*******************************************************************
if ($_POST["opcion"]=="GET_ESPECIALISTAS") {
	//print "entre";
	$strsql = "select idusuario, nombre ||' '|| apaterno as especialista from ctusuarios where idtipousuario=9";
	print getXML($strsql,$db);
}

	// --------------------------------
	// 		TODOS USUARIOS
	// --------------------------------	
if($_POST["opcion"]=="GET_USUARIOS"){	
	$strsql = "SELECT distinct (t1.idusuario), *, 
	((case when t1.apaterno is null then '' else t1.apaterno end) || ' ' || (case when t1.amaterno is null then '' else t1.amaterno end) || ' ' || t1.nombre) as nombreautor,
		t2.titulo as tipousuario			
	FROM 
	   	ctusuarios t1 			    
	INNER JOIN
	   	cttipousuarios t2 on t1.idtipousuario=t2.idtipousuario 
	WHERE 
	   	t1.idusuario!='4'"; 
	print getXML($strsql,$db);
	
}



	// --------------------------------
	// 		INFO USUARIO
	// --------------------------------	
if($_POST["opcion"]=="INFO_USUARIO"){	
	$idusuario     = textToDB($_POST["idusuario"]);
	$nombreusuario = textToDB($_POST["nombreusuario"]);
	
	if($nombreusuario=="" && $idusuario==""){
		print getXMLErrorMsg("id o nombre de usuario vacio");
		exit;	
	}else{
		if($nombreusuario!=""){
			$nombreusuario = " t1.nombreusuario='$nombreusuario' ";			
		}
		
		if($idusuario!=""){
			if(!is_numeric($idusuario) || $idusuario<=0){
				print getXMLErrorMsg("id válido: [$idusuario]");
				exit;
			}else{				
				$idusuario = " t1.idusuario='$idusuario' ";
			}
		}		
	}
	
	
	
	
	$strsql = "SELECT 
					t1.idusuario, t1.nombreusuario, t1.apaterno, t1.amaterno, t1.nombre, t1.correo, t1.telefono, t1.celular, t1.idtipousuario, t2.titulo as tipousuario			
			    FROM 
			    	ctusuarios t1 			    
			    INNER JOIN
			    	cttipousuarios t2 on t1.idtipousuario=t2.idtipousuario 
			    WHERE 
			    	$nombreusuario $idusuario "; 
	
	print getXML($strsql,$db);
}




	// --------------------------------
	// 		FILTRA USUARIOS
	// --------------------------------	
	/*TIPOS de USUARIO:
		1,'Vendedor'
		3,'Gerente'
		4,'Administrador'
		2,'Oficina'
		9,'Especialista'
	*/
if($_POST["opcion"]=="FILTRA_USUARIOS"){
	$idtipousuario = $_POST["idtipousuario"];
	$strsql = "                  SELECT distinct (t1.idusuario) as idusuario, //t1.*, 
		(   (case when t1.apaterno is null then '' else t1.apaterno end) || ' ' || 
		    (case when t1.amaterno is null then '' else t1.amaterno end) || ' ' || t1.nombre
		) as nombreCompleto
		,t1.usuario as usuario
		,t1.pass as pass
		,t1.apaterno as apaterno
		,t1.amaterno as amaterno
		,t1.email as correo
		,t2.titulo as tipousuario
		,t1.idtipousuario
        ,t1.nombre
        ,t1.empresa
        ,t1.telefonos			
			FROM 
			   	ctusuarios t1 			    
			INNER JOIN
			   	cttipousuarios t2 on t1.idtipousuario=t2.idtipousuario 
			WHERE 1=1";
			//and t1.idusuario!='17' and t1.idusuario!='1'";// and t1.idusuario!='4'";  //id superadmin
	
	$arrCamposBusqueda = array("apaterno","amaterno","nombre","pass","nombreCompleto","usuario","tipousuario","correo");	
	//hacerArchivo($strsql);
	paginacion_buscar($db,$strsql,$arrCamposBusqueda,"nombreCompleto",false,true,2);	
}


	// --------------------------------
	// 		PERMISOS USUARIO
	// --------------------------------	
if($_REQUEST["opcion"]=="PERMISOS_USUARIO"){
	$db->SetFetchMode(ADODB_FETCH_ASSOC);    	
	
	$grupos=array();
	$strsql="select * from tbpermisos_grupos order by clavepermisogrupopadre, clavepermisogrupo";
	$rs=$db->Execute($strsql);
	if($rs){
		while($row=$rs->fetchRow()){
			array_push($grupos,$row);
		}
	}
	//print_r($grupos);
	$permisos_asignados=array();
	
	$strsql="select * from tbpermisos_disponibles where clavepermiso in (select clavepermiso from tbpermisos_asignados where nombreusuario = 
	'".textToDB($_REQUEST["nombreusuario"])."' and tipoasignacion='1' union select clavepermiso from tbpermisos_tipos_usuario 
	where idtipousuario in(select idtipousuario from ctusuarios where usuario ='".textToDB($_REQUEST["nombreusuario"])."') 
	and clavepermiso not in(select clavepermiso from tbpermisos_asignados where nombreusuario = '".textToDB($_REQUEST["nombreusuario"])."'))
	order by clavepermisogrupo,accion";
	//hacerArchivo($strsql);
	$rs=$db->Execute($strsql);
	if($rs){
		while($row=$rs->fetchRow()){
			array_push($permisos_asignados,$row);
		}
	}
	//print_r($permisos_asignados);
	$permisos_disponibles=array();
	
	$strsql="select * from tbpermisos_disponibles where clavepermiso not in (select clavepermiso from tbpermisos_asignados 
	where nombreusuario = '".textToDB($_REQUEST["nombreusuario"])."' and tipoasignacion='1' union select clavepermiso from tbpermisos_tipos_usuario 
	where idtipousuario in(select idtipousuario from ctusuarios where usuario='".textToDB($_REQUEST["nombreusuario"])."') 
	and clavepermiso not in(select clavepermiso from tbpermisos_asignados where nombreusuario = '".textToDB($_REQUEST["nombreusuario"])."')) 
	order by clavepermisogrupo,accion";
	//hacerArchivo($strsql);
	$rs=$db->Execute($strsql);
	if($rs){
		while($row=$rs->fetchRow()){
			array_push($permisos_disponibles,$row);
		}
	}
	//print_r($permisos_disponibles);
	
	
	$padres=getPadres($grupos);
	$hijos=getHijos($padres,$grupos);
	//print_r($hijos);
	$xml.='<permisos>';
	$xml.='<permisos_asignados>';
	getXMLArbol($hijos,$permisos_asignados,$xml);
	$xml.='</permisos_asignados>';
	$xml.='<permisos_disponibles>';
	getXMLArbol($hijos,$permisos_disponibles,$xml);
	$xml.='</permisos_disponibles>';
	$xml.='</permisos>';
	echo $xml;
	
}

	// --------------------------------
	// 		CAPTURA USUARIO
	// --------------------------------	
if($_POST["opcion"]=="CAPTURA_USUARIO"){
	$db->SetFetchMode(ADODB_FETCH_ASSOC);    	
	$nombreusuario = textToDB($_POST["nombreusuario"]);
	$password      = textToDB($_POST["password"]);
	$nombre        = textToDB($_POST["nombre"]);
	$apaterno      = textToDB($_POST["apaterno"]);
	$amaterno      = textToDB($_POST["amaterno"]);
	$idtipousuario = textToDB($_POST["idtipousuario"]);
	$correo        = textToDB($_POST["correo"]);
	$celular        = textToDB($_POST["celular"]);
	$telefono        = textToDB($_POST["telefono"]);
	$permisos      = textToDB($_POST["permisos"]);
	
	
	
	
	if($nombreusuario==""){
		print "Error nombreusuario";
		exit;
	}
	if($password==""){
		print "Error password";
		exit;
	}
	if($apaterno==""){
		print "Error apaterno";
		exit;
	}
	if($nombre==""){
		print "Error nombre";
		exit;
	}
	if($idtipousuario=="" || !is_numeric($idtipousuario)){
		print "Error tipo_licitacion";
		exit;
	}
	
	  				
	$strsql="INSERT INTO
				ctusuarios (usuario,pass,nombre,apaterno,amaterno,idtipousuario,email,telefonos,fechaingreso)
	         VALUES
	        	('$nombreusuario','$password','$nombre','$apaterno','$amaterno','$idtipousuario','$correo','$telefono',now());";
	
	
	$db->starttrans();	
	if(($rs=$db->execute($strsql))){
				
//		$idusuario=getIdActual($db,"ctusuarios_idusuario_seq");
//		if($idusuario!='' && $idusuario>0){
			
			//PERMISOS QUE TIENE EL TIPO USUARIO
			$permisos_arr_tipo_usuario = array();	
			$strsql="select clavepermiso from tbpermisos_tipos_usuario where idtipousuario in (select idtipousuario from ctusuarios where usuario='".$nombreusuario."')";
			$rs=$db->execute($strsql);
			if($rs){
				while($row=$rs->fetchRow()){
					array_push($permisos_arr_tipo_usuario,$row['clavepermiso']);
				}
			}
			
			//PERMISOS DEL USUARIO
			$permisos_arr_usuario = array();	
			$strsql="select clavepermiso from tbpermisos_asignados where nombreusuario = '".$nombreusuario."'";
			$rs=$db->execute($strsql);
			if($rs){
				while($row=$rs->fetchRow()){
					array_push($permisos_arr_usuario,$row['clavepermiso']);
				}
			}
			
			//PERMISOS QUE VAN AGREGARSE
			if($_REQUEST["permisosAgregar"]!=""){
				$permisos_agregar=explode(",",$_REQUEST["permisosAgregar"]);
				foreach ($permisos_agregar as $permiso_agregar){
					if($permiso_agregar!=""){
						//SI EL TIPO DEL USUARIO CONTIENE EL PERMISO
						if(in_array($permiso_agregar,$permisos_arr_tipo_usuario)){
							//SI ESTA EN LOS PERMISOS ASIGNADOS, BORRARLO
							if(in_array($permiso_agregar,$permisos_arr_usuario)){
								$strsql="delete from tbpermisos_asignados where clavepermiso = '$permiso_agregar' and nombreusuario = '".$nombreusuario."'";
								$rs=$db->execute($strsql);
							}
						}
						else{
							//SI ESTA EN LOS PERMISOS ASIGNADOS, ACTUALIZARLO
							if(in_array($permiso_agregar,$permisos_arr_usuario)){
								$strsql="update tbpermisos_asignados set tipoasignacion='1' where clavepermiso = '$permiso_agregar' and nombreusuario = '".$nombreusuario."'";
								$rs=$db->execute($strsql);
							}
							else{
								$strsql="insert into tbpermisos_asignados (clavepermiso,nombreusuario,tipoasignacion) values ('$permiso_agregar','".$nombreusuario."', '1')";
								$rs=$db->execute($strsql);
							}
						}
					}
				}
			}
			
			
			//PERMISOS QUE VAN REMOVERSE
			if($_REQUEST["permisosRemover"]!=""){
				$permisos_remover=explode(",",$_REQUEST["permisosRemover"]);
				foreach ($permisos_remover as $permiso_remover){
					if($permiso_remover!=""){
						//SI EL PERFIL DEL USUARIO CONTIENE EL PERMISO
						if(in_array($permiso_remover,$permisos_arr_tipo_usuario)){	
							//SI ESTA EN LOS PERMISOS ASIGNADOS, ACTUALIZARLO
							if(in_array($permiso_remover,$permisos_arr_usuario)){
								$strsql="update tbpermisos_asignados set tipoasignacion = '2' where clavepermiso = '$permiso_remover' and nombreusuario = '".$nombreusuario."'";
								$rs=$db->execute($strsql);
							}
							else{
								$strsql="insert into tbpermisos_asignados (clavepermiso, nombreusuario, tipoasignacion) values ('$permiso_remover','".$nombreusuario."', '2')";
								$rs=$db->execute($strsql);
							}
						}
						else{
							//SI ESTA EN LOS PERMISOS ASIGNADOS, BORRARLO
							if(in_array($permiso_remover,$permisos_arr_usuario)){
								$strsql="delete from tbpermisos_asignados where clavepermiso = '$permiso_remover' and nombreusuario = '".$nombreusuario."'";
								$rs=$db->execute($strsql);
							}
						}
					}
				}
			}
			
			
//		}else{
//			$db->rollbacktrans();
//			print "Error: idusuario NO encontrado!"; 
//			exit;
//		}
	}
	else{
		if(strpos(strtoupper($db->ErrorMsg()),strtoupper("ctadministradores_nombreusuario_pkey"))!=0){
			print "yaexiste";
		}
		else{
			print "E1: ".$strsql." - ".$db->errormsg();//dbg
		}
	}
	if($db->completetrans()){
		print "ok";
	}
	else{
		$db->rollbacktrans();
		print "E3: ".$db->errormsg()." - ".$strsql;//dbg								
	}	
}




	// --------------------------------
	// 		EDITA USUARIO
	// --------------------------------	
if($_POST["opcion"]=="EDITA_USUARIO"){
	$db->SetFetchMode(ADODB_FETCH_ASSOC);    	
	$idusuario     = textToDB($_POST["idusuario"]);
	$nombreusuario = textToDB($_POST["nombreusuario"]);
	$password      = textToDB($_POST["password"]);
	$nombre        = textToDB($_POST["nombre"]);
	$apaterno      = textToDB($_POST["apaterno"]);
	$amaterno      = textToDB($_POST["amaterno"]);
	$idtipousuario = textToDB($_POST["idtipousuario"]);
	$correo        = textToDB($_POST["correo"]);
	$celular        = textToDB($_POST["celular"]);
	$telefono        = textToDB($_POST["telefono"]);
	$arr2["nombreusuario"]= textToDB($_POST["nombreusuario"]);

	
	
	if($idusuario=="" || !is_numeric($idusuario) || $idusuario<1){
		print "Error nombreusuario";
		exit;
	}
	if($nombreusuario==""){
		print "Error nombreusuario";
		exit;
	}
	if($password==""){
		print "Error password";
		exit;
	}
	if($apaterno==""){
		print "Error apaterno";
		exit;
	}
	if($nombre==""){
		print "Error nombre";
		exit;
	}
	//if($idtipousuario=="" || !is_numeric($idtipousuario)){
	//	print "Error tipo_licitacion";
	//	exit;
	//}
	
	
	$db->starttrans();
	
	//actualiza datos del usuario
	$strsql="UPDATE ctusuarios SET
		        usuario='$nombreusuario', nombre='$nombre', apaterno='$apaterno', amaterno='$amaterno',idtipousuario='$idtipousuario', email='$correo', telefonos='$telefono', pass='$password'  WHERE
			 	idusuario='$idusuario'; \n\n";

	
	
	if(($rs=$db->execute($strsql))){
		
		//PERMISOS QUE TIENE EL TIPO USUARIO
		$permisos_arr_tipo_usuario = array();	
		$strsql="select clavepermiso from tbpermisos_tipos_usuario where idtipousuario in (select idtipousuario from ctusuarios where usuario='".$nombreusuario."')";
		$rs=$db->execute($strsql);
		if($rs){
			while($row=$rs->fetchRow()){
				array_push($permisos_arr_tipo_usuario,$row['clavepermiso']);
			}
		}
		
		//PERMISOS DEL USUARIO
		$permisos_arr_usuario = array();	
		$strsql="select clavepermiso from tbpermisos_asignados where nombreusuario = '".$nombreusuario."'";
		$rs=$db->execute($strsql);
		if($rs){
			while($row=$rs->fetchRow()){
				array_push($permisos_arr_usuario,$row['clavepermiso']);
			}
		}
		
		//PERMISOS QUE VAN AGREGARSE
		if($_REQUEST["permisosAgregar"]!=""){
			$permisos_agregar=explode(",",$_REQUEST["permisosAgregar"]);
			foreach ($permisos_agregar as $permiso_agregar){
				if($permiso_agregar!=""){
					//SI EL TIPO DEL USUARIO CONTIENE EL PERMISO
					if(in_array($permiso_agregar,$permisos_arr_tipo_usuario)){
						//SI ESTA EN LOS PERMISOS ASIGNADOS, BORRARLO
						if(in_array($permiso_agregar,$permisos_arr_usuario)){
							$strsql="delete from tbpermisos_asignados where clavepermiso = '$permiso_agregar' and nombreusuario = '".$nombreusuario."'";
							$rs=$db->execute($strsql);
						}
					}
					else{
						//SI ESTA EN LOS PERMISOS ASIGNADOS, ACTUALIZARLO
						if(in_array($permiso_agregar,$permisos_arr_usuario)){
							$strsql="update tbpermisos_asignados set tipoasignacion='1' where clavepermiso = '$permiso_agregar' and nombreusuario = '".$nombreusuario."'";
							$rs=$db->execute($strsql);
						}
						else{
							$strsql="insert into tbpermisos_asignados (clavepermiso,nombreusuario,tipoasignacion) values ('$permiso_agregar','".$nombreusuario."', '1')";
							$rs=$db->execute($strsql);
						}
					}
				}
			}
		}
		
		
		//PERMISOS QUE VAN REMOVERSE
		if($_REQUEST["permisosRemover"]!=""){
			$permisos_remover=explode(",",$_REQUEST["permisosRemover"]);
			foreach ($permisos_remover as $permiso_remover){
				if($permiso_remover!=""){
					//SI EL PERFIL DEL USUARIO CONTIENE EL PERMISO
					if(in_array($permiso_remover,$permisos_arr_tipo_usuario)){	
						//SI ESTA EN LOS PERMISOS ASIGNADOS, ACTUALIZARLO
						if(in_array($permiso_remover,$permisos_arr_usuario)){
							$strsql="update tbpermisos_asignados set tipoasignacion = '2' where clavepermiso = '$permiso_remover' and nombreusuario = '".$nombreusuario."'";
							$rs=$db->execute($strsql);
						}
						else{
							$strsql="insert into tbpermisos_asignados (clavepermiso, nombreusuario, tipoasignacion) values ('$permiso_remover','".$nombreusuario."', '2')";
							$rs=$db->execute($strsql);
						}
					}
					else{
						//SI ESTA EN LOS PERMISOS ASIGNADOS, BORRARLO
						if(in_array($permiso_remover,$permisos_arr_usuario)){
							$strsql="delete from tbpermisos_asignados where clavepermiso = '$permiso_remover' and nombreusuario = '".$nombreusuario."'";
							$rs=$db->execute($strsql);
						}
					}
				}
			}
		}
	}
	else{
		if(strpos(strtoupper($db->ErrorMsg()),strtoupper("ctadministradores_nombreusuario_pkey"))!=0){
			print "yaexiste";
		}
		else{
			$db->rollbacktrans();
			print "E1: ".$strsql." - ".$db->errormsg();
		}
	}
	
	if($db->completetrans()){
		print "ok";
	}
	else{
		$db->rollbacktrans();
		print "E4: ".$db->errormsg();
	}
}







	// --------------------------------
	// 		BORRA USUARIO
	// --------------------------------	

if($_POST["opcion"]=="BORRA_USUARIO"){
	paginacion_borrar($db,"DELETE FROM ctusuarios WHERE idusuario=[id];");
}



?>
