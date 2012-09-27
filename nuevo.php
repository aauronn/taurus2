<?php
	include "include/entorno.inc.php";
	include "include/funciones.inc.php";
	
	//********************************************
	//				CONEXION POR ODBC
	//********************************************
	$pxVirtual = odbc_connect('Driver={Microsoft Paradox Driver (*.db )};DriverID=538;Fil=Paradox 5.X;DefaultDir=c:\paradox\tmpCheqpaq2;Dbq=c:\paradox\tmpCheqpaq2;CollatingSequence=ASCII;','','');
	$pxVirtual2 = odbc_connect('TestPdox','','');
	$pxVirtual3 = odbc_connect('cheqPack2012','','');
	$db->SetFetchMode(ADODB_FETCH_ASSOC); 
	
	//********************************************
	//				HTTP SERVICES
	//********************************************
	//$pErrores=0;	 $pCreadas=0; 	$pDiario=0; 	$pIngresos=0;
	$GLOBALS["gIVAf"]= 0.11;
	$GLOBALS["gIVA"] = 0.16;
	
	if($_POST["opcion"]=="TRAER_DATOS"){
		
		$fecha = textToDB($_POST['fecha']);
		
		
		list($year, $month, $day)=split('[/.-]',$fecha);
		
		$strsqlPagos = "select a.num_docto, a.clave_cliente, a.importe, a.moneda, b.nombre, a.cuenta from enc_pagos a 
						    inner join clientes b on a.clave_cliente=b.clave_cliente 
						    inner join bancos c on a.cuenta=c.clave_banco
						where a.fecha = convert(datetime,'$fecha',121) and a.status='A' 
						order by a.num_docto";
		print getXML($strsqlPagos, $db, 1, 100, true,0);
	}
	
