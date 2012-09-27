<?php
	if($db && $db->errormsg() == "Database connection failed"){
		print "<?xml version='1.0' encoding='UTF-8'?>\n
				<tablas>
					<error_cnx>\n
						<query>Error de Conexion</query>\n
						<msg>Problema al conectar a la base de datos</msg>\n
					</error_cnx>
			   </tablas>";
		exit;
	}
	
	if($db && $db->errormsg()!=""){
		print "<?xml version='1.0' encoding='UTF-8'?>\n
				<tablas>
					<error>\n
						<query>Error de Conexion</query>\n
						<msg>".$db->errormsg()."</msg>\n
					</error>
			   </tablas>";
		exit;
	}
	
	function getXMLErrorMsg($msg){
		return "<?xml version='1.0' encoding='UTF-8'?>\n
				<tablas>
					<error>\n
						<query>Error</query>\n
						<msg>$msg</msg>\n
					</error>
			   </tablas>";
	}
	
	require_once('libs/class.rc4crypt.php');
	
	function decrypt($data, $key){		
		return (rc4crypt::decrypt($key, pack("H*", $data)));
	}
	 
	function encrypt($data, $key){
		return (bin2hex(rc4crypt::encrypt($key, $data)));
	}

	function getSession(){
		$session = "";
		$session .= "<sesiones>\n";
		foreach ($_SESSION[$GLOBALS["sistema"]] as $campo=>$valor){		
			$session .= "\t<variable nombre='".textFromDB($campo)."' valor='".textFromDB($valor)."'/>\n";			
		}
		$session .= "</sesiones>\n";
		
		return $session;
	}
	
	function getFechaSistema($fecha) {		
		switch($GLOBALS["formato_fecha_entrada_bdd"]) {
			case "dd/mm/aaaa" :{ // es de tipo dd/mm/aaaa
					list($dia,$mes,$anio)=split('[/.-]',$fecha);
					break;
			}
			case "mm/dd/aaaa":{ // es de tipo mm/dd/aaaa
					list($mes,$dia,$anio)=split('[/.-]',$fecha);
					break;
			}
			case "aaaa/mm/dd":{ // es de tipo aaaa/mm/dd	
					list($anio,$mes,$dia)=split('[/.-]',$fecha);
					break;
			}
			case "aaaa/dd/mm":{ // es de tipo aaaa/dd/mm
					list($anio,$dia,$mes)=split('[/.-]',$fecha);
					break;	
			}
			case "aaaa-mm-dd":{ // es de tipo aaaa/dd/mm
					list($anio,$mes,$dia)=split('[/.-]',$fecha);
					break;	
			}
		}
		switch($GLOBALS["formato_fecha_salida_mostrar"]){
			case "dd/mm/aaaa" :{ // es de tipo dd/mm/aaaa
				if(($dia.$GLOBALS["separador_fecha"].$mes.$GLOBALS["separador_fecha"].$anio)=="//"){
					return "";
					break;
				}			
				return $dia.$GLOBALS["separador_fecha"].$mes.$GLOBALS["separador_fecha"].$anio;
				break;
			}			
			case "mm/dd/aaaa":{ // es de tipo mm/dd/aaaa
				return $mes.$GLOBALS["separador_fecha"].$dia.$GLOBALS["separador_fecha"].$anio;
				break;
			}			
			case "aaaa/mm/dd":{ // es de tipo aaaa/mm/dd
				return $anio.$GLOBALS["separador_fecha"].$mes.$GLOBALS["separador_fecha"].$anio;
				break;
	
			}			
			case "aaaa/dd/mm":{ // es de tipo aaaa/dd/mm
				return $anio.$GLOBALS["separador_fecha"].$dia.$GLOBALS["separador_fecha"].$mes;
				break;
			}
			case "aaaa-mm-dd":{ // es de aaaa-mm-dd
					return $anio.$GLOBALS["separador_fecha"].$mes.$GLOBALS["separador_fecha"].$dia;
					break;
			}
		}
	}
	
	function getIdActual(&$db,$strSecuencia){
		// REGRESA EL VALOR ACTUAL DE LA SECUENCIA INDICADA
		$id=0;
		$strSql="select currval('$strSecuencia') as id";
		
		$rs=$db->Execute($strSql);
		if($rs){
			if(($row=$rs->fetchRow())){
				$id=$row["id"];
			}
		}
		return $id;
	}

	function getXML($strsql, &$db, $limit=-1, $offset=0, $showRows=true, $paginacion=0, $orderbyD){
		//$db->SetFetchMode(ADODB_FETCH_ASSOC);
		//print "limit: ".$limit;
		$xml = "";
		$arrQuerys = explode(";",cleanQuery($strsql));
		
		$xml .= "<?xml version='1.0' encoding='UTF-8'?>\n";
		$xml .= "<tablas>\n";
		//print_r($arrQuerys);
		foreach($arrQuerys as $tabla => $query){
			$numrows = getNumRows($query, $db);
			
			if ($paginacion==2) {
				//$query2 = "SELECT ".$query;
				$numrows = getNumRows($query, $db);
				$strsql="SELECT TOP $limit 
					START AT $offset * FROM(".$query.") as tt ORDER BY $orderbyD";
				$query = $strsql;
//				hacerArchivo($query);
			}else {
				if($limit !== -1){
					$query=$query;
				}
			}
			//hacerArchivo($query);
			$rs=$db->execute($query);
		
			if ($rs){
				$xml .= "\t<tabla$tabla>\n";
				$xml .= "\t\t<numrows total='$numrows' limit='$limit' offset='$offset'/>\n"; 
				
				if($showRows){
					while ($row=$rs->fetchRow()){
						$xml .= "\t\t<rows";
						foreach($row as $campo=>$valor){
							if (!is_int($campo)){
								if(strpos($campo,"fecha")!==false){
									$xml .= " $campo='".textFromDB(getFechaSistema($valor))."'";//textFromDB
								}
								else{
									$xml .= " $campo='".textFromDB($valor)."'";//textFromDB
								}
							}
						}
						$xml .= "/>\n";
					}				
				}
				$xml .= "\t</tabla$tabla>\n";
			}
			else{					
				$xml .= "\t<error>\n";
				$xml .= "\t\t<query>Error en Query</query>\n";	//print "\t\t<query>".$query.".</query>\n";
				$xml .= "\t\t<msg>".$db->ErrorMsg().".</msg>\n";
				$xml .= "\t</error>\n";
				//print getMessage("Mensaje de Error: ".$db->errormsg()."\nQuery: ".$query); //TODO
				//exit;
			}			
		}
		$xml .= "</tablas>\n";
		
		//if( (isset($_POST["encrypted"]) && $_POST["encrypted"] === true) || (isset($_GET["encrypted"]) && $_GET["encrypted"] === true) ){ // SI NOS LLEGA REQUEST CIFRADO
			if(($GLOBALS["debug_level"] & DBG_LVL_3) > 0){ // SI TENEMOS DEBUG NIVEL 3 SIGNIFICA QUE NO VAMOS A CIFRAR LA SALIDA
				return $xml;
			}
			else{
				return "<tablas>\n\t<encrypted>".encrypt($xml, $GLOBALS["RC4_KEY"])."</encrypted>\n</tablas>";
			}
		/*}
		else{
			return $xml;
		}*/
	}
	
	
	
	
	function getMessage($msg){//TODO
		print $msg;
	}
	
	function cleanQuery($query){
		$query = trim($query);			
		if ($query[strlen($query)-1] == ";"){
			return substr($query,0,strlen($query)-1);
		}
		else{
			return $query;
		}
	}
	
	function getInfoTabla($tabla,&$db){
		$strsql = "SELECT a.attname AS column, a.attnotnull as notnull, pg_catalog.format_type(a.atttypid, a.atttypmod) AS datatype FROM pg_catalog.pg_attribute a WHERE a.attnum>0 AND NOT a.attisdropped AND a.attrelid = (SELECT c.oid FROM pg_catalog.pg_class c LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace WHERE c.relname ~'^($tabla)$' AND pg_catalog.pg_table_is_visible(c.oid));";		
		$arrCols = array();		
		$rs = $db->Execute($strsql);	
		if($rs){
			while($row=$rs->fetchRow()){
				$columna = $row['column'];
				$tipodedato = $row['datatype'];
				$notnull = $row['notnull']=='t'?1:0;	
				$strsql = "SELECT adsrc as default_value FROM pg_attrdef pad, pg_attribute pat, pg_class pc WHERE pc.relname='$tabla' AND pc.oid=pat.attrelid AND pat.attname='$columna' AND pat.attrelid=pad.adrelid AND pat.attnum=pad.adnum";
				$rs2 = $db->Execute($strsql);
				if($rs2){
					if($row2=$rs2->fetchRow()){
						array_push($arrCols,array("nombre"=>$columna,"default"=>$row2['default_value'],"notnull"=>$notnull,"tipo"=>$tipodedato));
					}
					else{
						array_push($arrCols,array("nombre"=>$columna,"notnull"=>$notnull,"tipo"=>$tipodedato));
					}
				}
			}
		}
		return $arrCols;
	}
	
	function getInfoTablaXML($tabla,&$db){
		$strsql = "SELECT a.attname AS column, a.attnotnull as notnull, pg_catalog.format_type(a.atttypid, a.atttypmod) AS datatype FROM pg_catalog.pg_attribute a WHERE a.attnum>0 AND NOT a.attisdropped AND a.attrelid = (SELECT c.oid FROM pg_catalog.pg_class c LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace WHERE c.relname ~'^($tabla)$' AND pg_catalog.pg_table_is_visible(c.oid));";		
		$arrCols = array();		
		$rs = $db->Execute($strsql);	
		if($rs){
			print "<infotabla>\n";
			while($row=$rs->fetchRow()){
				$columna = $row['column'];
				$tipodedato = $row['datatype'];
				$notnull = $row['notnull']=='t'?1:0;	
				$strsql = "SELECT adsrc as default_value FROM pg_attrdef pad, pg_attribute pat, pg_class pc WHERE pc.relname='$tabla' AND pc.oid=pat.attrelid AND pat.attname='$columna' AND pat.attrelid=pad.adrelid AND pat.attnum=pad.adnum";
				$rs2 = $db->Execute($strsql);
				if($rs2){					
					if($row2=$rs2->fetchRow()){								
						print "\t<columna nombre='".textFromDB($columna)."' default='".textFromDB($row2['default_value'])."' notnull='".textFromDB($notnull)."' tipo='".textFromDB($tipodedato)."'/>\n";
					}
					else{
						print "\t<columna nombre='$columna' notnull='$notnull' tipo='$tipodedato'/>\n";
					}
				}
			}
			print "</infotabla>\n";
		}
	}
	
	function getColumnasTabla($tabla, &$db){
		$strsql = "SELECT t1.column,t1.datatype,t1.notnull,t2.adsrc as default_value  from (SELECT a.attrelid, a.attnum, a.attname AS column, a.attnotnull as notnull, pg_catalog.format_type(a.atttypid, a.atttypmod) AS datatype FROM pg_catalog.pg_attribute a WHERE a.attnum>0 AND NOT a.attisdropped AND a.attrelid = (SELECT c.oid FROM pg_catalog.pg_class c LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace WHERE c.relname ~'^($tabla)$' AND pg_catalog.pg_table_is_visible(c.oid)))t1 left join pg_attrdef t2 on t2.adnum=t1.attnum and t1.attrelid=t2.adrelid order by t1.column";
		$arrCols = array();		
		$rs = $db->Execute($strsql);	
		if($rs){
			while($row=$rs->fetchRow()){
				$columna = textFromDB($row['column']);
				$tipodedato = textFromDB($row['datatype']);
				$notnull = $row['notnull']=='t'?TRUE:FALSE;	
				
				array_push($arrCols,array("nombre"=>$columna,"default"=>$row['default_value'],"notnull"=>$notnull,"tipo"=>$tipodedato));
			}
		}
		return $arrCols;
	}

	function getNumRows($query, &$db){
		$pre = "select count(*) as total from (";
		$pos = ") x";	
		if(($rs=$db->execute($pre.$query.$pos))){
			while($row=$rs->fetchrow()){
				return textFromDB($row["total"]);
			}
		}	
		else{
			return $db->ErrorMsg()." ".$query;
		}
	}
	
	function insertCat($arreglo,$tabla,&$db){
		$strsql="insert into $tabla (";
		foreach ($arreglo as $campo=>$valor){
			if($campo!="opcion"){
				$strsql.=$campo.",";
			}
		}
		$sql = substr_replace($strsql,"",-1);
		$sql.=") values (";
		foreach ($arreglo as $campo=>$valor){
			if($campo!="opcion"){
				$sql.="'".textToDB($valor)."',";
			}
		}
		$query=substr_replace($sql,"",-1);
		$query.=")";
		if($rs=$db->Execute($query)){
			print "ok";
		}
		else{
			print "Error:\n".$db->ErrorMsg();
		}
	}
	
	function updateCat($arreglo,$id,$tabla,&$db){
		$strsql="update $tabla set ";
		foreach ($arreglo as $campo=>$valor){
			if(($campo!="opcion")&&($campo!=$id)){
				$strsql.=$campo."="."'".textToDB($valor)."',";
			}
		}
		$sql = substr_replace($strsql,"",-1);
		$sql.=" where $id='".textToDB($_POST[$id])."'";
		if($rs=$db->Execute($sql)){
			print "ok";
		}
		else{
			print "Error:\n".$db->ErrorMsg();
		}
	}
	
	function deleteCat($id,$tabla,&$db){
		$strsql="delete from $tabla where $id='".textToDB($_POST[$id])."' ";
		if($rs=$db->Execute($strsql)){
			print "ok";
		}
		else{
			print "Error:\n".$db->ErrorMsg();
		}
	}
		
	function filtrarArreglo($datosPost, $datosInvalidos){
		$nuevosDatos=array();
		if(is_array($datosPost)){
			if(is_array($datosInvalidos)){
				foreach ($datosPost as $campo=>$datoPost){
					$encontrado=false;
					foreach ($datosInvalidos as $datoInvalido){
						if($datoInvalido==$campo){
							$encontrado=true;
						}
					}
					if(!$encontrado){
						$nuevosDatos[$campo]=$datoPost;
					}
				}
				return $nuevosDatos; 	
			}
			else{
				return $datosPost;
			}
		}
		else{
			return $nuevosDatos;
		}
	}
	
	function generateQuery($tipoQuery,$arreglo,$tabla,$identificador='',$datosInvalidos=array('opcion'),$textToDB=true,$camposWhere=array()){
		$datosValidos=filtrarArreglo($arreglo,$datosInvalidos);
		
		//HEADERS
		$queryInsert="insert into $tabla (";
		$queryUpdate="update $tabla set ";
		
		$contElem=0;
		
		foreach ($datosValidos as $campo=>$valor){
			$contElem+=1;
			if($contElem==count($datosValidos)){
				$queryInsert.="\"".$campo."\"";
			}
			else{
				$queryInsert.="\"".$campo."\",";
			}
		}
		
		$queryInsert.=") values (";
		
		$contElem=0;
		
		foreach ($datosValidos as $campo=>$valor){
			$contElem+=1;
			if($contElem==count($datosValidos)){
				//INSERT
				if($textToDB){
					$queryInsert.=trim($valor)==""?'NULL':"'".(textToDB($valor))."'";
				}
				else{
					$queryInsert.=trim($valor)==""?'NULL':"'".($valor)."'";
				}
				
				//UPDATE
				//$queryUpdate.=$campo."=";
				$queryUpdate.="\"".$campo."\""."=";
				if($textToDB){
					$queryUpdate.=trim($valor)==""?"NULL":"'".(textToDB($valor))."' ";				
				}
				else{
					$queryUpdate.=trim($valor)==""?"NULL":"'".($valor)."' ";
				}	
				
			}
			else{
				//INSERT
				if($textToDB){
					$queryInsert.=trim($valor)==""?'NULL,':"'".(textToDB($valor))."',";
				}
				else{
					$queryInsert.=trim($valor)==""?'NULL,':"'".($valor)."',";
				}
				
				//UPDATE
				//$queryUpdate.=$campo."=";
				$queryUpdate.="\"".$campo."\""."=";
				if($textToDB){
					$queryUpdate.=trim($valor)==""?"NULL,":"'".(textToDB($valor))."', ";				
				}
				else{
					$queryUpdate.=trim($valor)==""?"NULL,":"'".($valor)."', ";				
				}
			}
			
		}
		
		$queryInsert.=")";
		$queryDelete ="delete from $tabla";
		
		if(count($camposWhere)>0){
			$queryUpdate.=" where ";
			$queryDelete.=" where ";
			foreach ($camposWhere as $indice=>$campoWhere){
				if($indice==0){
					if($textToDB){
						if(is_array($campoWhere )){
							"\"".$campo."\""."=";
							$queryUpdate.="\"".$campoWhere[0]."\" ".$campoWhere[1]." '".textToDB($datosValidos[$campoWhere[0]])."' ";
							$queryDelete.="\"".$campoWhere[0]."\" ".$campoWhere[1]." '".textToDB($datosValidos[$campoWhere[0]])."' ";
						}
						else{
							$queryUpdate.="\"$campoWhere\"='".textToDB($datosValidos[$campoWhere])."' ";
							$queryDelete.="\"$campoWhere\"='".textToDB($datosValidos[$campoWhere])."' ";		
						}
						
					}
					else{
						if(is_array($campoWhere )){
							$queryUpdate.="\"".$campoWhere[0]."\" ".$campoWhere[1]." '".$datosValidos[$campoWhere[0]]."' ";
							$queryDelete.="\"".$campoWhere[0]."\" ".$campoWhere[1]." '".$datosValidos[$campoWhere[0]]."' ";
						}
						else{
							$queryUpdate.="\"$campoWhere\"='".$datosValidos[$campoWhere]."' ";
							$queryDelete.="\"$campoWhere\"='".$datosValidos[$campoWhere]."' ";
						}
					}
				}
				else{
					if($textToDB){
						if(is_array($campoWhere )){
							$queryUpdate.="and \"".$campoWhere[0]."\" ".$campoWhere[1]." '".textToDB($datosValidos[$campoWhere[0]])."' ";
							$queryDelete.="and \"".$campoWhere[0]."\" ".$campoWhere[1]." '".textToDB($datosValidos[$campoWhere[0]])."' ";
						}
						else{
							$queryUpdate.="and \"$campoWhere\"='".textToDB($datosValidos[$campoWhere])."' ";
							$queryDelete.="and \"$campoWhere\"='".textToDB($datosValidos[$campoWhere])."' ";
						}
					}
					else{
						if(is_array($campoWhere)){
							$queryUpdate.="and \"".$campoWhere[0]."\" ".$campoWhere[1]." '".$datosValidos[$campoWhere[0]]."' ";
							$queryDelete.="and \"".$campoWhere[0]."\" ".$campoWhere[1]." '".$datosValidos[$campoWhere[0]]."' ";
						}
						else{
							$queryUpdate.="and \"$campoWhere\"='".$datosValidos[$campoWhere]."' ";
							$queryDelete.="and \"$campoWhere\"='".$datosValidos[$campoWhere]."' ";
						}
					}
				}
			}			
		}
		else{
			if($textToDB){
				$queryUpdate.=" where \"$identificador\"='".textToDB($datosValidos[$identificador])."'";
				$queryDelete.=" where \"$identificador\"='".textToDB($datosValidos[$identificador])."'";
			}
			else{
				$queryUpdate.=" where \"$identificador\"='".$datosValidos[$identificador]."'";
				$queryDelete.=" where \"$identificador\"='".$datosValidos[$identificador]."'";
			}
		}
		
		switch (strtolower(trim($tipoQuery))){
			case 'insert':return $queryInsert;
			case 'update':return $queryUpdate;
			case 'delete':return $queryDelete;
			default: return "";
		}
		
	}
	
	
	
	function getWordsQuery($query){
		$parAbierto=0;
		$comillaDoble=0;
		$comillaSimple=0;
		$arrPalabras=array();
		$palabra='';
		
		$arrayChars = preg_split('//', trim($query), -1, PREG_SPLIT_NO_EMPTY);
		
		foreach($arrayChars as $index=>$char){
			switch ($char){				
				case " ":
					if(trim($palabra)!='' && $parAbierto==0 && $comillaDoble==0 && $comillaSimple==0){
						array_push($arrPalabras,trim($palabra));
						$palabra="";
						//hacerArchivoAppend("Entre case 1 IF".$char."\r\n");
					}
					else{
						//hacerArchivoAppend("Entre case 1 ELSE".$char."\r\n");
						$palabra.=$char;
					}
				break;				
				case "\n":
					//hacerArchivoAppend("Entre case 2".$char."\r\n");
					if(trim($palabra)!='' && $parAbierto==0 && $comillaDoble==0 && $comillaSimple==0){
						array_push($arrPalabras,trim($palabra));
						$palabra="";
					}
					else{
						$palabra.=$char;
					}					
				break;				
				case "\r":
					//hacerArchivoAppend("Entre case 3".$char."\r\n");
					if(trim($palabra)!='' && $parAbierto==0 && $comillaDoble==0 && $comillaSimple==0){
						array_push($arrPalabras,trim($palabra));
						$palabra="";
					}
					else{
						$palabra.=$char;
					}					
				break;				
				case "(": 
					if($comillaDoble==0 && $comillaSimple==0){
						//hacerArchivoAppend("Entre case 4 IF".$char."\r\n");
						if($parAbierto==0 && trim($palabra)!=''){
							array_push($arrPalabras,trim($palabra));
							$palabra=$char;
						}
						else{
							$palabra.=$char;
						}
						$parAbierto+=1;
					}
					else{
						//hacerArchivoAppend("Entre case 4 ELSE".$char."\r\n");
						$palabra.=$char;
					}					
				break;				
				case ")": 
					if($comillaDoble==0 && $comillaSimple==0){
						//hacerArchivoAppend("Entre case 5 IF".$char."\r\n");
						$parAbierto-=1;
						if($parAbierto==0){
							$palabra.=$char;
							array_push($arrPalabras,trim($palabra));
							$palabra='';
						}
						else{
							$palabra.=$char;
						}
						
					}
					else{
						//hacerArchivoAppend("Entre case 5 ELSE".$char."\r\n");
						$palabra.=$char;
					}
				break;				
				case "\"": 
					if($parAbierto==0 && $comillaSimple==0){
						//hacerArchivoAppend("Entre case 6 IF".$char."\r\n");
						$comillaDoble=$comillaDoble==0?1:0;
						if(trim($palabra)!='' & $comillaDoble==0){
							$palabra.=$char;
							array_push($arrPalabras,trim($palabra));
							$palabra='';
						}
						else{
							$palabra.=$char;
						}						
					}
					else{
						//hacerArchivoAppend("Entre case 6 ELSE".$char."\r\n");
						$palabra.=$char;
					}
				break;				
				case "'":
					if($parAbierto==0 && $comillaDoble==0){
						//hacerArchivoAppend("Entre case 7 IF".$char."\r\n");
						$comillaSimple=$comillaSimple==0?1:0;
						if(trim($palabra)!='' & $comillaSimple==0){
							$palabra.=$char;
							array_push($arrPalabras,trim($palabra));
							$palabra='';
						}
						else{
							$palabra.=$char;	
						}
					}
					else{
						//hacerArchivoAppend("Entre case 7 ELSE".$char."\r\n");
						$palabra.=$char;
					}					
				break;				
				case "=":
					if($parAbierto==0 && $comillaDoble==0 && $comillaSimple==0){
						//hacerArchivoAppend("Entre case 7 IF".$char."\r\n");
						if(trim($palabra)!=''){
							array_push($arrPalabras,trim($palabra));
						}
						array_push($arrPalabras,trim($char));
						$palabra='';
					}
					else{
						//hacerArchivoAppend("Entre case 7 ELSE".$char."\r\n");
						$palabra.=$char;
					}					
				break;				
				case ",":
					if($parAbierto==0 && $comillaDoble==0 && $comillaSimple==0){
						//hacerArchivoAppend("Entre case 7 IF".$char."\r\n");
						if(trim($palabra)!=''){
							array_push($arrPalabras,trim($palabra));
						}
						array_push($arrPalabras,trim($char));
						$palabra='';
					}
					else{
						//hacerArchivoAppend("Entre case 7 ELSE".$char."\r\n");
						$palabra.=$char;
					}					
				break;				
				default: $palabra.=$char; break;
			}
		}
		if(trim($palabra)!=''){
			array_push($arrPalabras,trim($palabra));
		}

		return $arrPalabras;
	}
	
	function regexRFC($string, $homoclave=1) {
		switch($homoclave){
			case 0:{
				$regex = '/(^[A-Za-z]{4}|^[A-Za-z]{3})\d{6}$/'; //sin homoclave
			}
			break;
			case 1:{
				$regex = '/(^[A-Za-z]{4})\d{6}[A-Za-z\d]{3}$/'; //con homoclave
			}
			break;
			case 2:{
				$regex = '/(^[A-Za-z]{4}|^[A-Za-z]{3})\d{6}(?:[A-Za-z\d]{3})?$/'; //con o sin homoclave
			}
			break;
		}
		return preg_match($regex, $string);	
	}
	
	function regexCURP($string) {
		$regex = '/[a-zA-Z]{4}([0-9]{6})([a-zA-Z]{6})([0-9]{2})$/';
		return preg_match($regex, $string);	
	}
	
	function textFromDB($in_str){
	  	$newString="";
		if(detect_encoding($in_str)=="utf-8"){
			$newString=htmlspecialchars(html_entity_decode(htmlspecialchars_decode(($in_str))));
			if(detect_encoding($newString)!="utf-8"){
				$newString=htmlspecialchars(str_replace("'","\"",html_entity_decode(str_replace("â€œ","\"",str_replace("â€?","\"",htmlspecialchars_decode($in_str))))));
				return utf8_encode($newString);	
			}
			else{
				return $newString;
			}
		}
		else{
		
			$newString=htmlspecialchars(str_replace("'","\"",html_entity_decode(str_replace("â€œ","\"",str_replace("â€?","\"",htmlspecialchars_decode($in_str))))));		
			return utf8_encode($newString);
		}	  
	} 
		
	function textToDB($str){
		if(detect_encoding($str)=="utf-8"){
			return utf8_decode(str_replace("'","\"",($str)));
		}
		else{
			return $str;
		}
	}
	
    function detect_encoding($string) { 
		//print "str: " . $string;
		//if(is_array($string)) print_r($string);
		static $list = array('utf-8','iso-8859-1','windows-1251');////TRANSLIT
	  	foreach ($list as $item) {
	    	$sample = iconv($item, $item, $string);
	    	if (md5($sample) == md5($string))
	      		return $item;
	  		}
	  	return null;
	}

	function object_to_array($obj) {
	    $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
	    foreach ($_arr as $key => $val) {
	            $val = (is_array($val) || is_object($val)) ? object_to_array($val) : $val;
	            $arr[$key] = $val;
	    }
	    return $arr;
	}
	
	function array_push_assoc(&$stack, $var, $assocstring){
		$tmp = array($assocstring=>$var);
		$stack = array_merge($stack,$tmp);
	}
	
	function generarPdf(&$pdf, $urlpath){
		$file = $GLOBALS["temp_archivos_dir"]."/"."reporte_".time().".pdf";
		if($urlpath[strlen($urlpath)-1] == "/"){
			$file_url = $urlpath.basename($file);
		}else{
			$file_url = $urlpath."/".basename($file);	
		}
		
		//Save PDF to file		
		$pdf->Output($file);
		
		//JavaScript redirection
		print "<HTML><SCRIPT>document.location='$file_url';</SCRIPT></HTML>";
	}
	
	function xml2array($contents, $get_attributes=1, $priority = 'tag') {
	    if(!$contents) return array();
	
	    if(!function_exists('xml_parser_create')) {
	        //print "'xml_parser_create()' function not found!";
	        return array();
	    }
	
	    //Get the XML parser of PHP - PHP must have this module for the parser to work
	    $parser = xml_parser_create('');
	    xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
	    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
	    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
	    xml_parse_into_struct($parser, trim($contents), $xml_values);
	    xml_parser_free($parser);
	
	    if(!$xml_values) return;//Hmm...
	
	    //Initializations
	    $xml_array = array();
	    $parents = array();
	    $opened_tags = array();
	    $arr = array();
	
	    $current = &$xml_array; //Refference
	
	    //Go through the tags.
	    $repeated_tag_index = array();//Multiple tags with same name will be turned into an array
	    foreach($xml_values as $data) {
	        unset($attributes,$value);//Remove existing values, or there will be trouble
	
	        //This command will extract these variables into the foreach scope
	        // tag(string), type(string), level(int), attributes(array).
	        extract($data);//We could use the array by itself, but this cooler.
	
	        $result = array();
	        $attributes_data = array();
	        
	        if(isset($value)) {
	            if($priority == 'tag') $result = $value;
	            else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
	        }
	
	        //Set the attributes too.
	        if(isset($attributes) and $get_attributes) {
	            foreach($attributes as $attr => $val) {
	                if($priority == 'tag') $attributes_data[$attr] = $val;
	                else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
	            }
	        }
	
	        //See tag status and do the needed.
	        if($type == "open") {//The starting of the tag '<tag>'
	            $parent[$level-1] = &$current;
	            if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
	                $current[$tag] = $result;
	                if($attributes_data) $current[$tag. '_attr'] = $attributes_data;
	                $repeated_tag_index[$tag.'_'.$level] = 1;
	
	                $current = &$current[$tag];
	
	            } else { //There was another element with the same tag name
	
	                if(isset($current[$tag][0])) {//If there is a 0th element it is already an array
	                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
	                    $repeated_tag_index[$tag.'_'.$level]++;
	                } else {//This section will make the value an array if multiple tags with the same name appear together
	                    $current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
	                    $repeated_tag_index[$tag.'_'.$level] = 2;
	                    
	                    if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
	                        $current[$tag]['0_attr'] = $current[$tag.'_attr'];
	                        unset($current[$tag.'_attr']);
	                    }
	
	                }
	                $last_item_index = $repeated_tag_index[$tag.'_'.$level]-1;
	                $current = &$current[$tag][$last_item_index];
	            }
	
	        } elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
	            //See if the key is already taken.
	            if(!isset($current[$tag])) { //New Key
	                $current[$tag] = $result;
	                $repeated_tag_index[$tag.'_'.$level] = 1;
	                if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data;
	
	            } else { //If taken, put all things inside a list(array)
	                if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...
	
	                    // ...push the new element into that array.
	                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
	                    
	                    if($priority == 'tag' and $get_attributes and $attributes_data) {
	                        $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
	                    }
	                    $repeated_tag_index[$tag.'_'.$level]++;
	
	                } else { //If it is not an array...
	                    $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
	                    $repeated_tag_index[$tag.'_'.$level] = 1;
	                    if($priority == 'tag' and $get_attributes) {
	                        if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
	                            
	                            $current[$tag]['0_attr'] = $current[$tag.'_attr'];
	                            unset($current[$tag.'_attr']);
	                        }
	                        
	                        if($attributes_data) {
	                            $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
	                        }
	                    }
	                    $repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
	                }
	            }
	
	        } elseif($type == 'close') { //End of tag '</tag>'
	            $current = &$parent[$level-1];
	        }
	    }
	    
	    return($xml_array);
	}
	
	function hacerArchivo($string){
		$fp=fopen("archivo.txt",'w');
		fwrite($fp,$string);
		fclose($fp);
	}
	
	function hacerArchivoAppend($string){
		$fp=fopen("archivoa.txt",'a');
		fwrite($fp,$string);
		fclose($fp);
	}
	
	
	
	
	
	
	function getPermisosDelUsuario(&$db, $user){ //username, ya no se usa el iduser
		if(!isset($_SESSION[$GLOBALS["sistema"]]['permisos_user'])||true){ // ||true
			$permisos_user=array();
			//$strsql="select * from tbpermisos_disponibles where clavepermiso in (select clavepermiso from tbpermisos_asignados where nombreusuario = '".textToDB($user)."') ";
			$strsql="select * from tbpermisos_disponibles where clavepermiso in (select clavepermiso from tbpermisos_asignados where nombreusuario = '".textToDB($user)."' and tipoasignacion='1' union select clavepermiso from tbpermisos_tipos_usuario where idtipousuario in(select idtipousuario from ctusuarios where usuario ='".textToDB($user)."') and clavepermiso not in(select clavepermiso from tbpermisos_asignados where nombreusuario = '".textToDB($user)."'))order by clavepermisogrupo,accion";
			
			
			$rs=$db->Execute($strsql);
			if($rs){
				while(($row=$rs->fetchRow())){
					array_push($permisos_user,$row);
				}
				$_SESSION[$GLOBALS["sistema"]]['permisos_user'] = $permisos_user;
			}
			else{
				print $db->errormsg();
				$arr = array("clavepermiso"=>"-1","nombrepermiso"=>"Error al obtener permisos.","descripcion"=>"Ocurrió un error al obetener la lista de permisos, intente más tarde.","dependencia"=>"null");
				array_push($permisos_user,$arr);
				$_SESSION[$GLOBALS["sistema"]]['permisos_user'] = $permisos_user;
			}
			return $permisos_user;
		}
		else{
			return $_SESSION[$GLOBALS["sistema"]]['permisos_user'];
		}
	}

	
	
	

	// --------------------------------
	// 		IMPORTAR DATOS
	// --------------------------------  
	function rmdirtree($dirname) {
	   if (is_dir($dirname)) {    //Operate on dirs only
	       $result=array();
	       if (substr($dirname,-1)!='/') {$dirname.='/';}    //Append slash if necessary
	       $handle = opendir($dirname);
	       while (false !== ($file = readdir($handle))) {
	           if ($file!='.' && $file!= '..') {    //Ignore . and ..
	               $path = $dirname.$file;
	               if (is_dir($path)) {    //Recurse if subdir, Delete if file
	                   $result=array_merge($result,rmdirtree($path));
	               }else{
	                   unlink($path);
	                   $result[].=$path;
	               }
	           }
	       }
	       closedir($handle);
	       rmdir($dirname);    //Remove dir
	       $result[].=$dirname;
	       return $result;    //Return array of deleted items
	   }else{
	       return false;    //Return false if attempting to operate on a file
	   }
	}

	function read_dir($dir) {
	   $array = array();
	   //print "entre";
	   if($dir!=""){
	   	   try{
	   	       //$d = dir($dir);
	   	       if(is_object($d=@dir($dir))){
				   while (false !== ($entry = $d->read())) {
				       if($entry!='.' && $entry!='..') {
				           $entry = $dir.'/'.$entry;
				           if(is_dir($entry)) {
				               $array[] = $entry;
				               $array = array_merge($array, read_dir($entry));
				           } else {
				               $array[] = $entry;
				           }
				       }
				   }
				   $d->close();	
	   	       }
	   	   }
	   	   catch (Exception $exc){

	   	   }
		   
	   }
	   return $array;
	}

	function getNombreIdTabla($tabla,&$db){
		$strsql="SELECT t1.column,t1.datatype,t1.notnull,t2.adsrc as default_value  from (SELECT a.attrelid, a.attnum, a.attname AS column, a.attnotnull as notnull, pg_catalog.format_type(a.atttypid, a.atttypmod) AS datatype FROM pg_catalog.pg_attribute a WHERE a.attnum>0 AND NOT a.attisdropped AND a.attrelid = (SELECT c.oid FROM pg_catalog.pg_class c LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace WHERE c.relname ~'^($tabla)$' AND pg_catalog.pg_table_is_visible(c.oid)))t1 left join pg_attrdef t2 on t2.adnum=t1.attnum and t1.attrelid=t2.adrelid where t2.adsrc ilike '%nextval%' order by t1.column";
		$rs = $db->Execute($strsql);	
		$arrIDS = array();		
		if($rs){
			while($row=$rs->fetchRow()){
				array_push($arrIDS,$row['column']);
			}
		}
		return $arrIDS;
		
	}
	
	function getColumnasTablaSoloNombres($tabla,&$db){
		$arrfin = array();
		$arrCols = getColumnasTabla($tabla,$db);
		foreach ($arrCols as $col){	
			if(strpos(($col['default']),'nextval(')===false){
				array_push($arrfin,$col['nombre']);
			}
		}
		return $arrfin;
	}
	
	function getTipoDatoColumna($tabla,$columna,&$db){
		$strsql = "SELECT a.attname AS column, a.attnotnull as notnull, pg_catalog.format_type(a.atttypid, a.atttypmod) AS datatype FROM pg_catalog.pg_attribute a WHERE a.attnum>0 AND NOT a.attisdropped AND a.attrelid = (SELECT c.oid FROM pg_catalog.pg_class c LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace WHERE c.relname ~'^($tabla)$' AND pg_catalog.pg_table_is_visible(c.oid)) AND a.attname ilike '$columna'";		
		$rs = $db->Execute($strsql);	
		if($rs){
			while($row=$rs->fetchRow()){
				return $row['datatype'];
			}
		}
		else{
			return null;
		}

	}	
	
	function getColumnasPkeyTabla($tabla,&$db){
		$strsql="SELECT t1.column,t1.datatype,t1.notnull,t2.adsrc as default_value from (SELECT a.attrelid, a.attnum, a.attname AS column, a.attnotnull as notnull, pg_catalog.format_type(a.atttypid, a.atttypmod) AS datatype FROM pg_catalog.pg_attribute a WHERE a.attnum>0 AND NOT a.attisdropped and a.attname in(SELECT att.attname AS pk_name FROM pg_class c, pg_attribute att, pg_index i WHERE c.relname = '$tabla' AND c.oid=i.indrelid AND att.attnum > 0 AND att.attrelid = i.indexrelid AND i.indisprimary='t') AND a.attrelid = (SELECT c.oid FROM pg_catalog.pg_class c LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace WHERE c.relname ~'^($tabla)$' AND pg_catalog.pg_table_is_visible(c.oid)))t1 left join pg_attrdef t2 on t2.adnum=t1.attnum and t1.attrelid=t2.adrelid";	
		$arrCols = array();		
		$rs = $db->Execute($strsql);	
		if($rs){
			while($row=$rs->fetchRow()){
				if(strpos(($row['default_value']),'nextval(')===false){
					$columna = $row['column'];
					$tipodedato = $row['datatype'];
					$notnull = $row['notnull']=='t'?TRUE:FALSE;	
					array_push($arrCols,array("nombre"=>$columna,"default"=>$row['default_value'],"notnull"=>$notnull,"tipo"=>$tipodedato));
				}
			}
			if(count($arrCols)<=0){
				$arrColumnas = getColumnasTabla($tabla,$db);
				foreach ($arrColumnas as $col){	
					if($col['notnull'] && $col['default']==""){
						array_push($arrCols,$col);
					}
				}
			}
		}
		
		return $arrCols;
	}
	
	function getSeparador($path){
		$fp = fopen($path, "r");
		$headers = fgets($fp);
		$arr = explode(",",$headers);
		if(count($arr)>1){
			return ",";
		}
		$arr = explode("|",$headers);
		if(count($arr)>1){
			return "|";
		}
		$arr = explode(";",$headers);
		if(count($arr)>1){
			return ";";
		}
		return ",";
	}
	
	function countFileLines($filepath) {
		$handle = fopen($filepath, "r");
		$count = 0;
		while(fgets($handle)){
			$count++;
		}
		fclose($handle);
		return $count;
 	}
 	
 	function getNombreTipoDeDatoEs($tipodato){
 		if($tipodato=="boolean"){
			return "Booleano";
		}
		if(strpos($tipodato,"character varying")!==false){
			return "Caracteres";
		}

		if(strpos($tipodato,"character")!==false && strpos($tipodato,"varying")===false){
			return "Caracter";
		}
		if($tipodato=="date"){
			return "Fecha";
		}
		
		if($tipodato=="double precision"){
			return "Numeros con Decimales";
		}
		if($tipodato=="integer"){
			return "Numeros Enteros";
		}
		
		if($tipodato=="smallint"){
			return "Numeros Enteros";
		}
		
		if(strpos($tipodato,"numeric")!==false){
			return "Numeros con Decimales";
		}
		if($tipodato=="real"){
			return "Numeros con Decimales";
		}
		if($tipodato=="text"){
			return "Texto";
		}
		if($tipodato=="time"){
			return "Horas";
		}
		if(strpos($tipodato,"timestamp")!==false){
			return "Fecha/Hora";
		}
 	}
 	
 	function getLengthTipoDeDatoEs($tipodato){
 		if($tipodato=="boolean"){
			$len = "Letra 't' para verdadero y 'f' para falso.";
		}
		if(strpos($tipodato,"character varying")!==false){
	 		$def = trim($tipodato);
			$ini = (strpos($def, "(")+1);
			$pos = (strlen($def)-1)-$ini;
			if(strpos($def,"character varying(")!==false){		
				$len = substr($def,$ini,$pos);
				$len = "Hasta ".trim($len)." Caracteres alfanuméricos.";
			}
			else{
				$len = -1;
			}
		}
		
		if(strpos($tipodato,"character")!==false && strpos($tipodato,"varying")===false){
			$len = "1 Caracter Alfanumérico.";
		}
		
		if($tipodato=="character"){
			$len = "1 Caracter Alfanumérico.";
		}
		if($tipodato=="date"){
			$len = "Fecha en formato dd/mm/aaaa.";
		}
		if($tipodato=="double precision"){
			$len = "Generalmente hasta 8 números enteros y hasta 4 números decimales.";
		}
		if($tipodato=="integer"){
			$len = "Número entero de hasta 4 digitos.";
		}
		
		if($tipodato=="smallint"){
			$len = "Número entero de hasta 4 digitos.";
		}
		if(strpos($tipodato,"numeric")!==false){
	 		$def = trim($tipodato);
			$ini = (strpos($def, "(")+1);
			$pos = (strlen($def)-1)-$ini;
			if(strpos($def,"numeric(")!==false){		
				$len = substr($def,$ini,$pos);
				$len = trim($len);
				$arr = explode(",",$len);
				$len = "Hasta ".$arr[0]." números enteros y ".$arr[1]." números decimales.";
			}
			else{
				$len = -1;
			}
		}
		if($tipodato=="real"){
			$len = "Generalmente hasta 4 números enteros y hasta 2 números decimales.";
		}
		if($tipodato=="text"){
			$len = "Texto libre.";
		}
		if($tipodato=="time"){
			$len = "Hora en formato 24h en hh:mm:ss.";
		}
		if(strpos($tipodato,"timestamp")!==false){
			$len = "Fecha/Hora en formato dd/mm/aaaa hh:mm:ss.";
		}
 		
		return $len;
 	}
	
	function trim_value(&$value) { 
    	$value = trim($value); 
	}
	
	function getFKDataBase(){
		$strsql="SELECT c.conname AS constraint_name, pkn.nspname AS pk_schema, fkn.nspname AS fk_schema,
				 fkt.relname AS fk_table, pkt.relname AS pk_table, pkf.attname AS pk_field,
				 fkf.attname AS fk_field, c.confkey AS pk_field_ordinals, c.conkey AS fk_field_ordinals,
				 pkf.attnum AS pk_field_ordinal, fkf.attnum AS fk_field_ordinal
				 FROM pg_constraint c INNER JOIN pg_namespace n ON c.connamespace = n.oid
				 INNER JOIN pg_class pkt ON c.confrelid=pkt.oid
				 INNER JOIN pg_namespace pkn ON pkt.relnamespace = pkn.oid
				 INNER JOIN pg_class fkt ON c.conrelid=fkt.oid
				 INNER JOIN pg_namespace fkn ON fkt.relnamespace = fkn.oid
				 INNER JOIN pg_attribute pkf ON pkf.attnum = ANY(c.confkey) AND pkf.attrelid = pkt.oid
				 INNER JOIN pg_attribute fkf ON fkf.attnum = ANY(c.conkey) AND fkf.attrelid = fkt.oid
				 WHERE c.contype='f' ORDER BY fk_schema ASC, fk_table ASC, constraint_name ASC, fk_field ASC, pk_schema ASC";
		
				/*
				OBTIENE LAS CONSTRAINTS DE UNA TABLA
				select r.relname as "Tabla", c.conname as "Nombre de Restriccion",
			   	contype as "Tipo de Restriccion", conkey as "Columnas de Llave",
			   	confkey as "Columnas Referidas", consrc as "Fuente"
				from pg_class r, pg_constraint c
				where r.oid = c.conrelid and relname = 'tabla';
		
				*/
				
				
				/*
				CAMPOS DE UNA TABLA
				select *,pg_catalog.format_type(a.atttypid, a.atttypmod) as tipo from pg_attribute a, pg_class b where b.oid=a.attrelid and b.relname='ctalumnos'
				//CHECAR
				and a.atttypid<>0 and attnum>0
				*/
		
	}
	
	function getCascadeInfo(&$db,$tabla,$sqlQuery="",&$dataArr=array()){
		$strsql="SELECT c.conname AS constraint_name, pkn.nspname AS pk_schema, fkn.nspname AS fk_schema,
				 fkt.relname AS fk_table, pkt.relname AS pk_table, pkf.attname AS pk_field,
				 fkf.attname AS fk_field, c.confkey AS pk_field_ordinals, c.conkey AS fk_field_ordinals,
				 pkf.attnum AS pk_field_ordinal, fkf.attnum AS fk_field_ordinal
				 FROM pg_constraint c INNER JOIN pg_namespace n ON c.connamespace = n.oid
				 INNER JOIN pg_class pkt ON c.confrelid=pkt.oid
				 INNER JOIN pg_namespace pkn ON pkt.relnamespace = pkn.oid
				 INNER JOIN pg_class fkt ON c.conrelid=fkt.oid
				 INNER JOIN pg_namespace fkn ON fkt.relnamespace = fkn.oid
				 INNER JOIN pg_attribute pkf ON pkf.attnum = ANY(c.confkey) AND pkf.attrelid = pkt.oid
				 INNER JOIN pg_attribute fkf ON fkf.attnum = ANY(c.conkey) AND fkf.attrelid = fkt.oid
				 WHERE c.contype='f' and (pkt.relname='$tabla') ORDER BY fk_schema ASC, fk_table ASC, constraint_name ASC, fk_field ASC, pk_schema ASC";
		$rs=$db->Execute($strsql);
		if($rs){
			$total=$rs->recordCount();
			$contador=0;
			$const="";
			$tbFK="";
			$tbPK="";
			while($row=$rs->fetchrow()){
				$contador++;
				
				$flag=false;
				foreach ($dataArr as $datos){
					if($datos["constraint"]==$row["constraint_name"] && $datos["tabla"]==$row["pk_table"] && $datos["tabladependiente"]==$row["fk_table"]){
						$flag=true;
						break;
					}
				}
				if(!$flag){
					if(($const=="" && $tabladp=="") || ($const!=$row["constraint_name"] || $tabladp!=$row["fk_table"])){
						if(!($const=="" && $tabladp=="")){
							$found=false;
							$query="";
							foreach ($dataArr as $datos){
								if($datos["tabla"]==$tabladp){
									$found=true;
									break;
								}
							}
							//Exists
							/*
							$sql="select b.* from ".$tabladp." b where 1=1";
							if($sqlQuery!=""){
								$sql.=" and exists (select a.* from (".$sqlQuery.")a where 1=1";
								for($i=0; $i<count($arrFieldsFK); $i++){
									$sql.= " and a.".$arrFieldsPK[$i]."= b.".$arrFieldsFK[$i];
								}
								$sql.=")";
							}
							$sqlDel="delete".substr($sql,10);
							*/
							
							//JOIN
							
							$sql="select b.* from ".$tabladp." b ";
							$sqlDel="delete from ".$tabladp." b ";
							if($sqlQuery!=""){
								$sql.=" join (select a.* from (".$sqlQuery.")a where 1=1";
								$sqlDel.=" using (select a.* from (".$sqlQuery.")a where 1=1";
								$sql.=") t1 on ";
								$sqlDel.=") t1 where ";
								for($i=0; $i<count($arrFieldsFK); $i++){
									if($i==0){
										$sql.= " t1.".$arrFieldsPK[$i]."= b.".$arrFieldsFK[$i];
										$sqlDel.= " t1.".$arrFieldsPK[$i]."= b.".$arrFieldsFK[$i];
									}
									else{
										$sql.= " and t1.".$arrFieldsPK[$i]."= b.".$arrFieldsFK[$i];
										$sqlDel.= " and t1.".$arrFieldsPK[$i]."= b.".$arrFieldsFK[$i];
									}
								}	
							}
							
							
							$totalCount=0;
							$countQuery="select count(*)".substr($sql,10);
							//hacerArchivoAppend($countQuery."\r\n");
							$resCount=$db->Execute($countQuery);
							if($resCount){
								while($rowCount=$resCount->fetchRow()){
									$totalCount=$rowCount["count"];
								}
							}
							
							if(!$found){
								getCascadeInfo($db,$tabladp,$sql,&$dataArr);	
							}
							
							ksort($arrFieldsFK);
							ksort($arrFieldsPK);

							$commentString="";
							$commentSQL="SELECT * from pg_class a left join pg_description b on a.oid=b.objoid where a.relname='$tabladp' and objsubid=0";
							$resComment=$db->Execute($commentSQL);
							if($resComment->recordcount()==1){
								while($rowComment=$resComment->fetchRow()){
									$commentString=$rowComment["description"];
								}
							}
							
							array_push($dataArr,array('constraint'=>$const,
													  'tabladependiente'=>$tabladp,
													  'comentario'=>$commentString,
													  'rows'=>$totalCount,
													  'campostabladependiente'=>$arrFieldsFK,
													  'tabla'=>$tabla,
													  'query'=>$sql,
													  'querydelete'=>$sqlDel,
													  'campostabla'=>$arrFieldsPK));
						}
						$const=$row["constraint_name"];
						$tabladp=$row["fk_table"];
						$strFK=str_ireplace('{','',$row["fk_field_ordinals"]);
						$strFK=str_ireplace('}','',$strFK);
						$expFK=explode(",",$strFK);
						//print_r($expFK);exit();
						$arrFieldsFK=array();
						$strPK=str_ireplace('{','',$row["pk_field_ordinals"]);
						$strPK=str_ireplace('}','',$strPK);	
						$expPK=explode(",",$strPK);
						$arrFieldsPK=array();
						
						
						for($i=0; $i<count($expFK); $i++){
							if($expFK[$i]==$row["fk_field_ordinal"]){
								if($arrFieldsFK[$i]==""){
									$arrFieldsFK[$i]=$row["fk_field"];
								}
								break;
							}	
						}
						
						for($i=0; $i<count($expPK); $i++){
							if($expPK[$i]==$row["pk_field_ordinal"]){
								if($arrFieldsPK[$i]==""){
									$arrFieldsPK[$i]=$row["pk_field"];
								}
								break;
							}	
						}
					}
					else {
						for($i=0; $i<count($expFK); $i++){
							if($expFK[$i]==$row["fk_field_ordinal"]){
								if($arrFieldsFK[$i]==""){
									$arrFieldsFK[$i]=$row["fk_field"];
								}
								break;
							}	
						}
						
						for($i=0; $i<count($expPK); $i++){
							if($expPK[$i]==$row["pk_field_ordinal"]){
								if($arrFieldsPK[$i]==""){
									$arrFieldsPK[$i]=$row["pk_field"];
								}
								break;
							}	
						}	
					}
					if($contador==$total){
						
						$found=false;
						foreach ($dataArr as $datos){
							if($datos["tabla"]==$tabladp){
								$found=true;
							}
						}
						
						//Exists
						/*
						$sql="select b.* from ".$tabladp." b where 1=1";
						if($sqlQuery!=""){
							$sql.=" and exists (select a.* from (".$sqlQuery.")a where 1=1";
							for($i=0; $i<count($arrFieldsPK); $i++){
								$sql.= " and a.".$arrFieldsPK[$i]."= b.".$arrFieldsFK[$i];
							}
							$sql.=")";
						}
						$sqlDel="delete".substr($sql,10);
						*/
						
						//JOIN
						
						$sql="select b.* from ".$tabladp." b ";
						$sqlDel="delete from ".$tabladp." b ";
						if($sqlQuery!=""){
							$sql.=" join (select a.* from (".$sqlQuery.")a where 1=1";
							$sqlDel.=" using (select a.* from (".$sqlQuery.")a where 1=1";
							$sql.=") t1 on ";
							$sqlDel.=") t1 where ";
							for($i=0; $i<count($arrFieldsFK); $i++){
								if($i==0){
									$sql.= " t1.".$arrFieldsPK[$i]."= b.".$arrFieldsFK[$i];
									$sqlDel.= " t1.".$arrFieldsPK[$i]."= b.".$arrFieldsFK[$i];
								}
								else{
									$sql.= " and t1.".$arrFieldsPK[$i]."= b.".$arrFieldsFK[$i];
									$sqlDel.= " and t1.".$arrFieldsPK[$i]."= b.".$arrFieldsFK[$i];
								}
							}	
						}
						
						
						$totalCount=0;
						$countQuery="select count(*)".substr($sql,10);
						//hacerArchivoAppend($countQuery."\r\n");
						$resCount=$db->Execute($countQuery);
						if($resCount){
							while($rowCount=$resCount->fetchRow()){
								$totalCount=$rowCount["count"];
							}
						}
						
						if(!$found){
							getCascadeInfo($db,$tabladp,$sql,&$dataArr);	
						}
						ksort($arrFieldsFK);
						ksort($arrFieldsPK);
						
						$commentString="";
						$commentSQL="SELECT * from pg_class a left join pg_description b on a.oid=b.objoid where a.relname='$tabladp' and objsubid=0";
						$resComment=$db->Execute($commentSQL);
						if($resComment->recordcount()==1){
							while($rowComment=$resComment->fetchRow()){
								$commentString=$rowComment["description"];
							}
						}
						
						array_push($dataArr,array('constraint'=>$const,
												  'tabladependiente'=>$tabladp,
												  'comentario'=>$commentString,
												  'rows'=>$totalCount,
												  'campostabladependiente'=>$arrFieldsFK,
												  'tabla'=>$tabla,
												  'query'=>$sql,
												  'querydelete'=>$sqlDel,
												  'campostabla'=>$arrFieldsPK));
					}
				}
				
			}
									  
		}
		return $dataArr;
	}
	
	function datosCascade($arrCascade){
		//hacerArchivoAppend(print_r($arrCascade,true));
		$arrDatosBorrar=array();
		foreach ($arrCascade as $arrDatos){
			
			//hacerArchivoAppend($arrDatos["query"]."\r\n");
			
			$found=false;
			$arrTempDatos=array();
			foreach ($arrDatosBorrar as $arrDatoBorrar){
				if($arrDatoBorrar["tabladependiente"]==$arrDatos["tabladependiente"]){
					$found=true;
					break;	
				}
			}
			if(!$found){
				if($arrDatos["rows"]!=0){
					array_push($arrTempDatos,$arrDatos);
				}
			}
			
			//BUSCAMOS CUALES USAN LA MISMA TABLA
			foreach ($arrCascade as $datosIgual){
				if(($datosIgual!=$arrDatos && $datosIgual["tabladependiente"]==$arrDatos["tabladependiente"]) && ($datosIgual["rows"]!=0)){
					foreach ($arrTempDatos as $idx=>$arrTempDato){
						if($arrTempDato["rows"]==$datosIgual["rows"]){
							if(count($arrTempDato["campostabladependiente"])<count($datosIgual["campostabladependiente"])){
								$arrTempDatos[$idx]=$datosIgual;
							}
						}
						else{
							if($arrTempDato["rows"]<$datosIgual["rows"]){
								array_insert($arrTempDatos,$datosIgual,$idx);
							}
							else{
								array_push($arrTempDatos,$datosIgual);
							}
						}
					}
				}
			}
			
			//INSERTAMOS LOS DATOS EN EL ARREGLO FINAL
			foreach ($arrTempDatos as $idx=>$arrTempDato){
				if($idx!=0){
					$arrTempDato["comentario"]="";
				}
				array_push($arrDatosBorrar,$arrTempDato);
			}
		}
		return $arrDatosBorrar;
	}
	
	function getInfoDelCascade($db,$query,$borrar=true){
		$dataExplode=explode(" ",$query);
		$tabla="";
		$selectSQL="";
		$selectCount="";
		
		foreach ($dataExplode as $idx=>$dataExp){
			if(trim(strtolower($dataExp))=='delete'){
				$selectSQL.="select *";
				$selectCount.="select count(*)";
			}
			else{
				if(trim(strtolower($dataExp))=="from"){
					$tabla=$dataExplode[$idx+1];
				}
				$selectSQL.=" ".$dataExp;
				$selectCount.=" ".$dataExp;	
			}
		}
		
		$rowCount=$db->getrow($selectCount);
		
		if($rowCount["count"] > 0){
			
			$arrCascade=datosCascade(getCascadeInfo($db,$tabla,$selectSQL));
			$comment="";
			
			if($borrar){
				if(is_array($arrCascade)){
					array_push($arrCascade,array('querydelete'=>$query));
					return $arrCascade;
				}
				else{
					array('querydelete'=>$query);
				}
			}
			else{
				foreach ($arrCascade as $cascade){
					
					if($cascade["comentario"]!=""){
						$comment.="(".$cascade["rows"].") ".$cascade["comentario"]." \r\n";
					}
				}
				
				$rowComment="";
				
				$commentSQL="SELECT * from pg_class a left join pg_description b on a.oid=b.objoid where a.relname='$tabla' and objsubid=0";
				$rs=$db->Execute($commentSQL);
				if($rs){
					while ($row=$rs->fetchRow()){
						$rowComment=$row["description"];
					}
				}
				
				$comment.="(".$rowCount["count"].") ".$rowComment." \r\n";
							
				return $comment;
			}
		}
		return "";
	}
	
	function sumarMensajesCascade($comentarios){
		
		$coment="";
		$arrNuevosComentarios=array();
		
		preg_match_all('/.*\r\n/im',$comentarios,$lineasArr);
		
		if(is_array($lineasArr[0])){
			foreach ($lineasArr[0] as $linea){
				$found=false;
				$arrTempDatos=array();
				
				foreach ($arrNuevosComentarios as $arrNuevoComentario){
					$rowLinea=limpiarComentario($linea);
					$rowNuevo=limpiarComentario($arrNuevoComentario);
					if($rowLinea["comentario"]==$rowNuevo["comentario"]){
						$found=true;
						break;	
					}
				}
				if(!$found){
					array_push($arrTempDatos,$linea);
				}
				
				//BUSCAMOS CUALES SON EL MISMO COMENTARIO
				foreach ($lineasArr[0] as $lineaCom){
					if($lineaCom!=$linea){
						foreach ($arrTempDatos as $idx=>$arrTempDato){
							$rowLineaCom=limpiarComentario($lineaCom);
							$rowTemp=limpiarComentario($arrTempDato);
							if($rowTemp["comentario"]==$rowLineaCom["comentario"]){
								$arrTempDatos[$idx]="(".($rowTemp["rows"]+$rowLineaCom["rows"]).") ".$rowLineaCom["comentario"]." \r\n";
							}
						}
					}
				}
				//INSERTAMOS LOS DATOS EN EL ARREGLO FINAL
				foreach ($arrTempDatos as $idx=>$arrTempDato){
					//print $arrTempDato;
					array_push($arrNuevosComentarios,$arrTempDato);
				}
			}
		}
		
		foreach ($arrNuevosComentarios as $arrNuevoComentario){
			//print $arrNuevoComentario;
			$coment.=$arrNuevoComentario;
		}
		
		return $coment;
	}

	
	
	
	
	

	
