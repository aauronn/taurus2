<?php
	include "include/entorno.inc.php";
	include "include/funciones.inc.php";	

	//*************************************
	//		DATAPROVIDER CLIENTES
	//*************************************

	
	// *******************************************************
	// 			FILTRA
	// *******************************************************
	if($_POST["opcion"]=="FILTRA_REGISTROS"){		
		$strsql=" SELECT *, (direccion_1 || ' ' || direccion_2) as direccion FROM proveedores";
		
		paginacion_buscar($db," $strsql ",array("clave_proveedor","nombre","rfc"),"clave_proveedor",false,true,2);		
		
	}
	
	//*******************************************************
	//			BORRAR
	//*******************************************************
	
	if($_POST["opcion"]=="BORRAR_REGISTROS"){	
		//paginacion_borrar($db,"DELETE FROM ctsbu WHERE idsbu=[id]",true);	
	}
	
	
	//*******************************************************
	//			EDITA SBU
	//*******************************************************
	if($_POST["opcion"]=="EDITAR_REGISTRO"){
		$clave_proveedor		=	textToDB(strtoupper($_POST["clave_proveedor"]));
		$cuentaProveedor		=	textToDB(strtoupper($_POST["cuentaProveedor"]));
		$pasivoAbonoMN			=	textToDB(strtoupper($_POST["pasivoAbonoMN"]));
		$pasivoAbonoDLS			=	textToDB(strtoupper($_POST["pasivoAbonoDLS"]));
		$IVA					=	textToDB(strtoupper($_POST["IVA"]));
		$flete					=	textToDB(strtoupper($_POST["flete"]));
		$fleteCargoImportacion	=	textToDB(strtoupper($_POST["fleteCargoImportacion"]));
		$fleteAbonoImportacion	=	textToDB(strtoupper($_POST["fleteAbonoImportacion"]));
		
		$strsql = "UPDATE proveedores SET poliza_cargo='$cuentaProveedor', poliza_abono_mn='$pasivoAbonoMN', ";
		$strsql.=" poliza_abono_dls='$pasivoAbonoDLS',poliza_iva='$IVA', poliza_cargo_flete='$flete', ";
		$strsql.=" poliza_cargo_flete_importacion='$fleteCargoImportacion', poliza_abono_flete_importacion='$fleteAbonoImportacion' ";
		$strsql.=" WHERE clave_proveedor='$clave_proveedor'";
		//print $strsql;
		
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