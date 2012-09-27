<?php
	//include "include/entorno.inc.php";
	//include "include/funciones.inc.php";	
	//***************************************************************************************
	//		CONNECTIONSTRING  ->  para las bases de datos de PARADOX usadas para CHEQPAQ.
	//***************************************************************************************
	//$conn 		-> 	es la base de datos temporal, donde se guardan los datos y verificamos tener 
	//					todos los datos antes de escribir en la base de datos en produccion
	//$CHQABSA2011	->	Es la base de datos en produccion actual, esta variable va a cambiar cuando en CHEQPAQ se cambie de base
	//					de datos, hay que estar bien pendientes.
	//$conn = odbc_connect('Driver={Microsoft Paradox Driver (*.db )};DriverID=538;Fil=Paradox 5.X;DefaultDir=c:\paradox\tmpCheqpaq;Dbq=c:\paradox\tmpCheqpaq;CollatingSequence=ASCII;','','');
  	//$CHQABSA2011 = odbc_connect('Driver={Microsoft Paradox Driver (*.db )};DriverID=538;Fil=Paradox 5.X;DefaultDir=c:\paradox\Empresas\CHQABSA2011 201;Dbq=c:\paradox\Empresas\CHQABSA2011;CollatingSequence=ASCII;','','');
  	$pxVirtual = odbc_connect('cheqPack2012','','');
  	$pxVirtual2 = odbc_connect('TestPdox','','');
  	//\\TAURUS\paradox\tmpCheqpaq2
  	//*********************************************************************
	//	AQUI EMPIEZO A METER DATOS EN EL TEMPORAL DE PARADOX PARA CHEQPAQ
	//*********************************************************************
	
	//$dbPx2 =&ADONewConnection('odbc');
	//$dbPx2->PConnect('Driver={Microsoft Paradox Driver (*.db )};DriverID=538;Fil=Paradox 5.X;DefaultDir=c:\ABSA\ABSA Sonora\ContpaqLink\tmpCheqpaq 201;Dbq=c:\ABSA\ABSA Sonora\ContpaqLink\tmpCheqpaq;CollatingSequence=ASCII;','','');
	
	//$strConsulta = "select * from movtos";
	
	//$strConsulta = "DELETE FROM movtos where 1=1";
	/*$strConsulta = "INSERT INTO movtos (Movto) VALUES (2)";
	
	$result = odbc_prepare($pxVirtual,$strConsulta);
	if (odbc_execute($result)) { 
	    print "OK";
	}
	else {
	    echo "Eres Un Error"; 
	}
	
	odbc_close($pxVirtual);
	*/
	$strConsultaMovtos = "select * FROM movtos";
	$strConsultaMovPolCh  = "SELECT * FROM MovPolCh";
	$strConsultaPolChqpW  = "SELECT * FROM PolChqpW";
	$strConsultaDepositos  = "SELECT * FROM Depositos";
		
	$strQuery="INSERT INTO movtos (Movto, Cuenta) VALUES (100, 1)";

