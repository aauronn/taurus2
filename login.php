<?php
	
	include "include/entorno.inc.php";
	include "include/funciones.inc.php";

	//--------------------------------
	//				LOGIN
	//--------------------------------
	$db->SetFetchMode(ADODB_FETCH_ASSOC); 
	if($_POST["opcion"]=="LOGIN"){
		
		if(isset($_POST["nombreusuario"])&&isset($_POST["password"])){	
			$GLOBALS["nombreusuario"]=$_POST["nombreusuario"];
			$GLOBALS["password"]=$_POST["password"];
			$strsql="SELECT * FROM ctusuarios WHERE UPPER(usuario) = '".strtoupper(textToDB($_POST["nombreusuario"])).
			"' AND UPPER(pass) = '".strtoupper(textToDB($_POST["password"]))."' ";		
			
			if($rs=$db->execute($strsql))
			
			
			{
			
				if($rs->recordCount()>0){				
					$row=$rs->fetchrow();
					/*
					$_SESSION[$GLOBALS["sistema"]] = array();
					$_SESSION[$GLOBALS["sistema"]]['logeado'] = true;
					$_SESSION[$GLOBALS["sistema"]]['id'] = $row["0"];
					$_SESSION[$GLOBALS["sistema"]]['usuario'] = $row['1'];	
					$_SESSION[$GLOBALS["sistema"]]['nombre'] = $row['3'];
					$_SESSION[$GLOBALS["sistema"]]['empresa'] = $row['4'];
					$_SESSION[$GLOBALS["sistema"]]['email'] = $row['5'];
					$_SESSION[$GLOBALS["sistema"]]['telefonos'] = $row['6'];
					$_SESSION[$GLOBALS["sistema"]]['clave_cliente'] = $row['7'];
					$_SESSION[$GLOBALS["sistema"]]['clave_vendedor'] = $row['8'];
					$_SESSION[$GLOBALS["sistema"]]['acceso'] = $row['9'];
					*/
					$_SESSION[$GLOBALS["sistema"]]['logeado'] = true;
					$_SESSION[$GLOBALS["sistema"]]['idusuario'] = $row["idusuario"];
					$_SESSION[$GLOBALS["sistema"]]['usuario'] = $row['usuario'];
					$_SESSION[$GLOBALS["sistema"]]['nombre'] = $row['nombre'];
					$_SESSION[$GLOBALS["sistema"]]['apaterno'] = $row['apaterno'];
					$_SESSION[$GLOBALS["sistema"]]['amaterno'] = $row['amaterno'];
					$_SESSION[$GLOBALS["sistema"]]['empresa'] = $row['empresa'];
					$_SESSION[$GLOBALS["sistema"]]['email'] = $row['email'];
					$_SESSION[$GLOBALS["sistema"]]['telefonos'] = $row['telefonos'];
					$_SESSION[$GLOBALS["sistema"]]['clave_cliente'] = $row['clave_cliente'];
					$_SESSION[$GLOBALS["sistema"]]['clave_vendedor'] = $row['clave_vendedor'];
					$_SESSION[$GLOBALS["sistema"]]['acceso'] = $row['acceso'];
					$_SESSION[$GLOBALS["sistema"]]['idtipousuario'] = $row['idtipousuario'];
					
					
					header("location:index.php");
					
					//print "***************USUARIO**************\n";
					//print $_SESSION[$GLOBALS["sistema"]]['id']."\n";
					//print_r($row);
						//print "\n";	
					//exit;					
				}else{	
					//print $strsql;
					$error_msg = "Nombre de usuario o contraseña incorrectos.";
				}
			}else{
				$error_msg = "Error al conectar con el Servidor: ".$db->errormsg();
			}	
		}else{
			$error_msg = "No se recibieron los datos.";
		}
		//print $error_msg;
	}
	
	
	
	if($_POST["opcion"]=="LOGIN_EXTERNO"){
		if(isset($_POST["nombreusuario"])&&isset($_POST["password"])){	

			$strsql="SELECT * FROM ctusuarios WHERE UPPER(usuario) = '".strtoupper(textToDB($_POST["nombreusuario"])).
			"' AND UPPER(pass) = '".strtoupper(textToDB($_POST["password"]))."' ";		
			//$db->SetFetchMode(ADODB_FETCH_ASSOC); 
			if($rs=$db->execute($strsql)){			
				if($rs->recordCount()>0){				
					$row=$rs->fetchrow();
					$_SESSION[$GLOBALS["sistema"]]['logeado'] = true;
					$_SESSION[$GLOBALS["sistema"]]['idusuario'] = $row["idusuario"];
					$_SESSION[$GLOBALS["sistema"]]['usuario'] = $row['usuario'];
					$_SESSION[$GLOBALS["sistema"]]['nombre'] = $row['nombre'];
					$_SESSION[$GLOBALS["sistema"]]['apaterno'] = $row['apaterno'];
					$_SESSION[$GLOBALS["sistema"]]['amaterno'] = $row['amaterno'];
					$_SESSION[$GLOBALS["sistema"]]['empresa'] = $row['empresa'];
					$_SESSION[$GLOBALS["sistema"]]['email'] = $row['email'];
					$_SESSION[$GLOBALS["sistema"]]['telefonos'] = $row['telefonos'];
					$_SESSION[$GLOBALS["sistema"]]['clave_cliente'] = $row['clave_cliente'];
					$_SESSION[$GLOBALS["sistema"]]['clave_vendedor'] = $row['clave_vendedor'];
					$_SESSION[$GLOBALS["sistema"]]['acceso'] = $row['acceso'];
					$_SESSION[$GLOBALS["sistema"]]['idtipousuario'] = $row['idtipousuario'];
					
					print "ok";	
					exit;					
				}else{	
					//print $strsql;
					$error_msg = "Nombre de usuario o contraseña incorrectos.";
				}
			}else{
				$error_msg = "Error al conectar con el Servidor: ".$db->errormsg();
			}		
		}else{
			$error_msg = "No se recibieron los datos.";
		}
		//print $error_msg;
	}
	
	
	// --------------------------------
	// 		LOGIN
	// --------------------------------