/*************** FUNCIONES SUPER GRID *****************/

	// --------------------------------
	// 		PAGINACION BUSCAR
	// --------------------------------   
function paginacion_buscar($db,$query,$arrCamposBusqueda,$orderbyD="",$orderbyD_isNumeric=true,$orderbyD_isAscending=true, $paginacion=0){
	$limit       = (textToDB($_POST["limit"])=="" || textToDB($_POST["limit"])=="0") ? -1 : textToDB($_POST["limit"]) ;
	$_texto      = removerAcentos(textToDB($_POST["texto"]));
	$offset      = textToDB($_POST["offset"]);	
	$orderbyS    = textToDB($_POST["orderby"]);
	$union       = textToDB($_POST["union"]);
	
	
	
	
	// --------------------------------
	// 		Validaciones
	// --------------------------------	
	if($query==""){
		print getXMLErrorMsg("Error al recibir el query");
		exit;
	}	
	if($arrCamposBusqueda=="" || !is_array($arrCamposBusqueda) || count($arrCamposBusqueda)==0){
		print getXMLErrorMsg("Error recibiendo los campos de busqueda");
		exit;
	}	
	if($limit!=-1 && ($offset=="" || !is_numeric($offset))){
		print getXMLErrorMsg("Error al recibir el offset");
		exit;
	}
	
	
	// Normalizando los tipos de campos recibidos
	foreach ($arrCamposBusqueda as $k => $v){
		if(!is_numeric($k)){ 			
			switch (trim(strtoupper($v))){					
				case "N":
				case "NUMERO":
				case "NUMBER":
					$arrCamposBusqueda[$k] = "NUMERO";
					break;	
						
				case "F":
				case "D":
				case "FECHA":  
				case "DATE":
					$arrCamposBusqueda[$k] = "FECHA";
					break;
					
				case "S":
				case "V":
				case "C":
				case "STRING":
				case "VARCHAR":
				case "CADENA":
					$arrCamposBusqueda[$k] = "STRING";
					break;
					
				default:
					print getXMLErrorMsg("Error con el tipo de campo recibido: [$v]");
					exit;
			}	
		}
	}
			
			
	
	
	$strsql = "SELECT * FROM ($query) as tt WHERE 1=1 ";
	
	
	
	
	
	
	// --------------------------------
	// 		Normas
	// --------------------------------	
	$flashvars = $_POST['normas'];
	if($flashvars!=""){
		if(get_magic_quotes_gpc()){
		    $flashvars = stripslashes($flashvars);
		}
		$arrNormas = object_to_array(unserialize(urldecode($flashvars)));
	}

	
	
	$where = "";
	if($arrNormas!="" && is_array($arrNormas)){
		if($union=="" || ($union!=1 && $union!=2)){
			print getXMLErrorMsg("Error recibiendo el tipo de union");
			exit;
		}
		
		$union = ($union==1) ? " AND " : " OR ";
		$num_normas   = count($arrNormas);
		
		foreach ($arrNormas as $norma){
			// Validaciones
			
			if($norma["campo"] == ""){ print getXMLErrorMsg("Campo vacío"); exit; }
			if($norma["where"] == ""){ print getXMLErrorMsg("Tipo norma vacío"); exit; }
			if($norma["where"] == "-"){ 
				if($where != "") $where .= $union;	
				$where .= " (1=2) ";
				continue; 
			}
			if(!is_numeric($norma["where"])){ print getXMLErrorMsg("Tipo norma no es numerico"); exit; }
			if((int)$norma["where"]<1 || (int)$norma["where"]>16){ print getXMLErrorMsg("Tipo norma fuera de rango"); exit; }
			
			
			
			
			// Init
			$tipoNorma      = (int)$norma["where"];
			$textoNorma     = removerAcentos(textToDB($norma["texto"]));
			$campoNorma		= textToDB($norma["campo"]);
			$existe 		= false;	
			$tipoCampo 		= "STRING";
			
			
			// Buscando tipo de campo del campo seleccionado
			foreach ($arrCamposBusqueda as $k => $v){
				if(is_numeric($k)){ //si tiene indice default string
					if($v==$campoNorma){ 
						$existe=true; 
						break; 
					}
				}else{
					if($k==$campoNorma){ 
						switch (strtoupper($v)){					
							case "NUMERO": 
							case "FECHA":  
								$tipoCampo = strtoupper($v); 
								$existe    = true;
								break;
						}						
					}
				}
			}
			if(!$existe){
				print getXMLErrorMsg("Campo de Norma no válido: [$campoNorma]");
				exit;
			}
			
			
			
			/*http://doc.sumy.ua/db/pgsql_book/node48.html
			begins with D 	~'^D'
			contains D 	~'D'
			D in second position 	~'^.D'
			begins with D and contains e 	~'^D.*e'
			begins with D, contains e, and then f 	~'D.*e.*f'
			contains A, B, C, or D 	~'[A-D]' or ~'[ABCD]'
			contains A or a 	~*'a' or ~'[Aa]'
			not contains D 	!~'D'
			not begins with D 	!~'^D' or ~'^[^D]'
			begins with D, with one optional leading space 	~'^  ?D'
			begins with D , with optional leading spaces 	~'^  *D'
			begins with D, with at least one leading space 	~'^  +D'
			*/
			
			
			$opc    = "";
			$_where = "";
			
				
			// NORMAS TIPO STRING
			if($tipoNorma>=1 && $tipoNorma<=8){			
				if($tipoCampo=="STRING"){
					//De esta Forma lo hizo Daniel para PG
					$pre = " (ascii($campoNorma) "; // Si campo es tipo STRING quitar acentos	
					//De esta Forma lo Hizo Luis para SQLAniwhere 11
					$pre = " ($campoNorma "; // Si campo es tipo STRING quitar acentos	
				}else{
					$pre = " (cast($campoNorma as varchar) ";	//Si campo es tipo NUMERO o FECHA castear a varchar
				}
				switch ($tipoNorma){
					//Codigo Daniel de Postgres
					/*
					case 1: $pos = "  like '$textoNorma' ) ";      	break; // igual
					case 2: $pos = "  not like '$textoNorma' ) ";  	break; // diferente						
					case 3: $pos = "  ~* '^$textoNorma' )";     	break; // comience con
					case 4: $pos = "  !~* '^$textoNorma' )";   	    break; // no comience con
					case 5: $pos = "  ~* '$textoNorma$' )"; 	  	break; // termine con
					case 6: $pos = "  !~* '$textoNorma$' ) ";   	break; // no termine con
					case 7: $pos = "  like '%$textoNorma%') "; 		break; // contenga				
					case 8: $pos = "  !~* '$textoNorma') ";     	break; // no contenga
					*/
					//CODIGO LUIS DE SYBASE
					case 1: $pos = "  like '$textoNorma' ) ";      	break; // igual
					case 2: $pos = "  not like '$textoNorma' ) ";  	break; // diferente						
					case 3: $pos = "  like '$textoNorma%' )";     	break; // comience con
					case 4: $pos = "  not like '$textoNorma%' )";   	    break; // no comience con
					case 5: $pos = "  like '%$textoNorma' )"; 	  	break; // termine con
					case 6: $pos = "  not like '%$textoNorma' ) ";   	break; // no termine con
					case 7: $pos = "  like '%$textoNorma%') "; 		break; // contenga				
					case 8: $pos = "  not like '%$textoNorma%') ";     	break; // no contenga
				}					
				$_where = $pre.$pos;
			}
			
				
				
				
			// NORMAS TIPO NUMERO
			if($tipoNorma>=9 && $tipoNorma<=13){			
				if($tipoCampo != "NUMERO" || !is_numeric($textoNorma)){
					$_where = " (1=2) "; //para que no traiga resultados, escribio texto en una norma tipo NUMERO Ã³ seleccionÃ³ un campo tipo STRING
				}else{
					switch ($tipoNorma){
						case 9:  $_where = " ($campoNorma =  $textoNorma) ";  break; //igual a
						case 10: $_where = " ($campoNorma <  $textoNorma) ";  break; //menor
						case 11: $_where = " ($campoNorma <= $textoNorma) ";  break; //menor o igual
						case 12: $_where = " ($campoNorma >  $textoNorma) ";  break; //mayor
						case 13: $_where = " ($campoNorma >= $textoNorma) ";  break; //mayor o igual
					}
				}
			}
				
				
				
			
			// NORMAS TIPO FECHA
			if($tipoNorma>=14 && $tipoNorma<=16){			
				if($tipoCampo != "FECHA" || strpos($textoNorma,"/")===false){
					$_where = " (1=2) "; //para que no traiga resultados, escribio texto en una norma tipo FECHA Ã³ seleccionÃ³ un campo tipo STRING
				}else{
					// En Bd:   1984-01-31
	 				// En Flex: 31/01/1984 
	 				$textoNorma = getFechaSistema($textoNorma);	
					switch ($tipoNorma){
						case 14: $_where = " ($campoNorma =  '$textoNorma') ";  break; //con fecha de
						case 15: $_where = " ($campoNorma >= '$textoNorma') ";  break; //con fecha mayor a
						case 16: $_where = " ($campoNorma <= '$textoNorma') ";  break; //con fecha menor a	
					}
				}
			}	
				
			
			if($_where != ""){				
				if($where != "") $where .= $union;	
				$where .= $_where;
			}
		}
		
		if($where!=""){
			$strsql .= " AND $where ";		
		}
	}
	
	
	
	
	
	// --------------------------------
	// 		Texto Busqueda
	//
	// cast(* as varchar) para no tener problemas al buscar en campos NO varchar (fecha,int,etc)
	// to_ascii() para remover acentos, ej: por si buscas 'sanchez' y en base de datos esta 'sÃ¡nchez' lo encuentre
	// --------------------------------	
	if($_texto!=""){
		$where_campos="";
		foreach ($arrCamposBusqueda as $k => $v){
			if($where_campos!="") $where_campos .= " OR ";			
			
			$tipoCampo = "STRING";		
			if(!is_numeric($k)){ 				
				switch (strtoupper($v)){
					case "NUMERO": 
					case "FECHA":  
						$tipoCampo = "CAST";
				}	
			}
			
			
			$campo = (is_numeric($k) ? $v : $k);			
			if($tipoCampo=="STRING"){
				$where_campos .= " ($campo like '%$_texto%') ";	
			}else{
				$where_campos .= " (cast($campo as varchar) like '%$_texto%') ";	
			}
		}
		$strsql .= " AND ($where_campos) ";
	}
	
	
	
	
	
	// --------------------------------
	// 		Order By
	//
	// to_ascii sólo acepta strings, castear primero y pasar a mayus
	// --------------------------------	
	
	$_orderBy="";
	$orderbyIsString = true;
	if($orderbyS!=""){ //
		$_orderBy = $orderbyS; //orderby seleccionado
		foreach ($arrCamposBusqueda as $k => $v){
			if(!is_numeric($k) && ($k == $_orderBy) && (strtoupper($v)=="NUMERO" || strtoupper($v)=="FECHA")){
				$orderbyIsString = false;
				break;
			}
		}
	}else{
		if($orderbyD!=""){ 
			$_orderBy = $orderbyD; // orderby default
			$orderbyIsString = !$orderbyD_isNumeric;
			$_POST["ascorder"] = ($orderbyD_isAscending) ? "1" : "2";
		}		
	}
	
	if($_orderBy!=""){
		if($orderbyIsString == false){
			$strsql .= " ORDER BY $_orderBy ";	
		}else{
			//$strsql .= " ORDER BY UPPER(ascii($_orderBy)) ";	//quitar acentos y hacer mayusculas para ordenar correctamente ej: a,A,Á	
			$strsql .= " ORDER BY $_orderBy ";	
		}
	}
	
	//if ($orderbyD!="") {
	//	$strsql .= " ORDER BY $orderbyD ";
	//}
	
	
	
	
	
	// --------------------------------
	// 		Sort By
	// --------------------------------	
	if($_orderBy!=""){
		if($_POST["ascorder"] && $_POST["ascorder"]==2){
			$strsql .= " DESC ";
		}else{
			$strsql .= " ASC ";
		}
	}
	
	/**
	 * Date
	 * 
	 * En Bd:   1984-01-31
	 * En Flex: 31/01/1984 
	 * 
	 * 
	 * Timestamp with out time zone
	 * - Necesario hacer un alias a estos campos ya que getFechaSistema() en getXML() lo convierte 
	 * 
	 * En Bd:             2011-03-04 10:14:45.749
	 * En Flex normal:    07 11:14:45/03/2011
	 * En Flex con alias: to_char(2011-03-04 10:14:45.749, 'YYYY/MM/DD HH:MI PM') => '2011/03/04 10:14 AM'
	 * 
	 */
	//dias_vencimiento
	//hacerArchivo($strsql);
	if($_POST["savequery"] == "si"){
		/**
		 * Opción de impresión
		 * Cuando se elige imprimir resultados se guarda el query de los registros y se regresa al Flex solo 
		 * los datos sin los rows, ya que lo único que se utilizará sera: 
		 * $xml .= "\t\t<numrows total='$numrows' limit='$limit' offset='$offset'/>\n"; 
		 */
		
		//$strsql2="SELECT TOP $limit 
			//		START AT $offset * FROM(".$strsql.") as tt ";
		
			
		//hacerArchivo($strsql);	
		$strsql_paginacion = $strsql;
		if($limit !== -1){
			$strsql_paginacion ="SELECT TOP $limit START AT $offset * FROM(".$strsql.") as tt ORDER BY $orderbyD";
		}			
		$_SESSION[$GLOBALS["sistema"]]['strsql_paginacion'] = $strsql_paginacion;	
		
		$limit=0;
		print getXML($strsql,$db,$limit,$offset,false,0, $_orderBy);			
		
	}else{
		print getXML($strsql,$db,$limit,$offset,true,$paginacion, $_orderBy);			
	}
}

	// --------------------------------
	// 		PAGINACION BORRAR
	// --------------------------------   
