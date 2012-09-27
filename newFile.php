<?php
	include "include/funciones.inc.php";
	$strsql = "SELECT * FROM BenefPag";
	$strsql2 = "select id, ctacontpaqw,moneda from cuentas order by ctacontpaqw";

  // Connect to DB
  $conn = odbc_connect('Driver={Microsoft Paradox Driver (*.db )};DriverID=538;Fil=Paradox 5.X;DefaultDir=c:\ABSA\ABSA Sonora\ContpaqLink\tmpCheqpaq 201;Dbq=c:\ABSA\ABSA Sonora\ContpaqLink\tmpCheqpaq;CollatingSequence=ASCII;','','');
  $CHQABSA2011 = odbc_connect('Driver={Microsoft Paradox Driver (*.db )};DriverID=538;Fil=Paradox 5.X;DefaultDir=c:\paradox\Empresas\CHQABSA2011 201;Dbq=c:\paradox\Empresas\CHQABSA2011;CollatingSequence=ASCII;','','');
//CHQABSA2011

$strConsulta = "INSERT INTO movtos (Movto) VALUES (1)";
$strBorradoTotal = "DELETE FROM movtos";
								
	$result = odbc_prepare($conn,$strBorradoTotal);
	if (!odbc_execute($result)) { 
	    /* error  */ 
	    echo "Eres Un Error\n"; 
	}
	else {
		print "OK\n";
	}
  // Query
  $qry = "SELECT TOP 1 * FROM movtos";

  // Get Result
  $result = odbc_exec($conn,$qry);

  // Get Data From Result
  while ($data[] = odbc_fetch_array($result));

  // Free Result
  odbc_free_result($result);

  // Close Connection
  odbc_close($conn);

  // Show data
  print_r($data);
  
  //print ParadoxGetNumRows($strsql,$CHQABSA2011);
  //print "\n\n";
//================================================================

if ($conn) 
{ 
  //the SQL statement that will query the database 
  $query = "select * from cars"; 
  //perform the query 
  $result=odbc_exec($conn,$qry); 

  echo "<table border=\"1\"><tr>"; 

  //print field name 
  $colName = odbc_num_fields($result); 
  for ($j=1; $j<= $colName; $j++) 
  {  
    echo "<th>"; 
    echo odbc_field_name ($result, $j ); 
    echo "</th>"; 
  } 

  //fetch tha data from the database 
  while(odbc_fetch_row($result)) 
  { 
    echo "<tr>"; 
    for($i=1;$i<=odbc_num_fields($result);$i++) 
    { 
      echo "<td>"; 
      echo odbc_result($result,$i); 
      echo "</td>"; 
    } 
    echo "</tr>"; 
  } 

  echo "</td> </tr>"; 
  echo "</table >"; 

  //close the connection 
  odbc_close ($CHQABSA2011); 
}


	function ParadoxGetXML($strsql, &$db, $limit=-1, $offset=0, $showRows=true)
	{
		$xml = "";
		$arrQuerys = explode(";",cleanQuery($strsql));
		
		$xml .= "<?xml version='1.0' encoding='UTF-8'?>\n";
		$xml .= "<tablas>\n";
		
		foreach($arrQuerys as $tabla => $query){
			$numrows = ParadoxGetNumRows($query, $db);
			$rs=odbc_exec($db, $query);
			//$result=odbc_exec
			
				if ($rs){
					$xml .= "\t<tabla$tabla>\n";
					$xml .= "\t\t<numrows total='$numrows' limit='$limit' offset='$offset'/>\n"; 
					
					if($showRows){
						
//**********************
//Aqui hay qe moverle
//**********************						
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
	}
	
	function ParadoxGetNumRows($query, $db){
		$pre = "select count(*) as total from (";
		$pos = ") x";		
		if(($rs=odbc_exec($db,$pre.$query.$pos))){
			
				print_r(odbc_result($rs,'total'));
				odbc_close($db);
		}	
		else{
			
			return odbc_errormsg($db)." ".$query;
			odbc_close($db);
		}
	}

?>