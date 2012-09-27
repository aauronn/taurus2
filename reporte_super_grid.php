<?php   


include "include/entorno.inc.php";
include "include/funciones.inc.php";
//include "../conexionbd.php";

	
	
	
	
	// --------------------------------------
	//  TABLAS
	// --------------------------------------	
	$flashvars = $_POST['arrtablas'];
	if($flashvars!=""){
		if(get_magic_quotes_gpc()){
		    $flashvars = stripslashes($flashvars);
		}
		$arrTablas = object_to_array(unserialize(urldecode($flashvars)));
		//print_r($arrTablas);
	}
	/*if($arrTablas=="" || !is_array($arrTablas) || count($arrTablas)==0){
		print "Error al recibir las tablas";
		exit;
	}*/
	
	
	
	
	
	
	
	// --------------------------------------
	//  GENERA JPG DE GRAFICA
	// --------------------------------------
	$img_grafica = "";
	if($_POST["img_grafica"] && $_POST["img_grafica"]!=""){
		$img_grafica = generaJPG(base64_decode($_POST["img_grafica"]));
	}
	
	
	
	
	
	
	
	
	
	
	
	
	// --------------------------------------
	//  VALIDACIONES
	// --------------------------------------
	if($_POST["from"]!="Tabla" && $_POST["from"]!="Grafica"){
		print "De donde vienes?";
		exit;		
	}
	if($_POST["tiporeporte"]!="PDF" && $_POST["tiporeporte"]!="EXCEL" && $_POST["tiporeporte"]!="CSV"){
		print "Tipo de Reporte invalido";
		exit;
	}
	if($_POST["hoja_tamanio"]=="" || ($_POST["hoja_tamanio"]!="legal" && $_POST["hoja_tamanio"]!="letter")){
		$_POST["hoja_tamanio"] = "letter";
	}
	if($_POST["hoja_orientacion"]=="" || ($_POST["hoja_orientacion"]!="P" && $_POST["hoja_orientacion"]!="L")){
		$_POST["hoja_orientacion"] = "P";
	}
	if($_POST["tamanioLetra"]=="" || !is_numeric($_POST["tamanioLetra"])){
		$_POST["tamanioLetra"] = 10;
	}
	if($_POST["from"]=="Tabla"){
		if($_POST["chbBorder"]=="" || !is_numeric($_POST["chbBorder"]) || ($_POST["chbBorder"]!="1" && $_POST["chbBorder"]!="0")){
			$_POST["chbBorder"] = "1";
		}
		if($_POST["chbCellPadding"]=="" || !is_numeric($_POST["chbCellPadding"]) || ($_POST["chbCellPadding"]!="1" && $_POST["chbCellPadding"]!="0")){
			$_POST["chbCellPadding"] = "1";
		}
		/*if($_POST["chbCellSpacing"]=="" || !is_numeric($_POST["chbCellSpacing"]) || ($_POST["chbCellSpacing"]!="1" && $_POST["chbCellSpacing"]!="0")){
			$_POST["chbCellSpacing"] = "1";
		}*/
		if($_POST["chbNumeroDeRenglon"]=="" || !is_numeric($_POST["chbNumeroDeRenglon"])){
			$_POST["chbNumeroDeRenglon"] = "1";
		}
		if($_POST["initNumeracion"]=="" || !is_numeric($_POST["initNumeracion"])){
			$_POST["initNumeracion"] = "1";
		}
		if($_POST["chbBgColor"]=="" || !is_numeric($_POST["chbBgColor"]) || ($_POST["chbBgColor"]!="1" && $_POST["chbBgColor"]!="0")){
			$_POST["chbBgColor"] = "1";
		}
		if($_POST["chbNumeroDeRenglon"]=="" || !is_numeric($_POST["chbNumeroDeRenglon"]) || ($_POST["chbNumeroDeRenglon"]!="1" && $_POST["chbNumeroDeRenglon"]!="0")){
			$_POST["chbNumeroDeRenglon"] = "1";
		}
		if($_POST["chbFooter"]=="" || !is_numeric($_POST["chbFooter"]) || ($_POST["chbFooter"]!="1" && $_POST["chbFooter"]!="0")){
			$_POST["chbFooter"] = "1";
		}
		
		
		$flashvars = $_POST['columnasHeaders'];
		if($flashvars!=""){
			if(get_magic_quotes_gpc()){
			    $flashvars = stripslashes($flashvars);
			}
			$_POST["columnasHeaders"] = object_to_array(unserialize(urldecode($flashvars)));
		}		
		if($_POST["columnasHeaders"]=="" || !is_array($_POST["columnasHeaders"])){
			print "Error columnasHeaders"; exit;
		}
		
		
		$flashvars = $_POST['columnasTabla'];
		if($flashvars!=""){
			if(get_magic_quotes_gpc()){
			    $flashvars = stripslashes($flashvars);
			}
			$_POST["columnasTabla"] = object_to_array(unserialize(urldecode($flashvars)));
		}
		if($_POST["columnasTabla"]=="" || !is_array($_POST["columnasTabla"])){
			print "Error columnasTabla"; exit;
		}
		
		
		$flashvars = $_POST['arrFooters'];
		if($flashvars!=""){
			if(get_magic_quotes_gpc()){
			    $flashvars = stripslashes($flashvars);
			}
			$_POST["arrFooters"] = object_to_array(unserialize(urldecode($flashvars)));
		}
		if($_POST["arrFooters"]=="" || !is_array($_POST["arrFooters"])){
			$_POST["arrFooters"]="";
		}
	}
	
	
	
	
	
	
	
	// --------------------------------------
	//  GENERANDO REPORTE
	// --------------------------------------
	if($_POST["tiporeporte"]=="PDF"){
		define('_MPDF_PATH','include/mpdf2_5/');		
		include("include/mpdf2_5/mpdf.php");
		
		class reporte extends mPDF {
		}	
		
		//foreach ($_POST as $k=>$v) $_POST[$k]=textToDB($v);
		
		
		$pdf=new reporte('win-1252',$_POST["hoja_tamanio"],7,'tahoma',9.972,9.892,27,12.892,9,9,$_POST["hoja_orientacion"]); 
		$pdf->SetDisplayMode(80,'default');
		$pdf->aliasNbPg="PAGETOTAL";	
		
		//$pdf->SetHeader('<img src="logo.jpg" width="110"  />{DATE d/m/Y}|CENTRO DE SOLUCIONES WEB|'.getDiaHora($db));			
		// <img src='./imagenes/logo_mediano.jpg' height='50'/>
		
		//<img src='./imagenes/logo_reportes.jpg' height='50'/>
		
		
		//<img src='./imagenes/logo_reportes.jpg' height='50'/>
		
		
		$tablaHeader ="<table width='100%' cellpadding='0' cellspacing='0'>
							<tr width='100%'>
								<td width='33%' align='left'></td>".
								//<td width='33%' align='center'><b>CENTRO DE SOLUCIONES WEB</b></td>
								"<td width='33%' align='center'><b>TAURUS</b></td>"
								."<td width='33%' align='right'><b>".getDiaHora($db)."</b></td>
							</tr>
							<tr width='100%'>
								<td colspan='3'>	
									<hr>
								</td>
							</tr>
						</table>";
		
		
		$pdf->SetHTMLHeader($tablaHeader);			
		//$pdf->SetFooter('{DATE d/m/Y}|Este Sistema es Propiedad de CSWeb S.A. de C.V.|PAGINA {PAGENO} DE PAGETOTAL');	
		$pdf->SetFooter('{DATE d/m/Y}|Taurus|PAGINA {PAGENO} DE PAGETOTAL');	
		

			
		$i=0;
		foreach($arrTablas as $tabla){
			//print generaPaginaPDF($db,$pregunta,$img_grafica);
			if($i>0) $pdf->AddPage();
			$pdf->WriteHTML(utf8_encode(generaPaginaPDF($db,$tabla,$img_grafica)));
			$i++;
		}
		generarPdf($pdf, "./temp_archivos/");		
		//if($cargaImagenes && $arrDatos["img"]!="")	unlink($arrDatos["img"]);
		
	}
	else if($_POST["tiporeporte"]=="EXCEL")
	{
		
		header('ETag: etagforie7download'); //IE7 requires this header
		header('Content-type: application/vnd.ms-excel');
		//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-disposition: attachment; filename=ReporteTAURUS.xls');
		
		
		
		$html = "<html>	
					<head>
						<meta http-equiv=Content-Type content='text/html; charset=utf-8'>
						<meta name=Generator content='Microsoft Word 11 (filtered)'>
						<style type='text/css'>
							body{
								text-align:left;
								font-size:".$_POST["tamanioLetra"]."px;									
							}
						</style>												
					</head>	
					<body lang=EN-US width='100%' height='100%' align='center'>		
						<center>			
							<table width='100%' >
									<tr width='100%'>
										<td width='150' align='center'>
											
										</td>
										<td width='600' align='center' valign='top' colspan='$numcolumnas'>
											<h2>".$_POST["tituloreporte"]."</h2>
										</td>
										<td width='33%' align='right'><b>".getDiaHora($db)."</b></td>
										<td width='150' valign='bottom' align='right'>
											
										</td>
									</tr>
							</table>";
						
		
		
		$i=0;
		foreach($arrTablas as $pregunta){
			if($i>0) $html .= "<br><br><br><br><br><br>";
			$html .= generaPaginaEXCEL($db,$pregunta);
			$i++;
		}
		
		
		$html .= "</center>
					</body>
					</html>";
		
		print $html;		
	}
	else if($_POST["tiporeporte"]=="CSV")
	{
		
		/*
			$GLOBALS["strPathAbsoluto"]="C:\\Program Files\\Apache Group\\Apache2\\htdocs\\virtual3\\";
			$pathErrorCSV = $GLOBALS["strPathAbsoluto"]."importar_datos/".$_REQUEST['importurl']."/errores.csv"; //path para el csv de errores;						
			$fpErrorCSV = fopen($pathErrorCSV,"wb+"); // resource de archivo para el errores csv
			
			$duplicados.=$columna.(($i!=count($data)-1)?$separador:"\r\n");
			fwrite($fpErrorCSV,"\"".agregarComillas($columna)."\"".(($i!=count($data)-1)?$separador:"\r\n"));
			
			function agregarComillas($cadena){
				$cadena=str_ireplace('"','""',$cadena);
				return $cadena;
			}
		*/

		
		$url_path = "./temp_archivos/";
		$filename = "reporte_".time().".csv";
		$file     = $GLOBALS["temp_archivos_dir"].$filename; //xampp para crear el archivo
		$file_url = $GLOBALS["temp_archivos_url"].$filename; //localhost para descarga de archivo
		$fpCSV    = fopen($file,"wb+"); // resource de archivo para el reporte csv
		
		
		
		
		$tabla_resultados = "";
		foreach($arrTablas as $pregunta){		
			$tabla_resultados .= generaPaginaCSV($db,$pregunta);
		}
		
		
		fwrite($fpCSV, $tabla_resultados);
		fclose($fpCSV);
		
		//JavaScript redirection
		print "<HTML><SCRIPT>document.location='$file_url';</SCRIPT></HTML>";
	}
	
	
	
	

	
	
	
	
	
	
	// --------------------------------------
	//  FUNCIONES
	// --------------------------------------
	
	function generaPaginaEXCEL($db,$pregunta){
		$tabla_resultados  = str_replace("\\\"","'",$pregunta["tabla_resultados"]);
		$tabla_filtros     = str_replace("\\\"","'",$_POST["tabla_filtros"]);
		//$tabla_autores     = str_replace("\'","",$_POST["tabla_autores"]);
		$tabla_autores     = str_replace("\'","",$_POST["tabla_autores"]);
		$tabla_informativa = str_replace("\\\"","'",$pregunta["tabla_informativa"]);		
		
		
		if($_POST["from"]=="Tabla" && $tabla_resultados=="query"){
			$tabla_resultados = generaTablaResultados($db);			
		}
		
		if($pregunta["tipopregunta"]!="" && $pregunta["tipopregunta"]==5){ //Pregunta Abierta
			$tabla_autores = "";
		}
		
		$tablas="";
		if($_POST["txtresultado"]!="") $tablas = $_POST["txtresultado"];
		if($tabla_informativa!=""){
			if($tablas!="") $tablas .= "<br>";
			$tablas .= $tabla_informativa;
		}
		if($tabla_filtros!=""){
			if($tablas!="") $tablas .= "<br>";
			$tablas .= $tabla_filtros;
		}
		if($img_grafica!=""){
			if($tablas!="") $tablas .= "<br>";
			$tablas .= "<img src='$img_grafica' />";
		}
		if($tabla_resultados!=""){
			if($tablas!="") $tablas .= "<br>";
			$tablas .= $tabla_resultados;
		}
		if($tabla_autores!=""){
			if($tablas!="") $tablas .= "<br>";
			$tablas .= $tabla_autores;
		}
		
		
		return $tablas;	
	}
		
	
	//".($_POST["chbCellSpacing"]=="1"?"8":"0")."
	
	function generaTablaResultados($db){
		$db->SetFetchMode(ADODB_FETCH_ASSOC);
		$html   = "";
		$strsql = $_SESSION[$GLOBALS["sistema"]]['strsql_paginacion'];
		//hacerArchivo($strsql);
		if($rs=$db->execute($strsql)){
			//hacerArchivo($strsql);
			$html = "<table class='tabla_resultados' border='1' width='100%' cellpadding='".($_POST["chbCellPadding"]=="1"?"5":"0")."' cellspacing='8'>";	        	
	        

        	//// Headers
        	$columnasHeaders = $_POST["columnasHeaders"];
			if(!is_array($columnasHeaders)){
				print "Error columnasHeaders";
				exit;	
			}
			
			$html .= "<thead>";		
			
        	$headers = "<tr width='100%' bgcolor='#CFCFCF' >";	        				
        	if($_POST["chbNumeroDeRenglon"]=="1") $headers .= "<th align='center'>#</th>";
			foreach ($columnasHeaders as $header){
				if($_POST["tiporeporte"]=="PDF"){
					$headers .= "<th>".textToDB($header)."</th>";				        		
				}else{
					$headers .= "<th>".$header."</th>";				        		
				}				
			}
        	$headers .= "</tr>";
        	$html .= $headers."</thead>";
	        	
			
        	if($_POST["tiporeporte"]!="PDF"){
        		$borderXLS=" border='1' ";
        	}
			
			//// Body
			$html .= "<tbody>";
			$i = 0;
			$initNumeracion = $_POST["initNumeracion"];
			$columnasTabla  = $_POST["columnasTabla"];
			while ($row=$rs->fetchRow()){
				//hacerArchivo(print_r($row));
				$bgcolor = ($_POST["chbBgColor"]=="1" && $i%2!=0) ? " bgcolor='#DEDEDE' " : "";
	        			
        		$html .= "<tr width='100%' $bgcolor $borderXLS>";
        		if( $_POST["chbNumeroDeRenglon"]=="1" ){
        			$html .= "<td width='20' $borderXLS>$initNumeracion</td>";
        			$initNumeracion++;
        		}
        		
        		foreach($columnasTabla as $columna){
        			if($_POST["tiporeporte"]=="PDF"){
        				$html .= "<td $borderXLS>".textToDB($row[$columna])."</td>";
        			}else{
        				$html .= "<td $borderXLS>".textFromDB($row[$columna])."</td>";
        			}
        			//hacerArchivo("<td $borderXLS>".textFromDB($row[$columna])."</td>");
        		}	
        		$html .= "</tr>";
        		$i++;
			}
			//print $initNumeracion;
			
			
			//// Footers
			if($_POST["chbFooter"]=="1"){
				$arrFooters = $_POST["arrFooters"];
				if($arrFooters!="" && is_array($arrFooters) && count($arrFooters)>0){
					$footers = "<tr width='100%' bgcolor='#CFCFCF' >";	        				
		        	if($_POST["chbNumeroDeRenglon"]=="1") $footers .= "<th align='center'>&nbsp;</th>";
					foreach ($arrFooters as $footer){
						if($_POST["tiporeporte"]=="PDF"){
							$footers .= "<th>".textToDB($footer)."</th>";				        		
						}else{
							$footers .= "<th>".$footer."</th>";				        		
						}				
					}
		        	$footers .= "</tr>";
		        	$html .= $footers;
				}
				$html .= $headers;
	        }
	        	
	        $html .= "</tbody></table>";
	        
	        return $html;
		}else{
			print "Error strsql_paginacion: ".$db->errormsg();
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	function generaPaginaPDF($db,$pregunta,$img_grafica=""){
		foreach ($pregunta as $k=>$v) $pregunta[$k]=textToDB($v);
		
		
		$tabla_resultados  = str_replace("\\\"","'",$pregunta["tabla_resultados"]);
		$tabla_filtros     = str_replace("\\\"","'",$_POST["tabla_filtros"]);
		$tabla_autores     = str_replace("\\\"","'",$_POST["tabla_autores"]);
		$tabla_informativa = str_replace("\\\"","'",$pregunta["tabla_informativa"]);
		$tabla_header      = str_replace("\\\"","'",$pregunta["tabla_header"]);
		$tabla_footer      = str_replace("\\\"","'",$pregunta["tabla_footer"]);
		
		$style = "table.tabla_resultados {border:outset 5px; border-collapse:collapse}";
		//$style = "table {border: 2px; }";
			
		if($pregunta["tipopregunta"]!="" && $pregunta["tipopregunta"]==5){ //Pregunta Abierta
			$tabla_autores = "";
		}
		
		
		if($_POST["from"]=="Tabla" && $tabla_resultados=="query"){
			$tabla_resultados = generaTablaResultados($db);			
		}
		$border="";
		if($_POST["chbBorder"]=="1"){
			$border=" border:1px; ";
		}
		
		$numcolumnas = ($pregunta["numcolumnas"]=="" || !is_numeric($pregunta["numcolumnas"])) ? 4 : $_pregunta["numcolumnas"];	
		if($_POST["from"]=="Tabla"){
			if($numcolumnas<=3) $numcolumnas=1;
			else $numcolumnas = $numcolumnas - 2;			
		}
		
		
		$tablas="";
		if($tabla_informativa!="") $tablas = $tabla_informativa;
		if($tabla_filtros!=""){
			if($tablas!="") $tablas .= "<br>";
			$tablas .= $tabla_filtros;
		}
		if($img_grafica!=""){
			if($tablas!="") $tablas .= "<br>";
			$tablas .= "<img src='$img_grafica' />";
		}
		if($tabla_header!=""){
			if($tablas!="") $tablas .= "<br>";
			$tablas .= $tabla_header;
		}
		if($_POST["txtresultado"]!=""){
			if($tablas!="") $tablas .= "<br>";
			$tablas .= textToDB($_POST["txtresultado"]);
		}		
		if($tabla_resultados!=""){
			if($tablas!="") $tablas .= "<br>";
			$tablas .= $tabla_resultados;
		}
		if($tabla_autores!=""){
			if($tablas!="") $tablas .= "<br>";
			$tablas .= $tabla_autores;
		}
		if($tabla_footer!=""){
			if($tablas!="") $tablas .= "<br>";
			$tablas .= $tabla_footer;
		}
		
		return "<html>	
					<head>
						<meta http-equiv=Content-Type content='text/html; charset=utf-8'>
						<meta name=Generator content='Microsoft Word 11 (filtered)'>
						<style type='text/css'>
							body{
								text-align:left;
								font-size:".$_POST["tamanioLetra"]."px;									
							}
							$style
							table.tabla_resultados td {border-left: none; border-right:none; $border }	
							table.tabla_resultados th {font-size:".$_POST["tamanioLetra"]."px }
						</style>												
					</head>	
					<body lang=EN-US width='100%' height='100%' align='center'>		
						<center>			
							<table width='100%' >
									<tr width='100%'>
										<td width='150' align='center'>
											
										</td>
										<td width='600' align='center' valign='top' colspan='$numcolumnas'>
											<br>&nbsp;</br>
											<h1>".textToDB($_POST["tituloreporte"])."</h1s>
											<br>&nbsp;</br>
										</td>
										<td width='150' valign='bottom' align='right'>
											
										</td>
									</tr>
							</table>	
							$tablas	
						</center>
					</body>
					</html>";
	
			/*
			<br>
							$tabla_informativa		
							<br>	
							$tabla_filtros
							".($img_grafica != "" ? "<img src='$img_grafica' />" : "")."	
							<br>
							".textToDB($_POST["txtresultado"])."
							$tabla_resultados
							<br>
							$tabla_autores
							*/
	}
	
	
	
	
	// --------------------------------
	// 		CSV
	// --------------------------------	
	function generaPaginaCSV($db,$pregunta){
		$db->SetFetchMode(ADODB_FETCH_ASSOC);
		// Generar tabla de resultados en base al query
		if($_POST["from"]=="Tabla" && $pregunta["tabla_resultados"]=="query"){			
			return generaTablaResultadosCSV($db);			
		}
		
		//Cuando el resultado es enviado desde Flex (cuando en el DG existe algun labelfunction) hacer textToDB() para poner texto con acentos y caracteres especiales
		return textToDB($pregunta["tabla_resultados"]);		
	}

	
	
	function agregarComillas($cadena){
		$cadena=str_ireplace('"','""',$cadena);
		return $cadena;
	}
	
	function generaTablaResultadosCSV($db){
		$separador = ",";
		$tabla_resultados = "";
		
		$strsql = $_SESSION[$GLOBALS["sistema"]]['strsql_paginacion'];
		if($rs=$db->execute($strsql)){
			
			
        	//// Headers
        	$columnasHeaders = $_POST["columnasHeaders"];
			if(!is_array($columnasHeaders)){
				print "Error columnasHeaders";
				exit;	
			}
			
			if($_POST["chbNumeroDeRenglon"]=="1"){
				$tabla_resultados .= "\"".agregarComillas("#")."\"".$separador;								
			}
			
			
			$i = 0;
			foreach ($columnasHeaders as $header){
				$tabla_resultados .= "\"".agregarComillas(textToDB($header))."\"".(($i!=count($columnasHeaders)-1)?$separador:"\r\n");								
				$i++;				
			}
			
			
			
			//// Body
			$initNumeracion = $_POST["initNumeracion"];
			$columnasTabla  = $_POST["columnasTabla"];			
			while ($row=$rs->fetchRow()){
				if($_POST["chbNumeroDeRenglon"]=="1" ){
        			$tabla_resultados .= "\"".agregarComillas($initNumeracion)."\"".$separador;
        			$initNumeracion++;
        		}
        		
        		$i = 0;
        		foreach($columnasTabla as $columna){
        			$tabla_resultados .= "\"".agregarComillas($row[$columna])."\"".(($i!=count($columnasTabla)-1)?$separador:"\r\n");
        			$i++;
        		}	
			}
			
			
			
			
			//// Footers
			if($_POST["chbFooter"]=="1"){
				if($_POST["chbNumeroDeRenglon"]=="1"){
					$tabla_resultados .= "\"".agregarComillas("#")."\"".$separador;								
				}
				
				
				$i = 0;
				foreach ($columnasHeaders as $header){
					$tabla_resultados .= "\"".agregarComillas(textToDB($header))."\"".(($i!=count($columnasHeaders)-1)?$separador:"\r\n");								
					$i++;				
				}
	        }
	        
	        return $tabla_resultados;
	        
		}else{
			print "Error strsql_paginacion: ".$db->errormsg();
			exit;
		}
	}
	// --------------------------------
	// 		CSV
	// --------------------------------	

	
	
	
	
	
	
	
	
	
	
	
	function generaJPG($img){
		list($usec, $sec) = explode(" ", microtime());
		$usec=str_replace(".","",$usec);	
		$file=tempnam($GLOBALS["temp_archivos_dir"],'tmp');
		$singlefile=basename($file."_$usec$sec.jpg");
		//rename($file,$file."_$usec$sec.jpg");
		rename($file,"img_$usec$sec.jpg");
		$file.="_$usec$sec.jpg";	
		
		if (!$handle = fopen($file,'w')) {
			echo "Cannot open file ($file)";
			exit;
		}
		if (fwrite($handle, $img) === FALSE) {
			echo "Cannot write to file ($file)";
			exit;
		}

		fclose($handle);
		
		chown($file,"ftpjnemer");
		chmod($file,0777);

		return $GLOBALS["temp_archivos_dir"]."/$singlefile";
	}
	
	
	function getDiaHora($db){
		$db->SetFetchMode(ADODB_FETCH_ASSOC);
		$m = date(m) -1;
		$ma= array(Enero,Febrero,Marzo,Abril,Mayo,Junio,Julio,Agosto,Septiembre,Octubre,Noviembre,Diciembre) ;
		$fecha = date("d")." de $ma[$m] del ".date("Y"); 
			
		//2009-12-09	09:08 AM
		//$strsql="SELECT date(now()) as dia,to_char(now(), 'HH:MI PM') AS hora ";	
		$strsql="SELECT date(now()) as dia,convert (text,now(), 0) AS hora";	
		$rs=$db->execute($strsql);
		
		if($rs){		
			$row=$rs->fetchrow();		
			$dia=$row["dia"];
			$hora=substr($row["hora"],12);
			$dia=explode("-",$dia);		
			//print ($hora);
			//hacerArchivo($hora);
			return $dia[2]." de ".$ma[$dia[1]-1]." del ".$dia[0]." - ".$hora;				
		}else{
			return "-1";
		}	
	}
	
	function getDiaHoraBuro($fecha){
		list($year, $month, $day)=split('[/.-]',$fecha);
		hacerArchivo($fecha);
		return "Creado de la fecha: $day de $month del $year";
	}
	
?>