<?php
	$base="TestPdox";
	$login="";
	$password="";
	$connexion=odbc_connect($base,$login,$password) or die("Erreur de connexion");
	
	//$query="INSERT INTO Test (Nom) VALUES ('$fnom')";
	//odbc_do($connexion,$query) or die("Requête d'insertion incorrecte");
	//echo("Insertion réussie.\n");
	
	$query="SELECT * FROM movtos";
	$strConsulta = "INSERT INTO movtos (Movto) VALUES (20)";
	//$result=odbc_do($connexion,$query) or die("Se ve mal");
								
								$result = odbc_prepare($connexion,$strConsulta);
								if (odbc_execute($result)) { 
								    print "OK";
								}
								else {
								    echo "Eres Un Error"; 
								}
								
								odbc_close($connexion);
	
	/*
	echo("<br><br>Affichage des inscrits :<br><br>\n");
	echo("<table border=\"1\">\n");
	echo("<tr>");
	echo "<th>Movto</th>";
	echo "<th>Cuenta</th>";
	echo "<th>FechaMovto</th>";
	echo "<th>CNaturaleza</th>";
	echo "<th>Tipo Movto</th>";
	echo "<th>NumeroCheque</th>";
	echo "<th>fechaAplica</th>";
	echo "<th>Concepto Gral</th>";
	echo "<th>ImporteMonedaCuenta</th>";
	echo "<th>Cuenta Nombre</th>";
	echo "<th>Cuenta TXT</th>";
	echo "</tr>\n";
	
	while (odbc_fetch_row($result))
	{
		$movto=odbc_result($result,1);
		// ou $codeclient=odbc_result($result,"CodeClient");
		$cuenta=odbc_result($result,2);
		$fechaMonvto=odbc_result($result,3);
		$cNaturaleza=odbc_result($result,4);
		$TipoMovto=odbc_result($result,5);
		$numeroCheque=odbc_result($result,6);
		$fechaAplica=odbc_result($result,7);
		$referencia=odbc_result($result,8);
		$conceptoGral=odbc_result($result,9);
		$importeMonedaCuenta=odbc_result($result,10);
		$cuentaNombre=odbc_result($result,38);
		$cuentaTxt=odbc_result($result,39);
		echo"<tr>";
		echo "<td>$movto</td>";
		echo "<td>$cuenta</td>";
		echo "<td>$fechaMonvto</td>";
		echo "<td>$cNaturaleza</td>";
		echo "<td>$TipoMovto</td>";
		echo "<td>$numeroCheque</td>";
		echo "<td>$fechaAplica</td>";
		echo "<td>$conceptoGral</td>";
		echo "<td>$importeMonedaCuenta</td>";
		echo "<td>$cuentaNombre</td>";
		echo "<td>$cuentaTxt</td>";
		echo "</tr>\n";
				  
	} 
	echo("</table>\n");

*/
?>