function paginacion_borrar($db,$query,$isNumeric=true){
	$ids    = textToDB($_POST["ids"]);	
	
	if($ids==""){
		print "1) No se recibió el post ids";
		
	}elseif($query==""){
		print "2) No se recibió el query";
		
	}elseif(strrpos($query,"[id]") === false){		
		print "3) No se encontró [id] en el query";
		
	}else{		
		$strsql = "";
		$arrIds = explode("|",$ids);
		
		if($arrIds=="" || !is_array($arrIds) || count($arrIds)==0){
			print "4) Error recibiendo ids: $ids";
			
		}else{
			foreach($arrIds as $id){
				if($id!=""){
					if($isNumeric && !is_numeric($id)){
						print "5) El id NO es numerico";
						exit;
					}else{
						$strsql .= str_replace("[id]",$id,$query).";";	
					}
				}else{
					print "6) Error recibiendo el id: $id";
					exit;
				}
			}		
			
			if($strsql==""){
				print "7) Error creando la consulta";	
				
			}else{
				hacerArchivo($strsql);
				$db->starttrans();
				if($rs=$db->Execute($strsql)){
					
					if($db->completetrans()){
						print "ok";
						
					}else{
						$db->rollbacktrans();
						print "8) Error al completar la operación de borrado:\n\n".$db->errormsg();			
					}
				}
				else{
					$db->rollbacktrans();
					print "9) Error al realizar la operación de borrado:\n\n".$db->errormsg();
				}
			}
		}
	}
}

