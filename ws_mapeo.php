<?php


include "include/entorno.inc.php";
include "include/funciones.inc.php";


// --------------------------------
	// 		PARA LLENAR COMBO CON DATAPROVIDERS 
	// --------------------------------
	if($_POST["opcion"]=="CAT_MAPEOS"){	
		$strsql = "SELECT * FROM tbmapeos_tablas ";
		print getXML($strsql,$db);
	}


	if($_POST["opcion"]=="HACER_QUERYS"){
		$idtabla=textToDB($_REQUEST["idtabla"]);
		$idtipousuario=textToDB($_REQUEST["idtipousuario"]);
		$nombretabla=textToDB($_REQUEST["nombretabla"]);
		
		$db->starttrans();	
		
		$strsql=generateQuery($_REQUEST["tipo_movimiento"],$_POST,"tbmapeos_tablas",'',array('tipo_movimiento','opcion'),true,array('idtabla'));
		//print $strsql;
		$rs=$db->execute($strsql);
		
		if ($db->completetrans()) {
			print "ok";
		}
		else{
			print $strsql;
			print "no";
			print $db->errormsg();
		}
	}



?>