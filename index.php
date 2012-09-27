<?php
	include "include/entorno.inc.php";
	header("Cache-Control: no-cache, must-revalidate");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	
	if(!isset($_SESSION[$GLOBALS["sistema"]]) || $_SESSION[$GLOBALS["sistema"]]['logeado'] !== true){
		header("location:login.php");
		//print "Me fui a login";
	}
	else{
		include "include/funciones.inc.php";
		include "include/conexionbd.inc.php";
		$SSID = $_REQUEST['PHPSESSID'];
		//hacerArchivo2($_REQUEST['PHPSESSID']);
		/*
		$strRtmpAddressChat = $GLOBALS["rtmpAddressChat"];
		$strRtmpAddressUploader = $GLOBALS["rtmp_address"];
		$chat_jdbcURL = $GLOBALS["jdbcURL"];
		$chat_bandwidth =$GLOBALS["bandwidth"];
		$chat_quality =$GLOBALS["quality"];
		$chat_fps =$GLOBALS["fps"];
		$chat_kfi =$GLOBALS["kfi"];
		$chat_width =$GLOBALS["width"];
		$chat_height =$GLOBALS["height"];
		$chat_prefixNombreSo =$GLOBALS["prefixNombreSo"];	
		$chat_videollamada =$GLOBALS["_videollamada"];
		$chat_asignar =$GLOBALS["_asignar"];
		//$revision = getRevision2(); 
		//$fileSize = ini_get('upload_max_filesize');
		//print "me fui al else";
		*/
	}
	
	
	
	//// POSTS /////
	$arrPosts = array();
	$arrPosts["SSID"]				 = $SSID;
	$arrPosts["strHostUrl"]			 = $GLOBALS["url_host"];
	$arrPosts["strNombreSistema"]	 = $GLOBALS["nombre_sistema_completo"];
	$arrPosts["strUsernameExterno"]	 = $GLOBALS["nombreusuario"];
	$arrPosts["atnc_password"]		 = $GLOBALS["password"];
	$arrPosts["tempArchivosUrl"]	 = $GLOBALS["temp_archivos_url"];
	$arrPosts["documentosUrl"]		 = $GLOBALS["documentos_url"];
	$arrPosts["archivosUploadUrl"]   = $GLOBALS["archivos_upload_url"];
	/*$arrPosts["chat_jdbcURL"]		 = $chat_jdbcURL;
	$arrPosts["chat_bandwidth"]		 = $chat_bandwidth;
	$arrPosts["chat_quality"]		 = $chat_quality;
	$arrPosts["chat_fps"]			 = $chat_fps;
	$arrPosts["chat_kfi"]			 = $chat_kfi;
	$arrPosts["chat_width"] 		 = $chat_width;
	$arrPosts["chat_height"] 		 = $chat_height;
	$arrPosts["chat_prefixNombreSo"] = $chat_prefixNombreSo;
	$arrPosts["chat_videollamada"]	 = $chat_videollamada;
	$arrPosts["chat_asignar"] 		 = $chat_asignar;
	*/
	
	$strPOST = "";
	foreach ($arrPosts as $k=>$v){
		if($strPOST != "") $strPOST .= "&";
		$strPOST .= "$k=$v";
	}
	
	if($strPOST!="") $strPOST = "?".$strPOST;
	
	
	/*
	 	$strPOST  =>   ?SSID=<?php print $SSID; ?>&strHostUrl=<?php print $GLOBALS["url_host"]; ?>&strNombreSistema=<?php print $GLOBALS["nombre_sistema_completo"]; ?>&rtmpAddress=<?php print $rtmpAddress; ?>&archivosUploadUrl=<?php print $GLOBALS["archivos_upload_url"]; ?>&tempArchivosUrl=<?php print $GLOBALS["temp_archivos_url"]; ?>&documentosUrl=<?php print $GLOBALS["documentos_url"]; ?>&rtmpAddress=<?php print $strRtmpAddressUploader; ?>&strRtmpAddressChat=<?php print $strRtmpAddressChat; ?>&chat_jdbcURL=<?php print $chat_jdbcURL; ?>&chat_bandwidth=<?php print $chat_bandwidth; ?>&chat_quality=<?php print $chat_quality; ?>&chat_fps=<?php print $chat_fps; ?> &chat_kfi=<?php print $chat_kfi; ?>&chat_width=<?php print $chat_width; ?>&chat_height=<?php print $chat_height; ?>&chat_prefixNombreSo=<?php print $chat_prefixNombreSo; ?>&chat_videollamada=<?php print $chat_videollamada; ?>&chat_asignar=<?php print $chat_asignar; ?>
	*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0014)about:internet -->
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en"> 
    <head>
        <title><?php print $GLOBALS["nombre_sistema_completo"] . " - " . $GLOBALS["nombre_institucion_completo"] ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=latin1" />       
        <!-- Include CSS to eliminate any default margins/padding and set the height of the html element and 
             the body element to 100%, because Firefox, or any Gecko based browser, interprets percentage as 
             the percentage of the height of its parent container, which has to be set explicitly.  Fix for
             Firefox 3.6 focus border issues.  Initially, don't display flashContent div so it won't show 
             if JavaScript disabled.
        -->
        <style type="text/css" media="screen"> 
            html, body  { height:100%; }
            body { margin:0; padding:0; overflow:auto; text-align:center; 
                   background-color: #ffffff; }   
            object:focus { outline:none; }
            #flashContent { display:none; }
        </style>
        
        <!-- Enable Browser History by replacing useBrowserHistory tokens with two hyphens -->
        <!-- BEGIN Browser History required section -->
        <link rel="stylesheet" type="text/css" href="history/history.css" />
        <script type="text/javascript" src="history/history.js"></script>
        <!-- END Browser History required section -->  
            
        <script type="text/javascript" src="swfobject.js"></script>
        <script type="text/javascript">
            // For version detection, set to min. required Flash Player version, or 0 (or 0.0.0), for no version detection. 
            var swfVersionStr = "10.2.0";
            // To use express install, set to playerProductInstall.swf, otherwise the empty string. 
            var xiSwfUrlStr = "playerProductInstall.swf";
            var flashvars = {};
            var params = {};
            params.quality = "high";
            params.bgcolor = "#ffffff";
            params.allowscriptaccess = "sameDomain";
            params.allowfullscreen = "true";
            var attributes = {};
            attributes.id = "loader";
            attributes.name = "loader";
            attributes.align = "middle";
            swfobject.embedSWF(
                "loader.swf<?php print $strPOST; ?>", "flashContent", 
                "100%", "100%", 
                swfVersionStr, xiSwfUrlStr, 
                flashvars, params, attributes);
            // JavaScript enabled so display the flashContent div in case it is not replaced with a swf object.
            swfobject.createCSS("#flashContent", "display:block;text-align:left;");
        </script>
		<script language="JavaScript" type="text/javascript">
			// This function returns the appropriate reference, depending on the browser.
			
			function getFlexApp(appName) {
				if (navigator.appName.indexOf ("Microsoft") !=-1) {
					return window[appName];
				} 
				else {
					return document[appName];
				}
			}
			
			function goCallFlex(url){
				getFlexApp("loader").setVideoURL(url);
			}
		</script>
    </head>
    <body>
        <!-- SWFObject's dynamic embed method replaces this alternative HTML content with Flash content when enough 
             JavaScript and Flash plug-in support is available. The div is initially hidden so that it doesn't show
             when JavaScript is disabled.
        -->
        <div id="flashContent">
            <p>
                To view this page ensure that Adobe Flash Player version 
                10.2.0 or greater is installed. 
            </p>
            <script type="text/javascript"> 
                var pageHost = ((document.location.protocol == "https:") ? "https://" : "http://"); 
                document.write("<a href='http://www.adobe.com/go/getflashplayer'><img src='" 
                                + pageHost + "www.adobe.com/images/shared/download_buttons/get_flash_player.gif' alt='Get Adobe Flash player' /></a>" ); 
            </script> 
        </div>
        
        <noscript>
            <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
			id="loader" width="100%" height="100%"
			codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab">
			<param name="movie" value="loader.swf<?php print $strPOST; ?>"/>
			<param name="quality" value="high" />
			<param name="bgcolor" value="#869ca7" />
			<param name="allowScriptAccess" value="always" />
			<param name="allowFullScreen" value="true" />
			<embed src="loader.swf<?php print $strPOST; ?>" quality="high" bgcolor="#869ca7"
				width="100%" height="100%" name="loader" align="middle"
				play="true"
				loop="false"
				quality="high"
				allowScriptAccess="always"
				allowFullScreen="true"
				type="application/x-shockwave-flash"
				pluginspage="http://www.adobe.com/go/getflashplayer">
			</embed>
	</object>
        </noscript>     
   </body>
</html>