function paginacion_borrar_multiple($db,$arrquery,$isNumeric=true){
	$ids    = textToDB($_POST["ids"]);

	if($ids==""){
		print "1) No se recibió el post ids";
/*
	}elseif($query==""){
		print "2) No se recibió el query";

	}elseif(strrpos($query,"[id]") === false){
		print "3) No se encontró [id] en el query";
*/
	}else{
		$strsql = "";
		$arrIds = explode("|",$ids);

		if($arrIds=="" || !is_array($arrIds) || count($arrIds)==0){
			print "4) Error recibiendo ids: $ids";
				
		}else{
			foreach ($arrquery as $query){
				foreach($arrIds as $id){
					if($id!=""){
						if($isNumeric && !is_numeric($id)){
							print "5) El id NO es numerico";
							exit;
						}else{
							$strsql .= str_replace("[id]",$id,$query).";";
						}
					}else{
						print "6) Error recibiendo el id: $id";
						exit;
					}
				}
			}
			if($strsql==""){
				print "7) Error creando la consulta";

			}else{
				hacerArchivo($strsql);
				$db->starttrans();
				if($rs=$db->Execute($strsql)){
						
					if($db->completetrans()){
						print "ok";

					}else{
						$db->rollbacktrans();
						print "8) Error al completar la operación de borrado:\n\n".$db->errormsg();
					}
				}
				else{
					$db->rollbacktrans();
					print "9) Error al realizar la operación de borrado:\n\n".$db->errormsg();
				}
			}
		}
	}
}


	// --------------------------------
	// 		REMOVER ACENTOS
	// --------------------------------   
