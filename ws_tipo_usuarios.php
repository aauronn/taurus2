<?php

include "include/entorno.inc.php";
include "include/funciones.inc.php";



	// --------------------------------
	// 		CATALOGO TIPO DE USUARIOS (USADO PARA COMBOBOX)
	// --------------------------------
if($_POST["opcion"]=="CAT_TIPO_USUARIOS"){	
	$strsql = "SELECT * FROM cttipousuarios order by titulo ";
	print getXML($strsql,$db);
}







	// --------------------------------
	// 		FILTRA TIPO DE USUARIOS
	// --------------------------------
if($_POST["opcion"]=="FILTRA_REGISTROS"){		
	paginacion_buscar($db,"SELECT * FROM cttipousuarios ",array("titulo","descripcion"),"titulo",false);		
}







	// --------------------------------
	// 		BORRA TIPO DE USUARIO
	// --------------------------------
if($_POST["opcion"]=="BORRA_REGISTRO"){	
	paginacion_borrar($db,"DELETE FROM cttipousuarios WHERE idtipousuario=[id]");
}







	// --------------------------------
	// 		CAPTURA TIPO DE USUARIO
	// --------------------------------
if($_POST["opcion"]=="NUEVO_REGISTRO"){
	$titulo      = textToDB($_POST["titulo"]);
	$descripcion = textToDB($_POST["descripcion"]);
	
	if(trim($titulo)==""){
		print "El titulo no puede estar vacío";
		exit;
	}
	
	
	$strsql="INSERT INTO cttipousuarios (titulo,descripcion) VALUES ('$titulo','$descripcion')";
	
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
			if(strpos(strtoupper($db->ErrorMsg()),strtoupper("cttipousuarios_pkey"))!=0){
				print "yaexiste";
			}
			else{
				print "E1: ".$db->errormsg();//dbg
			}
		}
}



	// --------------------------------
	// 		EDITA TIPO DE USUARIO
	// --------------------------------
if($_POST["opcion"]=="EDITAR_REGISTRO"){	
	$id          = textToDB($_POST["idtipousuario"]);
	$titulo      = textToDB($_POST["titulo"]);
	$descripcion = textToDB($_POST["descripcion"]);
	
	if($id=="" || !is_numeric($id)){
		print "Error id";
		exit;
	}
	
	if(trim($titulo)==""){
		print "El titulo no puede estar vacío";
		exit;
	}
	
	$db->starttrans();	
	
	$strsql="UPDATE cttipousuarios SET titulo='$titulo',descripcion='$descripcion' WHERE idtipousuario=$id ";
	hacerArchivo($strsql);
	
	if(($rs=$db->execute($strsql))){
	
		if($db->completetrans()){
			print "ok";
		}
		else{
			$db->rollbacktrans();
			print "E4: ".$db->errormsg();
		}
	}
}

// --------------------------------
// 		OBTIENE LOS PERMISOS SEGUN EL TIPO DE USUARIO
// --------------------------------
if($_REQUEST["opcion"]=="PERMISOS_TIPO_USUARIO"){
	    	
	$grupos=array();
	$strsql="select * from tbpermisos_grupos order by clavepermisogrupopadre NULLS FIRST, clavepermisogrupo";
	$rs=$db->Execute($strsql);
	if($rs){
		while($row=$rs->fetchRow()){
			array_push($grupos,$row);
		}
	}
	
	$permisos_asignados=array();
	$permisos_disponibles=array();
	
	if(isset($_REQUEST["idtipousuario"]) && $_REQUEST["idtipousuario"]!=''){
		$strsql="select * from tbpermisos_disponibles where clavepermiso in (select clavepermiso from tbpermisos_tipos_usuario where idtipousuario='".$_REQUEST["idtipousuario"]."' )order by clavepermisogrupo,accion";
		//print $strsql;
		$rs=$db->Execute($strsql);
		if($rs){
			while($row=$rs->fetchRow()){
				array_push($permisos_asignados,$row);
			}
		}
		
		
		
		$strsql="select * from tbpermisos_disponibles where clavepermiso not in (select clavepermiso from tbpermisos_tipos_usuario where idtipousuario='".$_REQUEST["idtipousuario"]."' )order by clavepermisogrupo,accion";
		$rs=$db->Execute($strsql);
		if($rs){
			while($row=$rs->fetchRow()){
				array_push($permisos_disponibles,$row);
			}
		}
	}
	else{
		
		$strsql="select * from tbpermisos_disponibles where 1=1 order by clavepermisogrupo,accion";
		$rs=$db->Execute($strsql);
		if($rs){
			while($row=$rs->fetchRow()){
				array_push($permisos_disponibles,$row);
			}
		}
	}
	$padres=getPadres($grupos);
	$hijos=getHijos($padres,$grupos);
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


?>