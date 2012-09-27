<?php
	include "include/funciones.inc.php";

  	$pxVirtual = odbc_connect('cheqPack2012','','');
  	$pxVirtual2 = odbc_connect('TestPdox','','');
  		
	if($_POST["opcion"]=="EXPORTAR_POLIZA"){
		
		$fechaExportar 	= $_POST["fechaExportar"];
		$lNoPoliza 		= $_POST["lNoPoliza"];
		//print "lNoPoliza: $lNoPoliza=======Post:".$_POST["lNoPoliza"];
		//$strConsultaMovtos 		= "SELECT * FROM movtos";
		$strConsultaMovPolCh  	= "SELECT * FROM MovPolCh";
		
		$strConsultaDepositos  	= "SELECT * FROM Depositos";
		
		//$strMaxMovPolCh 		= ;
		$lIDPolizaMovPolCh		= getMaxPx($pxVirtual,"Select Max(IDPolChqpW) from MovPolCh")+20; //poliza
		//$lIDMovPolCh
		
		$lIDPoliza				= getMaxPx($pxVirtual,"Select Max(IdPolChqpw) from PolChqpW")+20; 
		$lIDMovto				= getMaxPx($pxVirtual,"Select Max(movto) from movtos")+20;
		$lDepositos				= getMaxPx($pxVirtual,"Select Max(CIDDeposito) from depositos")+20;
		
		$lIDPolizaIni 			= $lIDPoliza;
		$lIDMovtoIni 			= $lIDMovto;
		$lNoPolizaIni			= $lNoPoliza;
		
		$lIdDepositosIni		= $lDepositos;
		$lIDPolizaMovPolChIni	= $lIDPolizaMovPolCh;
		//$lIdPolChqpWIni		= $lIdPolChqpW;
		
		//COPIA POLIZAS TABLA POLCHQPW
		$strConsultaPolChqpW  	= "SELECT * FROM PolChqpW";		
		$result = odbc_do($pxVirtual2,$strConsultaPolChqpW);
		//print_r($result);
		while (odbc_fetch_row($result))
		{
			$IdPolChqpwP	=	odbc_result($result,1);
			$FechaP			=	odbc_result($result,2);
			$TipoP			=	odbc_result($result,3);
			$Numero			=	odbc_result($result,4);
			$ConceptoP		=	odbc_result($result,5);
			
			$strsqlPolizaPagoTrue = "INSERT INTO PolChqpW (IdPolChqpw, Fecha, Tipo, Numero, Concepto)";
			$strsqlPolizaPagoTrue.= "VALUES($lIDPolizaMovPolCh, '$FechaP', $TipoP, $lNoPoliza, '$ConceptoP')";
			
			$polizapago = odbc_prepare($pxVirtual,$strsqlPolizaPagoTrue); if (odbc_execute($polizapago)) {}//echo "okPolizaPago\n";}
			else {echo "Eres Un Error-PolizaPago\n"; } 
			//ECHO "$strsqlPolizaPagoTrue========$lIDPoliza";
			$granqueryPolizaPagoTrue.= "$strsqlPolizaPagoTrue \n";
			
			$lIDPolizaMovPolCh++;
			$lNoPoliza++;
		}
		
		//COPIA DET POLIZAS
		$granqueryPolizaPagoTrue.= "Det Polizas: \n";
		$resultMovPolCh = odbc_do($pxVirtual2,$strConsultaMovPolCh);
		while (odbc_fetch_row($resultMovPolCh))
		{
			$IdPolChqpwP2	=	odbc_result($resultMovPolCh,1);
			$NumeroMovto2	=	odbc_result($resultMovPolCh,2);
			$CuentaContpaq2	=	odbc_result($resultMovPolCh,3);
			$Referencia2	=	odbc_result($resultMovPolCh,4);
			$Concepto2		=	odbc_result($resultMovPolCh,5);
			$TipoMovto2		=	odbc_result($resultMovPolCh,6);
			$CargoMN2		=	odbc_result($resultMovPolCh,7);
			$AbonoMN2		=	odbc_result($resultMovPolCh,8);
			$CargoME2		=	odbc_result($resultMovPolCh,9);
			$AbonoME2		=	odbc_result($resultMovPolCh,10);
			$IDDeposito		=	odbc_result($resultMovPolCh,11);
			$Diario			=	odbc_result($resultMovPolCh,12);
			$cDescripcionCtai=	odbc_result($resultMovPolCh,14);
			
			$tdetpolchqw 	 = $IdPolChqpwP2-1+$lIDPolizaMovPolChIni;
			$suma = $Referencia2-1+$lNoPolizaIni;
			$ReferenciaNueva = "I $suma";
			
			//**************
			//	NOTA
			//**************
			//Para usar la modificacion 02 utilizo el campo Diario, que es donde guardo la cuenta contpaq desde que genero los arreglos en
			//ws_polizas_ingresos
			
			//EMPIEZA DETALLE POLIZA
			//Se pone la cuenta de dollares la 02
			/*if ($row["moneda"]=="DLS") {
				$row["cuenta_contpaq"] = substr($row["cuenta_contpaq"],0,3).'2'.substr($row["cuenta_contpaq"],4,8);
			}*/
			
			//$strsqlMovPolCh ="INSERT INTO MovPolCh (IdPolChqpW, NumeroMovto, CuentacontPaqW, Referencia, Concepto, TipoMovto, CargoMN, AbonoMn, CargoME, AbonoME, IDDeposito, Diario) ";
			//$strsqlMovPolCh.="VALUES ($tdetpolchqw, $NumeroMovto2, '$Diario', '$ReferenciaNueva', '$Concepto2', '$TipoMovto2', $CargoMN2, $AbonoMN2, $CargoME2, $AbonoME2, $IDDeposito, $Diario)";
			$strsqlMovPolCh ="INSERT INTO MovPolCh (IdPolChqpW, NumeroMovto, CuentacontPaqW, Referencia, Concepto, TipoMovto, CargoMN, AbonoMn, cDescripcionCtai) ";
			$strsqlMovPolCh.="VALUES ($tdetpolchqw, $NumeroMovto2, '$Diario', '$ReferenciaNueva', '$Concepto2', '$TipoMovto2', $CargoMN2, $AbonoMN2, '$cDescripcionCtai')";
			$granqueryPolizaPagoTrue.="eNTRE¿¿$strsqlMovPolCh\n";
			$ResPolizaPago = odbc_prepare($pxVirtual,$strsqlMovPolCh); if (odbc_execute($ResPolizaPago)) {}//echo "okMovPolCh\n";}
			else {echo "Eres Un Error-MovPolCh\n"; } 
		}
		
		
		
		
		//***************************************************
		//COPIA MOVTOS
		//$granqueryPolizaPagoTrue ="";
		$strConsultaMovtos 		= "SELECT * FROM movtos";
		$granqueryPolizaPagoTrue.= "Movtos: \n";
		
		$resultMovtos = odbc_do($pxVirtual2,$strConsultaMovtos);
		print "\n".odbc_num_rows($resultMovtos)."----$strConsultaMovtos\n";
		$MINUMERO=1;
				
		while (odbc_fetch_row($resultMovtos))
		{
			print $MINUMERO;
			$Movto					=	odbc_result($resultMovtos,1);
			$Cuenta					=	odbc_result($resultMovtos,2);
			$FechaMovto				=	odbc_result($resultMovtos,3);
			$CNaturaleza			=	odbc_result($resultMovtos,4);
			$TipoMovto				=	odbc_result($resultMovtos,5);
			$NumeroCheque			=	odbc_result($resultMovtos,6);
			$FechaAplica			=	odbc_result($resultMovtos,7);
			$Referencia				=	odbc_result($resultMovtos,8);
			$ConceptoGral			=	odbc_result($resultMovtos,9);
			$ImporteMonedaCuenta	=	odbc_result($resultMovtos,10);
			$ImporteTercerMoneda	=	odbc_result($resultMovtos,11);
			$TercerMoneda			=	odbc_result($resultMovtos,12);
			$TipoCambioTercerMoneda =	odbc_result($resultMovtos,13);
			$EstadoMovtoProy		=	odbc_result($resultMovtos,14);
			$EstadoMovtoCons		=	odbc_result($resultMovtos,15);
			$EstadoMovtoAsoc		=	odbc_result($resultMovtos,16);
			$EstadoMovtoContab		=	odbc_result($resultMovtos,17);
			$EstadoMovto			=	odbc_result($resultMovtos,18);
			$Prioridad				=	odbc_result($resultMovtos,19);
			$Deposito				=	odbc_result($resultMovtos,20);
			$BeneficiarioPagador	=	odbc_result($resultMovtos,21);
			$Leyenda				=	odbc_result($resultMovtos,22);
			$MovimientoRepetitivo	=	odbc_result($resultMovtos,23);
			$Traspaso				=	odbc_result($resultMovtos,24);
			$Categoria				=	odbc_result($resultMovtos,25);
			$Aviso					=	odbc_result($resultMovtos,26);
			$TS						=	odbc_result($resultMovtos,27);
			$FechaRecordatorio		=	odbc_result($resultMovtos,28);
			$Nota					=	odbc_result($resultMovtos,29);
			$ChequeCorto			=	odbc_result($resultMovtos,30);
			$DocumentoMegapaq		=	odbc_result($resultMovtos,31);
			$Poliza					=	odbc_result($resultMovtos,32);
			$TSUltimaActualizacion	=	odbc_result($resultMovtos,33);
			$UserAlta				=	odbc_result($resultMovtos,34);
			$UserActualizacion		=	odbc_result($resultMovtos,35);
			$CuentaNombre			=	odbc_result($resultMovtos,31);
			
			$Movto = $lIDMovto;
			if (($poliza != null) or ($poliza != "") )  {
				$poliza = $poliza-1+$lIDPolizaMovPolChIni;
			}
			if ($Deposito != 0) {
				$Deposito = $Deposito-1+$lDepositos;
			}
			
			$strsqlCuentaBanco = "select ID from CUENTAS where id_cuenta = '".$banco["cuenta_cheqpaq"]."'";
			$resultCuentaBanco = odbc_do($pxVirtual,$strsqlCuentaBanco); 
			while (odbc_fetch_row($resultCuentaBanco)){ $Cuenta = odbc_result($resultCuentaBanco,1); }
			//print "\n".$strsqlCuentaBanco."\n $Cuenta\n";
			
			if ($BeneficiarioPagador > 0) {
					$resultGeneral = odbc_do($pxVirtual,"Select id_beneficiario from benefpag where cuentacontpaqW='$BeneficiarioPagador'"); 
					$granqueryPolizaPagoTrue.= "Primero--->Select id_beneficiario from benefpag where cuentacontpaqW='$BeneficiarioPagador';\n";
					while (odbc_fetch_row($resultGeneral)){ $id_beneficiario = odbc_result($resultGeneral,1); }
					
					if (($id_beneficiario == null) or ($id_beneficiario == "")){
					//$granqueryPolizaPagoTrue.="CeroCEROCERO: entre\n";
						$snombre = $CuentaNombre;
						//print "Nombre:$snombre ======Nombre2: $CuentaNombre\n";
						$result2 = odbc_do($pxVirtual,"Select id_beneficiario from benefpag where nombre like '$snombre%'");
						$granqueryPolizaPagoTrue.= "Segundo---->Select id_beneficiario from benefpag where nombre like '$snombre%' \n";
						while (odbc_fetch_row($result2)){  $id_beneficiario2 = odbc_result($result2,1); }
						//$granqueryPolizaPagoTrue.= "id_beneficiario2 = $id_beneficiario2\n";
						if (($id_beneficiario2 == null) or ($id_beneficiario2 == "")) {
							//$granqueryPolizaPagoTrue.="Cero: entre--->$id_beneficiario2\n";
						$resultMaxBenefPaq = odbc_do($pxVirtual,"select max(id_beneficiario) from benefpag"); while (odbc_fetch_row($resultMovtos)) { $lBenef = odbc_result($resultMaxBenefPaq,1)+10; }
						$strsqlInsert = "insert into benefpag(id_beneficiario,nombre,tipobeneficiariopagador,registrado,cuentacontpaqw,incluirleyenda) ";
						$strsqlInsert.= "values ('$lBenef','$snombre','P',TRUE,'$BeneficiarioPagador',0)";
						
						$granqueryPolizaPagoTrue.= "Tercero--Entre INSERT--->$strsqlInsert\n";
						
						$ResBenefPag = odbc_prepare($pxVirtual,$strsqlInsert); 
						if (odbc_execute($ResBenefPag)) { }//echo "okInsertBenefPag\n";} 
						else {echo "Eres Un Error-InsertBenefPag\n"; } 
					
						$BeneficiarioPagador = $lBenef;
						}
						else{
							//$granqueryPolizaPagoTrue.="CeroCERO: entre\n";
							$lBenef = odbc_result($result2,1);
							$strsqlUpdate = "update benefpag set cuentacontpaqW ='$BeneficiarioPagador' where id_beneficiario=$id_beneficiario2" ;
							$ResBenefPag = odbc_prepare($pxVirtual,$strsqlUpdate); if (odbc_execute($ResBenefPag)) {}//echo "okUpdateBenefPag\n";}else {echo "Eres Un Error-UpdateBenefPag\n"; } 
							
							$granqueryPolizaPagoTrue.= "Cuarto--ENTE UPDATE--->$strsqlUpdate\n";
						
							$BeneficiarioPagador = $lBenef;
						}
					}else{
					$BeneficiarioPagador = $id_beneficiario;
					}
				}
			$strsqlMovtos = "INSERT INTO movtos (Movto, Cuenta, FechaMovto, Referencia, TipoMovto, FechaAplica, ImporteMonedaCuenta, Deposito, BeneficiarioPagador, TS, FechaRecordatorio, TSUltimaActualizacion, UserAlta, UserActualizacion) ";
			$strsqlMovtos.="VALUES ($Movto, $Cuenta, '$FechaMovto', '$Referencia', $TipoMovto, '$FechaAplica', $ImporteMonedaCuenta, $Deposito, $BeneficiarioPagador, '$TS', '$FechaRecordatorio', '$TSUltimaActualizacion', '$UserAlta', '$UserActualizacion')";	
			
			$granqueryPolizaPagoTrue.= "Quinto----->$strsqlMovtos\n";
			$granqueryPolizaPagoTrue.="$strsqlMovtos\n";
			$ResMovtos = odbc_prepare($pxVirtual,$strsqlMovtos); if (odbc_execute($ResMovtos)) {}//echo "okMovtos\n";}else {echo "Eres Un Error-Movto\n"; } 
			
			$lIDMovto++;
			
			$MINUMERO++;
		}

		//***************************************************
		
		//Depositos
		$granqueryPolizaPagoTrue.= "Depositos: \n";
		$resultDepositos = odbc_do($pxVirtual2,$strConsultaDepositos);
		while (odbc_fetch_row($resultDepositos))
		{
			$CIdDeposito 			=	odbc_result($resultDepositos,1);
			$CIDCuenta				=	odbc_result($resultDepositos,2);
			$CImporte				=	odbc_result($resultDepositos,3);
			$CConceptoGral			=	odbc_result($resultDepositos,4);
			$CReferencia			=	odbc_result($resultDepositos,5);
			$CTipoIngreso			=	odbc_result($resultDepositos,6);
			$CFechaDeposito			=	odbc_result($resultDepositos,7);
			$CFechaAplicacion		=	odbc_result($resultDepositos,8);
			$CEstadoConciliado		=	odbc_result($resultDepositos,9);
			$CEstadoContable		=	odbc_result($resultDepositos,10);
			$CIDPoliza				=	odbc_result($resultDepositos,11);
			$CTSAlta				=	odbc_result($resultDepositos,12);
			$CTSUltimaActualizacion	=	odbc_result($resultDepositos,13);
			$CUserAlta				=	odbc_result($resultDepositos,14);
			$CUserActualizacion		=	odbc_result($resultDepositos,15);
			
			$CIDPoliza = $CIDPoliza-1+$lIDPolizaMovPolChIni;
			$CIdDeposito = $CIdDeposito-1+$lDepositos;
			
			$strsqlDeposito = "INSERT INTO Depositos (CIdDeposito, CIDCuenta, CImporte, CConceptoGral, CTipoIngreso, CFechaDeposito, CFechaAplicacion, CEstadoConciliado, CEstadoContable, CIDPoliza, CTSAlta, CTSUltimaActualizacion, CUserAlta, CUserActualizacion)";
			$strsqlDeposito.= "VALUES ($CIdDeposito , $CIDCuenta ,$CImporte, '$CConceptoGral',$CTipoIngreso, '$CFechaDeposito', '$CFechaAplicacion', $CEstadoConciliado, $CEstadoContable, $CIDPoliza, '$CTSAlta', '$CTSUltimaActualizacion', 'SUPERVISOR', 'SUPERVISOR')";
			
			$granqueryPolizaPagoTrue.="$strsqlDeposito\n";
			
			$ResDeposito = odbc_prepare($pxVirtual,$strsqlDeposito); if (odbc_execute($ResDeposito)) {}//echo "okDeposito\n";}else {echo "Eres Un Error-Deposito\n"; } 
			
		}
		/*
		//CAUSAIVA
		$granqueryPolizaPagoTrue.= "CausaIVA: \n";
		$strsqlCausaIVA = "SELECT * FROM causaiva";
		$resultCausaIVA = odbc_do($pxVirtual2,$strConsultaDepositos);
		while (odbc_fetch_row($resultDepositos))
		{
			$IdPolChqpWCI	=	odbc_result($resultCausaIVA,1); 
            $TipoMovtoCI	=	odbc_result($resultCausaIVA,2); 
            $rTottasa15CI	=	odbc_result($resultCausaIVA,3); 
            $rBasetasa15CI	=	odbc_result($resultCausaIVA,4); 
            $rIvaTasa15CI	=	odbc_result($resultCausaIVA,5); 
            $rTottasa10CI	=	odbc_result($resultCausaIVA,6); 
            $rBasetasa10CI	=	odbc_result($resultCausaIVA,7); 
            $rIvatasa10CI	=	odbc_result($resultCausaIVA,8); 
            $Tottasa0CI		=	odbc_result($resultCausaIVA,9); 
            $Basetasa0CI	=	odbc_result($resultCausaIVA,10); 
            $TotExentoCI	=	odbc_result($resultCausaIVA,11); 
            $BaseExentoCI	=	odbc_result($resultCausaIVA,12); 
            $TotratasaCI	=	odbc_result($resultCausaIVA,13); 
            $BaseOtraCI		=	odbc_result($resultCausaIVA,14); 
            $IvaOtraCI		=	odbc_result($resultCausaIVA,15); 
            $ImpSunTuaCI	=	odbc_result($resultCausaIVA,16); 
            $TotOtrosCI		=	odbc_result($resultCausaIVA,17); 
            $CaptadoCI		=	odbc_result($resultCausaIVA,18); 
            $NoCausarCI		=	odbc_result($resultCausaIVA,19); 
            $CIvaRetCI		=	odbc_result($resultCausaIVA,20); 
            
            $IdPolChqpWCI = $IdPolChqpWCI-1+$lIDPolizaMovPolChIni;
            
            $strsqlCI = "INSERT INTO causaiva (IdPolChqpW, TipoMovto, Tottasa15, Basetasa15, IvaTasa15, Tottasa10, Basetasa10, Ivatasa10, Tottasa0, Basetasa0, TotExento, BaseExento, Totratasa, BaseOtra, IvaOtra, ImpSunTua, TotOtros, Captado, NoCausar, CIvaRet)";
            $strsqlCI.= "VALUES ($IdPolChqpWCI, $TipoMovtoCI, $rTottasa16CI, $rBasetasa16CI, $rIvaTasa16CI, $rTottasa11CI, $rBasetasa11CI, $rIvatasa11CI, $Tottasa0CI, $Basetasa0CI, $TotExentoCI ,$BaseExentoCI, $TotratasaCI, $BaseOtraCI, $IvaOtraCI, $ImpSunTuaCI, $TotOtrosCI, $CaptadoCI, $NoCausarCI, $CIvaRetCI)";
            
            $granqueryPolizaPagoTrue.="$strsqlCI\n";
            
            $ResCausaIVA = odbc_prepare($pxVirtual,$strsqlCI); if (odbc_execute($ResCausaIVA)) {}else {echo "Eres Un Error-CausaIVA\n"; } 
            
		}
		*/
		//Saldos en Cuentas
	/*	$strsqlCausaIVA = "SELECT * FROM SaldoCta";
		$resultCausaIVA = odbc_do($pxVirtual2,$strConsultaDepositos);
		while (odbc_fetch_row($resultDepositos))
		{
			tCuentasSaldoLibros.AsFloat := Rnd(dSubtotalBancos,2);
            tCuentasSaldoProyectado.AsFloat := Rnd(dSubtotalBancos,2);
            tCuentasSaldoContable.AsFloat := Rnd(dSubtotalBancos,2);
		}
		*/
		echo "ok";
		odbc_close($pxVirtual2);	odbc_close($pxVirtual);
		hacerArchivo($granqueryPolizaPagoTrue);
	}
	
	
	function ejecutarQuery(&$conexionbd, $strsql){
		$result = odbc_prepare($conexionbd,$strsql);
		if (odbc_execute($result)) { 
		}
		else {
		    echo "Eres Un Error"; 
		}
		
		odbc_close($conexionbd);
	}
	
	function ejecutarQueryConAlerta(&$conexionbd, $strsql){
		$result = odbc_prepare($conexionbd,$strsql);
		if (odbc_execute($result)) { 
		    print "OK";
		}
		else {
		    echo "Eres Un Error"; 
		}
		
		odbc_close($conexionbd);
	}
	
	function getMaxPx(&$conexionbd, $strsql){
		$resultado = "";
		$result = odbc_do($conexionbd,$strsql);
		while (odbc_fetch_row($result)){
			$resultado=odbc_result($result,1);
		}
		//odbc_close($conexionbd);
		return $resultado;
	}
?>