<?php
	include "include/entorno.inc.php";
	include "include/funciones.inc.php";
	//include "/download.php";
	
	
	//$GLOBALS["gIVAf"]= 0.11;
	
	if ($_POST["opcion"]=="EXPORTAR_TXT") {
		$dbAcs->SetFetchMode(ADODB_FETCH_ASSOC);
		$formato = '%1$08d';
		$fechaExportar  =	$_POST["fechaExportar"];
		
		
		$lNoPoliza		=	$_POST["lNoPoliza"];
		$lMovtoIni		=	$_POST["lMovtoIni"];
		$lDepIni		=	$_POST["lDepIni"];
		
		$lIDPoliza=1; 		
		$lDeposito=1; 		
		$lIDDocto=0;		
		$lBenef=0;
		$iField=0;
		$sNombre="";
		
		$lIDMovto		=	$lMovtoIni;
		$lIDPolizaini	=	$lIDPoliza;
		$lIDMovtoini	=	$lIDMovto;
		$lIDDep			=	$lDepIni;
		$lNoPolizaIni	=	$lNoPoliza;
		$lIDDoctoIni	=	$lIDDocto;
		$lIDDepIni		=	$lIDDep;
		
		$lsLines=""; 		$lsDep="";
		$sLine="";
		$sTipoMovto="";
	
		//*********************************************
		//		COPIA POLIZAS
		//*********************************************
		$strConsultaPolChqpW  	= "SELECT * FROM PolChqpW";	
		$rsPolChqW=$dbAcs->Execute($strConsultaPolChqpW);
		//$result = odbc_do($pxVirtual2,$strConsultaPolChqpW);
		
		if($rsPolChqW){
			
			while ($row = $rsPolChqW->fetchRow()) {
				$IdPolChqpwP	=	$row[IDPolChqpW];
				//$FechaP			=	$row[Fecha];
				$FechaP			=	date("Ymd",strtotime(substr($row[Fecha],0,10)));
				$TipoP			=	$row[Tipo];
				$Numero			=	$row[Numero];
				$ConceptoP		=	$row[Concepto];
				
				$sprint = sprintf($formato,$lNoPoliza);
				list($year, $month, $day)=split('[/.-]',$FechaP);
			
				$strConsultaMovPolCh	=	"SELECT * FROM MovPolCh WHERE IDPolChqpW= $IdPolChqpwP ORDER BY NumeroMovto";		//DETALLE POLIZA
				//Encabezado de la poliza
				$sLine.= "P  $year$month$day    1  $sprint 1 0          ".substr("$ConceptoP                                                                                                           ",0,100)." 11 0 0 \r\n";
				
				$rsMovPolChq=$dbAcs->Execute($strConsultaMovPolCh);
				print_r($strConsultaMovPolCh);
				if ($rsMovPolChq) {
					
					while ($row2 = $rsMovPolChq -> fetchRow()) {
						$IdPolChqpwP2	=	$row2[IDPolChqpW];
						$NumeroMovto2	=	$row2[NumeroMovto];
						$CuentaContpaq2	=	$row2[CuentaContpaqW];
						$Referencia2	=	$row2[Referencia];
						$Concepto2		=	$row2[Concepto];
						$TipoMovto2		=	$row2[TipoMovto];
						$CargoMN2		=	$row2[CargoMN];
						$AbonoMN2		=	$row2[AbonoMN];
						$CargoME2		=	$row2[CargoME];
						$AbonoME2		=	$row2[AbonoME];
						$IDDeposito		=	$row2[IDDeposito];
						$Diario			=	$row2[Diario];
						
						if ($TipoMovto2=="F") {
							$sTipoMovto = "0";
						}else {
							$sTipoMovto = "1";
						}
						
						$sLine.="M  ".str_pad($CuentaContpaq2,30," ", STR_PAD_RIGHT)." ";
						$sLine.=str_pad(" ",10," ",STR_PAD_RIGHT)."  ".$sTipoMovto." ";
						$sLine.=str_pad($CargoMN2,15," ", STR_PAD_RIGHT)."      0          0.0                  ";
						$sLine.=str_pad($Concepto2,100," ", STR_PAD_RIGHT)."\r\n";
						/*
						$sLine.= "M  ".substr(contpaqPesos2Dolares($CuentaContpaq2,$row2[moneda])."                                                                               ",0,30)." ".
												substr(''."                                   ",0,10).
												" ".
												$TipoMovto2." ".
												substr($CargoMN2+$AbonoMN2."                      ",0,15).
												"      0          0.0                   ".substr($Concepto2,0,100) ."      \r\n";
						*/
					}
				}
				$lIdPoliza++;
				$lNoPoliza++;
			}
		}
		
		//Poliza
		$nombreArchivoPoliza= "Polizas_Ingresos $lNoPolizaIni - ".($lNoPoliza-1);
		crearArchivoIngresos($sLine,$nombreArchivoPoliza, "Poliza Compaq");

		
		//*************************************************
		//			COPIA INGRESOS NO DEPOSITOS
		//*************************************************
		$strConsultaDepositos  	= "SELECT * FROM Depositos";
		$rsDeposito = $dbAcs->Execute($strConsultaDepositos);
		if($rsDeposito){
			
			while ($Deposito = $rsDeposito->fetchRow()) {

				$CIdDeposito 			=	$Deposito[CIdDeposito];
				$CIDCuenta				=	$Deposito[CIDCuenta];
				$CImporte				=	$Deposito[CImporte];
				$CConceptoGral			=	$Deposito[CConceptoGral];
				$CReferencia			=	$Deposito[CReferencia];
				$CTipoIngreso			=	$Deposito[CTipoIngreso];
				$CFechaDeposito			=	$Deposito[CFechaDeposito];
				$CFechaAplicacion		=	$Deposito[CFechaAplicacion];
				$CEstadoConciliado		=	$Deposito[CEstadoConciliado];
				$CEstadoContable		=	$Deposito[CEstadoContable];
				$CIDPoliza				=	$Deposito[CIDPoliza];
				$CTSAlta				=	$Deposito[CTSAlta];
				$CTSUltimaActualizacion	=	$Deposito[CTSUltimaActualizacion];
				$CUserAlta				=	$Deposito[CUserAlta];
				$CUserActualizacion		=	$Deposito[CUserActualizacion];
				$CuentaTXT				= 	$Deposito[cuenta_txt];
				
				$datos.="DE 04030                             44 ";
				$datos.=str_pad($lIDDep, 20, " ", STR_PAD_LEFT)." ";
				$datos.=date("Ymd",strtotime(substr($CFechaDeposito,0,10)))." ";
				$datos.=date("Y m",strtotime(substr($CFechaDeposito,0,10)))." ";
				$datos.=date("Ymd",strtotime(substr($CFechaDeposito,0,10)))." ";
				$datos.=date("Y m",strtotime(substr($CFechaDeposito,0,10)))." ";
				$datos.=str_pad($CuentaTXT,20," ", STR_PAD_RIGHT)." ";
				$datos.='2 0'." ";
				$datos.=str_pad($CImporte,20," ", STR_PAD_RIGHT)." "; 
				$datos.=str_pad($CReferencia,20," ", STR_PAD_RIGHT)." "; 
				$datos.=str_pad($CConceptoGral,100," ", STR_PAD_RIGHT)." "; 
				$datos.="0          0 "; 
				$datos.=date("Y m",strtotime(substr($CFechaDeposito,0,10)))." ";
				$datos.=str_pad("1",10," ",STR_PAD_LEFT)." ";
				$datos.=str_pad(($CIDPoliza+($lNoPolizaIni-1)),10," ", STR_PAD_LEFT)." "; 
				$datos.="0 ";
				$datos.=str_pad("0",10," ",STR_PAD_LEFT)." ";
				$datos.="  201 ";
				$datos.=str_pad($lIDDocto,10," ", STR_PAD_LEFT)." 0 ";
				$datos.="\r\n";
				
				$lIDDocto++;
				
				$strConsultaMovtos = "SELECT * FROM movtos WHERE Deposito = $CIdDeposito";
				$rsMovtos = $dbAcs->Execute($strConsultaMovtos);
				if ($rsMovtos) {
					while ($movto = $rsMovtos->fetchrow()) {		
						$strMovto3.="ID 04010                             38 ";
						$strMovto3.=str_pad("$lIDMovto",20," ",STR_PAD_LEFT)." ";
						$strMovto3.=date("Ymd",strtotime(substr($movto[FechaMovto],0,10)))." ";
						$strMovto3.=date("Y m",strtotime(substr($movto[FechaMovto],0,10)))." ";
						$strMovto3.=date("Ymd",strtotime(substr($movto[FechaMovto],0,10)))." ";
						$strMovto3.=date("Y m",strtotime(substr($movto[FechaMovto],0,10)))." ";
						$strMovto3.=str_pad($movto[Beneficiariopagador],6," ",STR_PAD_RIGHT)." ";
						$strMovto3.=str_pad($movto[Cuentanombre],200," ",STR_PAD_RIGHT)." ";
						$strMovto3.="2 0    1    2 ";
						$strMovto3.=str_pad("0.0",20," ",STR_PAD_RIGHT)." ";
						$strMovto3.=str_pad($movto[ImporteMonedaCuenta],20," ",STR_PAD_RIGHT)." ";
						$strMovto3.=str_pad((substr($movto[Referencia],0,2)." ".substr($movto[Referencia],6)),20," ",STR_PAD_RIGHT)." ";
						$strMovto3.=str_pad(" ",100," ")." ";
						$strMovto3.="0                               0 0   201          0 ";
						$strMovto3.=str_pad($movto[cuenta_txt],20," ",STR_PAD_RIGHT)." ";
						$strMovto3.=str_pad('1.0',20," ",STR_PAD_RIGHT)." ";
						$strMovto3.=str_pad($lIDDocto,10," ",STR_PAD_LEFT)." 0 0 ";
						$strMovto3.="\r\n";
						
						
						$strMovto2.="DI 04010                             38 ";
						$strMovto2.=str_pad($lIDMovto,20," ",STR_PAD_LEFT)." ";
						$strMovto2.=date("Ymd",strtotime(substr($movto[FechaMovto],0,10)))." ";
						$strMovto2.=str_pad('1.0',20," ",STR_PAD_RIGHT)." ";
						$strMovto2.="\r\n";
						
						$lIDDocto++;
						$lIDMovto++;
					}
				}
				$lIDDep++;
				//hacerArchivo($strMovto2."\r\n\r".$movto[FechaMovto]."\r\n\r".$lIdMovto."\r\n".$strConsultaMovtos);
			}
		}
		
		
				
		//Depositos
		$datos_concatenados=$datos.$strMovto2;
		$nombreArchivoDeposito= "Depositos $lIDDepIni - ".($lIDDep-1);
		crearArchivoIngresos($datos_concatenados,$nombreArchivoDeposito, "Poliza Depositos");
		
		//Ingresos no depositados
		$nombreArchivoIngresosNoDep= "Ingresos_NoDep $lMovtoIni - ".($lIDMovto-1);
		crearArchivoIngresos($strMovto3,$nombreArchivoIngresosNoDep, "Poliza Ingreso No Depositado");
		
		$dbAcs->Close();
		
		//hacerArchivo($GLOBALS["nombreArchivoPoliza"]."\r\n".$GLOBALS['sLine']."\r\n".$GLOBALS["nombreArchivoDeposito"]."\r\n".$GLOBALS['datos_concatenados']."\r\n".$GLOBALS["nombreArchivoIngresosNoDep"]."\r\n".$GLOBALS['strMovto2']);
		
		$xml = "";
		$xml .= "<?xml version='1.0' encoding='UTF-8'?>\r\n";
		$xml .= "<tablas>";
		$xml .= "<pnombre>".$nombreArchivoPoliza."</pnombre>\r\n";
		$xml .= "<pdatos>$sLine</pdatos>\r\n";
		$xml .= "<dnombre>".$nombreArchivoDeposito."</dnombre>\r\n";
		$xml .= "<ddatos>$datos_concatenados</ddatos>\r\n";
		$xml .= "<indnombre>".$nombreArchivoIngresosNoDep."</indnombre>\r\n";
		$xml .= "<idndatos>$strMovto2</idndatos>\r\n";
		$xml .= "</tablas>";
		
		//print $xml;
		
		
	}
	
	function crearArchivoIngresos($dato, $nombreArchivo, $tipo){
		$url_path = "./temp_archivos/";
		$filename = $nombreArchivo.".txt";
		$file     = $GLOBALS["temp_archivos_dir"].$filename; //xampp para crear el archivo
		$file_url = $GLOBALS["temp_archivos_url"].$filename; //localhost para descarga de archivo
		$fpTXT    = fopen($file,"wb+"); // resource de archivo para el reporte csv
		
		fwrite($fpTXT, $dato);
		fclose($fpTXT);
		
		//return $file_url;
		//$text = file_get_contents($file_url);
		//header('ETag: etagforie7download'); //IE7 requires this header
		//header('Content-type: text/plain');
		//header('Content-disposition: attachment; filename="'.$file_url.'"');
		hacerArchivo($file_url);
		print "<html>
<head>
<title></title>

</head>
<body>


<CENTER> <a href='".$GLOBALS["url_host"]."download.php?filename=".$GLOBALS["temp_archivos_dir"].$filename."&name=$filename'>Descargar $tipo </a>     </center><br>
</body>
</html>";
		//print "$text";
	}

?>