function removerAcentos($str){
    $from = 'ÀÁÃÂÉÊÍÓÕÔÚÜÇàáãâéêíóõôúüçñÑ';
    $to   = 'AAAAEEIOOOUUCaaaaeeiooouucnN';
    return strtr($str, $from, $to);
}
/*************** FUNCIONES SUPER GRID *****************/










/*************** FUNCIONES ENCUESTAS *****************/		
function getBorrados($arrayids,$arrayidsEditadas){
	$borrados=array();
	for ($i=0;$i<count($arrayids);$i++){
		if (!in_array($arrayids[$i],$arrayidsEditadas)){
			array_push($borrados,$arrayids[$i]);
		}
	}
	return $borrados;
}

function borrarIds(&$db,$arrayids,$tabla,$campo){
	for ($i=0;$i<count($arrayids);$i++){
		$strsql="delete from ".textToDB($tabla)." where ".textToDB($campo)."='".textToDB($arrayids[$i])."'";
		$rs=$db->execute($strsql);
		if(!$rs){
			print $db->errormsg();
		}
	}		
}
	
function revisaEncuestaRealizada(&$db, $idencuesta){
	$strsql="SELECT idencuesta FROM tbencuestas_contestadas WHERE idencuesta='$idencuesta' and idusuario='".$_SESSION[$GLOBALS["sistema"]]['idusuario']."' ";
	if($rs=$db->execute($strsql)){
		if($rs->recordCount()>0){
			return 1;
		}else{
			return 0;
		}
	}else{
		print "Error revisaEncuestaRealizada: \n $strsql \n ".$db->errormsg();
		return -1;
	}
}