if($_POST["opcion"]=="RELOGIN_USUARIO"){	
	$idusuario = textToDB($_POST["idusuario"]);
	$usuario   = strtoupper(textToDB($_POST["usuario"]));
	$password  = strtoupper(textToDB($_POST["password"]));
	
	if($idusuario=="" || !is_numeric($idusuario)){
		print "Error al recibir el id usuario"; exit;
	}
	if($usuario==""){
		print "Error al recibir el usuario"; exit;
	}
	if($password==""){
		print "Error al recibir el password"; exit;
	}
	
	$strsql="SELECT * FROM ctusuarios WHERE UPPER(usuario) = '$usuario' AND UPPER(pass) = '$password' ";		
			//$db->SetFetchMode(ADODB_FETCH_ASSOC); 
			if($rs=$db->execute($strsql)){			
				if($rs->recordCount()>0){				
					$row=$rs->fetchrow();
					$_SESSION[$GLOBALS["sistema"]]['logeado'] = true;
					$_SESSION[$GLOBALS["sistema"]]['idusuario'] = $row["idusuario"];
					$_SESSION[$GLOBALS["sistema"]]['usuario'] = $row['usuario'];
					$_SESSION[$GLOBALS["sistema"]]['nombre'] = $row['nombre'];
					$_SESSION[$GLOBALS["sistema"]]['apaterno'] = $row['apaterno'];
					$_SESSION[$GLOBALS["sistema"]]['amaterno'] = $row['amaterno'];
					$_SESSION[$GLOBALS["sistema"]]['empresa'] = $row['empresa'];
					$_SESSION[$GLOBALS["sistema"]]['email'] = $row['email'];
					$_SESSION[$GLOBALS["sistema"]]['telefonos'] = $row['telefonos'];
					$_SESSION[$GLOBALS["sistema"]]['clave_cliente'] = $row['clave_cliente'];
					$_SESSION[$GLOBALS["sistema"]]['clave_vendedor'] = $row['clave_vendedor'];
					$_SESSION[$GLOBALS["sistema"]]['acceso'] = $row['acceso'];
					$_SESSION[$GLOBALS["sistema"]]['idtipousuario'] = $row['idtipousuario'];
					
					print "ok";	
		}else{				
			print "no";
		}
	}else{
		print "error: $strsql - ".$db->errormsg();
	}
	exit;
}
	
	
	// --------------------------------
	// 		SESSION
	// --------------------------------
	if($_POST["opcion"]=="SESSION"){
		
		if(!isset($_SESSION[$GLOBALS["sistema"]]['logeado']) || $_SESSION[$GLOBALS["sistema"]]['logeado']!=true){
			//print getXMLErrorMsg("Usted No esta logeado");
			//exit;
			header("location:cerrarsesion.php");
			exit;
		}	
		
		//guardo la visita
		//agregaVisita($db,$_SESSION[$GLOBALS["sistema"]]['idtipousuario'],$_SESSION[$GLOBALS["sistema"]]['idusuario']);
			
		print "<sesiones>";
		print "    <sesion 
					logged='1' 
					id='".textFromDB($_SESSION[$GLOBALS["sistema"]]['idusuario'])."' 				
					usuario='".textFromDB($_SESSION[$GLOBALS["sistema"]]['usuario'])."' 
					nombre='".textFromDB($_SESSION[$GLOBALS["sistema"]]['nombre'])."' 
					apaterno='".textFromDB($_SESSION[$GLOBALS["sistema"]]['apaterno'])."' 
					amaterno='".textFromDB($_SESSION[$GLOBALS["sistema"]]['amaterno'])."' 
					empresa='".textFromDB($_SESSION[$GLOBALS["sistema"]]['empresa'])."' 
					email='".textFromDB($_SESSION[$GLOBALS["sistema"]]['email'])."'
					telefonos='".textFromDB($_SESSION[$GLOBALS["sistema"]]['telefonos'])."'
					clave_cliente='".textFromDB($_SESSION[$GLOBALS["sistema"]]['clave_cliente'])."'
					clave_vendedor='".textFromDB($_SESSION[$GLOBALS["sistema"]]['clave_vendedor'])."'
					acceso='".textFromDB($_SESSION[$GLOBALS["sistema"]]['acceso'])."'
					idtipousuario='".textFromDB($_SESSION[$GLOBALS["sistema"]]['idtipousuario'])."'
			   />\n"; 
		
		//permisos asignados
		$arrPermisos = array();	
		$permisos_user = getPermisosDelUsuario($db,$_SESSION[$GLOBALS["sistema"]]['usuario']);	
		if(count($permisos_user)>0){	
			foreach ($permisos_user as $permiso) {
				$arrPermisos[] = $permiso["clavepermiso"];
				print "<permiso_a id='".textFromDB($permiso['clavepermiso'])."' nombre='".textFromDB($permiso['nombrepermiso'])."' descripcion='".textFromDB($permiso['descripcion'])."'/>\n";
			}
		}
		else{
			print "<permiso_a id='-1' nombre='No hay permisos asignados.' descripcion='Este usuario no tiene permisos asignados, seleccione algunos de la lista de la izquierda y asignelos arrastrandolos a la lista de la derecha.'/>\n";
		}
		
		print "\n";
		
		print "</sesiones>";
		exit();
	}
	
	if($_POST["opcion"]=="TRAER_USUARIO"){
		$usuario = strtoupper(textToDB($_POST["usuariot"]));
		
		$strsql = "SELECT * FROM ctusuarios WHERE 1=1 AND usuario='$usuario'";
		
		print getXML($strsql, $db);
	}
	
		// --------------------------------
		// 		CERRAR SESION
		// --------------------------------
	if($_POST["opcion"]=="CERRAR_SESSION"){	
		unset($_SESSION[$GLOBALS["sistema"]]);
		session_destroy();
		print "ok";
		exit;
	}
	
		// --------------------------------
		// 		REVISA SESION
		// --------------------------------
	if($_POST["opcion"]=="REVISA_SESION"){	
		if(!$_SESSION[$GLOBALS["sistema"]]['logeado']||$_SESSION[$GLOBALS["sistema"]]['logeado']=='' || !$_SESSION[$GLOBALS["sistema"]]['idusuario']||$_SESSION[$GLOBALS["sistema"]]['idusuario']=='') 
			print "no";
		else 
			print "ok";
		exit;
	}
	
