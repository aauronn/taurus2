<?php
	include "include/entorno.inc.php";
	include "include/funciones.inc.php";
	
	//**********************************
	//			OPERACIONES
	//**********************************
	$db->SetFetchMode(ADODB_FETCH_ASSOC); 
		
	$pErrores=0;
	$pCreadas=0;
	$pDiario=0;
	$pIngresos=0;
	
	if($_POST["opcion"]=="CREAR_POLIZA_FACTURAS"){
		$fecha = textToDB($_POST['fecha']);
		$poliza = 0;
		$GLOBALS["poliza"] = textToDB($_POST['poliza']);
		$notas = $_POST['notas'];
		$ventas = $_POST['ventas'];
		$GLOBALS["gIVAf"]= 0.11;
		$GLOBALS["gIVA"] = 0.16;
		
		$strDatos = "";
		
		if ($ventas=="true") 
		{
			$strDatos.=crearPolizaVentas($fecha,$GLOBALS["poliza"],$db);
		}
		if ($notas=="true") 
		{
			$strDatos.=crearPolizasNotas($db,$GLOBALS["poliza"],$fecha);		
		}
		//hacerArchivo($strDatos);
		print encrypt($strDatos,$GLOBALS["RC4_KEY"]);	
	}
	
	if ($_POST["opcion"]=="EXPORTAR_POLIZA_FACTURAS") {
		$strDatos = textToDB($_POST["strDatos"]);
		$fecha = textToDB($_POST['fecha']);
		list($year, $month, $day)=split('[/.-]',$fecha);
		
		//header('ETag: etagforie7download'); //IE7 requires this header
		//header('Content-type: application/octet_stream');
		//header('Content-disposition: attachment; filename="'.$year.$month.$day.'.txt"');
		
		//echo $strDatos;
		
		crearArchivoDiario($strDatos, "DIARIO".$year.$month.$day, "Poliza Diario");
	}
	
	function crearPolizaVentas( $fecha, $poliza, &$db){
		list($year, $month, $day)=split('[/.-]',$fecha);
		$TipoMovto='';
		$Cancelada='';
		$datos="";
		
	//Consulta para Obtener Bodegas	
		$strsqlGroup="SELECT d.clave_bodega, d.descripcion bodega, MIN(num_docto) min, MAX(num_docto) max
			FROM enc_facturas  a 
				JOIN bodegas c on a.clave_bodega = c.clave_bodega
			    JOIN bodegas d on c.clave_bodega_grupo = d.clave_bodega
			WHERE a.fecha = CONVERT(DATETIME,'$fecha',121)
			GROUP BY d.clave_bodega, d.descripcion
			    UNION
			SELECT d.clave_bodega, d.descripcion bodega, MIN(num_docto) min, MAX(num_docto) max 
			FROM enc_facturas  a 
				JOIN bodegas c on a.clave_bodega = c.clave_bodega
			    JOIN bodegas d on c.clave_bodega_grupo = d.clave_bodega
			WHERE a.fecha_cancelacion = CONVERT(DATETIME,'$fecha',121) 
			AND a.clave_bodega not in (select d.clave_bodega from enc_facturas  a 
			    JOIN bodegas c on a.clave_bodega = c.clave_bodega
			    JOIN bodegas d on c.clave_bodega_grupo = d.clave_bodega
			    WHERE a.fecha = CONVERT(DATETIME,'$fecha',121)
			    GROUP BY d.clave_bodega, d.descripcion)
			    GROUP BY d.clave_bodega, d.descripcion";
		//hacerArchivo($strsqlGroup);
		$arrDatos=array();
		$rs=$db->Execute($strsqlGroup);
		$formato = '%1$08d';
		if ($rs) {

			while ($row = $rs->fetchRow()) {
				$sprint = sprintf($formato,$GLOBALS["poliza"]);
				$dSubtotal = 0;
				$dIva = 0;
				$datos.= "P  $year$month$day    3  $sprint 1 0          ".substr("VENTAS DE ".textFromDB($row[bodega])." F$row[min] - F$row[max]                               ".
				"                                                                            ",0,100)." 11 0 0 \r\n";
							
				$strsqlFacturas="SELECT d.clave_bodega,d.descripcion,b.cuenta_contpaq, a.num_docto, a.sub_total, a.iva
					, a.moneda, a.tipo_cambio, a.cancelada, a.clave_cliente
					FROM enc_facturas a JOIN clientes b on a.clave_cliente=b.clave_cliente
					    JOIN bodegas c on a.clave_bodega = c.clave_bodega
					    JOIN bodegas d on c.clave_bodega_grupo=d.clave_bodega
					WHERE fecha=CONVERT(DATETIME, '$fecha',121) and d.clave_bodega=".textToDB($row[clave_bodega]) ."
					    order by d.clave_bodega, a.num_docto";
				
				$fCancelada=" ";
				$strDatos2;
				//hacerArchivo($strsqlFacturas);
				//*********************************************
				//	Ciclo para Generar las Polizas por Bodega
				//*********************************************
				$rs2=$db->Execute($strsqlFacturas);
				if ($rs2) {
					while ($row2 = $rs2->fetchRow()) {
						
						if ($row2[cancelada]=="S") {
							$fCancelada= " CANCELADA";
						}else {
							$fCancelada= " ";
						}
						//IMPORTE
							$sTipoMovto=0;
							$datos.= "M  ".substr(contpaqPesos2Dolares($row2[cuenta_contpaq],$row2[moneda])."                                                                               ",0,30)." ".
										substr(''."                                   ",0,10).
										" ".
										$sTipoMovto." ".
										substr(convertirDollaresCalculado($row2[sub_total],$row2[iva],$row2[moneda],$row2[tipo_cambio],0)."                      ",0,15).
										"      0          0.0                  F $row2[num_docto]$fCancelada \r\n";
							
					$dSubtotal += convertirDollaresCalculado($row2[sub_total],$row2[iva],$row2[moneda],$row2[tipo_cambio],1);
					$dIva      += convertirDollaresCalculado($row2[sub_total],$row2[iva],$row2[moneda],$row2[tipo_cambio],2);
					}	
				}
				
				
				//**********************************************
				//	Imprime el Total de las Polizas por Bodegas
				//**********************************************
					//SUBTOTAL
					$sTipoMovto=1;
					$datos.= "M  ".substr(CtaVtas($row[clave_bodega])."                                                                               ",0,30)." ".//falta cuenta de bodega
								substr(''."                                   ",0,10).
								" ".
								$sTipoMovto." ".
								substr($dSubtotal."                          ",0,15).
								"      0          0.0                  "."VENTAS DE ".strtoupper($row[bodega])." F$row[min] - F$row[max] \r\n";
								
					//IVA
					$sTipoMovto=1;
					$datos.= "M  ".substr(CtaIVA($row[clave_bodega])."                                                                               ",0,30)." ".//falta cuenta de bodega
								substr(''."                                   ",0,10).
								" ".
								$sTipoMovto." ".
								substr($dIva."                          ",0,15).
								"      0          0.0                   "."VENTAS DE ".strtoupper($row[bodega])." F$row[min] - F$row[max] \r\n";
				
				//Canceladas	
				$datos.=crearPolizasVentasCancelacion($db,$fecha,$row[clave_bodega]);
				$GLOBALS["poliza"]++;
				//hacerArchivo($wer);
			}
			return $datos;
		}
	}
	
	function crearPolizasVentasCancelacion(&$db, $fecha, $clave_bodega){
		$strsqlCanceladas="SELECT a.clave_bodega,c.descripcion,b.cuenta_contpaq, a.num_docto, a.moneda, a.tipo_cambio,a.sub_total_cancelado
				,a.iva_cancelado, a.clave_cliente
			FROM enc_facturas a join clientes b on a.clave_cliente=b.clave_cliente
			    JOIN bodegas c on a.clave_bodega = c.clave_bodega
			WHERE a.fecha <>a.fecha_cancelacion AND a.fecha_cancelacion=CONVERT(DATETIME, '$fecha',121)
			    AND a.clave_bodega=$clave_bodega
			    ORDER BY a.clave_bodega, a.num_docto";
		$strDatos="";
		
		//***************************************************
		//		Cancelaciones
		//***************************************************
		$rs3=$db->Execute($strsqlCanceladas);
		print $db->ErrorMsg();
		if ($rs3) {
			
			while ($row = $rs3->fetchRow()) {				
				//IMPORTE
				$sTipoMovto=0;
				$strDatos.="M  ".substr(contpaqPesos2Dolares($row[cuenta_contpaq],$row[moneda])."                                                                               ",0,30)." ".
							substr(''."                                   ",0,10).
							" ".
							$sTipoMovto." -".
							substr(convertirDollaresCalculado($row[sub_total_cancelado],$row[iva_cancelado],$row[moneda],$row[tipo_cambio],0),0,15).
							"     0          0.0                   F $row[num_docto] Cancelada \r\n";
				
				//SUBTOTAL
				$sTipoMovto=1;
				$strDatos.="M  ".substr(CtaVtas($row[clave_bodega])."                                                                               ",0,30)." ".
							substr(''."                                   ",0,10).
							" ".
							$sTipoMovto." -".
							substr(convertirDollaresCalculado($row[sub_total_cancelado],$row[iva_cancelado],$row[moneda],$row[tipo_cambio],1),0,15).
							"     0          0.0                   F $row[num_docto] Cancelada \r\n";
							
				//IVA
				$sTipoMovto=1;
				$strDatos.="M  ".substr(CtaIVA($row[clave_bodega])."                                                                                ",0,30)." ".
							substr(''."                                   ",0,10).
							" ".
							$sTipoMovto." -".
							substr(convertirDollaresCalculado($row[sub_total_cancelado],$row[iva_cancelado],$row[moneda],$row[tipo_cambio],2),0,15).
							"     0          0.0                   F $row[num_docto] Cancelada \r\n";
			}
			return $strDatos;
		}
	}
	
	function crearPolizasNotas(&$db, $poliza, $fecha)
	{
		list($year, $month, $day)=split('[/.-]',$fecha);
		$datos="";
		$strsql="SELECT a.clave_bodega, b.descripcion, UCASE(min(a.observaciones)) min, UCASE(max(a.observaciones)) max 
			FROM pagos_cxc a JOIN bodegas b on a.clave_bodega=b.clave_bodega
			WHERE a.fecha=CONVERT(DATETIME,'$fecha',121) and a.clave_conc_cxc not in ('PAGO','VTA') 
			GROUP BY a.clave_bodega, b.descripcion";
			//hacerArchivo($strsql);
		$rs=$db->Execute($strsql);
		$formato = '%1$08d';
		if ($rs) {
			while ($row = $rs->fetchRow()) {
				$sprint = sprintf($formato,$GLOBALS["poliza"]);
				$dSubtotal = 0;
				$dIva = 0;
				$min = $row[min];
				$max = $row[max];
				$datos.= "P  $year$month$day    3  $sprint 1 0          ".substr("NOTAS DE CREDITO ".textFromDB($row[descripcion]) ." ".textFromDB(remplazaObservaciones($min))." - ".textFromDB(remplazaObservaciones($max))."                                                                                                 ",0,100)." 11 0 0 \r\n";
				
				$strsqlNotas="SELECT a.clave_bodega, a.num_docto, a.fecha, a.importe subtotal, a.iva, a.importe+a.iva importe, a.moneda, a.observaciones, a.clave_conc_cxc, b.clave_cliente,
					    (b.sub_total+b.iva) importefac, b.moneda monedafac, c.cuenta_contpaq, b.tipo_cambio tipo_cambio_fac, d.tipo_mov_cxc
					FROM pagos_cxc a 
					    JOIN enc_facturas b ON a.num_docto=b.num_docto  
					    AND a.clave_bodega=b.clave_bodega 
					        JOIN clientes c ON b.clave_cliente=c.clave_cliente
					        JOIN conceptos_cxc d ON a.clave_conc_cxc = d.clave_conc_cxc 
					WHERE a.fecha=CONVERT(DATETIME,'".textToDB($fecha)."',121) 
					    AND a.clave_conc_cxc NOT IN ('PAGO','VTA')
					    AND a.clave_bodega=".textToDB($row[clave_bodega])."
					    ORDER BY a.observaciones";
				//hacerArchivo($strsqlNotas);
				$subtotalNotas=0;
				$ivaNotas=0;
				$rs2=$db->Execute($strsqlNotas);
				if ($rs2) {
					while ($row2 = $rs2->fetchRow()){
						$datos.=calcularCamposNotasCredito($db,$fecha,textFromDB($row2[moneda]), textFromDB($row2[tipo_cambio_fac]),textFromDB($row2[importe]), textFromDB($row2[importefac]),textFromDB($row2[iva]),textFromDB($row2[clave_bodega]),textFromDB($row2[clave_conc_cxc]),textFromDB($row2[tipo_mov_cxc]),textFromDB($row2[cuenta_contpaq]),textFromDB($row2[observaciones]),textFromDB($row2[num_docto]),textFromDB($row2[subtotal]));
						//break;
					}
				}
				$GLOBALS['poliza']++;
			}
		}
		//hacerArchivo($strsqlNotas);
		//break;
		return $datos ;
	}
	
	function convertirDollaresCalculado($subTotal, $iva, $tipoMoneda, $tipoCambio, $dato=0)
	{
		setlocale(LC_MONETARY, 'en_DE');
		$cSubTotal=0;
		$cIVA=0;
		$salida;
		
		if ($tipoMoneda=='MN') {
			$cSubTotal = floatval($subTotal);
			$cIVA = floatval($iva);
		}
		else
		{
			$cSubTotal = floatval(($subTotal*$tipoCambio));
			$cIVA = floatval(($iva*$tipoCambio));
		}
		
		switch ($dato){
			case 0:
				$salida = $cSubTotal+$cIVA;
				break;
			case 1:
				$salida = $cSubTotal;
				break;
			case 2:
				$salida = $cIVA;
				break;
			case 3:
				$salida = $cIVA;
				break;
		}
		return sprintf('%-15s',number_format($salida, 2, '.', ''));
	}
	
	//******************************************************************
	//	FUNCION QUE GENERA LOS CAMPOS DE NOTAS DE CREDITO
	//******************************************************************
	function calcularCamposNotasCredito(&$db, $fecha, $moneda, $tipo_cambio_fac, $importe, $importefac, $iva, $clave_bodega, $clave_conc_cxc, $tipo_mov_cxc,$cuenta_contpaq,$observaciones,$num_docto, $subtotal)
	{
		//TIPO DE CAMBIO UN DIA ANTERIOR
		$tipoCambio = getTipoCambio($db,$fecha);
		$dSubTotal;
		$importesFactura_mn=0;
		
		if ($importe==$importefac) {
			$importesFactura_mn = getImportesFactura_mn($moneda,$importe,$tipo_cambio_fac);
			if (($clave_bodega=="60")||($clave_bodega=="30")) {
				$dSubTotal2 = $importesFactura_mn / (1+$GLOBALS["gIVAf"]);
			}else {
				$dSubTotal2 = $importesFactura_mn/(1+$GLOBALS["gIVA"]);
			}
			$dIVA=sprintf('%-15s',number_format(($importesFactura_mn - $dSubTotal2), 2, '.', ''));
			$dSubTotal = sprintf('%-15s',number_format($dSubTotal2,2,'.',''));
			$dNotasString = substr(remplazaObservaciones($observaciones)."  F-$num_docto",0,20);
			$sTipoMovto;
			
			$strDatos;
			
			if ($tipo_mov_cxc=="A") {
				$sTipoMovto="1";
			}else {
				$sTipoMovto="0";
			}
			
			//IMPORTE
			$strDatos="M  ".substr(contpaqPesos2Dolares($cuenta_contpaq,$moneda)."                                                                               ",0,30)." ".
						substr(''."                                   ",0,10).
						" ".
						$sTipoMovto." ".
						substr("$importesFactura_mn                                                       ",0,15).
						"      0          0.0                  $dNotasString \r\n";
			
			//IVA
			if ($tipo_mov_cxc=="A") {
				$sTipoMovto="0";
			}else {
				$sTipoMovto="1";
			}
			$strDatos.="M  ".substr(CtaIVA($clave_bodega,$clave_bodega)."                                                                               ",0,30)." ".
						substr(''."                                   ",0,10).
						" ".
						$sTipoMovto." ".
						substr("".$dIVA."                                                               ",0,15).
						"      0          0.0                  $dNotasString \r\n";
						
			//SUBTOTAL
			if ($tipo_mov_cxc=="A") {
				$sTipoMovto="0";
			}else {
				$sTipoMovto="1";
			}
			$strDatos.="M  ".substr(CtaCpto($clave_conc_cxc)."                                                                                ",0,30)." ".
						substr(''."                                   ",0,10).
						" ".
						$sTipoMovto." ".
						substr("".$dSubTotal."                                                           ",0,15).
						"      0          0.0                  $dNotasString \r\n";	
		}else {
			$importesFactura_mn = getImportesFactura_mn($moneda,$importe,$tipo_cambio_fac);
			if (($clave_bodega=="60")||($clave_bodega=="30")) {
				$dSubTotal2 = $importesFactura_mn / (1+$GLOBALS["gIVAf"]);
			}else {
				$dSubTotal2 = $importesFactura_mn/(1+$GLOBALS["gIVA"]);
			}
			
			if ($moneda=="DLS") {
				$tipo_cambioElse = floatval($tipo_cambio_fac);
			}else {
				$tipo_cambioElse = 1;
			}
			
			//VARIABLES QUE SON USADAS EN CADA ROW QUE SE IMPRIME
			$dSubTotal = round((floatval($subtotal)*$tipo_cambioElse),2);
			$dIVA = round((floatval($iva)*$tipo_cambioElse),2);
			$dNotasImporte = round(($dSubTotal+$dIVA),2);
			$dNotasString = substr(remplazaObservaciones($observaciones)."  F-$num_docto",0,20);
			
			$sTipoMovto;
			
			$strDatos;
			
			if ($tipo_mov_cxc=="A") {
				$sTipoMovto="1";
			}else {
				$sTipoMovto="0";
			}
			
			$datos.=//IMPORTE
			
			$strDatos="M  ".substr(contpaqPesos2Dolares($cuenta_contpaq,$moneda)."                                                                               ",0,30)." ".
						substr(''."                                   ",0,10).
						" ".
						$sTipoMovto." ".
						substr("$dNotasImporte                                                       ",0,15).
						"      0          0.0                   $dNotasString \r\n";
			
			//IVA
			if ($tipo_mov_cxc=="A") {
				$sTipoMovto="0";
			}else {
				$sTipoMovto="1";
			}
			$strDatos.="M  ".substr(CtaIVANotasCredito($clave_bodega)."                                                                               ",0,30)." ".
						substr(''."                                   ",0,10).
						" ".
						$sTipoMovto." ".
						substr("".$dIVA."                                                               ",0,15).
						"      0          0.0                   $dNotasString \r\n";
						
			//SUBTOTAL
			if ($tipo_mov_cxc=="A") {
				$sTipoMovto="0";
			}else {
				$sTipoMovto="1";
			}
			$strDatos.="M  ".substr(CtaCpto($clave_conc_cxc)."                                                                                ",0,30)." ".
						substr(''."                                   ",0,10).
						" ".
						$sTipoMovto." ".
						substr("".$dSubTotal."                                                           ",0,15).
						"      0          0.0                   $dNotasString \r\n";
			//hacerArchivo($strDatos);	
		}
				
		return $strDatos;
	}
	
	//*************************************************************************
	//	FUNCION PARA OBTENER LOS IMPORTES EN MONEDA NACIONAL
	//*************************************************************************
	
	function getImportesFactura_mn($moneda, $notasImporteFactura, $tipoCambioRow){
		if ($moneda=="DLS") {
			$notasImporteFactura_mn = round(($notasImporteFactura*$tipoCambioRow),2);
		}else {
			$notasImporteFactura_mn = round($notasImporteFactura,2);
		}
		return $notasImporteFactura_mn;
	}
	
	//*************************************************************
	//	FUNCION PARA OBTENER LA CUENTA DE IVA DE NOTAS DE CREDITO
	//*************************************************************
	
	function getNotasCuentaIVA($clave_bodega){
		$cuentaIVA;
		if (($clave_bodega=="60") || ($clave_bodega=="30")){
			$cuentaIVA = "160202000";
		}
		else {
			$cuentaIVA = "160201000";
		}
		return $cuentaIVA;
	}
	
	//****************************************************************************
	//	FUNCION PARA OBTENER EL TIPO DE CAMBIO DE UN DIA ANTERIOR
	//****************************************************************************
	function getTipoCambio(&$db, $fecha)
	{
		$strsqlTC = "SELECT tipo_cambio FROM tipos_cambio WHERE fecha < CONVERT(DATETIME,'$fecha',121) ORDER BY fecha DESC";
		$tipoCambio="";
		$rsTC=$db->Execute($strsqlTC);
		if ($rsTC) {
			while ($row = $rsTC->fetchRow()){
				
				$tipoCambio = $row[tipo_cambio];
				
				if ($tipoCambio!="") {
					break;
				}
			}
		}else {
			print	$db->ErrorMsg();
		}
		return round($tipoCambio,2);
	}
	
	//**********************************************************************
	//	FUNCION PARA REEMPLAZAR LAS OBSERVACIONES EN LAS NOTAS DE CREDITO
	//**********************************************************************
	function remplazaObservaciones($observaciones)
	{
		//$observaciones2 ="";
		if (substr($observaciones,0,11)=="CANCELACION") {
			return $observaciones;
		}
		elseif (substr($observaciones,0,9)=="DESCUENTO"){
			return $observaciones;
		}
		else {
			return substr($observaciones,0,7);
		}
		
	}
	//*********************************************************
	//	FUNCION QUE ME DA LA CUENTA DE CONCEPTOS CXC EN CONPAQ
	//*********************************************************
	function CtaCpto($concepto, $bodega)
	{
		$resultado="";
		 switch ($concepto)
		 {
		 	case '004': //Descuento Proto Pago
		 		$resultado="800201000";
		 		break;
		 	case 'CXDM'	: 	//CARGOX DEV.MERC
		 	case 'CHEQ'	:	//CARGO CHEQUE DEV.
		 	case ' EP'	:	//Cargo Error Precio
		 		if (($bodega=="30") or $bodega=="60"){
		 			$resultado="720100000";
		 		}else {
		 			$resultado="720200000";
		 		}
		 		break;
		 	default:
		 		$resultado="800101000";
		 }
		 return $resultado;
	}
	
	//*********************************************************
	//	FUNCION QUE ME DA LA CUENTA DE VENTAS BODEGA EN CONPAQ
	//*********************************************************
	function CtaVtas($bodega)
	{
		$resultado="";
		 switch ($bodega)
		 {
		 	case '01': //HERMOSILLO
		 		$resultado="700100000";
		 		break;
		 	case '50': //CULIACAN
		 		$resultado="700200000";
		 		break;
		 	case '60': //NOGALES
		 		$resultado="700400000";
		 		break;
		 	case '30': //TIJUANA
		 		$resultado="700500000";
		 		break;
		 	case '70': //OBREGON
		 		//$resultado="700100"; Era la cuenta anterior
		 		$resultado="700300000";
		 		break;
		 	case '40': //MEXICO
		 		$resultado="700600000";
		 		//$resultado="700200";
		 	case '90': //GAMESA
		 		$resultado="700600000";
		 }
		 return $resultado;
	}
	
	//****************************************************************
	//	FUNCION QUE ME DA LA CUENTA DE IVA DE DIARIO BODEGA EN CONPAQ 
	//****************************************************************
	function CtaIVA($bodega)
	{
		$resultado="";
		switch ($bodega)
		{
			case '60': //NOGALES
				$resultado = "160202000";
				break;
			case '30': //TIJUANA
				$resultado = "160202000";
				break;
			default: 
				$resultado = "160201000";
				break;
		}
		return $resultado;
	}
	
	
	//**************************************************************************
	//	FUNCION QUE ME DA LA CUENTA DE IVA DE NOTAS DE CREDITO BODEGA EN CONPAQ 
	//**************************************************************************
	function CtaIVANotasCredito($bodega)
	{
		$resultado="";
		switch ($bodega)
		{
			case '60': //NOGALES
				$resultado = "160202000";
				break;
			case '30': //TIJUANA
				$resultado = "160202000";
				break;
			default: 
				$resultado = "160201000";
				break;
		}
		return $resultado;
	}
	
	//**************************************************************************
	//	FUNCION QUE CAMBIA DE PESOS A DOLARES LA CUENTA CONTPAQ CON EL FORMATO
	//	XX-XX-XX-XXX, DONDE EL SEGUNDO SEGMENTO INDICA PESOS(01) O DOLARES(02)
	//**************************************************************************
	function contpaqPesos2Dolares($cuentaContpaq, $moneda)
	{
		$resultado="";
		$cuentaNueva="";
		//130113003
		//130213003
		//130123003
		switch ($moneda)
		{
			case 'MN': //Moneda Nacional
				$cuentaNueva = $cuentaContpaq;
				$resultado = $cuentaNueva;
				break;
			case 'DLS': //Dolares
				$cuentaNueva = substr($cuentaContpaq,0,3).'2'.substr($cuentaContpaq,4,8);
				$resultado = "$cuentaNueva";
				break;
		}
		return $resultado;
	}
	
	function crearArchivoDiario($dato, $nombreArchivo, $tipo){
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