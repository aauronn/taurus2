<?php
	
	$dsn = "Driver={Microsoft Paradox Driver (*.db )};DriverID=538;Fil=Paradox 5.X;DefaultDir=c:\paradox\Empresas\ABSA SONORA 201;Dbq=c:\paradox\Empresas\ABSA SONORA 201;CollatingSequence=ASCII;";

	$conn = odbc_connect($dsn,'','');
	
?>