function getXMLPreguntasEncuesta($db,$idencuesta){	
	$cont=1;
	$xml="";
	$strsql = "select idcategoria,titulo from tbencuestas_categorias where idencuesta=$idencuesta";	
	if(($rs=$db->execute($strsql))){
		$xml= "<categorias>";
		while($row=$rs->fetchrow()){
			$xml.= "<categoria>
						<idcategoria>".textFromDB($row["idcategoria"])."</idcategoria>
						<titulo>".textFromDB($row["titulo"])."</titulo>";
			$strsql2 = "select idpregunta,descripcion,num_pregunta,tipopregunta,numrespuestaselegir,sumatotal,tiposuma,instrucciones,numeracion,numincisoselegir,idtipoelegir,idtipoelegir_inciso,obligatoria from tbencuestas_preguntas where idcategoria=".textFromDB($row["idcategoria"])." order by num_pregunta";
			if(($rs2=$db->execute($strsql2))){
				$xml.= "<preguntas>";
				while($row2=$rs2->fetchrow()){
					foreach ($row2 as $k=>$v) $row2[$k]=textFromDB($v);
					extract($row2,EXTR_PREFIX_ALL,"preg");	
					
					$xml.= "<pregunta>
								<idpregunta>$preg_idpregunta</idpregunta>
								<idcategoria>".textFromDB($row["idcategoria"])."</idcategoria>
								<descripcion>$preg_descripcion</descripcion>
								<num_pregunta>$cont</num_pregunta>
								<tipopregunta>$preg_tipopregunta</tipopregunta>
								<numrespuestaselegir>$preg_numrespuestaselegir</numrespuestaselegir>
								<sumatotal>$preg_sumatotal</sumatotal>
								<tiposuma>$preg_tiposuma</tiposuma>
								<instrucciones>$preg_instrucciones</instrucciones>
								<numeracion>$preg_numeracion</numeracion>
								<obligatoria>$preg_obligatoria</obligatoria>
								<numincisoselegir>$preg_numincisoselegir</numincisoselegir>
								<idtipoelegir>$preg_idtipoelegir</idtipoelegir>
								<idtipoelegir_inciso>$preg_idtipoelegir_inciso</idtipoelegir_inciso>"; 
					
					$strsql3 = "select * from tbencuestas_archivos where idpregunta='$preg_idpregunta' order by idarchivo";
					if(($rs3=$db->execute($strsql3))){
						$xml.= "<archivos>";
						while($row3=$rs3->fetchrow()){
							foreach ($row3 as $k=>$v) $row3[$k]=textFromDB($v);
							extract($row3,EXTR_PREFIX_ALL,"archivo");							
							$xml.= "<archivo>
										<idarchivo>$archivo_idarchivo</idarchivo>		
										<ruta>$archivo_ruta</ruta>								
										<descripcion>$archivo_descripcion</descripcion>
										<tipoarchivo>$archivo_tipoarchivo</tipoarchivo>
										<extension>$archivo_extension</extension>
										<nombreoriginal>$archivo_nombreoriginal</nombreoriginal>
										<tamanioarchivo>$archivo_tamanioarchivo</tamanioarchivo>
										<interno>$archivo_interno</interno>
									</archivo>";
						}
						$xml.= "</archivos>";
					}else{
						return "[3]: ".$db->errormsg()."\n\n";
					}
					
					
					// Agrupada
					if($preg_tipopregunta == 4){
						// incisos:  0-pregunta  1-resp_normal  2-grupo   3-resp_en_grupo
						$strsql3 = "select idrespuesta,descripcion,bifurcada,abierta,inciso,valor,grupo,tiporespuesta,obligatoria,idtipoelegir,numincisoselegir from tbencuestas_respuestas where idpregunta='$preg_idpregunta' and inciso<3 order by idrespuesta";
						if(($rs3=$db->execute($strsql3))){
							$xml.= "<respuestas>";
							while($row3=$rs3->fetchrow()){
								foreach ($row3 as $k=>$v) $row3[$k]=textFromDB($v);
								extract($row3,EXTR_PREFIX_ALL,"resp");							
								$xml.= "<respuesta>
											<idrespuesta>$resp_idrespuesta</idrespuesta>
											<descripcion>$resp_descripcion</descripcion>
											<bifurcada>$resp_bifurcada</bifurcada>
											<abierta>$resp_abierta</abierta>
											<inciso>$resp_inciso</inciso>
											<valor>$resp_valor</valor>											
											<tiporespuesta>$resp_tiporespuesta</tiporespuesta>
											<obligatoria>$resp_obligatoria</obligatoria>
											<idtipoelegir>$resp_idtipoelegir</idtipoelegir>
											<numincisoselegir>$resp_numincisoselegir</numincisoselegir>";
								
								// grupos
								if($resp_inciso==2){
									$xml .= " <arrrespuestas>";
									
									$strsql4 = "select idrespuesta,descripcion,bifurcada,abierta,inciso,valor,grupo,tiporespuesta,obligatoria,idtipoelegir,numincisoselegir from tbencuestas_respuestas where idpregunta='$preg_idpregunta' and inciso=3 and grupo=$resp_idrespuesta order by idrespuesta";
									if(($rs4=$db->execute($strsql4))){
										while($row4=$rs4->fetchrow()){
											foreach ($row4 as $k=>$v) $row4[$k]=textFromDB($v);
											extract($row4,EXTR_PREFIX_ALL,"inciso");							
											$xml.= "<respuesta>
														<idrespuesta>$inciso_idrespuesta</idrespuesta>
														<descripcion>$inciso_descripcion</descripcion>
														<bifurcada>$inciso_bifurcada</bifurcada>
														<abierta>$inciso_abierta</abierta>
														<inciso>$inciso_inciso</inciso>
														<valor>$inciso_valor</valor>
														<tiporespuesta>$inciso_tiporespuesta</tiporespuesta>
														<obligatoria>$inciso_obligatoria</obligatoria>
													</respuesta>";
										}
									}else{
										return "[3b]: ".$db->errormsg()."<br>";
									}
									
									$xml .= "</arrrespuestas>";
								}
								
								$xml .= "</respuesta>";
							}
							$xml.= "</respuestas>";
						}else{
							return "[3a]: ".$db->errormsg()."<br>";
						}
						
						
					}else{
						$strsql3 = "select idrespuesta,descripcion,bifurcada,abierta,inciso,valor from tbencuestas_respuestas where idpregunta='$preg_idpregunta' order by idrespuesta";
						if(($rs3=$db->execute($strsql3))){
							$xml.= "<respuestas>";
							while($row3=$rs3->fetchrow()){
								foreach ($row3 as $k=>$v) $row3[$k]=textFromDB($v);
								extract($row3,EXTR_PREFIX_ALL,"resp");							
								$xml.= "<respuesta>
											<idrespuesta>$resp_idrespuesta</idrespuesta>
											<descripcion>$resp_descripcion</descripcion>
											<bifurcada>$resp_bifurcada</bifurcada>
											<abierta>$resp_abierta</abierta>
											<inciso>$resp_inciso</inciso>
											<valor>$resp_valor</valor>
										</respuesta>";
							}
							$xml.= "</respuestas>";
						}else{
							return "[3]: ".$db->errormsg()."<br>";
						}
					}
					
					$xml.= "</pregunta>";
					
					$cont++;
				}
				$xml.= "</preguntas>";	
				
			}else{
				return "[2]: ".$db->errormsg()."<br>";
			}
			$xml.= "</categoria>";
		}
		$xml.= "</categorias>";		
		return $xml;
	}
	else{
		return "[1]: ".$db->errormsg()."<br>";
	}
}
	





