<?php

include "include/entorno.inc.php";
include "include/funciones.inc.php";


	// --------------------------------
	// 		CATALOGO PERMISOS DISPONIBLES (UTILIZADO EN MODULO DE USUARIOS)
	// --------------------------------
if($_POST["opcion"]=="CAT_PERMISOS_DISPONIBLES"){	
	$strsql = "select distinct (modulo) from tbpermisos_disponibles order by modulo ";
	
	$xml = "<?xml version='1.0' encoding='UTF-8'?>\n";
	$xml .= "<tablas>\n";
	if(($rs=$db->execute($strsql))){		
		$xml .= "\t<tabla0>\n";
		while($row=$rs->fetchRow()){			
			$xml .= "<permiso clavepermiso='' nombre='".textFromDB($row["modulo"])."' descripcion='' >  ";
			
			$strsql = "select clavepermiso,accion,descripcion from tbpermisos_disponibles where modulo='".$row["modulo"]."' ";
			if(($rs2=$db->execute($strsql))){		
				while($row2=$rs2->fetchRow()){			
					$xml .= "<permiso clavepermiso='".$row2["clavepermiso"]."' nombre='".textFromDB($row2["accion"])."' descripcion='".textFromDB($row2["descripcion"])."' /> ";
				}
			}else{
				print "Error2: ".$db->ErrorMsg();
			}
			$xml .= "</permiso>\n";
		}
		$xml .= "\t</tabla0>\n";
	}else{
		print "Error: ".$db->ErrorMsg();
	}
	$xml .= "</tablas>\n";
	print $xml;
}







	// --------------------------------
	// 		FILTRA PERMISOS
	// --------------------------------
if($_POST["opcion"]=="FILTRA_PERMISOS"){	
	$strsql = "select * from tbpermisos_disponibles ";

	$arrCamposBusqueda = array("titulo","descripcion");
	
	paginacion_buscar($db,$strsql,$arrCamposBusqueda,"clavepermiso");
}








?>