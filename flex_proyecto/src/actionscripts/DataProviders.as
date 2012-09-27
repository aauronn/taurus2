package actionscripts
{
	import actionscripts.DataProvidersEvent;
	
	import components.SuperGrid.SuperGrid;
	import components.SuperPlayer.SuperPlayer;
	import components.loaderBar;
	
	import flash.display.DisplayObject;
	import flash.utils.Dictionary;
	
	import flex_modulos.VentanaRelogin;
	
	import mx.collections.ArrayCollection;
	import mx.controls.Alert;
	import mx.controls.ComboBox;
	import mx.core.UIComponent;
	import mx.events.ListEvent;
	import mx.managers.PopUpManager;
	import mx.messaging.messages.ErrorMessage;
	import mx.messaging.messages.HTTPRequestMessage;
	import mx.rpc.Fault;
	import mx.rpc.events.FaultEvent;
	import mx.rpc.events.ResultEvent;
	import mx.rpc.http.HTTPService;
	import mx.utils.ObjectProxy;
	
	
	
	public class DataProviders extends UIComponent
	{
		[Bindable] public var modPopup:*;
		[Bindable] public var Icons:Iconos = new Iconos();
		private  var ServicesHandler:HTTPService;
		[Bindable] public static var ventanaLoader:loaderBar;
		[Bindable] public var  numWsCargando:int=0;
		[Bindable] public var ventanaLoaderActiva:Boolean=false;
		public var revisandoSesion:Boolean=false;	
		public var logOut:Boolean=false;
		[Bindable]private var ObjWS:Object=new Object();
		
		[Bindable] public var _padreLoader:loader;
		
		
		// Permisos
		//public var arrPermisos:Array=new Array();
		public var arrPermisos:Dictionary = new Dictionary();		
		
		//Variables que se usan en el filtrado de los Combos
		[Bindable] private static var arrcPadre:ArrayCollection;
		[Bindable] private static var arrcHijo:ArrayCollection;
		[Bindable] private static var _comboPadre:ComboBox;
		[Bindable] private static var _comboHijo:ComboBox;
		[Bindable] private static var _filterField:Array;
		
		
		////////////////////////////////
		//
		//      ARRAYS COMBOBOX
		//
		////////////////////////////////
		public var arrTipoUsuarios:ArrayCollection					=	new ArrayCollection();
		public var arrCiudades:ArrayCollection 						= 	new ArrayCollection();
		public var arrMapeos:ArrayCollection						=	new ArrayCollection();
		public var arrFamilias:ArrayCollection 						= 	new ArrayCollection();
		public var arrEspecialistas:ArrayCollection 				=	new ArrayCollection();
		public var arrUltimoProyecto:ArrayCollection 				=	new ArrayCollection();
		public var arrPeriodoNommina:ArrayCollection 				=	new ArrayCollection();
		public var arrSBU:ArrayCollection 							=	new ArrayCollection();
		public var arrFabricantes:ArrayCollection 					=	new ArrayCollection();
		public var arrVendedoresProyecto:ArrayCollection			=	new ArrayCollection();
		public var arrVendedoresProyectoAsignados:ArrayCollection	=	new ArrayCollection();
		
		public function DataProviders()
		{
			super();
		}
		
		private function generarWS(str:String):void{
			//Función que manda los HTTPService 
			var ws:HTTPService=new HTTPService();
			var obj:Object=datosWS(str);
			var cadena:String="";
			ws.resultFormat='object';
			ws.method='POST';
			ws.url=obj.url;
			for (cadena in obj){
				if(cadena!="url"){
					if(cadena=="resultFormat"){
						ws.resultFormat=obj[cadena];
					}else{
						ws.request[cadena]=obj[cadena];
					}
				}
			}
			ws.requestTimeout=30;
			ws.addEventListener(ResultEvent.RESULT,resultWS);
			ws.addEventListener(FaultEvent.FAULT,faultHandler);
			ws.send();
		}
		
		private function datosWS(str:String):Object{
			//Datos que se usaran como REQUEST al realizar el HTTPService 
			switch(str.toLowerCase()){
				case 'tipousuarios'					: return {opcion:"CAT_TIPO_USUARIOS", tipoWS:str.toLowerCase(), url: loader.strHostUrl + "ws_tipo_usuarios.php"}; break;
				case 'ciudades'						: return {opcion:"CAT_CIUDADES", tipoWS:str.toLowerCase(), url: loader.strHostUrl + "ws_ciudades.php"}; 				break;
				case 'mapeos'						: return {opcion:"CAT_MAPEOS", tipoWS:str.toLowerCase(), url: loader.strHostUrl + "ws_mapeos.php"}; 					break;
				case 'familias'						: return {opcion:"CAT_FAMILIAS", tipoWS:str.toLowerCase(), url: loader.strHostUrl + "ws_familias.php"}; 				break;
				case 'especialistas'				: return {opcion:"GET_ESPECIALISTAS", tipoWS:str.toLowerCase(), url: loader.strHostUrl + "ws_usuarios.php"}; 			break;
				case 'ultimoproyecto'				: return {opcion:"GET_ULTIMOPROYECTO", tipoWS:str.toLowerCase(), url: loader.strHostUrl + "ws_proyectos.php"}; 			break;
				case 'periodonomina'				: return {opcion:"GET_PERIODO", tipoWS:str.toLowerCase(), url: loader.strHostUrl + "ws_nominas.php"}; 					break;
				case 'sbu'							: return {opcion:"GET_SBU", tipoWS:str.toLowerCase(), url: loader.strHostUrl + "ws_sbu.php"}; 							break;
				case 'fabricantes'					: return {opcion:"CAT_FABRICANTES", tipoWS:str.toLowerCase(), url: loader.strHostUrl + "ws_fabricantes.php"}; 			break;
				case 'vendedoresproyecto'			: return {opcion:"GET_VENDEDORES", tipoWS:str.toLowerCase(), url: loader.strHostUrl + "ws_proyectos.php"}; 				break;
				case 'vendedoresproyectoasignados'	: return {opcion:"GET_VENDEDORES_ASIGNADOS", tipoWS:str.toLowerCase(), url: loader.strHostUrl + "ws_proyectos.php"};	break;
				default								: return {}; 																											break;
			}
		}
		
		public function hacerWS(arr:Array,refresh:Boolean=false):void{
			//Función que se encarga de pedir los HTTPService
			if(arr.length>0){
				var str:String=arr.pop();
				
				//Realizar Httpservice uno X uno
				ObjWS.arrWS=arr;
				ObjWS.refresh=refresh;
				
				if(refresh){
					if(!validarWS(str)){
						generarWS(str);
					}
					else{
						if(arr.length>0){
							hacerWS(arr,refresh);
						}
						else{
							dispatchEvent(new DataProvidersEvent({}));
						}
					}	
				}
				else{
					if(validarWS(str)){
						generarWS(str);
					}
					else{
						if(arr.length>0){
							hacerWS(arr,refresh);	
						}
						else{
							dispatchEvent(new DataProvidersEvent({}));
						}
						
					}
				}
			}		
		}
		
		private function validarWS(str:String):Boolean{
			//Función que se usan para ver si se pide o no un HTTPService 
			switch(str.toLowerCase()){
				case 'tipousuarios'					: return arrTipoUsuarios.length==0; 				break;
				case 'ciudades'						: return arrCiudades.length==0;						break;
				case 'mapeos'						: return arrMapeos.length==0;						break;
				case 'familias'						: return arrFamilias.length==0;						break;
				case 'especialistas'				: return arrEspecialistas.length==0;				break;
				case 'periodonomina'				: return arrPeriodoNommina.length==0;				break;
				case 'sbu'							: return arrSBU.length==0;							break;
				case 'fabricantes'					: return arrFabricantes.length==0;					break;
				case 'vendedoresproyecto'			: return arrVendedoresProyecto.length==0;			break;
				case 'vendedoresproyectoasignados'	: return arrVendedoresProyectoAsignados.length==0;	break;
				
				default								: return false; 									break;
			}
		}
		
		private function resultWS(event:ResultEvent):void{
			//Función que se usan para llenar los datos en los arreglos que se utilizaran para los combos
			try{
				//var wsTarget:HTTPService=event.target as HTTPService;
				//switch(wsTarget.request.tipoWS)
				switch (String(event.target.request.tipoWS)){
					case 'tipousuarios'					: arrTipoUsuarios					= Utils.getRowsEvent(event);break;
					case 'ciudades'						: arrCiudades 						= Utils.getRowsEvent(event);break;
					case 'mapeos'						: arrMapeos 						= Utils.getRowsEvent(event);break;
					case 'familias'						: arrFamilias 						= Utils.getRowsEvent(event);break;
					case 'especialistas'				: arrEspecialistas 					= Utils.getRowsEvent(event);break;
					case 'periodonomina'				: arrPeriodoNommina					= Utils.getRowsEvent(event);break;
					case 'sbu'							: arrSBU							= Utils.getRowsEvent(event);break;
					case 'fabricantes'					: arrFabricantes					= Utils.getRowsEvent(event);break;
					case 'vendedoresproyecto'			: arrVendedoresProyecto 			= Utils.getRowsEvent(event);break;
					case 'vendedoresproyectoasignados'	: arrVendedoresProyectoAsignados	= Utils.getRowsEvent(event);break;
					default								: 																break;
				}
				
				if(ObjWS!=null && (ObjWS.arrWS as Array).length>0){
					hacerWS(ObjWS.arrWS,ObjWS.refresh);
				}
				else{
					dispatchEvent(new DataProvidersEvent({}));
				}	
			}
			
			catch(ex:Error){
				loader.msgAviso("Error->DataProviders->resultWS",3,ex);
				dispatchEvent(new DataProvidersEvent({}));
			}
		}
		
		public function faultHandler(evento:FaultEvent,mod:*=null):void{
			try{
				removeLoader(true);
				modPopup=mod;
				ServicesHandler=new HTTPService;
				ServicesHandler=evento.target as HTTPService;
				loader.msgReintentar("Ocurrió un problema al recibir los Resultados:\n\n-"+(evento.message as ErrorMessage).faultString, errorHandler);	
			}catch(e:Error){
				loader.msgAviso("Error->DP->FaultHandler: Ocurrió un problema en los datos recibidos",3,e);	
			}
		}
		
		private function errorHandler(evento:Object):void{        	
			if(evento.detail == Alert.YES) {
				ServicesHandler.send();	
				numWsCargando++;
			}
			if(evento.detail == Alert.NO) {
				//removeLoader(true);
				ServicesHandler=new HTTPService();
				if(modPopup==null){
					//this.parentApplication.enabled=true;
				}else{
					modPopup.enabled=true;
				}
			}
		}
		
		
		public static function insertarSource(arrCol:ArrayCollection,item:ObjectProxy,pos:int=0):Array {
			var arreglo:Array=new Array();
			if(arrCol.source.length>0){
				for(var i:int=0; i<arrCol.source.length; i++){
					if(i==pos){
						arreglo.push(item);
					}
					arreglo.push(arrCol.source[i]);
				}
			}
			else{
				arreglo.push(item);
			}
			return arreglo;	
		}
		
		
		
		
		public function cargaLoader(mod:*):void{
			try{        	
				if(!ventanaLoaderActiva){	        		
					ventanaLoader=new loaderBar();
					PopUpManager.addPopUp(ventanaLoader,UIComponent(mod.parentApplication),true);            	
					PopUpManager.centerPopUp(ventanaLoader);
					ventanaLoaderActiva=true;
				}
			}catch(e:Error){
				//loader.msgAviso("Error al cargar el Loader: "+e.message,3);
				ventanaLoaderActiva=false;
			}
		}
		
		public function removeLoader(forzarCerrado:Boolean=false):void{
			try{
				numWsCargando--;
				
				if(numWsCargando<=1 || forzarCerrado){
					PopUpManager.removePopUp(ventanaLoader);
					ventanaLoaderActiva=false;
					if(forzarCerrado) numWsCargando=0;
				}	
			}catch(e:Error){
				loader.msgAviso("Error al quitar el loader: "+e.message,3);
			}
		}
		
		
		
		
		
		
		
		
		/*********** WS Revisa Sesion **************/
		public var httpS:HTTPService;
		public function reintentarHttpService(aviso:String,miService:HTTPService,e:*=null):void{
			try{
				loader.dp.removeLoader();	
				loader.dp.numWsCargando=0;
				
				if(e && loader.debug){
					if(e is FaultEvent || e is ErrorMessage){
						if(e.message is HTTPRequestMessage){
							if(e.fault is Fault){
								aviso += "\n\n"+(e.fault as Fault).faultDetail;
							}
						}else{
							aviso += "\n\n"+(e.message as ErrorMessage).faultString;
						}
					}else{
						aviso+="\n\n"+e.message;
					}
				}
				
				httpS=miService;
				loader.msgReintentar(aviso, reintentarSINO);	
			}catch(ex:Error){
				loader.msgAviso("Error->reintentarHttpService",3,ex);	
			}	
		}
		
		public function reintentarSINO(evento:Object):void{
			if(evento.detail == Alert.YES){
				httpS.send();	
			}
		}
		
		
		public function cargaService(https:HTTPService):void{				
			//revisaSesion(https);
			https.send();
		}
		
		public function revisaSesion(https:HTTPService):void{
			if(logOut) return void;
			
			numWsCargando++;
			if(!revisandoSesion)
			{
				revisandoSesion=true;
				
				ServicesHandler=new HTTPService;
				ServicesHandler=https;
				
				sendRevisaSesion();
			}else{
				https.send();
			}
		}
		
		public function sendRevisaSesion():void{
			var wsRevisaSesion:HTTPService = new HTTPService();
			wsRevisaSesion.resultFormat='text';
			wsRevisaSesion.method='POST';
			wsRevisaSesion.url = loader.strHostUrl + "login.php";
			wsRevisaSesion.request.opcion='REVISA_SESION';
			wsRevisaSesion.addEventListener(ResultEvent.RESULT,resultRevisaSesion);
			wsRevisaSesion.addEventListener(FaultEvent.FAULT,faultRevisaSesion);
			wsRevisaSesion.send();	
		}
		
		public function faultRevisaSesion(event:FaultEvent):void{
			revisandoSesion=false;
			numWsCargando=0;
			removeLoader();
			loader.msgAviso("Ocurrió un problema al revisar la sesión:\n\n"+(event.message as ErrorMessage).faultString,3);
		}
		
		public function resultRevisaSesion(event:ResultEvent):void{		
			if(String(event.result)=="ok"){				
				ServicesHandler.send();            	
			}else{
				logOut = true;
				revisandoSesion=false;
				numWsCargando=0;
				removeLoader();
				
				if(String(event.result)=="no"){		
					logOut=true;		
					
					var ventana:VentanaRelogin = new VentanaRelogin();
					PopUpManager.addPopUp(ventana,_padreLoader as DisplayObject,true);	        		
					PopUpManager.centerPopUp(ventana);	            	
				}else{
					loader.msgReintentar("Problema al conectar a la base de datos.",reintentarLoginSINO);
				}            	
				logOut = false;
			}
			revisandoSesion=false;
		}
		
		public function reintentarLoginSINO(evento:Object):void{
			if(evento.detail == Alert.YES){
				sendRevisaSesion();	
			}
		}
		/*********** WS Revisa Sesion **************/
		
		
		
		
		
		public function cierraSesion(evento:Object):void
		{
			Utils.goToUrl(loader.strHostUrl + 'cerrarsesion.php')	
		}
		
		
		
		public function checarWS():void{
			this.dispatchEvent(new linkEvent(new Object));	
		}
		
		
		
		
		
		
		/*******************************
		 * 
		 * REVISA PERMISOS
		 * 
		 * *****************************/
		
		public function revisaPermisos(arrDatos:ArrayCollection):void{
			var obj:Object;
			var existe:Boolean;
			var permiso:String;
			var encontrado:Boolean;
			var numPermisosEncontrados:int=0;
			
			for(var i:int=arrDatos.length-1;i>=0;i--){
				obj = arrDatos[i];
				
				if(obj.children){
					revisaPermisos(new ArrayCollection(obj.children));
				}
				
				if((obj.permiso && obj.permiso!="")){
					permiso = String(obj.permiso);
					numPermisosEncontrados = 0;
					
					var arrP:Array = permiso.split(",");
					
					for(var k:int=0; k<arrP.length; k++){
						if(String(arrP[k]).indexOf("-") != -1){
							var arrP2:Array = String(arrP[k]).split("-");
							if(arrP2.length!=2){
								loader.msgAviso("Error con el permiso especificado ["+arrP[k]+"] para modulo: "+obj.label,2); 
							}else{
								if(String(arrP2[0])=="" || String(arrP2[1])==""){
									loader.msgAviso("Error con el permiso especificado ["+arrP[k]+"] para modulo: "+obj.label,2);
								}else{										
									for(var c:int=int(arrP2[0]); c<=int(arrP2[1]); c++){
										if(arrPermisos[String(c)]){
											numPermisosEncontrados++;
											break;
										}
									}
								} 
							}									
						}else{
							if(String(arrP[k])==""){
								loader.msgAviso("Error con el permiso especificado ["+arrP[k]+"] para modulo: "+obj.label,2);
							}else{
								if(arrPermisos[String(arrP[k])]){
									numPermisosEncontrados++;
									break;
								}
							}
						}	
					}
					
					//si no tiene al menos un permiso del modulo, quitar boton
					if(numPermisosEncontrados==0){
						arrDatos.removeItemAt(arrDatos.getItemIndex(obj));
					}
				}					
			}
			var uno:int=1;													 						
		}
		
		
		
		public function revisaPermisosGrid(GD:SuperGrid, idConsulta:String, idNuevo:String, idEditar:String, idBorrar:String, idCopiar:String, idImprimir:String):void{
			if(idConsulta!="" && !loader.dp.arrPermisos[idConsulta]) GD.quitarConsultar();
			if(idNuevo!=""    && !loader.dp.arrPermisos[idNuevo])    GD.quitarNuevo();
			if(idEditar!=""   && !loader.dp.arrPermisos[idEditar])	 GD.quitarEditar();
			if(idBorrar!=""   && !loader.dp.arrPermisos[idBorrar])   GD.quitarBorrar();
			if(idCopiar!=""   && !loader.dp.arrPermisos[idCopiar])   GD.quitarCopiar();
			if(idImprimir!="" && !loader.dp.arrPermisos[idImprimir]) GD.quitarImprimir();
		}
		
		
		public static function selectedIndexCombo(combo:ComboBox,campo:String,valor:String,changeBubble:Boolean = true):void{
			var flag:Boolean=false;
			var dp:ArrayCollection=(combo.dataProvider as ArrayCollection);
			for(var i:int; i<dp.length; i++){
				if(!flag && String(dp[i][campo]) == valor){
					combo.selectedIndex=i;
					flag=true;		
				}
			}
			if(!flag){
				//combo.selectedIndex=-1;
			}
			if(changeBubble){
				combo.dispatchEvent(new ListEvent(ListEvent.CHANGE));
			}
		}
		
		public static function filtrarCombo(comboPadre:ComboBox, comboHijo:ComboBox, filterField:Array):void{
			//Función que manda los datos de 2 combos dependientes uno del otro a una función de filtrado
			_comboPadre = comboPadre;
			_comboHijo = comboHijo;
			var prompt:String=_comboHijo.prompt;
			_filterField = filterField;
			arrcPadre = comboPadre.dataProvider as ArrayCollection;
			arrcHijo = comboHijo.dataProvider as ArrayCollection;				
			arrcHijo.filterFunction = comboFilterFunction;
			arrcHijo.refresh();
			
			if(prompt!=null && prompt!=''){
				_comboHijo.selectedIndex=-1;
				_comboHijo.prompt=prompt;
			}
			else{	
				_comboHijo.selectedIndex=0;
			}
		} 
		
		private static function comboFilterFunction(item:Object):Boolean{
			//Función que filtra el combo dependientes segun uno o más campos de filtrado			
			var campo:String="";
			var flag:Boolean=false;
			
			for (campo in _filterField){
				if(_comboPadre.selectedItem!=null){
					if(String(item[_filterField[campo]]).toLowerCase()=='todos' || String(item[_filterField[campo]]).toLowerCase()==''){
						flag=true;
					}
					else{
						if(item[_filterField[campo]] == _comboPadre.selectedItem[_filterField[campo]]){
							flag=true;
						}
						else{
							flag=false;
							break;
						}
					}	
				}
				else{
					flag=false;
					break;
				}		
			}
			return flag;
		}
		
		
		
		public function resultArchivosSuperPlayer(event:ResultEvent):void{			
			try{
				
				//Obtenemos los archivos
				var arrArchivos:ArrayCollection = Utils.getArchivosFromEvent(event); 
				if(arrArchivos){
					if(arrArchivos.length>0){
						//Abrimos el SuperPlayer
						var ventana:SuperPlayer = new SuperPlayer();
						ventana.arrArchivos = arrArchivos.toArray();
						PopUpManager.addPopUp(ventana, _padreLoader as DisplayObject, true);
						PopUpManager.centerPopUp(ventana);
					}else{
						loader.msgAviso("No se recibieron archivos");
					}
				}
				
			}catch(ex:Error){
				loader.dp.reintentarHttpService("Error->DP->resultArchivosSuperPlayer-> Ocurrió un problema al recibir los archivos", event.currentTarget as HTTPService, ex);
			}
			
			loader.dp.removeLoader();			
		}
		
	}
}