//           20/09/2010       
	// --------------------------------
	// 		getUsuariosInPreguntasSeleccionadas
	// --------------------------------   
function getUsuariosInPreguntasSeleccionadas($db,$arrFiltros){
	$arreglo=array();
	$strIdsUsuarios="";
	foreach ($arrFiltros as $filtro){		
		$idencuesta   = ($filtro["idencuesta_contestada"]  && $filtro["idencuesta_contestada"]!="")  ? " and t1.idencuesta_contestada=".textToDB($filtro["idencuesta_contestada"])." " : "";
		$idpregunta   = ($filtro["idpregunta"]  && $filtro["idpregunta"]!="")                        ? " and t2.idpregunta=".textToDB($filtro["idpregunta"])." "                       : "";
		$idrespuesta  = ($filtro["idrespuesta"] && $filtro["idrespuesta"]!="")                       ? " and t2.idrespuesta=".textToDB($filtro["idrespuesta"])." "                     : "";
		$idinciso     = ($filtro["idinciso"] && $filtro["idinciso"]!="")                             ? " and t2.idinciso=".textToDB($filtro["idinciso"])." "                           : "";
		$idjerarquica = ($filtro["idjerarquica"] && $filtro["idjerarquica"]!="")                     ? " and t2.idjerarquica=".textToDB($filtro["idjerarquica"])." "                   : "";
		
		if($idencuesta=="" && $idpregunta=="" && $idrespuesta=="" && $idinciso=="" && $idjerarquica==""){
			print "getUsuariosInPreguntasSeleccionadas-> Error al recibir el filtro";
			exit;
		}		
		
		$strsql="SELECT 
					t1.idusuario 
				 FROM 
				 	tbencuestas_contestadas t1 INNER JOIN tbencuestas_contestadas_respuestas t2 ON t1.idencuesta_contestada=t2.idencuesta_contestada 
				 WHERE 
				 	1=1 $idencuesta $idpregunta $idrespuesta $idinciso $idjerarquica ";
		
		if(($rs=$db->execute($strsql))){
			$arreglo2=array();
			while($row=$rs->fetchrow()){
				array_push($arreglo2,$row["idusuario"]);	
			}
			
			if(sizeof($arreglo)==0){
				$arreglo = $arreglo2;
			}else{
				$arreglo = array_intersect($arreglo,$arreglo2);	
			}
		}else{
			print "[1]: ".$db->errormsg()."<br>";
			exit;
		}
	}

	
	if(sizeof($arreglo)==0){
		print "oops: getUsuariosInPreguntasSeleccionadas";
		exit;			
	}elseif(sizeof($arreglo)==1){
		$strIdsUsuarios=" and idusuario=".implode("",$arreglo)." ";			
	}else{
		$strIdsUsuarios=" and idusuario in (".implode(",",$arreglo).") ";		
	}
	return $strIdsUsuarios;				
}





	// --------------------------------
	// 		getEncuestasInPreguntasSeleccionadas
	// --------------------------------   
function getEncuestasInPreguntasSeleccionadas($db,$arrFiltros){
	$arreglo=array();
	$strIdsEncuestas="";
	foreach ($arrFiltros as $filtro){
		$idencuesta   = ($filtro["idencuesta_contestada"]  && $filtro["idencuesta_contestada"]!="")  ? textToDB($filtro["idencuesta_contestada"]) : "";
		$idpregunta   = ($filtro["idpregunta"]  && $filtro["idpregunta"]!="")                        ? " and idpregunta=".textToDB($filtro["idpregunta"])." "                       : "";
		$idrespuesta  = ($filtro["idrespuesta"] && $filtro["idrespuesta"]!="")                       ? " and idrespuesta=".textToDB($filtro["idrespuesta"])." "                     : "";
		$idinciso     = ($filtro["idinciso"] && $filtro["idinciso"]!="")                             ? " and idinciso=".textToDB($filtro["idinciso"])." "                           : "";
		$idjerarquica = ($filtro["idjerarquica"] && $filtro["idjerarquica"]!="")                     ? " and idjerarquica=".textToDB($filtro["idjerarquica"])." "                   : "";
		
		if($idencuesta=="" && $idpregunta=="" && $idrespuesta=="" && $idinciso=="" && $idjerarquica==""){
			print "Error al recibir el filtro";
			exit;
		}		
		if($idencuesta!=""){
			return " and idencuesta_contestada=$idencuesta ";
		}
		
		
		$strsql="select idencuesta_contestada from tbencuestas_contestadas_respuestas where 1=1 $idpregunta $idrespuesta $idinciso $idjerarquica";
		if(($rs=$db->execute($strsql))){
			$arreglo2=array();
			while($row=$rs->fetchrow()){
				array_push($arreglo2,$row["idencuesta_contestada"]);	
			}
			
			if(sizeof($arreglo)==0){
				$arreglo = $arreglo2;
			}else{
				$arreglo = array_intersect($arreglo,$arreglo2);	
			}
			
		}else{
			print "[1]: ".$db->errormsg()."<br>";
			exit;
		}	
	}
			
	if(sizeof($arreglo)==0){
		print "oops: getEncuestasInPreguntasSeleccionadas";
		exit;			
	}elseif(sizeof($arreglo)==1){			
		$strIdsEncuestas=" and idencuesta_contestada=".implode("",$arreglo);			
	}else{
		$strIdsEncuestas=" and idencuesta_contestada in (".implode(",",$arreglo).") ";		
	}	
	return $strIdsEncuestas;				
}
/*************** FUNCIONES ENCUESTAS *****************/	





/*************** FUNCIONES ARCHIVOS UPLOAD *****************/	
function archivosUpload_nuevo($db,$arrArchivos,$tabla,$tabla_id,$id){
	$strsql = "";
	foreach ($arrArchivos as $archivo){
		foreach ($archivo as $k=>$v) $archivo[$k]=textToDB($v);
		extract($archivo,EXTR_PREFIX_ALL,"archivo");	
			
		$strsql .= "INSERT INTO $tabla 
						($tabla_id,ruta,descripcion,tipoarchivo,extension,nombreoriginal,nombreusuario_autor,tamanioarchivo,interno) 
					VALUES 
						($id,'$archivo_ruta','$archivo_descripcion','$archivo_tipoarchivo','$archivo_extension','$archivo_nombreoriginal','".$_SESSION[$GLOBALS["sistema"]]['nombreusuario']."','$archivo_tamanioarchivo','$archivo_interno'); ";						
	}
	
	if($rs=$db->execute($strsql)){
		return true;
	}else{
		print "Error archivosUpload_nuevo: $strsql\n\n".$db->errormsg();
		return false;
	}
}




function archivosUpload_editar($db,$arrArchivos,$tabla,$tabla_id,$id){
	if(is_array($arrArchivos) && count($arrArchivos)>0){	
				
		// Obtenemos idarchivo de los archivos recibidos ya existentes
		$arrArchivosRecibidos = array();
		foreach ($arrArchivos as $archivo){
			foreach ($archivo as $k=>$v) $archivo[$k]=textToDB($v);
			extract($archivo,EXTR_PREFIX_ALL,"archivo");	
			if($archivo_idarchivo!="" && $archivo_idarchivo!="0"){
				if(is_numeric($archivo_idarchivo)){
					array_push($arrArchivosRecibidos,$archivo_idarchivo);		
				}else{
					/*$db->rollbacktrans();
					print "E4: idarchivo no numerico";
					exit;*/
					print "E4: idarchivo no numerico";
					return false;
				}					
			}				
		}
		
		// Borramos aquellos archivos que no esten en las recibidas
		if(sizeof($arrArchivosRecibidos)>0){
			$strsql = "DELETE FROM $tabla WHERE $tabla_id=$id and idarchivo not in (".implode(",",$arrArchivosRecibidos)."); ";	
		}else{
			$strsql = "DELETE FROM $tabla WHERE $tabla_id=$id; ";
		}
		
		
		
		
		// Recorremos los archivos recibidos y actualizamos o insertamos segun sea el caso (idarchivo==0 significa que fue agregada ahi mismo)
		foreach ($arrArchivos as $archivo){
			foreach ($archivo as $k=>$v) $archivo[$k]=textToDB($v);
			extract($archivo,EXTR_PREFIX_ALL,"archivo");		

			if($archivo_idarchivo!="" && $archivo_idarchivo!="0"){
				if(is_numeric($archivo_idarchivo)){
					$strsql .= "UPDATE $tabla SET descripcion='$archivo_descripcion' WHERE idarchivo='$archivo_idarchivo'; ";
				}else{
					print "Error archivosUpload_editar:\n id archivo editar NO numerico: [$archivo_idarchivo] ";
					return false;
				}
			}else{
				$strsql .= "INSERT INTO $tabla ($tabla_id,ruta,descripcion,tipoarchivo,extension,nombreoriginal,nombreusuario_autor,tamanioarchivo,interno) values ($id,'$archivo_ruta','$archivo_descripcion','$archivo_tipoarchivo','$archivo_extension','$archivo_nombreoriginal','".$_SESSION[$GLOBALS["sistema"]]['nombreusuario']."','$archivo_tamanioarchivo','$archivo_interno'); ";
			}			
		}
		
		
		
		
		// Realizamos todas las operaciones al mismo tiempo
		$db->execute($strsql);
		if($db->completetrans()){
			return true;
		}else{			
			print "Error archivosUpload_editar:\n".$db->errormsg()." - ".$strsql;//dbg								
			return false;
		}			
	}else{
		// Si no se recibieron archivos borrar todos los que existan
		$strsql = "DELETE FROM $tabla WHERE $tabla_id=$id ";
		
		if($rs=$db->execute($strsql)){
			return true;
		}
		else{
			print "E2: ".$db->errormsg()." - ".$strsql;//dbg								
			return false;
		}
	}
}

/*************** FUNCIONES ARCHIVOS UPLOAD *****************/	




/*************** FUNCIONES SUPER ASIGNADOR *****************/	
function superAsignador_nuevo($db,$arrAsignaciones,$tabla,$tabla_campo,$tabla_id){
	if($tabla_id==""){
		print "Error tabla id";
		return false;
	}
	
	
	
	$strsql = "";
	foreach($arrAsignaciones as $asignacion){
		/*
			$asignacion [
				idasignacion => id en la tabla, si es cero es una nueva asignacion, sino es una ya existente
				id => nombreusuario / idtipousuario / clavepermiso
				tipoasignacion => 1-Usuario  2-Tipo de Usuario  3-Permiso				
			]
		*/
		
		$id = textToDB($asignacion["id"]);
		if($id==""){
			print "Asignaciones: id no recibido";
			return false;
		}
		
		$tipoasignacion = $asignacion["tipoasignacion"];
		if($tipoasignacion=="" || !is_numeric($tipoasignacion) || $tipoasignacion<1 || $tipoasignacion>3){
			print "Asignaciones: tipo asignacion [$tipoasignacion] ";
			return false;
		}
		
		
		if($tipoasignacion==2 && !is_numeric($id)){
			print "Error id tipousuario NO numerico";
			return false;
		}
		
		
		
		$nombreusuario = ($tipoasignacion==1) ? "'$id'" : "NULL";
		$idtipousuario = ($tipoasignacion==2) ? "'$id'" : "NULL";
		$clavepermiso  = ($tipoasignacion==3) ? "'$id'" : "NULL";
		
		
		$strsql .= "INSERT INTO $tabla 
						($tabla_campo, nombreusuario, idtipousuario, clavepermiso, tipoasignacion) 
					VALUES 
						($tabla_id,$nombreusuario,$idtipousuario,$clavepermiso,$tipoasignacion); "; 
		
	}
	
	if($strsql==""){
		print "Error superAsignador_nuevo: strsql vacío";
		return false;	
	}else{
		if($rs=$db->execute($strsql)){
			return true;
		}else{
			print "Error superAsignador_nuevo: $strsql\n\n".$db->errormsg();
			return false;
		}
	}
}