//	if($_POST["opcion"]=="ENVIAR_DATOS"){
		
	//	ejecutarQuery($pxVirtual2,"DELETE FROM movtos where 1=1"); ejecutarQuery($pxVirtual2,"DELETE FROM MovPolCh where 1=1");ejecutarQuery($pxVirtual2,"DELETE FROM PolChqpW where 1=1");ejecutarQuery($pxVirtual2,"DELETE FROM Depositos where 1=1");
		
		$fecha = textToDB($_POST['fecha']);
		//$fechaDeposito = textToDB($_POST['fechaDeposito']);
		$fechaDeposito = "2011-11-15";
		
		
		$strSerializado = $_POST["datos"];
		
		
		list($year, $month, $day)=split('[/.-]',$fechaDeposito);
		list($year2, $month2, $day2)=split('[/.-]',$fechaDeposito);
		
		//$fechaDepositoF = $day2."/".($month2-1)."/".$year2;
		//$fechaDepositoF = $year2."/".($month2-1)."/".$day2;
		//$fechaDepositoFpx = $day2.".".($month2-1).".".$year2;
		
		$fechaDepositoF = $year."-".($month-1)."-".$day;
		$fechaDepositoFpx = $day."/".($month-1)."/".$year;
		
		//crearPolizaIngresos($strSerializado,$fechaDepositoF,$fechaDepositoFpx, $db);
		//function crearPolizaIngresos($pagosSelParaFiltrarBancosSerializado, $fechaDeposito, $fechaDepositoPx, &$db){}
		
		
		$sTipoMovto; 		$sCancelada; 	$sNotas;
		$dSubtotal; 		$dIVA; 			$dUtil; 	$dImporte;
		$nLine;
		$dSubtotalBancos; 	$dUtilBancos;
		$dSubtotalBancos_MN;
		$dB10Importe; 		$dAcum;
		$dB10Subtotal;
		$dB10IVA;
		$dB15Importe;
		$dB15Subtotal;
		$dB15Iva;
		
		$iNumMovto;
		$nDetNumMovto;
		$lCuenta;
	    $sCuenta;
	    $sContpaqw;
		$nDeposito;
		$nPoliza;
		$nIdPoliza;
		$iCont; $icont2;
		$dSaldo;
		$fFile;
		$sPath;
		$s1; $s2;
	    $sREcord;
	    
	    /**		Inicia Deserializado de Datos 	**/
	    $noSerializado = array();
	    $strSerializado = $_POST["datos"];
	    if($strSerializado!="")	{
			if(get_magic_quotes_gpc()){
			    $strSerializado = stripslashes($strSerializado);
			}
			$noSerializado = object_to_array(unserialize(urldecode($strSerializado)));
		}
		foreach ($noSerializado as $miArreglo)
		{
			$pagosSelParaFiltrarBancos.="'".$miArreglo["num_docto"]."', ";
		}
		$pagosSelParaFiltrarBancos = "'00051740', '00051741', '00051742', '00051744'";
		//$pagosSelParaFiltrarBancos = substr($pagosSelParaFiltrarBancos, 0, -2);
		/**		Termina Deserializado de Datos 	**/
	    
	    //FILTRAR BANCOS
		/*
	    $strsqlFiltrarBancos="select a.cuenta,b.descripcion, b.cuenta cuenta_contpaq,max( b.id_cuenta_bancos) as cuenta_cheqpaq, max(codigo_moneda) as moneda ";
    	$strsqlFiltrarBancos.="from enc_pagos a join bancos b on a.cuenta=b.clave_banco ";
    	$strsqlFiltrarBancos.="where a.num_docto in ($pagosSelParaFiltrarBancos)";
		$strsqlFiltrarBancos.="and a.status='A'";
		$strsqlFiltrarBancos.="group by a.cuenta,b.descripcion, b.cuenta";
		*/		
		$strsqlFiltrarBancos = "select a.cuenta,b.descripcion, b.cuenta cuenta_contpaq,max( b.id_cuenta_bancos) as cuenta_cheqpaq, max(codigo_moneda) as moneda from enc_pagos a join bancos b on a.cuenta=b.clave_banco where a.num_docto in ('00051740', '00051741', '00051742', '00051744')and a.status='A'group by a.cuenta,b.descripcion, b.cuenta ";
		
		$granQueryBanco.=$strsqlFiltrarBancos." \n";
		
		//hacerArchivo($strsqlFiltrarBancos);
		
		$dTipoCambioFecha = getTipoCambioEnPolizasIngresos($fechaDeposito, $db);
		
		$iNumMovto 	= 1;
		$nPolizaI 	= $polizaInicialaExportar;
		$nPoliza	= 1;
		$nIdPoliza	= 1;
		$nDeposito	= 1;
		$lNoerrors	= 1;
		
		//BorrarTemporales --Crear una funcion luego
		
		$rsBancos=$db->Execute($strsqlFiltrarBancos);
		if ($rsBancos) {
			while ($banco  = $rsBancos->fetchRow()) {
				$lCuenta   = 1;
				$sCuenta   = $banco[cuenta_cheqpaq];
				$sContpaqw = $banco[cuenta_contpaq];
				
				//QGROUP3
				/*
				$strsqlGroup3="select a.num_docto, a.clave_cliente, a.importe, a.moneda, c.cuenta, a.observaciones, b.cuenta_contpaq, b.nombre ";
				$strsqlGroup3.="from enc_pagos a inner join clientes b on a.clave_cliente=b.clave_cliente ";
				$strsqlGroup3.="inner join bancos c on a.cuenta=c.clave_banco ";
				$strsqlGroup3.="where a.num_docto in ('00051740', '00051741', '00051742', '00051744') ";
				$strsqlGroup3.="and a.cuenta='".$banco[cuenta]."'";
				$strsqlGroup3.="and a.status='A' ";
				$strsqlGroup3.="order by a.num_docto ";
				*/
				//$strsqlGroup3 = ;
				print "$strsqlGroup3";
				//hacerArchivo($strsqlGroup3);
				$granQueryGrupo3.=$strsqlGroup3." \n";
				$rsGroup3=$db->Execute("select a.num_docto, a.clave_cliente, a.importe, a.moneda, c.cuenta, a.observaciones, b.cuenta_contpaq, b.nombre from enc_pagos a inner join clientes b on a.clave_cliente=b.clave_cliente inner join bancos c on a.cuenta=c.clave_banco where a.num_docto in ('00051740', '00051741', '00051742', '00051744') and a.cuenta='".$banco[cuenta]."' and a.status='A' order by a.num_docto  ");
				if ($rsGroup3) {
					while ($row  = $rsGroup3->fetchRow()) {
						//codigo de informacion de creando poliza X 
						//con tipo de cambio X
						if ($row[cuenta_contpaq] == "") {
							$GLOBALS[errores] = "Cliente sin cuenta asignada ".$row[clave_cliente];
							$lNoerrors++;
						}
						$dSubtotalBancos 	= 0;
						$dSubtotalBancos_MN = 0;
						$dUtilBancos		= 0;
						$nDetNumMovto		= 1;
						$dUtil				= 0;
						$dB10Importe		= 0;
						$dB10Subtotal		= 0;
			            $dB10IVA 			= 0;
			            $dB15Importe 		= 0;
			            $dB15Subtotal 		= 0;
			            $dB15IVA  			= 0;
			            $dAcum 				= 0;
			            
			            //qPagos
			            $strsqlPagos="select a.num_docto, a.importe,  a.pago_real_mn, a.pago_real_ex, ";
    					$strsqlPagos.="a.moneda, a.moneda_monto,b.tipo_cambio, b.saldo, b.sub_total, ";
    					$strsqlPagos.="b.clave_bodega ";
						$strsqlPagos.="from pagos_cxc a ";
						$strsqlPagos.="join enc_facturas b on a.num_docto=b.num_docto ";
    					$strsqlPagos.="and a.clave_bodega=b.clave_bodega ";
						$strsqlPagos.="where a.num_docto_pago='".$row[num_docto]."'";
						
						$dSubtotal = 0;
						$granQerypago.=$strsqlPagos."\n";
						$rsPagos=$db->Execute($strsqlPagos);
						if ($rsPagos) {
							print FieldCount($rsPagos);
							/*
							while ($pago  = $rsPagos->fetchRow()) {
								
								
								
								list($qpagosBaseIVA, $qPagosImporteFac, $qPagosImporteFac_mn, $qPagosImporte_Real, $qPagosUtil, $qPagosTC_pago)=qPagosCalcFields($pago["sub_total"],$pago["clave_bodega"],$pago["moneda"],$pago["importe"],$pago["tipo_cambio"],$dTipoCambioFecha,$pago["pago_real_ex"]);
								
								$strsqlMovtos = "INSERT INTO movtos ";
								$strsqlMovtos.="(Movto, Cuenta, ";
								$strsqlMovtos.="FechaMovto, Referencia, TipoMovto, ";
								$strsqlMovtos.="FechaAplica, ImporteMonedaCuenta, ";
								$strsqlMovtos.="Deposito, BeneficiarioPagador, ";
								$strsqlMovtos.="TS, FechaRecordatorio, ";
								$strsqlMovtos.="TSUltimaActualizacion, UserAlta, ";
								$strsqlMovtos.="UserActualizacion, DocumentoMegapaq) ";
								
								$strsqlMovtos.="VALUES ";
								$strsqlMovtos.="($iNumMovto, $lCuenta, ";
								$strsqlMovtos.="'$fechaDepositoFpx', 'NP ".$row[num_docto]."', 2, ";
								$strsqlMovtos.="'$fechaDepositoFpx', ".ImporteEnMoneda($banco["moneda"],$pago["importe"],$pago["moneda"],$dTipoCambioFecha).", ";
								$strsqlMovtos.="$nDeposito, '".$row["cuenta_contpaq"]."', ";
								$strsqlMovtos.="'$fechaDepositoFpx', '30/12/1899', ";
								$strsqlMovtos.="'$fechaDepositoFpx', 'SUPERVISOR', ";
								$strsqlMovtos.="'SUPERVISOR', '".$row["nombre"]."')";
								
								$granQuery.=$strsqlMovtos."\n";
								
								//Ejecuto el Query $strsqlMovtos
								//$result = odbc_prepare($pxVirtual2,$strsqlMovtos); if (odbc_execute($result)) {} else {echo "Eres Un Error-Movtos\n"; } //odbc_close($pxVirtual2);
								
								$dImporte = $pago["pago_real_mn"];
								
								//EMPIEZA DETALLE POLIZA

								
								$strsqlMovPolCh ="INSERT INTO MovPolCh (IdPolChqpW, NumeroMovto, CuentacontPaqW, Referencia, Concepto, TipoMovto, AbonoMn, CargoMn) ";
								$strsqlMovPolCh.="VALUES ($nIdPoliza, $nDetNumMovto, '".$row["cuenta_contpaq"]."', '$nPoliza',   'F ".$pago["num_docto"]."', 'T', ".round($dImporte,2).",0)";
								
								$granQueryMovPolCh.="$strsqlMovPolCh \n";
								
								//Ejecuto el Query $strsqlMovPolCh
								//$result2 = odbc_prepare($pxVirtual2,$strsqlMovPolCh); if (odbc_execute($result2)) {}else {echo "Eres Un Error-MovPolCh\n"; } //odbc_close($pxVirtual2);
								
								if ($qpagosBaseIVA==$GLOBALS["gIVAf"]) {
									$dB10Importe 	= $dB10Importe+round($dImporte,2);
									$dB10Subtotal 	= $dB10Subtotal+round($dImporte/(1+$GLOBALS["gIVAf"]),2);
									$dB10IVA		= $dB10IVA+round(($dImporte/(1+$GLOBALS["gIVAf"]))*($GLOBALS["gIVAf"]),2);
								}else {
									$dB15Importe 	= $dB15Importe+round($dImporte,2);
									$dB15Subtotal 	= $dB15Subtotal+round($dImporte/(1+$GLOBALS["gIVA"]),2);
									$dB15IVA		= $dB15IVA+round(($dImporte/(1+$GLOBALS["gIVA"]))*($GLOBALS["gIVA"]),2);
								}
								
								$nDetNumMovto++;
								$iNumMovto++;
								
								$dAcum		= $dAcum		+ round($dImporte,2);
								$dSubtotal 	= $dSubtotal 	+ round($dImporte,2);
								$dUtil 		= $dUtil		+ $qPagosUtil;
							}
							*/
						}
						
						$dSubtotalBancos = $dSubtotalBancos + ImporteEnMoneda($banco["moneda"],$row["importe"],$row["moneda"]);
						$dSubtotalBancos_MN = $dSubtotalBancos_MN+$row["importe"];
						
						
						
						
						
						
					//****************************************
					// Agrega Util/Perdida Cambiaria
					//****************************************
					$dUtil = round($dSubtotalBancos_MN,2)-$dAcum;
					//Utilidad
					$strsqlUtilidadPerdida = "";
					if ($dUtil>0) {
						$strsqlUtilidadPerdida ="INSERT INTO MovPolCh (IdPolChqpW, NumeroMovto, CuentacontPaqW, Referencia, Concepto, TipoMovto,CargoMn, AbonoMn) ";
						$strsqlUtilidadPerdida.=" VALUES ($nIdPoliza, $nDetNumMovto, '710202000', '$nPoliza', 'Utilidad', 'T', 0,". round($dUtil,2).")";
					}
					//Perdida
					else if ($dUtil<0) {
						$strsqlUtilidadPerdida = "INSERT INTO MovPolCh (IdPolChqpW, NumeroMovto, CuentacontPaqW, Referencia, Concepto, TipoMovto,CargoMn, AbonoMn) ";
						$strsqlUtilidadPerdida.= "VALUES($nIdPoliza, $nDetNumMovto, '850502000', '$nPoliza', 'Perdida', 'F',". round($dUtil,2).",0)";
					}
					$granQueryUtilidad.= $strsqlUtilidadPerdida."\n";
					if ($strsqlUtilidadPerdida!="") {
					//	$result3 = odbc_prepare($pxVirtual2,$strsqlUtilidadPerdida); if (odbc_execute($result3)) {}else {echo "Eres Un Error-UtilidadPerdida\n"; } //odbc_close($pxVirtual2);
					}
					
					
					
					//Movimiento Principal
					$strsqlMovPrincipal = "INSERT INTO MovPolCh (IdPolChqpW, NumeroMovto, CuentacontPaqW, Referencia, Concepto, TipoMovto,CargoMn, AbonoMn) ";
					$strsqlMovPrincipal.= "VALUES ($nIdPoliza, 0, '$sContpaqw', '$nPoliza','Depositos al :$fechaDeposito', 'F', 0, ".round($dSubtotalBancos_MN,2).")";
					
					$granQueryMovPrincipal.= $strsqlMovPrincipal."\n";
					//$result4 = odbc_prepare($pxVirtual2,$strsqlMovPrincipal); if (odbc_execute($result4)) {}else {echo "Eres Un Error-MovPrincipal\n"; } //odbc_close($pxVirtual2);
					
					//Agrega Poliza del Pago
					$strsqlPolizaPago = "INSERT INTO PolChqpW (IdPolChqpw, Fecha, Tipo, Numero, Concepto)";
					$strsqlPolizaPago.= "VALUES($nIdPoliza, '$fechaDeposito', 1, $nPoliza, 'Depositos al $fechaDeposito')";
					
					$granQueryPolizaPago.= $strsqlPolizaPago."\n";
				//	$result5 = odbc_prepare($pxVirtual2,$strsqlPolizaPago); if (odbc_execute($result5)) {}else {echo "Eres Un Error-PolizaPago\n"; } //odbc_close($pxVirtual2);
					
					//Agrega Deposito
					$strsqlDeposito = "INSERT INTO Depositos (CIdDeposito, cIDCuenta, CImporte, CConceptoGral, CTipoIngreso, CFechaDeposito, CFechaAplicacion, CEstadoConciliado, CEstadoContable, CIDPoliza, CTSAlta, CTSUltimaActualizacion, CUserAlta, CUserActualizacion)";
					$strsqlDeposito.= "VALUES ($nDeposito , $lCuenta ,". round($dSubtotalBancos,2).", 'Depositos al $fechaDeposito',-1, '$fechaDeposito', '$fechaDeposito', 0, 1, $nIdPoliza, now(), now(), 'SUPERVISOR', 'SUPERVISOR')";
					
					$granQueryDeposito.= $strsqlDeposito."\n";
					//$result6 = odbc_prepare($pxVirtual2,$strsqlDeposito); if (odbc_execute($result6)) {}else {echo "Eres Un Error-Deposito\n"; } 
					
					
						//$iNumMovto++;
						$nIdPoliza++;
						$nPoliza++;
						$nDeposito = $iNumMovto;
					}
					
					
					//$nPoliza++;
					//$nIdPoliza++;
				}else {
					
				}
				//$nPoliza++;
				//$nIdPoliza++;
				$nDeposito = $iNumMovto;
				
			}
		}
		//odbc_close($pxVirtual2);
		hacerArchivo("Nuevo-->\nBancos: \n".$granQueryBanco."\n"."Group3: \n".$granQueryGrupo3."\n". "Pagos: \n".$granQerypago."\n". "Movtos: \n".$granQuery ."\n". "MovPolCh: \n".$granQueryMovPolCh."\n". "Utilidad: \n".$granQueryUtilidad ."\n"."MovPrincipal: \n".$granQueryMovPrincipal ."\n"."PolizaPago: \n".$granQueryPolizaPago ."\n"."Deposito: \n".$granQueryDeposito);
