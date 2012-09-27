<?php
	include "include/entorno.inc.php";
	include "include/funciones.inc.php";
	
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
				$FechaP			=	$row[Fecha];
				$TipoP			=	$row[Tipo];
				$Numero			=	$row[Numero];
				$ConceptoP		=	$row[Concepto];
				
				$sprint = sprintf($formato,$lNoPoliza);
				list($year, $month, $day)=split('[/.-]',$FechaP);
			
				$strConsultaMovPolCh	=	"SELECT * FROM MovPolCh WHERE IDPolChqpW= '$IdPolChqpwP'";		//DETALLE POLIZA
				//Encabezado de la poliza
				$sLine = "P  $year$month$day    1  $sprint 1 0          ".substr("$ConceptoP                                                                                                           ",0,100)." 11 0 0 \r\n";
				
				$rsMovPolChq=$dbAcs->Execute($strConsultaMovPolCh);
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
						
						$sLine.= "M  ".substr(contpaqPesos2Dolares($CuentaContpaq2,$row2[moneda])."                                                                               ",0,30)." ".
												substr(''."                                   ",0,10).
												" ".
												$TipoMovto2." ".
												substr($CargoMN2+$AbonoMN2."                      ",0,15).
												"      0          0.0                   ".substr($Concepto2,0,100) ."      \r\n";
					}
				}
				$lIdPoliza++;
				$lNoPoliza++;
			}
		}
		//Aqui va el codigo para generar el TXT
//		header('ETag: etagforie7download'); //IE7 requires this header
//		header('Content-type: application/octet_stream');
		header('Content-disposition: attachment; filename="Polizas Ingresos '.$lNoPolizaIni.'-'.($lNoPoliza-1).'.txt"');
		echo "$sLine";
		
		//*************************************************
		//			COPIA INGRESOS NO DEPOSITOS
		//*************************************************
		$strConsultaDepositos  	= "SELECT * FROM Depositos";
		$Deposito = $dbAcs->Execute($strConsultaDepositos);
		if($rsPolChqW){
			
			while ($Deposito = $Deposito->fetchRow()) {
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
				$datos.=substr($lIDDep,0,20)." ";
				$datos.=date("Ymd",$CFechaDeposito)." ";
				$datos.=date("Y m",$CFechaDeposito)." ";
				$datos.=date("Ymd",$CFechaDeposito)." ";
				$datos.=date("Y m",$CFechaDeposito)." ";
				$datos.=substr($CuentaTXT,0,20)." ";
				$datos.='2 0'." ";
				$datos.=substr($CImporte,0,20)." "; 
				$datos.=substr($CReferencia,0,20)." "; 
				$datos.=substr($CConceptoGral,0,100)." "; 
				$datos.="0          0 "; 
				$datos.=date("Y m",$CFechaDeposito)." ";
				$datos.="1          ";
				$datos.=substr($CIDPoliza+($lIDPolizaIni-1),0,10)." "; 
				$datos.="0 ";
				$datos.="0           ";
				$datos.="  201 ";
				$datos.=substr($lIDDocto,0,10)." 0 ";
				$datos.="\r\n";
				
				$lIDDocto++;
				
				$strConsultaMovtos = "SELECT * FROM movtos WHERE Deposito = $CIdDeposito";
				
				$rsMovtos = $dbAcs->Execute($strConsultaMovtos);
				if ($rsMovtos) {
					while ($movto = $rsMovtos->fetchrow()) {		
						$srtMovto.="ID 04010                             38 ";
						$srtMovto.=substr($movto[lIdMovto],0,20)." ";
						$srtMovto.=date("Ymd",$movto[fechamovto])." ";
						$srtMovto.=date("Y m",$movto[fechamovto])." ";
						$srtMovto.=date("Ymd",$movto[fechamovto])." ";
						$srtMovto.=date("Y m",$movto[fechamovto])." ";
						$srtMovto.=substr($movto[Beneficiariopagador],0,6)." ";
						$srtMovto.=substr($movto[Cuentanombre],0,200)." ";
						$srtMovto.="2 0    1    2 ";
						$srtMovto.=substr("0.0",0,20)." ";
						$srtMovto.=substr($movto[ImporteMonedaCuenta],0,20)." ";
						$srtMovto.=substr($movto[Referencia],0,20)." ";
						$srtMovto.=substr(" ",0,100)."";
						$srtMovto.="0                               0 0   201          0 ";
						$srtMovto.=substr($movto[cuenta_txt],0,20)." ";
						$srtMovto.=substr('1.0',0,20)." ";
						$srtMovto.=substr($lIDDocto,0,10)." 0 0 ";
						$srtMovto.="\r\n";
						
						
						$strMovto2.="DI 04010                             38 ";
						$srtMovto2.=substr($movto[$lIDMovto],0,20)." ";
						$srtMovto2.=date("Ymd",$movto[fechamovto])." ";
						$srtMovto2.=substr('1.0',0,20)." ";
						$srtMovto2.="\r\n";
						
						$lIDDocto++;
						$lIDMovto++;
					}
				}
				$lIDDep++;
			}
		}
		//******************************************
		//				DEPOSITOS
		//******************************************
		
//		header('ETag: etagforie7download'); //IE7 requires this header
//		header('Content-type: application/octet_stream');
//		header('Content-disposition: attachment; filename="Depositos '.$lIDDepIni.'-'.($lIDDep-1).'.txt"');
//		echo "$datos$strMovto2";
		
		//******************************************
		//			INGRESOS NO DEPOSITADOS
		//******************************************
		
//		header('ETag: etagforie7download'); //IE7 requires this header
//		header('Content-type: application/octet_stream');
//		header('Content-disposition: attachment; filename="Ingresos No Dep '.$lIDMovtoIni.'-'.($lIDMovto-1).'.txt"');
//		echo "$srtMovto";
		
	}

?>