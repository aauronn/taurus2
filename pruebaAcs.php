<?php
	include "include/entorno.inc.php";
	include "include/funciones.inc.php";	
  
	$strConsultaMovtos = "select * FROM movtos";
	$strConsultaMovPolCh  = "SELECT * FROM MovPolCh";
	$strConsultaPolChqpW  = "SELECT * FROM PolChqpW";
	$strConsultaDepositos  = "SELECT * FROM Depositos";
	
	$borrarMovtos 	= "DELETE FROM movtos where 1=1";
	$borrarMovPolCh = "DELETE FROM MovPolCh where 1=1";
	$borrarPolchqpW	= "DELETE FROM PolChqpW where 1=1";
	$borrarDepositos= "DELETE FROM Depositos where 1=1";
	
	$strQuery="INSERT INTO movtos (Movto, Cuenta) VALUES (100, 1)";
	
	
	
	//$rsBorrado = 
	//$dbAcs -> execute($borrarMovtos);
	//; ; 
	
	print getXML($strConsultaDepositos, $dbAcs);

?>