?>
	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=latin1" />
	<title><?php print $GLOBALS["nombre_sistema_completo"] . " - " . $GLOBALS["nombre_institucion_completo"] ?></title>
	<link href="login.css" rel="stylesheet" type="text/css" />
		<script language="javascript">
			function login(){
				document.forma_login.opcion.value = "LOGIN";
				document.forma_login.submit();
			}
		
			function checkKeyUser(e){
				var intKeyCode = 0;
				if (navigator.appName == "Microsoft Internet Explorer"){
					if (window.event.keyCode == 13){
						setTimeout(function(){
							document.getElementById("password").focus();
						}, 100);
					}
				}
				if (navigator.appName == "Netscape"){
					intKeyCode = e.which;
					if (intKeyCode == 13){
						setTimeout(function(){
							document.getElementById("password").focus();
						}, 100);
					}
				}
			}
			
			function checkKeyPassword(e){
				var intKeyCode = 0;
				if (navigator.appName == "Microsoft Internet Explorer"){
					if (window.event.keyCode == 13){
						document.forma_login.opcion.value = "LOGIN";
						document.forma_login.submit();
					}
				}
				if (navigator.appName == "Netscape"){
					intKeyCode = e.which;
					if (intKeyCode == 13){
						document.forma_login.opcion.value = "LOGIN";
						document.forma_login.submit();
					}
				}
			}
		</script>
	</head>
	<body>
		<form id="forma_login" name="forma_login" method="post" action="login.php">
			<div align="center">
				<div id="login-box">
					<H2>Iniciar Sesi&oacute;n</H2>
					<a class="letras"><?php print strtoupper($GLOBALS["nombre_sistema_completo"]); ?></a>
					<br />
					<br />
					<div id="login-box-name" style="margin-top:20px;">Usuario:</div><div id="login-box-field" style="margin-top:20px;"><input id="nombreusuario" name="nombreusuario" class="form-login" title="Username" value="" size="30" maxlength="2048" onKeyPress="javascript:checkKeyUser(event)" /></div>
					<div id="login-box-name">Contrase&ntilde;a:</div><div id="login-box-field"><input id="password" name="password" type="password" class="form-login" title="Password" size="30" maxlength="2048" onKeyPress="javascript:checkKeyPassword(event)" /></div>
					<br />
					<br />
					<br />
					<a href="javascript:login();"><img src="imagenes/login-btn.png" width="103" height="42" style="margin-left:90px;" /></a>
					<div style="color:#FF0000; font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-weight:bold; padding-left:90px; line-height:12px; text-align:left; padding-top:10px;"><?php if(isset($error_msg) && $error_msg != "") print $error_msg;?></div>
				</div>
			</div>
		<input type="hidden" name="opcion" id="opcion"/>
		</form>
	</body>
</html>