//	ejecutarQuery($pxVirtual2,"DELETE FROM movtos where 1=1"); ejecutarQuery($pxVirtual2,"DELETE FROM MovPolCh where 1=1");ejecutarQuery($pxVirtual2,"DELETE FROM PolChqpW where 1=1");ejecutarQuery($pxVirtual2,"DELETE FROM Depositos where 1=1");
	
	imprimirTablaMovtos($pxVirtual2,$strConsultaMovtos);
	echo "<br>";
	imprimirTablaMovPolCh($pxVirtual2,$strConsultaMovPolCh);
	echo "<br>";
	imprimirTablaPolChqpW($pxVirtual2,$strConsultaPolChqpW);
	echo "<br>";
	imprimirTablaDepositos($pxVirtual2,$strConsultaDepositos);
	
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
	
	function imprimirTablaMovtos (&$conexionbd, $strsql){
		$result = odbc_do($conexionbd,$strsql);
		echo odbc_num_fields($result)."\n";
		echo "<b>Movtos</b>\n";
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
		echo "<th>Deposito</th>";
		echo "<th>ImporteMonedaCuenta</th>";
		echo "<th>Cuenta Nombre</th>";
		echo "<th>Cuenta TXT</th>";
		echo "<th>Referencia</th>";
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
			$Deposito=odbc_result($result,20);
			$importeMonedaCuenta=odbc_result($result,10);
			$cuentaNombre=odbc_result($result,31);
			$referencia = odbc_result($result,8);
			//$cuentaTxt=odbc_result($result,39);
			echo"<tr>";
			echo "<td>$movto</td>";
			echo "<td>$cuenta</td>";
			echo "<td>$fechaMonvto</td>";
			echo "<td>$cNaturaleza</td>";
			echo "<td>$TipoMovto</td>";
			echo "<td>$numeroCheque</td>";
			echo "<td>$fechaAplica</td>";
			echo "<td>$conceptoGral</td>";
			echo "<td>$Deposito</td>";
			echo "<td>$importeMonedaCuenta</td>";
			echo "<td>$cuentaNombre</td>";
			echo "<td>$cuentaTxt</td>";
			echo "<td>$referencia</td>";
			echo "</tr>\n";
					  
		} 
		echo("</table>\n");
		odbc_close($conexionbd);
	}
	
	function imprimirTablaMovPolCh (&$conexionbd, $strsql){
		$result = odbc_do($conexionbd,$strsql);
		echo odbc_num_fields($result)."\n";
		echo "<b>MovPolCh</b>\n";
		echo("<table border=\"1\">\n");
		echo("<tr>");
		echo "<th>IDPolChqpW</th>";
		echo "<th>NumeroMovto</th>";
		echo "<th>CuentaContpaqW</th>";
		echo "<th>Referencia</th>";
		echo "<th>Concepto</th>";
		echo "<th>TipoMovto</th>";
		echo "<th>CargoMn</th>";
		echo "<th>AbonoMn</th>";
		echo "<th>CargoME</th>";
		echo "<th>AbonoME</th>";
		echo "<th>IDDeposito</th>";
		echo "<th>Diario</th>";
		echo "</tr>\n";
		while (odbc_fetch_row($result))
		{
			$IDPolChqpW=odbc_result($result,1);
			// ou $codeclient=odbc_result($result,"CodeClient");
			$NumeroMovto=odbc_result($result,2);
			$CuentaContpaqW=odbc_result($result,3);
			$Referencia=odbc_result($result,4);
			$Concepto=odbc_result($result,5);
			$TipoMovto=odbc_result($result,6);
			$CargoMn=odbc_result($result,7);
			$AbonoMn=odbc_result($result,8);
			$CargoME=odbc_result($result,9);
			$AbonoME=odbc_result($result,10);
			$IDDeposito=odbc_result($result,11);
			$Diario = odbc_result($result,12);
			//$cuentaTxt=odbc_result($result,39);
			echo"<tr>";
			echo "<td>$IDPolChqpW</td>";
			echo "<td>$NumeroMovto</td>";
			echo "<td>$CuentaContpaqW</td>";
			echo "<td>$Referencia</td>";
			echo "<td>$Concepto</td>";
			echo "<td>$TipoMovto</td>";
			echo "<td>$CargoMn</td>";
			echo "<td>$AbonoMn</td>";
			echo "<td>$CargoME</td>";
			echo "<td>$AbonoME</td>";
			echo "<td>$IDDeposito</td>";
			echo "<td>$Diario</td>";
			echo "</tr>\n";
					  
		} 
		echo("</table>\n");
		odbc_close($conexionbd);
	}
	
	function imprimirTablaPolChqpW (&$conexionbd, $strsql){
		$result = odbc_do($conexionbd,$strsql);
		//echo odbc_num_fields($result)."\n";
		echo "<b>PolChqpW</b>\n";
		echo("<table border=\"1\">\n");
		echo("<tr>");
		echo "<th>IdPolChqpw</th>";
		echo "<th>Fecha</th>";
		echo "<th>Tipo</th>";
		echo "<th>Numero</th>";
		echo "<th>Concepto</th>";
		echo "</tr>\n";
		while (odbc_fetch_row($result))
		{
			$IdPolChqpw=odbc_result($result,1);
			$Fecha=odbc_result($result,2);
			$Tipo=odbc_result($result,3);
			$Numero=odbc_result($result,4);
			$Concepto=odbc_result($result,5);
			
			echo"<tr>";
			echo "<td>$IdPolChqpw</td>";
			echo "<td>$Fecha</td>";
			echo "<td>$Tipo</td>";
			echo "<td>$Numero</td>";
			echo "<td>$Concepto</td>";
			echo "</tr>\n";
					  
		} 
		echo("</table>\n");
		odbc_close($conexionbd);
	}
	
	function imprimirTablaDepositos (&$conexionbd, $strsql){
				
		$result = odbc_do($conexionbd,$strsql);
		echo odbc_num_fields($result)."\n";
		echo "<b>Depositos</b>\n";
		echo("<table border=\"1\">\n");
		echo("<tr>");
		echo "<th>CIdDeposito</th>";
		echo "<th>cIDCuenta</th>";
		echo "<th>CImporte</th>";
		echo "<th>CConceptoGral</th>";
		echo "<th>CReferencia</th>";
		echo "<th>CTipoIngreso</th>";
		echo "<th>CFechaDeposito</th>";
		echo "<th>CFechaAplicacion</th>";
		echo "<th>CEstadoConciliado</th>";
		echo "<th>CEstadoContable</th>";
		echo "<th>CIDPoliza</th>";
		echo "<th>CTAlta</th>";
		echo "<th>CTSUltimaActualizacion</th>";
		echo "</tr>\n";
		while (odbc_fetch_row($result))
		{
			$CIdDeposito=odbc_result($result,1);
			$cIDCuenta=odbc_result($result,2);
			$CImporte=odbc_result($result,3);
			$CConceptoGral=odbc_result($result,4);
			$CReferencia=odbc_result($result,5);
			$CTipoIngreso=odbc_result($result,6);
			$CFechaDeposito=odbc_result($result,7);
			$CFechaAplicacion=odbc_result($result,8);
			$CEstadoConciliado=odbc_result($result,9);
			$CEstadoContable=odbc_result($result,10);
			$CIDPoliza=odbc_result($result,11);
			$CTAlta=odbc_result($result,12);
			$CTSUltimaActualizacion = odbc_result($result,13);
			//$cuentaTxt=odbc_result($result,39);
			echo"<tr>";
			echo "<td>$CIdDeposito</td>";
			echo "<td>$cIDCuenta</td>";
			echo "<td>$CImporte</td>";
			echo "<td>$CConceptoGral</td>";
			echo "<td>$CReferencia</td>";
			echo "<td>$CTipoIngreso</td>";
			echo "<td>$CFechaDeposito</td>";
			echo "<td>$CFechaAplicacion</td>";
			echo "<td>$CEstadoConciliado</td>";
			echo "<td>$CEstadoContable</td>";
			echo "<td>$CIDPoliza</td>";
			echo "<td>$CTAlta</td>";
			echo "<td>$CTSUltimaActualizacion</td>";
			echo "</tr>\n";
					  
		} 
		echo("</table>\n");
		odbc_close($conexionbd);
	}
?>