function superAsignador_editar($db,$arrAsignaciones,$tabla,$tabla_campo,$tabla_id){
	
	if($tabla==""){
		print "Error->superAsignador_editar: tabla vacía";
		return false;
	}
	if($tabla_campo==""){
		print "Error->superAsignador_editar: tabla campo vacía";
		return false;
	}
	if($tabla_id==""){
		print "Error->superAsignador_editar: tabla id vacía";
		return false;
	}
	
	
	$tabla = textToDB($tabla);
	$tabla_campo = textToDB($tabla_campo);
	$tabla_id = textToDB($tabla_id);
	
	
	// Borrar todas las asignaciones
	$strsql = "DELETE FROM $tabla WHERE $tabla_campo='$tabla_id'; ";
	if(!$rs=$db->execute($strsql)){
		print "Error->superAsignador_editar:\n".$db->errormsg()." - ".$strsql;//dbg								
		return false;
	}
	
	
	
		
	$strsql="";	
	foreach ($arrAsignaciones as $asignacion){
		$id = textToDB($asignacion["id"]);
		$tipoasignacion = textToDB($asignacion["tipoasignacion"]);
		
		
		// Validaciones
		if($id==""){
			print "Asignaciones: id no recibido";
			return false;
		}
		
		if($tipoasignacion=="" || !is_numeric($tipoasignacion) || $tipoasignacion<1 || $tipoasignacion>3){
			print "Asignaciones: tipo asignacion [$tipoasignacion] ";
			return false;
		}
		
		if($tipoasignacion==2 && !is_numeric($id)){
			print "Error id tipousuario NO numerico";
			return false;
		}
		// Validaciones
		
		
		
		$nombreusuario = ($tipoasignacion==1) ? "'$id'" : "NULL";
		$idtipousuario = ($tipoasignacion==2) ? "'$id'" : "NULL";
		$clavepermiso  = ($tipoasignacion==3) ? "'$id'" : "NULL";
						
		$strsql .= "INSERT INTO $tabla 
						($tabla_campo, nombreusuario, idtipousuario, clavepermiso, tipoasignacion) 
					VALUES 
						($tabla_id,$nombreusuario,$idtipousuario,$clavepermiso,$tipoasignacion); "; 
		
	}
	
	
	
	// Realizamos todas las operaciones al mismo tiempo
	if($rs=$db->execute($strsql)){
		return true;
	}else{
		print "Error superAsignador_editar:\n".$db->errormsg()." - ".$strsql;//dbg								
		return false;
	}	
}


function superAsignador_consulta($db,$tabla,$tabla_id,$id){
	$strsql = "SELECT * FROM $tabla WHERE $tabla_id = '$id' ";	
	$numrows = getNumRows($strsql, $db);
	if(!is_numeric($numrows)){
		print "Error superAsignador_numrows";
		exit;
	}
	
	
	
	$xml = "<?xml version='1.0' encoding='UTF-8'?>\n
				<tablas>\n
				\t<tabla0>\n
				\t\t<numrows total='$numrows' limit='-1' offset='0'/>\n"; 
	
	
	// Usuarios
	$strsql = "SELECT 
					t1.nombreusuario,
					((case when t2.apaterno is null then '' else t2.apaterno end) || ' ' || (case when t2.amaterno is null then '' else t2.amaterno end) || ' ' || t2.nombre) as nombre
			   FROM 
			   		$tabla t1
			   INNER JOIN
			   		ctusuarios t2 ON t1.nombreusuario=t2.nombreusuario 
			   WHERE 
			   		t1.$tabla_id = $id AND tipoasignacion='1'
			   ORDER BY 
			   		nombre ";
	
	if(($rs=$db->execute($strsql))){		
		while($row=$rs->fetchRow()){			
			$xml .= "\t\t<rows id='".textFromDB($row["nombreusuario"])."' nombre='".textFromDB($row["nombre"])."' tipoasignacion='1' />\n";
		}
	}else{
		print "Error superAsignador_consulta #1: ".$db->ErrorMsg();
		exit;
	}
	
	
	
	
	// Tipo de Usuarios
	$strsql = "SELECT 
					t1.idtipousuario,
					t2.titulo as nombre
			   FROM 
			   		$tabla t1
			   INNER JOIN
			   		cttipousuarios t2 ON t1.idtipousuario=t2.idtipousuario
			   WHERE 
			   		t1.$tabla_id = $id AND tipoasignacion='2'
			   ORDER BY 
			   		nombre ";
	
	if(($rs=$db->execute($strsql))){		
		while($row=$rs->fetchRow()){			
			$xml .= "\t\t<rows id='".textFromDB($row["idtipousuario"])."' nombre='".textFromDB($row["nombre"])."' tipoasignacion='2' />\n";
		}
	}else{
		print "Error superAsignador_consulta #2: ".$db->ErrorMsg();
		exit;
	}
	
	
	
	// Permisos
	$strsql = "SELECT 
					t1.clavepermiso,
					(t2.modulo || ' - ' || t2.accion) as nombre					
			   FROM 
			   		$tabla t1
			   INNER JOIN
			   		tbpermisos_disponibles t2 ON t1.clavepermiso=t2.clavepermiso
			   WHERE 
			   		t1.$tabla_id = $id AND tipoasignacion='3'
			   ORDER BY 
			   		nombre ";
	
	if(($rs=$db->execute($strsql))){		
		while($row=$rs->fetchRow()){			
			$xml .= "\t\t<rows id='".textFromDB($row["clavepermiso"])."' nombre='".textFromDB($row["nombre"])."' tipoasignacion='3' />\n";
		}
	}else{
		print "Error superAsignador_consulta #3: ".$db->ErrorMsg();
		exit;
	}
	
	
	$xml .= "	\t</tabla0>\n
			</tablas>\n";
	
	
	if(($GLOBALS["debug_level"] & DBG_LVL_3) > 0){ // SI TENEMOS DEBUG NIVEL 3 SIGNIFICA QUE NO VAMOS A CIFRAR LA SALIDA
		print $xml;
	}
	else{
		print "<tablas>\n\t<encrypted>".encrypt($xml, $GLOBALS["RC4_KEY"])."</encrypted>\n</tablas>";
	}	
}
/*************** FUNCIONES SUPER ASIGNADOR *****************/	


//FUNCIONES RECURSIVAS PARA GENERAR EL XML EN BASE A LAS CARPETAS
    
function getPadres($arreglo){
	//print_r($arreglo);
	
	$padres=array();
	foreach ($arreglo as $grupo){
		if($grupo["clavepermisogrupopadre"]!='' || $grupo["clavepermisogrupopadre"]!=null){
			$grupo["hijos"]=array();
			array_push($padres,$grupo);
		}
	}
	//print_r($padres);
	return $padres;
}


function getHijos($padres,$grupos){
	foreach ($grupos as $grupo){
		for($i=0; $i<count($padres); $i++){
			creaArbol($padres[$i],$grupo);	
		}
	}
	
	return $padres;	
}

function creaArbol(&$padre,&$hoja){
	$band=false;
	if($padre["clavepermisogrupo"] == $hoja["clavepermisogrupopadre"]){
		//print "encontrado la hoja: ".$hoja["idtemario"]." en el padre: ".$padre["idtemario"]."<br>";
		$hoja["hijos"]=array();
		array_push($padre["hijos"],$hoja);
		$band=true;
		return $band;	
	}
	if(!$band && count($padre["hijos"])>0){
		for($i=0; $i<count($padre["hijos"]); $i++){
			if(creaArbol($padre["hijos"][$i],$hoja)){
				break;
			}
		}
	}
	return false;
}

function countHijosPermisos($carpeta,$permisos,&$count=0){
	$found=false;
	foreach ($permisos as $permiso){
		if($carpeta["clavepermisogrupo"] == $permiso["clavepermisogrupo"]){
			//print "encontrado en ".$padre["nombre_grupo"]."  ".$permiso["nombrepermiso"];
			$found=true;
			$count+=1;
			return $count;
		}
	}
	if(!$found && count($carpeta["hijos"])>0){
		for($i=0; $i<count($carpeta["hijos"]); $i++){
			if(countHijosPermisos($carpeta["hijos"][$i],$permisos,$count)>0){
				break;
			}
		}
	}
	
	return $count;
}

function getXMLArbol($carpetas,$permisos,&$xmlCadena){
	//print "<arboles>";
	foreach ($carpetas as $carpeta){
		if(countHijosPermisos($carpeta,$permisos)>0){
			$xmlCadena.= "<permiso idpermiso='' clavepermiso='' modulo='' nombre='".$carpeta["nombrepermisogrupo"]."' descripcion=''>";
			
			foreach ($permisos as $permiso){
				if($carpeta["clavepermisogrupo"] == $permiso["clavepermisogrupo"]){
					$xmlCadena.= "<permiso idpermiso='".$permiso["idpermiso"]."' clavepermiso='".$permiso["clavepermiso"]."' modulo='".$permiso["modulo"]."' nombre='".$permiso["accion"]."' clavepermisogrupo = '".$permiso["clavepermisogrupo"]."'  descripcion='".$permiso["descripcion"]."' />";
				}
			}
			
			if(count($carpeta["hijos"])>0){
				getXMLArbol($carpeta["hijos"],$permisos,$xmlCadena);
			}
			$xmlCadena.= "</permiso>";
			
		}
	}
}

	function mandarCorreofeo($from, $to, $subject, $bodytxt, $bodyhtml, $attachments=""){	 
		$text = $bodytxt;
		$html = $bodyhtml;
		$file = $attachments;
		$crlf = "\n";
		
		$smtp_params["host"] = $GLOBALS["smtp_Host"];
        $smtp_params["port"] = $GLOBALS["smtp_Port"];
        $smtp_params["auth"] = true;
        $smtp_params["username"] = $GLOBALS["smtp_Username"];
        $smtp_params["password"] = $GLOBALS["smtp_Password"];
        $smtp_params["timeout"] = "30";
				
		$hdrs = array('From' => $from, 'To' => $to, 'Subject' => $subject);
		 
	    $mime = new Mail_mime($crlf);
		 
	    $mime->setTXTBody($text);
	    $mime->setHTMLBody(textToDB($html));
	    
	    if($attachments != ""){
		    foreach($attachments as $attachment){	    	
		    	$mime->addAttachment($attachment,'application/octet-stream',getFilename($attachment));
		    }
	    }
	    
		$body = $mime->get();
		$hdrs = $mime->headers($hdrs);
		
		$smtp =& Mail::factory('smtp', $smtp_params);
		
		$mail = $smtp->send($to, $hdrs, $body);
		
		$ret = "";
		if (PEAR::isError($mail)) {
			$ret = $mail->getMessage();
		}
		else {
			$ret = "OK|".$to;
		}
		
		return $ret;
	}
	
	function mandarCorreo($from, $fromName, $to, $toName, $subject, $msgHTML){
		$takeit="http://www.takeit.mx/";
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPAuth 	= true;
		$mail->SMTPSecure 	= $GLOBALS["SMTPSecure"];
		$mail->Host 		= $GLOBALS["smtp_Host"];
		$mail->Port 		= $GLOBALS["smtp_Port"];
		$mail->Username 	= $GLOBALS["smtp_Username"];
		$mail->Password 	= $GLOBALS["smtp_Password"];
		$mail->SMTPDebug  	= 2;
			
		$mail->From = $from;
		$mail->FromName = $fromName;
		$mail->Subject = $subject;
		//$mail->AltBody = "Tu Registro se ha completado satisfactoriamente";   //este no se para que sirve
			
		//este es lo que vas a mostrar cuando abres el correo
		$mail->MsgHTML($msgHTML);
		
		//$mail->AddAttachment("files/files.zip");
		//$mai->AddAttachment("files/img03.jpg");
		$mail->AddAddress("$to", $toName);
		$mail->IsHTML(true);
		
		$ret = "";
		
		if(!$mail->Send()) {
			$ret = "Error: " . $mail->ErrorInfo;
		}
		else {
			$ret = "OK|".$to;
			//echo "Mensaje enviado correctamente";
		}
		return $ret;
	}
	
	function getFilename($file) {
	    $filename = substr($file, strrpos($file,'/')+1,strlen($file)-strrpos($file,'/'));
	    return $filename;
	}
	
	function stripImgTag($html){
		return preg_replace('/<img(.*?)>/is',"",$html);
	}
	
	/*
		//MANDAR CORREO
		require_once "Mail.php";
		require_once "Mail/mime.php";
		$html = textToDB(stripslashes(urldecode($_POST["htmlcorreo"])));
		$res = mandarCorreo("noreply@correo.com.mx", $correos_destino, textToDB($_POST["fecha"]." Sistema XXXX"), "Plain text", $html);
		if(strstr($res, "OK|")){
			$correos_error = false; //CORREOS ENVIADOS CORRECTAMENTE
		}
		else{
			$correos_error = true;
		}
		//MANDAR CORREO	
	*/
	
	function checarCarpetaCurso($idcurso){
		//hacerArchivo("entre carpetas");
		$d1=is_dir($GLOBALS["strPathAbsoluto"]."cursos/");
		if(!$d1){
			$d1=mkdir($GLOBALS["strPathAbsoluto"]."cursos", 0775);
		}
		
		$d2=is_dir($GLOBALS["strPathAbsoluto"]."cursos/".$idcurso);
		if($d1 && !$d2){
			$d2=mkdir($GLOBALS["strPathAbsoluto"]."cursos/$idcurso", 0775);
		}
		
		$dgenerales=is_dir($GLOBALS["strPathAbsoluto"]."cursos/$idcurso/generales");
		if($d1 && $d2 && !$dgenerales){
			$dgenerales=mkdir($GLOBALS["strPathAbsoluto"]."cursos/$idcurso/generales", 0775);
		}
		
		$drecursos=is_dir($GLOBALS["strPathAbsoluto"]."cursos/$idcurso/recursos");
		if($d1 && $d2 && !$drecursos){
			$drecursos=mkdir($GLOBALS["strPathAbsoluto"]."cursos/$idcurso/recursos", 0775);
		}
		
		$dforos=is_dir($GLOBALS["strPathAbsoluto"]."cursos/$idcurso/foros/");
		if($d1 && $d2 && !$dforos){
			$dforos=mkdir($GLOBALS["strPathAbsoluto"]."cursos/$idcurso/foros", 0775);
		}
		
		$dactividades=is_dir($GLOBALS["strPathAbsoluto"]."cursos/$idcurso/actividades/");
		if($d1 && $d2 && !$dactividades){
			$dactividades=mkdir($GLOBALS["strPathAbsoluto"]."cursos/$idcurso/actividades", 0775);
		}
		
		$dchat_archivos=is_dir($GLOBALS["strPathAbsoluto"]."cursos/$idcurso/chat_archivos/");
		if($d1 && $d2 && !$dchat_archivos){
			$dchat_archivos=mkdir($GLOBALS["strPathAbsoluto"]."cursos/$idcurso/chat_archivos", 0775);
		}
		
		return true;
	}
	
	function ultimoProyectoAgregado($claveProyecto, $db){
		$strsql = "SELECT idproyecto FROM tbproyectos WHERE clave_proyecto='$claveProyecto'";
		$rs=$db->Execute($strsql);
		if ($rs) {
			while ($row = $rs->fetchRow()) {
				$idproyecto=$row[idproyecto];
			}
		}
		return $idproyecto;
	}

?>