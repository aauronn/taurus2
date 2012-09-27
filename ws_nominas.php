<?php
	include "include/entorno.inc.php";
	include "include/funciones.inc.php";
	
	//$strsql = "select distinct b.ejercicio,a.idperiodo,b.numeroperiodo, b.fechainicio, b.fechafin from nom10008 a left join 
	//	nom10002 b on a.idperiodo=b.idperiodo";
	//	print getXML($strsql,$dbNom);
	
	if ($_POST["opcion"]=="GET_PERIODO") {
		$strsqlSel="select distinct b.ejercicio,a.idperiodo,b.numeroperiodo, b.fechainicio, b.fechafin from nom10008 a left join 
		nom10002 b on a.idperiodo=b.idperiodo";
		$texto="";
		$rs=$dbNom->Execute($strsqlSel);
		if ($rs) {
			while ($row = $rs->fetchRow()) {
				$dateInicio = new DateTime($row[fechainicio]);
				$dateFinal = new DateTime($row[fechafin]);
				$texto= $row[numeroperiodo]."| Del ".date_format($dateInicio, 'd-m-y')." al ".date_format($dateFinal, 'd-m-y');
			}
		}
		$xml1="<?xml version='1.0' encoding='UTF-8'?>\n";
		$xml1.="<tablas>\n";
		$xml1.= "\t<tabla0>\n";
		$xml1.= "\t\t<numrows total='1' limit='0' offset='0'/>" ;
		$xml1.= "\t\t<rows texto='".$texto."'/>\n";
		$xml1.= "\t</tabla0>\n";
		$xml1.= "</tablas>\n";
		print $xml1;
	}
	
	if ($_POST["opcion"]=="FILTRAR_NOMINAS"){
			
		//$numeroPeriodo;
		$strsqlSel="select distinct b.ejercicio,a.idperiodo,b.numeroperiodo, b.fechainicio, b.fechafin from nom10008 a left join 
		nom10002 b on a.idperiodo=b.idperiodo";
		
		$rs=$dbNom->Execute($strsqlSel);
		if ($rs) {
			while ($row = $rs->fetchRow()) {
				$idPeriodo=textToDB($row[idperiodo]);
				$numeroPeriodo=$row[numeroperiodo];
				hacerArchivo($row[numeroperiodo]);
			}
		}
		
		
		$strsqlNomina = "select b.nombre,b.nombrelargo,b.codigoempleado,b.sucursalpagoelectronico, b.cuentapagoelectronico,
		a.importetotal from nom10008 a left join nom10001 b on a.idempleado=b.idempleado where importetotal>0 and idconcepto=1 
		and idperiodo=$idPeriodo ";
		//Le quite esto al final para que jale el query en MSSERVER
		//order by nombrelargo
		print getXML($strsqlNomina, $dbNom);
		$GLOBALS['idPeriodo']=$numeroPeriodo;
		//paginacion_buscar($dbNom,$strsqlNomina, array("idperiodo"),"nombrelargo",false,true,0);
	}
	
	if($_POST["tiporeporte"]=="CSV")
	{
		$fecha =new DateTime($_POST['fecha']);
		//hacerArchivo($fecha);
		//$fecha = new DateTime('2012/02/29');
		$fecha2=date_format($fecha,'Y/m/d');
		$numeroperiodo;
		$strsqlSel="select distinct b.ejercicio,a.idperiodo,b.numeroperiodo, b.fechainicio, b.fechafin from nom10008 a left join 
		nom10002 b on a.idperiodo=b.idperiodo";
		$rs=$dbNom->Execute($strsqlSel);
		if ($rs) {
			while ($row = $rs->fetchRow()) {
				
				$numeroperiodo= $row[numeroperiodo];
			}
		}
		
		$flashvars = $_POST['arrtablas'];
		if($flashvars!=""){
			if(get_magic_quotes_gpc()){
			    $flashvars = stripslashes($flashvars);
			}
			$arrTablas = object_to_array(unserialize(urldecode($flashvars)));
		//	print_r($arrTablas);
		}
		
		$url_path = "./temp_archivos/";
		$filename = "reporte_".time().".csv";
		$file     = $GLOBALS["temp_archivos_dir"].$filename; //xampp para crear el archivo
		$file_url = $GLOBALS["temp_archivos_url"].$filename; //localhost para descarga de archivo
		$fpCSV    = fopen($file,"wb+"); // resource de archivo para el reporte csv
		
		foreach ($arrTablas as $registro) {
			//$papa=$registro[nombrelargo];
			//Cuenta general=05847664
			//Sucursal=110
			$strDatos.="CHQ,".$registro[sucursalpagoelectronico].",05847664,CHQ,".$registro[sucursalpagoelectronico].",";
			$strDatos.=$registro[cuentapagoelectronico].",MXN,".sprintf("%.2F",$registro[importetotal]).",".$registro[nombre].",";
			$strDatos.=$registro[codigoempleado].", , , ,".$fecha2.",Nomina Catorcena No. ".$numeroperiodo."\r\n";
		}
		fwrite($fpCSV, $strDatos);
		fclose($fpCSV);
		//hacerArchivo($papa);
		
		//JavaScript redirection
		print "<HTML><SCRIPT>document.location='$file_url';</SCRIPT></HTML>";
	}
?>
