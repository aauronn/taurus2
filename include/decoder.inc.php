<?php
	include_once("./include/config.inc.php");
	include_once("./include/funciones.inc.php");
	
	if(isset($_POST["encrypted"]) && $_POST["encrypted"] != ""){		
		$decryptedXML = decrypt($_POST["encrypted"], $GLOBALS['RC4_KEY']);	// DECODIFICAMOS LAS VARIABLES POST ESCONDIDAS DENTRO DE $_POST["encrypted"]				
		$decryptedArr = xml2array($decryptedXML);
		
		foreach($decryptedArr["data"] as $req => $val){
			$_POST[$req] = $val;
		}
		
		$_POST["encrypted"] = true;
	}
	
	if(isset($_GET["encrypted"]) && $_GET["encrypted"] != ""){		
		$decryptedXML = decrypt($_GET["encrypted"], $GLOBALS['RC4_KEY']);	// DECODIFICAMOS LAS VARIABLES GET ESCONDIDAS DENTRO DE $_POST["encrypted"]
		$decryptedArr = xml2array($decryptedXML);
		
		foreach($decryptedArr["data"] as $req => $val){
			$_GET[$req] = $val;
		}
		
		$_GET["encrypted"] = true;
	}
?>