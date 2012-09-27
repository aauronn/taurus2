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
	
	if($_POST["opcion"]=="CREAR_POLIZA_COMPRAS"){
		$fecha = textToDB($_POST['fecha']);
		$poliza = 0;
		$GLOBALS["poliza"] = textToDB($_POST['poliza']);
		//$notas = $_POST['notas'];
		//$ventas = $_POST['ventas'];
		$GLOBALS["gIVAf"]= 0.11;
		$GLOBALS["gIVA"] = 0.16;
		
		//$strDatos = "";
		
		//if ($ventas=="true") 
		//{
			$strDatos=crearPolizaCompras($fecha,$GLOBALS["poliza"],$db);
		//}
		print encrypt($strDatos,$GLOBALS["RC4_KEY"]);	
		//hacerArchivo($strDatos);
	}
	
	if ($_POST["opcion"]=="EXPORTAR_POLIZA_COMPRAS") {
		$strDatos = textToDB($_POST["strDatos"]);
		$fecha = textToDB($_POST['fecha']);
		list($year, $month, $day)=split('[/.-]',$fecha);
		/*
		header('ETag: etagforie7download'); //IE7 requires this header
		header('Content-type: application/octet_stream');
		header('Content-disposition: attachment; filename="COMPRAS_'.$year.$month.$day.'.txt"');
		
		echo $strDatos;
		*/
		crearArchivoCompras($strDatos, "COMPRAS_".$year.$month.$day, "Poliza Compras");
	}
	
	function crearPolizaCompras( $fecha, $poliza, &$db){
		list($year, $month, $day)=split('[/.-]',$fecha);
		$TipoMovto='';
		$Cancelada='';
		$datos="";
		//print "soy tu padre";
		$cuenta=0;
		
	//Consulta para Obtener Bodegas	
		$strsqlGroup="SELECT d.clave_bodega, d.descripcion bodega, MIN(num_docto) min, MAX(num_docto) max
						FROM enc_compras  a 
							JOIN bodegas c on a.clave_bodega = c.clave_bodega
						    JOIN bodegas d on c.clave_bodega_grupo = d.clave_bodega
						WHERE a.fecha = CONVERT(DATETIME,'$fecha',121)
						GROUP BY d.clave_bodega, d.descripcion
				    UNION
					SELECT d.clave_bodega, d.descripcion bodega, MIN(num_docto) min, MAX(num_docto) max 
						FROM enc_compras  a 
							JOIN bodegas c on a.clave_bodega = c.clave_bodega
						    JOIN bodegas d on c.clave_bodega_grupo = d.clave_bodega
						WHERE a.fecha_factura = CONVERT(DATETIME,'$fecha',121) 
							AND a.clave_bodega 
				                not in (
				                    select d.clave_bodega from enc_compras  a 
						            JOIN bodegas c on a.clave_bodega = c.clave_bodega
						            JOIN bodegas d on c.clave_bodega_grupo = d.clave_bodega
						                WHERE a.fecha = CONVERT(DATETIME,'$fecha',121)
						                GROUP BY d.clave_bodega, d.descripcion
				                        )
						    GROUP BY d.clave_bodega, d.descripcion";
		//hacerArchivo($strsqlGroup);	
		$arrDatos=array();
		$rs=$db->Execute($strsqlGroup);
		$formato = '%1$08d';
		if ($rs) {
			//ENCABEZADO DE LA POLIZA
			$sprint = sprintf($formato,$GLOBALS["poliza"]);
			$datos.= "P  $year$month$day    3  $sprint 1 0          ".substr("COMPRAS DEL  $day DE ".strtoupper(mesLetra($month)) ." DE $year                               ".
				"                                                                            ",0,100)." 11 0 0 \r\n";
			while ($row = $rs->fetchRow()) {
				$dSubtotal = 0;
				$dIva = 0;
				
				//***********************************
				//	CREO UNA NUEVA POLIZA DE COMPRAS
				//***********************************
				$strCompras="SELECT d.clave_bodega,d.descripcion,
				        b.poliza_cargo, b.poliza_abono_mn, b.poliza_abono_dls, b.poliza_iva, b.poliza_cargo_flete, 
				        b.poliza_cargo_flete_importacion, b.poliza_abono_flete_importacion, 
				        a.num_docto, a.sub_total, a.iva, a.moneda, a.tipo_cambio, a.clave_proveedor, a.numero_pedimento
					FROM enc_compras a JOIN proveedores b on a.clave_proveedor=b.clave_proveedor
					    JOIN bodegas c on a.clave_bodega = c.clave_bodega
					    JOIN bodegas d on c.clave_bodega_grupo=d.clave_bodega
					WHERE fecha=CONVERT(DATETIME, '$fecha',121) and d.clave_bodega='$row[clave_bodega]'//
					    order by d.clave_bodega, a.num_docto";//
				//hacerArchivo($strCompras);
				//***************************************************
				//		Compras
				//***************************************************
				$rs3=$db->Execute($strCompras);
				if ($rs3) {
					while ($row = $rs3->fetchRow()) {				
							//SUBTOTAL
							$sTipoMovto=0;
							$datos.="M  ".substr(CtaComprasContpaq(textFromDB($row[poliza_abono_mn]),textFromDB($row[poliza_abono_dls]),textFromDB($row[numero_pedimento]))."                                                                               ",0,30)." ".
										substr(''."                                   ",0,10).
										" ".
										$sTipoMovto." ".
										substr(convertirDollaresCalculado($row[sub_total],$row[iva],$row[moneda],$row[tipo_cambio],$row[numero_pedimento],1),0,15).
										"     0          0.0                   F $row[num_docto] \r\n";
							//IVA
							if ($row[numero_pedimento]==null || $row[numero_pedimento]=="") {
								$sTipoMovto=0;
								$datos.="M  ".substr("160100000                                                                                ",0,30)." ".
											substr(''."                                   ",0,10).
											" ".
											$sTipoMovto." ".
											substr(convertirDollaresCalculado($row[sub_total],$row[iva],$row[moneda],$row[tipo_cambio],$row[numero_pedimento],2),0,15).
											"     0          0.0                   F $row[num_docto] \r\n";
							}			
							//IMPORTE
							$sTipoMovto=1;
							$datos.="M  ".substr(CtaOtroProveedor($row[poliza_abono],$row[poliza_cargo],$row[clave_proveedor],$row[numero_pedimento],$row[poliza_abono_flete_importacion],$row[moneda])."                                                                               ",0,30)." ".
										substr(''."                                   ",0,10).
										" ".
										$sTipoMovto." ".
										substr(convertirDollaresCalculado($row[sub_total],$row[iva],$row[moneda],$row[tipo_cambio],$row[numero_pedimento],0),0,15).
										"     0          0.0                   F $row[num_docto] \r\n";
							$cuenta++;
					}			
				}
						$GLOBALS["poliza"]++;
			}
			return $datos;
		}
	}
	
	function convertirDollaresCalculado($subTotal, $iva, $tipoMoneda, $tipoCambio, $numero_pedimento, $dato=0)
	{
		setlocale(LC_MONETARY, 'en_DE');
		$cSubTotal=0;
		$cIVA=0;
		$salida;
		if ($numero_pedimento=="" || $numero_pedimento==null) {
			if ($tipoMoneda=='MN') {
				$cSubTotal = floatval($subTotal);
				$cIVA = floatval($iva);
			}
			else
			{
				$cSubTotal = floatval(($subTotal*$tipoCambio));
				$cIVA = floatval(($iva*$tipoCambio));
			}
		}else {
			if ($tipoMoneda=='MN') {
				$cSubTotal = floatval($subTotal);
				$cIVA = 0;
			}
			else
			{
				$cSubTotal = floatval(($subTotal*$tipoCambio));
				$cIVA = 0;
			}
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
	
	function getNotasCuentaIVA($numero_pedimento){
		$cuentaIVA;
		if (($numero_pedimento!="") || ($numero_pedimento!=null)){
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
	
	//*********************************************************
	//	FUNCION QUE ME DA LA CUENTA DE VENTAS BODEGA EN CONPAQ
	//*********************************************************
	function CtaVtas($bodega)
	{
		$resultado="";
		 switch ($bodega)
		 {
		 	case '01': //HERMOSILLO
		 		$resultado="700100";
		 		break;
		 	case '50': //CULIACAN
		 		$resultado="700200";
		 		break;
		 	case '60': //NOGALES
		 		$resultado="700400";
		 		break;
		 	case '30': //TIJUANA
		 		$resultado="700500";
		 		break;
		 	case '70': //OBREGON
		 		//$resultado="700100"; Era la cuenta anterior
		 		$resultado="700300";
		 		break;
		 	case '40': //MEXICO
		 		$resultado="700600";
		 		//$resultado="700200";
		 }
		 return $resultado;
	}
	
	//*****************************************************
	//	FUNCION QUE ME DA LA CUENTA DE IVA BODEGA EN CONPAQ
	//*****************************************************
	function CtaIVA($bodega)
	{
		$resultado="";
		switch ($bodega)
		{
			case '60': //NOGALES
				$resultado = "160202";
				break;
			case '30': //TIJUANA
				$resultado = "160202";
				break;
			default: 
				$resultado = "160201";
				break;
		}
		return $resultado;
	}
	
	//******************************************************************
	//	FUNCION QUE ME IDENTIFICA LOS PROVEDORES COMO OTROS_PROVEEDORES
	//******************************************************************
	
	function CtaOtroProveedor($poliza_abono_mn,$poliza_cargo,$clave_proveedor,$numero_pedimento,$poliza_abono_flete_importacion,$moneda)
	{
		$resultado="";
		/*if ($numero_pedimento!=null || $numero_pedimento!="") {
			if ($poliza_abono_flete_importacion!=null || $numero_pedimento!="") {
				$resultado = "$poliza_abono_flete_importacion";
			}else {
				$resultado=$poliza_abono_dls;
			}
		}else {
			$resultado=$poliza_cargo;
		}
		*/
		
		switch ($clave_proveedor)
		{
			case 'ARAN':
				if ($numero_pedimento!=null || $numero_pedimento!="") {
					$resultado = "$poliza_abono_flete_importacion";
				}else {
					$resultado="418201002";
				}
				break;
			case 'DUR':
				if ($numero_pedimento!=null || $numero_pedimento!="") {
					$resultado = "$poliza_abono_flete_importacion";
				}else {
					$resultado="418201002";
				}
				break;
			case 'IVS':
				if ($numero_pedimento!=null || $numero_pedimento!="") {
					$resultado = "$poliza_abono_flete_importacion";
				}else {
					$resultado="418201002";
				}
				break;
			case 'SAMS':
				if ($numero_pedimento!=null || $numero_pedimento!="") {
					$resultado = "$poliza_abono_flete_importacion";
				}else {
					$resultado="418201002";
				}
				break;
			case 'ATD':
				if ($numero_pedimento!=null || $numero_pedimento!="") {
					$resultado = "$poliza_abono_flete_importacion";
				}else {
					$resultado="418201002";
				}
				break;
			case 'FEL':
				$resultado="418201002";
				break;
			case 'BRIT':
				if ($numero_pedimento!=null || $numero_pedimento!="") {
					$resultado = "$poliza_abono_flete_importacion";
				}else {
					$resultado="418201002";
				}
				break;
			case 'REIM':
				if ($numero_pedimento!=null || $numero_pedimento!="") {
					$resultado = "$poliza_abono_flete_importacion";
				}else {
					$resultado="418201002";
				}
				break;
			case 'IPER':
				if ($numero_pedimento!=null || $numero_pedimento!="") {
					$resultado = "$poliza_abono_flete_importacion";
				}else {
					$resultado="418201002";
				}
				break;
			case 'TAM':
				if ($numero_pedimento!=null || $numero_pedimento!="") {
					$resultado = "$poliza_abono_flete_importacion";
				}else {
					$resultado="418201002";
				}
				break;
			case 'LIF':
				if ($numero_pedimento!=null || $numero_pedimento!="") {
					$resultado = "$poliza_abono_flete_importacion";
				}else {
					$resultado="418201002";
				}
				break;
			case 'SCH':
				if ($numero_pedimento!=null || $numero_pedimento!="") {
					$resultado = "$poliza_abono_flete_importacion";
				}else {
					$resultado="418201002";
				}
				break;
			case 'EUR':
				if ($numero_pedimento!=null || $numero_pedimento!="") {
					$resultado = "$poliza_abono_flete_importacion";
				}else {
					$resultado="418201002";
				}
				break;
			case 'EMB':
				if ($numero_pedimento!=null || $numero_pedimento!="") {
					$resultado = "$poliza_abono_flete_importacion";
				}else {
					$resultado="418201002";
				}
				break;
			case 'VTA':
				if ($numero_pedimento!=null || $numero_pedimento!="") {
					$resultado = "$poliza_abono_flete_importacion";
				}else {
					$resultado="418201002";
				}
				break;
			default:
				if ($numero_pedimento!=null || $numero_pedimento!="") {
					if ($poliza_abono_flete_importacion=="" || $poliza_abono_flete_importacion==null) {
						$resultado=$poliza_cargo;
					}else {
						$resultado = "$poliza_abono_flete_importacion";
					}
					
				}else {
					if ($moneda=="DLS") {
						$resultado = substr($poliza_cargo,0,5).'2'.substr($poliza_cargo,6,8);
					}else {
						$resultado=$poliza_cargo;
					}
					
				}
				break;
		}
		
		return $resultado;
	}
	
	function proveedorNumPedimento($numero_pedimento,$poliza_abono_mn, $poliza_abono_dls)
	{
		$resultado="";
		if (($numero_pedimento!="")||($numero_pedimento!=null)) {
			$resultado=$poliza_abono_dls;
		}else {
			$resultado=$poliza_abono_mn;
		}
		return $resultado;
	}
	//*****************************************************************************
	//	FUNCION QUE ME SELECCIONA LA CUENTA DE COMPRAS SI ES NACIONAL O EXTRANGERA
	//*****************************************************************************
	function CtaComprasContpaq($poliza_abono_mn, $poliza_abono_dls, $numero_pedimento){
		$resultado="";
		$archivo.="";
		if ($numero_pedimento==null || $numero_pedimento=="") {
			if ($poliza_abono_mn=="") {
				$resultado = $poliza_abono_dls;
				
			}else {
				$resultado=$poliza_abono_mn;
			}
		}else {
			$resultado=$poliza_abono_dls;
		}
		if (strlen($resultado)==4) {
			$resultado.="00";
		}elseif (strlen($resultado==0)){
			$resultado="000000";
		}
		return $resultado;
	}
	
	function mesLetra($numeroMes)
	{
		switch ($numeroMes) {
			case "01":
				$resultado = "Enero";
				break;
			case "02":
				$resultado = "Febrero";
				break;
			case "03":
				$resultado = "Marzo";
				break;
			case "04":
				$resultado = "Abril";
				break;
			case "05":
				$resultado = "Mayo";
				break;
			case "06":
				$resultado = "Junio";
				break;
			case "07":
				$resultado = "Julio";
				break;
			case "08":
				$resultado = "Agosto";
				break;
			case "09":
				$resultado = "Septiembre";
				break;
			case "10":
				$resultado = "Octubre";
				break;
			case "11":
				$resultado = "Noviembre";
				break;
			case "12":
				$resultado = "Diciembre";
				break;
		
			default:
				$resultado = "No Hay valor numerico de Mes";
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
	
	function crearArchivoCompras($dato, $nombreArchivo, $tipo){
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