//	}
	
	if($_POST["opcion"]=="EXPORTAR_POLIZA"){
		
		$fechaExportar 	= $_POST["fechaExportar"];
		$lNoPoliza 		= $_POST["lNoPoliza"];
		
		$strConsultaMovtos 		= "SELECT * FROM movtos";
		$strConsultaMovPolCh  	= "SELECT * FROM MovPolCh";
		
		$strConsultaDepositos  	= "SELECT * FROM Depositos";
		
		$strMaxMovtos 		= "Select Max(movto) from movtos";
		$strMaxMovPolCh 	= "Select Max(IDPolChqpW) from MovPolCh";
		$strMaxPolChqpW 	= "Select Max(IDPolChqpW) from PolChqpW";
		$strMaxDepositos 	= "Select Max(CIDDeposito) from depositos";
		
		$lIDMovto			= getMaxPx($pxVirtual3,$strMaxMovtos);
		$lIDMovPolCh		= getMaxPx($pxVirtual3,$strMaxMovPolCh);
		$lIDPoliza			= getMaxPx($pxVirtual3,$strMaxPolChqpW); //poliza
		$lDepositos			= getMaxPx($pxVirtual3,$strMaxDepositos);
		
		$lIDMovtoIni 		= $lIDMovto;
		$lIDMovPolChIni		= $lIDMovPolCh;
		//$lIdPolChqpWIni		= $lIdPolChqpW;
		$lIdDepositosIni	= $lDepositos;
		$lNoPolizaIni		= $lNoPoliza;
		$lIDPolizaIni 		= $lIDPoliza;
		
		//COPIA POLIZAS TABLA POLCHQPW
		$strConsultaPolChqpW  	= "SELECT * FROM PolChqpW";		
		$result = odbc_do($pxVirtual2,$strConsultaPolChqpW);
		//print_r($result);
	/*	while (odbc_fetch_row($result))
		{
			$IdPolChqpwP	=	odbc_result($result,1);
			$FechaP			=	odbc_result($result,2);
			$TipoP			=	odbc_result($result,3);
			$Numero			=	Podbc_result($result,4);
			$ConceptoP		=	odbc_result($result,5);
			
			print "Este:$IdPolChqpwP\n";
			
			$strsqlPolizaPagoTrue = "INSERT INTO PolChqpW (IdPolChqpw, Fecha, Tipo, Numero, Concepto)";
			$strsqlPolizaPagoTrue.= "VALUES($lIdPoliza, $FechaP, $TipoP, $lNoPoliza, '$ConceptoP')";
			
			//$polizapago = odbc_prepare($pxVirtual3,$strsqlDeposito); 
			//if (odbc_execute($polizapago)) {echo "ok";}else {echo "Eres Un Error-Deposito\n"; } 
			
			$granqueryPolizaPagoTrue.= "$strsqlPolizaPagoTrue \n";
			
			$lIdPoliza++;
			$lNoPoliza++;
		}
		
		*/
		
		odbc_close($pxVirtual2);	odbc_close($pxVirtual3);
		hacerArchivo($granqueryPolizaPagoTrue);
	}
	
	//********************************************
	//				VARIABLES
	//********************************************
	$GLOBALS[errores];
	
	//********************************************
	//				FUNCIONES
	//********************************************
	function CopiarRegistro(){
		
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
	
	function getTipoCambioEnPolizasIngresos($fecha, &$db){
		$db->SetFetchMode(ADODB_FETCH_ASSOC);
		//la fecha debe estar en formato YYYY-MM-DD
		//Se usa la fecha que se captura en el campo de fechaDeposito
		$result="";
		
		$strsql="select first tipo_cambio, fecha ";
		$strsql.="from tipos_cambio ";
		$strsql.="where fecha < convert(datetime,'$fecha',121) ";
		$strsql.="order by fecha desc";
		
		$rs=$db->Execute($strsql);
		if ($rs) {
			if(($row=$rs->fetchRow())){
				$result = $row["tipo_cambio"];
			}
		}
		
		return $result;
	}
	
	function ImporteEnMoneda($qBancosCodigoMoneda, $qGroupImporte, $qGroupMoneda,$tipoCambioFecha){
		$importe_mn=0;
		$importe_dls=0;
		$result = "";
		
		//SACO LOS IMPORTES EN DLS O MN SEGUN LA MONEDA EN EL QUERY
		//QGROUP
		if ($qGroupMoneda=='DLS') {
			$importe_mn = round($qGroupImporte*$tipoCambioFecha,2);
			$importe_dls = $qGroupImporte;
		}else {
			$importe_mn = $qGroupImporte;
			$importe_dls = round($qGroupImporte/$tipoCambioFecha,2);
		}
		/*
		print $qBancosCodigoMoneda."\n";
		print $qGroupImporte."\n";
		print $qGroupMoneda."\n";
		print $tipoCambioFecha."\n";
		print $importe_mn."\n";
		print $importe_dls."\n";
		print "\n";
		*/
		//REGRESO LOS RESULTADOS SEGUN EL CODIGO MONEDA DEL 
		//QUERY BANCOS
		//MXN = 1       DLS = 2
		if ($qBancosCodigoMoneda==1) {
			$result = $importe_mn;
		}else {
			$result = $importe_dls;
		}
		return $result;
	}
	
	function ejecutarQuery(&$conexionbd, $strsql){
		$result = odbc_prepare($conexionbd,$strsql);
		if (odbc_execute($result)) { 
			//echo "OK";
		}
		else {
		    echo "Eres Un Error"; 
		}
		
		odbc_close($conexionbd);
		
	}
	
	function qPagosCalcFields($qPagosSub_total,$qPagosClave_bodega, $qPagosMoneda, $qPagosImporte, $qPagosTipo_cambio, $dTipoCambioFecha2, $qPagosPago_real_ex){
		$dImporte; $dIva; $dSubtotal;
		
		$dSubtotal = round($qPagosSub_total,2);
		if (($qPagosClave_bodega=="60") or ($qPagosClave_bodega=="30")) {
			$qpagosBaseIVA = $GLOBALS["gIVAf"];
			$dIva = round($qPagosSub_total*$GLOBALS["gIVAf"],2);
		}else{
			$qpagosBaseIVA = $GLOBALS["gIVA"];
			$dIva = round($qPagosSub_total*$GLOBALS["gIVA"],2);
		}
		
		$dImporte = round($dSubtotal+$dIva,2);
		$qPagosImporteFac = $dImporte;
		
		
		if ($qPagosMoneda == "MN") {
			$qPagosImporteFac_mn = $dImporte;
			$qPagosImporte_Real = $qPagosImporte;
		}else {
			$qPagosImporteFac_mn = round($dImporte*$qPagosTipo_cambio,2);
			$qPagosImporte_Real = round(($qPagosImporte * $dTipoCambioFecha2),2);
		}
		
		
		if (($qPagosMoneda!="MX") or ($qPagosMoneda_monto!="MN")) {
			$qPagosUtil = round($qPagosPago_real_ex*($dTipoCambioFecha2 - $qPagosTipo_cambio),2);			
		}else {
			$qPagosUtil = 0;
		}
		$qPagosTC_pago = $dTipoCambioFecha2;
		
		//$qpagosBaseIVA, $qPagosImporteFac, $qPagosImporteFac_mn
		//$qPagosImporte_Real, $qPagosUtil, $qPagosTC_pago
		
		$ReArreglo = array($qpagosBaseIVA, $qPagosImporteFac, $qPagosImporteFac_mn, $qPagosImporte_Real, $qPagosUtil, $qPagosTC_pago);
		
		return $ReArreglo;
	}
	
	
	//**********************************
	//			OPERACIONES
	//**********************************
	/*
	$db->SetFetchMode(ADODB_FETCH_ASSOC);
	
	$strsql = "select * from movtos";
	
	//print getXML($strsql,$dbPx);
	//print "entre";
	$recordSet = $dbPx->Execute($strsql); 

    if (!$recordSet) 
        print $dbPx->ErrorMsg(); 
    else 
        while (!$recordSet->EOF) { 
            print $recordSet->fields[0].' '.$recordSet->fields[1].'<BR>'; 
            $recordSet->MoveNext(); 
        }    $recordSet->Close(); # optional 
    $dbPx->Close(); # optional 
	*/
?>