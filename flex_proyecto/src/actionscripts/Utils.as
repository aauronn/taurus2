package actionscripts
{
	import com.hurlant.crypto.Crypto;
	import com.hurlant.crypto.symmetric.ICipher;
	import com.hurlant.crypto.symmetric.NullPad;
	import com.hurlant.util.Hex;
	
	import flash.display.DisplayObject;
	import flash.events.Event;
	import flash.events.HTTPStatusEvent;
	import flash.events.IEventDispatcher;
	import flash.events.IOErrorEvent;
	import flash.events.MouseEvent;
	import flash.events.ProgressEvent;
	import flash.events.SecurityErrorEvent;
	import flash.events.TimerEvent;
	import flash.net.FileReference;
	import flash.net.URLRequest;
	import flash.net.URLVariables;
	import flash.net.navigateToURL;
	import flash.utils.ByteArray;
	import flash.utils.Timer;
	import flash.xml.XMLDocument;
	import flash.xml.XMLNode;
	import flash.xml.XMLNodeType;
	
	import mx.collections.ArrayCollection;
	import mx.collections.XMLListCollection;
	import mx.containers.TitleWindow;
	import mx.containers.VBox;
	import mx.controls.Alert;
	import mx.controls.Button;
	import mx.controls.ComboBase;
	import mx.controls.DateField;
	import mx.controls.ProgressBar;
	import mx.controls.TextArea;
	import mx.controls.TextInput;
	import mx.controls.listClasses.ListBase;
	import mx.core.Container;
	import mx.events.CloseEvent;
	import mx.events.ValidationResultEvent;
	import mx.formatters.CurrencyFormatter;
	import mx.formatters.NumberBase;
	import mx.managers.PopUpManager;
	import mx.rpc.events.ResultEvent;
	import mx.rpc.http.HTTPService;
	import mx.rpc.xml.SimpleXMLDecoder;
	import mx.utils.ObjectProxy;
	import mx.validators.CurrencyValidator;

	public class Utils
	{
		private static var tabs:Number = 1;
		private static var flag:Boolean = true;
		private static var ret:String = "";
		
		private static var fileRef:FileReference;
		
		
		//VARIABLES PARA REGRESAR BYTES FORMATEADOS
		static public const KBYTES : int = 1;
		static public const MBYTES : int = 2;
		static public const GBYTES : int = 3
		static public const TBYTES : int = 4
		
		private static var _formats : Array = [ "KB", "MB", "GB", "TB" ];
		
		public function Utils()
		{
		}
		
		public static function print_r(_obj:*):String{
			tabs = 1;
			flag = true;
			ret = "";
			var_dump(_obj);			
			return ret;
		}
		
		private static function gettabs(tabnumber:Number):String{
			var output:String = "";
			for(var i:int=0; i<tabnumber; i++){
				output+="\t";
			}
			return output;
		}
		
		private static function var_dump(_obj:*):void{				
			if(flag){
				//trace("Object(");
				ret += "Object(\n";
				flag = false;
			}
			for (var i:String in _obj) {					
				if (typeof(_obj[i]) == "object") {						
					//trace(gettabs(tabs)+"["+i+"] => "+"Object(");
					ret+=gettabs(tabs)+"["+i+"] => "+"Object(\n";
					tabs++;
					var_dump(_obj[i]);
					tabs--;
				}
				else{
					//trace(gettabs(tabs)+"["+i+"] => "+_obj[i]+":"+typeof(_obj[i]));
					ret+=gettabs(tabs)+"["+i+"] => "+_obj[i]+"("+typeof(_obj[i])+")\n";
				}
			}				
			//trace(gettabs(tabs-1)+")");
			ret+=gettabs(tabs-1)+")\n";
		}
		
		public static function count(_obj:*):Number{
			var counter:Number = 0;
			for each (var i:String in _obj) {
				counter++;
			}
			return counter;
		}
		
		public static function ltrim(matter:String):String {
			if ((matter.length>1) || (matter.length == 1 && matter.charCodeAt(0)>32 && matter.charCodeAt(0)<255)) {
				var i:int = 0;
				while (i<matter.length && (matter.charCodeAt(i)<=32 || matter.charCodeAt(i)>=255)) {
					i++;
				}
				matter = matter.substring(i);
			} else {
				matter = "";
			}
			return matter;
		}
		
		public static function rtrim(matter:String):String {
			if ((matter.length>1) || (matter.length == 1 && matter.charCodeAt(0)>32 && matter.charCodeAt(0)<255)) {
				var i:int = matter.length-1;
				while (i>=0 && (matter.charCodeAt(i)<=32 || matter.charCodeAt(i)>=255)) {
					i--;
				}
				matter = matter.substring(0, i+1);
			} else {
				matter = "";
			}
			return matter;
		}
		
		public static function trim(matter:String):String {
			return ltrim(rtrim(matter));
		}
		
		
		public static function compareDates(d1:*, d2:*):int {					
			var date1:Date;
			var date2:Date;
			var arrfecha1:Array;
			var arrfecha2:Array;
			//Alert.show(d1+"  "+d2);
			if((d1 is DateField) && (d2 is DateField)){
				arrfecha1 = d1.text.split("/");
				date1 = new Date(Number(arrfecha1[2].toString()),Number(arrfecha1[1].toString())-1,Number(arrfecha1[0].toString()));					
				arrfecha2 = d2.text.split("/");
				date2 = new Date(Number(arrfecha2[2].toString()),Number(arrfecha2[1].toString())-1,Number(arrfecha2[0].toString()));
			}
			else{
				if((d1 is String) && (d2 is String)){
					arrfecha1 = d1.split("/");
					date1 = new Date(Number(arrfecha1[2].toString()),Number(arrfecha1[1].toString())-1,Number(arrfecha1[0].toString()));					
					arrfecha2 = d2.split("/");
					date2 = new Date(Number(arrfecha2[2].toString()),Number(arrfecha2[1].toString())-1,Number(arrfecha2[0].toString()));
				}
				else{
					throw new Error("Solo se aceptan DateFields y Strings en formato D/M/A");
				}
			}			
			
			if(date1 != null && date2 != null){
				var d1ms:Number = date1.getTime();
				var d2ms:Number = date2.getTime();			
				if(d1ms > d2ms) {
					return -1;
				}
				else if(d1ms < d2ms) {
					return 1;
				}
				else {
					return 0;
				}		
			}
			else{
				throw new Error("Solo se aceptan DateFields y Strings en formato D/M/A");
				return null;
			}
			
		}
		
		public static function borrarInfo(comp:*):void{
			var arrParameters:Array;
			if(comp is Container){
				var arreglo:Array=(comp as Container).getChildren();
				for(var i:int=0; i<arreglo.length; i++){
					borrarInfo(arreglo[i]);
				}	
			}
			else{
				if(comp is TextInput || comp is TextArea || comp is DateField){
					comp.text="";
					arrParameters = new Array()
					arrParameters.push(comp);
					(comp.parent as Container).callLater(borraErrorString,arrParameters);
				}				
				if(comp is ListBase){
					if(comp.dataProvider!=null){
						comp.dataProvider.removeAll();
					}
				}				
				if(comp is ComboBase){
					comp.selectedIndex=0;					
					arrParameters = new Array()
					arrParameters.push(comp);
					(comp.parent as Container).callLater(borraErrorString,arrParameters);
				}
				if(comp is Button){
					comp.selected = false;
					arrParameters = new Array()
					arrParameters.push(comp);
					(comp.parent as Container).callLater(borraErrorString,arrParameters);
				}
			} 			
		}
		
		private static function borraErrorString(comp:*):void{
			comp.MESSAGEBOX_ERRORString = "";
		}
		
		public static function convertirFecha(fecha:String):Date{
			var date1:Date;
			var arrfecha1:Array;
			arrfecha1 = fecha.split("/");
			date1 = new Date(Number(arrfecha1[2].toString()),(Number(arrfecha1[1].toString())-1),Number(arrfecha1[0].toString()));
			return date1;						
		}
		
		public static function htmlEscape(str:String):String{
			
			return XML( new XMLNode( XMLNodeType.TEXT_NODE, str ) ).toXMLString();
		}
		
		public static function htmlUnescape(str:String):String{
			return new XMLDocument(str).firstChild.nodeValue;
		}
		
		public static function fechaToString(fecha:Date):String{
			var mes:Number=fecha.month+1;
			var anio:Number=fecha.fullYear;
			var dia:Number=fecha.date;
			
			return dia+"/"+mes+"/"+anio;
		}
		
		public static function stripHTML(value:String):String{
			return value.replace(/<img.*?>/gi, "");
			//return value.replace(/<.*?>/g, "");
		}
		
		public static function truncateText(input:String, length:int):String{
			var ret:String = "";
			
			if(input.length>length){
				ret = input.substring(0,length);
				ret += "...";
			}
			else{
				ret = input;
			}		
			
			return ret;
		}
		
		// optional flag, 1 capitalise only first word, else every word is capitalised
		public static function awCapitalize(sent:String, flag:Boolean):String{
			
			var str:String = sent;
			var tmp:String ="";
			var tmp2:String ="";
			var allWords:Array=new Array();
			var terug:String="";
			
			allWords = str.split(" ");	// into array delimiter is space
			
			if (!flag ){	// omitted will yield true
				for (var i:int=0; i<=allWords.length-1; i++) {
					tmp = allWords[i]; // for clarity sake only
					allWords[i] = tmp.substr(0,1).toUpperCase() + tmp.substr(1, tmp.length-1);
				}
			}
				
			else{	// flag is false: only first element is capitalised
				tmp2 = allWords[0];
				allWords[0] = tmp2.substr(0,1).toUpperCase()+ tmp2.substr(1, tmp2.length-1);
			}
			
			for (var j:int=0; j<=allWords.length-2; j++){
				terug += allWords[j] + " ";
			}
			
			return terug + allWords[allWords.length-1];
			// otherwise a space will be added to the final word as well.
		}
		
		public static function clone(source:*):*{
			var clone:ByteArray = new ByteArray();
			clone.writeObject (source);
			clone.position = 0;
			return clone.readObject();
		}
		
		/*public static function getRows(obj:Object, tabla:String='tabla0', showError:Boolean=true):ArrayCollection{
		var arrRet:ArrayCollection;			
		try{		
		if(!obj.error){
		if(obj[tabla].rows != null && Number(obj[tabla].numrows.total) > 0){
		if(obj[tabla].rows is ArrayCollection){
		arrRet = new ArrayCollection(obj[tabla].rows.source);
		}
		else{
		arrRet = new ArrayCollection();
		arrRet.addItem(obj[tabla].rows);
		}
		}
		else{
		arrRet = new ArrayCollection();
		}
		}
		else{
		if(showError) Alert.show(obj.error.msg+"\n\nQUERY: "+obj.error.query+"\n\nFavor de notificar al área de sistemas. Se recomienda guardar la información del error.","Error en Consulta SQL", Alert.OK,null,null,Iconos.MESSAGEBOX_ERROR, Alert.OK);
		arrRet = null;
		}
		}
		catch(e:Error){
		if(showError) Alert.show(e.message+"\n\n"+obj+"\n\nFavor de notificar al área de sistemas. Se recomienda guardar la información del error.","Error en Consulta SQL", Alert.OK,null,null,Iconos.MESSAGEBOX_ERROR,Alert.OK);
		arrRet = null;
		}
		finally{
		return arrRet;
		}
		}*/
		
		public static function getRows(obj:Object, tabla:String='tabla0', showError:Boolean=true):ArrayCollection { //recibe el event.result
			var arrRet:ArrayCollection;				                	
			try{		
				if(!obj.error){					
					if(obj.encrypted){
						var xml:XML = XML(Utils.decrypt(obj.encrypted, loader.RC4_KEY));												
						var decoder:SimpleXMLDecoder = new SimpleXMLDecoder(true);
						var xmlDoc:XMLDocument = new XMLDocument(xml.toXMLString());
						var resultObj:Object = decoder.decodeXML(xmlDoc);
						obj = resultObj.tablas;
					}			
					if(obj[tabla].rows != null && Number(obj[tabla].numrows.total) > 0){
						if(obj[tabla].rows is ArrayCollection){
							arrRet = new ArrayCollection(obj[tabla].rows.source);
						}
						else{
							arrRet = new ArrayCollection();
							arrRet.addItem(obj[tabla].rows);
						}
					}
					else{
						arrRet = new ArrayCollection();
					}
				}
				else{
					if(showError) Alert.show(obj.error.msg+"\n\nQUERY: "+obj.error.query+"\n\nFavor de notificar al área de sistemas. Se recomienda guardar la información del error.","Error en Consulta SQL", Alert.OK,null,null,null,Alert.OK);
					arrRet = null;
				}
			}
			catch(e:Error){
				if(showError) Alert.show(e.message+"\n\n"+Utils.print_r(obj)+"\n\nFavor de notificar al área de sistemas. Se recomienda guardar la información del error.","Error en Consulta SQL", Alert.OK,null,null,null,Alert.OK);
				arrRet = null;
			}
			finally{
				return arrRet;
			}
		}
		
		
		public static function getRowsEvent(event:ResultEvent, tabla:String="tabla0"):ArrayCollection {
			var arrRet:ArrayCollection;			
			var obj:Object;
			
			try{	
				//Verificando que existe el objeto event
				if(!event || event==null){
					loader.dp.removeLoader();
					loader.msgAviso("Error: No se recibió el evento",3);
					return null;	
				}	
				
				
				//Verificando que se obtuvo respuesta alguna
				if(String(event.result)==""){
					loader.dp.removeLoader();
					loader.dp.reintentarHttpService("Error: La operación solicitada no devolvió respuesta alguna", event.currentTarget as HTTPService);
					return null;
				}
				
				
				//Verificando que existe el objeto event.result.tablas				
				if(!event.result.tablas || event.result.tablas==null){
					loader.dp.removeLoader();
					loader.dp.reintentarHttpService("Error: Problema al recibir result.tablas", event.currentTarget as HTTPService);
					return null;
				}
				obj = event.result.tablas;
				
				
				//Verificando que no hubo problemas de conexión (event.result.tablas.error_cnx -> funciones.php top)
				if(obj && obj.error_cnx){
					loader.dp.removeLoader();
					loader.dp.reintentarHttpService("Problema al conectar a la base de datos", event.currentTarget as HTTPService);
					return null;
				}
				
				
				//Desencriptar en caso de ser necesario
				if(obj.encrypted){
					var xml:XML = XML(Utils.decrypt(obj.encrypted, loader.RC4_KEY));												
					var decoder:SimpleXMLDecoder = new SimpleXMLDecoder(true);
					var xmlDoc:XMLDocument = new XMLDocument(xml.toXMLString());
					var resultObj:Object = decoder.decodeXML(xmlDoc);
					obj = resultObj.tablas;
				}
				
				
				//Verificamos si hubo errores
				if(obj.error){
					loader.dp.removeLoader();
					if(loader.debug){
						loader.dp.reintentarHttpService("Ocurrió un problema al obtener los datos:\n\n- "+String(obj.error.msg)+"\n\nQUERY: "+obj.error.query, event.currentTarget as HTTPService);
					}else{
						loader.dp.reintentarHttpService("Ocurrió un problema al obtener los datos:\n\n- "+String(obj.error.msg), event.currentTarget as HTTPService);
					}
					
					return null;
				}
				
				
				//Obtenemos los Rows
				if(obj[tabla].rows != null && Number(obj[tabla].numrows.total) > 0){
					if(obj[tabla].rows is ArrayCollection){
						arrRet = new ArrayCollection(obj[tabla].rows.source);
					}
					else{
						arrRet = new ArrayCollection();
						arrRet.addItem(obj[tabla].rows);
					}
				}
				else{
					arrRet = new ArrayCollection();
				}
				
				
			}
			catch(e:Error){
				if(loader.debug){
					loader.dp.reintentarHttpService("Error->Utils->getRows:", event.currentTarget as HTTPService, e);
				}else{
					loader.msgAviso("Error->Utils->getRows\n\nFavor de notificar al área de sistemas. Se recomienda guardar la información del error.",3);
				}				
				arrRet = null;
			}
			finally{
				return arrRet;
			}
		}
		
		
		
		public static function getNumRows(obj:Object, tabla:String='tabla0', showError:Boolean=false):Number{
			var numRet:Number;			
			try{		
				if(!obj.error){
					if(obj.encrypted){
						var xml:XML = XML(Utils.decrypt(obj.encrypted, loader.RC4_KEY));												
						var decoder:SimpleXMLDecoder = new SimpleXMLDecoder(true);
						var xmlDoc:XMLDocument = new XMLDocument(xml.toXMLString());
						var resultObj:Object = decoder.decodeXML(xmlDoc);
						obj = resultObj.tablas;
					}	
					if(obj[tabla].numrows){
						numRet = Number(obj[tabla].numrows.total);
					}
					else{
						numRet = -1;
					}
				}
				else{
					if(showError) Alert.show(obj.error.msg+"\n\nQUERY: "+obj.error.query+"\n\nFavor de notificar al área de sistemas. Se recomienda guardar la información del error.","Error en Consulta SQL", Alert.OK,null,null,null,Alert.OK);
					numRet = -1;
				}
			}
			catch(e:Error){
				if(showError) Alert.show(e.message+"\n\n"+obj+"\n\nFavor de notificar al área de sistemas. Se recomienda guardar la información del error.","Error en Consulta SQL", Alert.OK,null,null,null,Alert.OK);
				numRet = -1;
			}
			finally{
				return numRet;
			}	
		}
		
		public static function decodeResultObject(obj:Object):Object{
			if(obj.encrypted){
				var xml:XML = XML(Utils.decrypt(obj.encrypted, loader.RC4_KEY));												
				var decoder:SimpleXMLDecoder = new SimpleXMLDecoder(true);
				var xmlDoc:XMLDocument = new XMLDocument(xml.toXMLString());
				var resultObj:Object = decoder.decodeXML(xmlDoc);
				obj = resultObj.tablas;
			}
			return obj;
		}
		
		public static function getRowsFromObj(obj:Object, descendant:String):ArrayCollection{
			if(obj){
				if(obj[descendant]){
					if(obj[descendant] is ArrayCollection){
						return new ArrayCollection(obj[descendant].source);
					}
					else{
						var arrRet:ArrayCollection = new ArrayCollection();
						arrRet.addItem(obj[descendant]);
						return arrRet;
					}				
				}
				else{
					return new ArrayCollection();
				}
			}
			else{
				return new ArrayCollection();
			}
		}
		
		//HACER BINDABLE UN ARRAY COLLECTION
		public static function applyObjectProxy(targetAC:ArrayCollection):ArrayCollection{
			for(var i:String in targetAC){				
				if(targetAC[i] is Array || targetAC[i] is ArrayCollection){
					applyObjectProxy(targetAC[i]);
				}				
				targetAC[i] = new ObjectProxy(targetAC[i]);
			}
			return targetAC;
		}
		
		public static function strPad(str:String, len:int, char:String="0", side:String="left"):String{
			var ret:String = str;
			if(side == "left"){
				while(ret.length < len){
					ret = char+ret;
				}
			}
			else{
				while(ret.length < len){
					ret = ret+char;
				}
			}
			return ret;
		}				
		
		//OBTENER BYTES DEL TAMAÑO DEL MAX UPLOAD FILE (PHP)
		public static function return_bytes(val:String):Number {
			//30G, 30M, 30K
			val = trim(val);
			if(isNaN(Number(val))){
				var bSize:Number = Number(val.substring(0,(val.length-1)));
				var last:String = val.substr(val.length-1).toLowerCase();
				
				switch(last) {
					// The 'G' modifier is available since PHP 5.1.0
					case 'g':
						bSize *= 1024;
					case 'm':
						bSize *= 1024;
					case 'k':
						bSize *= 1024;
				}
				
				return bSize;
			}
			else{
				return Number(val);
			}
		}
		
		public static function returnHumanSize( bytes : Number, format : int = 0, decimals : int = 0) : String{
			var divider : Number = Math.pow( 1024, format )
			return roundWithDecimals( bytes / divider,decimals) + " " + _formats[ format - 1 ];
		}
		
		public static function roundWithDecimals( value : Number, decimals : int = 0 ) : Number{
			var rounder : Number = Math.pow( 10, decimals );
			return Math.round( value * rounder ) / rounder;
		}
		
		public static function formatToLargest( value : Number, decimals : int= 0) : String{
			for( var i : Number = 4 ; i > 0 ; i-- ){
				if( value >= Math.pow(1024, i)) break;
			}
			return returnHumanSize( value, i, decimals );
		}	
		
		
		
		public static function goToUrl(url:String, parameters:Object=null, targetStr:String="_self", method:String='POST'):void{			
			var page:URLRequest = new URLRequest();
			var params:URLVariables = new URLVariables();
			var cadena:String="";
			
			if(parameters){
				for (cadena in parameters){
					params[cadena]=parameters[cadena]; 
				}
			}
			
			page.url=url;
			page.method=method.toUpperCase();
			page.data=params;
			
			navigateToURL(page,targetStr.toLowerCase());		
		}		
		
		
		public static function compareObject(obj1:Object,obj2:Object):Boolean{
			var buffer1:ByteArray = new ByteArray();
			buffer1.writeObject(obj1);
			var buffer2:ByteArray = new ByteArray();
			buffer2.writeObject(obj2);
			
			// compare the lengths
			var size:uint = buffer1.length;
			if (buffer1.length == buffer2.length) {
				buffer1.position = 0;
				buffer2.position = 0;
				
				// then the bits
				while (buffer1.position < size) {
					var v1:int = buffer1.readByte();
					if (v1 != buffer2.readByte()) {
						return false;
					}
				}    
				return true;                        
			}
			return false;
		}
		
		
		public static function restringir(codigoRestriccion:String):String{
			var palabras:Array=codigoRestriccion.split(',');
			var restricciones:String="";
			
			for(var i:int=0; i<palabras.length; i++){
				switch(palabras[i]){
					case 'MAYUSCULAS':
						restricciones+= 'A-Z';
						break;
					case 'MINUSCULAS':
						restricciones+= 'a-z';
						break;
					case 'NTILDEMAYUS':
						restricciones+= 'Ñ';
						break;
					case 'NTILDEMINUS':
						restricciones+= 'ñ';
						break;
					case 'ACENTOS':
						restricciones+= 'ÁÉÍÓÚáéíóú';
						break;
					case 'NUMEROS':
						restricciones+= '0-9';
						break;
					case 'GUIONES':
						restricciones+= '_\\-';
						break;
					case 'PUNTOS':
						restricciones+= '.';
						break;
					case 'CARACTERERES':
						restricciones+= ':#()';
						break;
					case 'MASMENOS':
						restricciones+= '+\\-';
						break;
					default:
						restricciones+="";
						break;	
				}	
			}
			return restricciones;			
		}
		
		public static function numeraCollection(arr:*):*{
			var arrRet:*
			
			if(arr is ArrayCollection){
				arrRet=new ArrayCollection();
			}
			else{
				arrRet=new XMLListCollection();
			}
			
			var idx:Number = 0;
			
			for each(var row:* in arr){
				if(arr is ArrayCollection){
					row["idx"] = ++idx;
				}
				else{
					row["@idx"]= ++idx;
				}
				arrRet.addItemAt(row,arrRet.length);
			}
			
			return arrRet;
		}
		
		
		
		
		public static function validateFormatMoney(txti:TextInput,canBeNothing:Boolean=false):void{
			var cF:CurrencyFormatter = new CurrencyFormatter();
			var deFormat:NumberBase = new NumberBase();
			var temp:Number = 0;
			
			var cV:CurrencyValidator = new CurrencyValidator();
			cV.source = txti;
			cV.required=false;			
			cV.property="text"; 
			cV.alignSymbol="left";
			cV.allowNegative="true";
			cV.currencySymbol="$";
			cV.currencySymbolError="Número inválido.";
			cV.decimalPointCountError="Número inválido.";
			cV.decimalSeparator=".";
			cV.exceedsMaxError="Número inválido.";
			cV.invalidCharError="Número inválido.";
			cV.invalidFormatCharsError="Número inválido.";
			cV.lowerThanMinError="Número inválido.";
			cV.maxValue="9999999999.99";
			cV.minValue="NaN";
			cV.negativeError="Número inválido.";
			cV.precision="2";
			cV.precisionError="Usar solo 2 decimales.";
			cV.separationError="Número inválido.";
			cV.thousandsSeparator=",";
			
			if(canBeNothing && txti.text=='') return;
			
			
			if (cV.validate().type==ValidationResultEvent.VALID) {
				if(!isNaN(Number(deFormat.parseNumberString(txti.text)))){
					temp = Number(deFormat.parseNumberString(txti.text))
				}
				else{
					temp = 0;
				}
				cF.precision = "2";			
				cF.useThousandsSeparator="true"; 
				cF.decimalSeparatorFrom=".";
				cF.thousandsSeparatorFrom=",";
				cF.useNegativeSign="true";
				txti.text = cF.format(temp);
			}	
		}
		
		public static function deFormatMoney(money:String):Number{            	
			var deFormat:NumberBase = new NumberBase();
			if(!isNaN(Number(deFormat.parseNumberString(money)))){            		
				return Number(deFormat.parseNumberString(money));
			}
			else{
				return -1;
			}
		}
		
		public static function str_replace(str_original:String, busca:String, reemplaza_con:String  ):String
		{
			var array:Array = str_original.split(busca);
			return array.join(reemplaza_con);
		}
		
		public static function basename(url:String):String {
			var i:Number;
			url = url.replace("\\","/");
			if ((i = url.lastIndexOf("/")) > -1) {
				url = url.substr(i+1);
			}
			return url;
		}
		
		public static function validarFechas(fecha1:DateField,fecha2:DateField):void{
			if(fecha1.text!="" && fecha2.text!=""){
				if(compareDates(fecha1,fecha2)==-1){
					fecha1.errorString="La fecha de inicio es mayor que la fecha de vencimiento.";  		
					fecha2.errorString="La fecha de inicio es mayor que la fecha de vencimiento.";
					
				}
				else{
					fecha1.errorString="";
					fecha2.errorString="";
					
				}
			}     			   	
		}
		
		
		public function compareDates(d1:*, d2:*):int {					
			var date1:Date;
			var date2:Date;
			var arrfecha1:Array;
			var arrfecha2:Array;
			//Alert.show(d1+"  "+d2);
			if((d1 is DateField) && (d2 is DateField)){
				arrfecha1 = d1.text.split("/");
				date1 = new Date(Number(arrfecha1[2].toString()),Number(arrfecha1[1].toString())-1,Number(arrfecha1[0].toString()));					
				arrfecha2 = d2.text.split("/");
				date2 = new Date(Number(arrfecha2[2].toString()),Number(arrfecha2[1].toString())-1,Number(arrfecha2[0].toString()));
			}
			else{
				if((d1 is String) && (d2 is String)){
					arrfecha1 = d1.split("/");
					date1 = new Date(Number(arrfecha1[2].toString()),Number(arrfecha1[1].toString())-1,Number(arrfecha1[0].toString()));					
					arrfecha2 = d2.split("/");
					date2 = new Date(Number(arrfecha2[2].toString()),Number(arrfecha2[1].toString())-1,Number(arrfecha2[0].toString()));
				}
				else{
					throw new Error("Solo se aceptan DateFields y Strings en formato D/M/A");
				}
			}			
			
			if(date1 != null && date2 != null){
				var d1ms:Number = date1.getTime();
				var d2ms:Number = date2.getTime();			
				if(d1ms > d2ms) {
					return -1;
				}
				else if(d1ms < d2ms) {
					return 1;
				}
				else {
					return 0;
				}		
			}
			else{
				throw new Error("Solo se aceptan DateFields y Strings en formato D/M/A");
				return null;
			}
			
		}
		
		public static function encrypt(input:String, key:String):String {
			var mode:ICipher = Crypto.getCipher("rc4", Hex.toArray(Hex.fromString(key)), new NullPad);					
			var data:ByteArray = Hex.toArray(Hex.fromString(input));             
			mode.encrypt(data);
			
			if (data == null) return null;
			
			return Hex.fromArray(data);
		}
		
		public static function decrypt(input:String, key:String):String {
			var data:ByteArray = Hex.toArray(input);
			var mode:ICipher = Crypto.getCipher("rc4", Hex.toArray(Hex.fromString(key)), new NullPad);	
			mode.decrypt(data);
			
			if (data == null) return null;
			
			return Hex.toString(Hex.fromArray(data));
		}
		
		public static function execRequest(call:HTTPService, enableEncryption:Boolean=true):void{
			if(enableEncryption){
				var req:Object = clone(call.request);
				var res:String = "";
				
				res += "<data>\n";
				for (var i:String in req){
					if(String(req[i]).length > 0){ //@TODO: ES POSIBLE QUE EN UN FUTURO SE PUEDAN MANDAR DATOS BINARIOS O DIFERENTES A STRING, HAY QUE BUSCAR OTRA FORMA DE VALIDAR POR VACIO
						res += "<" + i + ">" + req[i] + "</" + i + ">\n";
					}
				}
				res += "</data>";
				
				res = encrypt(res, loader.RC4_KEY);
				call.send({encrypted:res});
			}
			else{
				call.send();
			}
		}
		
		public static function downloadFile(clase:DisplayObject,urlReq:URLRequest, modal:Boolean=true, infoBitacora:Object=null):void{
			
			var totalBytesLoaded:int=0;
			var sizeFile:int=0;
			var nameFile:String='';
			
			var checkTimeOut:Timer=new Timer(5000);
			
			fileRef = new FileReference();
			
			configureListeners(fileRef);
			
			function configureListeners(dispatcher:IEventDispatcher):void {
				dispatcher.addEventListener(Event.CANCEL, doEvent);
				dispatcher.addEventListener(Event.COMPLETE, doEvent);
				dispatcher.addEventListener(Event.OPEN, doEvent);
				dispatcher.addEventListener(Event.SELECT, doEvent);
				dispatcher.addEventListener(HTTPStatusEvent.HTTP_STATUS, doEvent);
				dispatcher.addEventListener(IOErrorEvent.IO_ERROR, doEvent);
				dispatcher.addEventListener(ProgressEvent.PROGRESS, doEvent);
				dispatcher.addEventListener(SecurityErrorEvent.SECURITY_ERROR, doEvent);
				
				checkTimeOut.addEventListener(TimerEvent.TIMER, timeOutEvt);
			}
			
			//Ventana de Progreso
			var progWin:TitleWindow;
			
			var contProgWin:VBox=new VBox();
			contProgWin.setStyle('verticalAlign','middle');
			contProgWin.setStyle('horizontalAlign','center');
			
			var progBar:ProgressBar=new ProgressBar();
			progBar.percentWidth=100;
			progBar.height=20;
			progBar.mode="manual";
			
			var btnCerrar:Button=new Button();
			btnCerrar.label="Cancelar";
			btnCerrar.addEventListener(MouseEvent.CLICK,cancelar);
			
			fileRef.download(urlReq);
			
			function cancelar(evt:MouseEvent):void{
				if(infoBitacora){
					//saveLogs({tipomovto:'download', modulo:(infoBitacora.modulo?infoBitacora.modulo:''), claveusuario:(Virtual.strTipoUsuario=='1'?Virtual.strMatricula:Virtual.strClaveempleado), tipousuario:Virtual.strTipoUsuario, clavecurso:Virtual.strClaveCurso, claveescuela:Virtual.strClaveEscuela, eventotipo:'cancelDownload', nombrearchivo:nameFile, tamanioarchivo:sizeFile, totaldescargado:totalBytesLoaded, titulo:(infoBitacora.titulo?infoBitacora.titulo:''), msg:(infoBitacora.msg?infoBitacora.msg:'')});
				}
				fileRef.cancel();
				cerrarventana(null);
			}
			
			function cerrarventana(event:CloseEvent) : void {
				PopUpManager.removePopUp(progWin);
			}
			
			function doEvent(evt:Event):void {
				
				var fr:FileReference = evt.currentTarget as FileReference;
				
				if(evt.type=="select"){
					progWin=PopUpManager.createPopUp(clase,mx.containers.TitleWindow,modal) as TitleWindow;
					progWin.showCloseButton=false;
					//progWin.setStyle('styleName','catalogPanel');
					//progWin.styleName='catalogPanel';
					try{
						progWin.title="Descargando: "+fr.name;
						progWin.setStyle('verticalAlign','middle');
						progWin.setStyle('horizontalAlign','center');
						contProgWin.addChild(progBar);
						contProgWin.addChild(btnCerrar);
						progWin.addChild(contProgWin);
						PopUpManager.centerPopUp(progWin);
						progBar.setProgress(0,1000);
						progBar.labelPlacement = "center";
						progBar.label ="Loading";
						nameFile=fr.name
						checkTimeOut.start();
					}
					catch(e:*){
						progWin.title="Descargando Archivo";
						progWin.setStyle('verticalAlign','middle');
						progWin.setStyle('horizontalAlign','center');
						contProgWin.addChild(progBar);
						contProgWin.addChild(btnCerrar);
						progWin.addChild(contProgWin);
						PopUpManager.centerPopUp(progWin);
						progBar.setProgress(0,1000);
						progBar.labelPlacement = "center";
						progBar.label ="Loading";
						checkTimeOut.start();
					}   
				}
				
				if(evt.type=="complete"){
					checkTimeOut.stop();
					if(infoBitacora){
						Alert.show("Archivo descargado correctamente","Descargar archivo",0,null,null,Iconos.MESSAGEBOX_OK,Alert.YES);
						//saveLogs({tipomovto:'download', modulo:(infoBitacora.modulo?infoBitacora.modulo:''), claveusuario:(Virtual.strTipoUsuario=='1'?Virtual.strMatricula:Virtual.strClaveempleado), tipousuario:Virtual.strTipoUsuario, clavecurso:Virtual.strClaveCurso, claveescuela:Virtual.strClaveEscuela, eventotipo:evt.type, nombrearchivo:nameFile, tamanioarchivo:sizeFile, totaldescargado:totalBytesLoaded, titulo:(infoBitacora.titulo?infoBitacora.titulo:''), msg:(infoBitacora.msg?infoBitacora.msg:'')});
					}
					fileRef.cancel();
					cerrarventana(null);
					//trace(evt.type);
				}
				
				if(evt.type=="ioError"){
					checkTimeOut.stop();
					loader.msgReintentar("Ocurrió un error al descargar el archivo.",retryDownload);
					
					if(infoBitacora){
						//saveLogs({tipomovto:'download', modulo:(infoBitacora.modulo?infoBitacora.modulo:''), claveusuario:(Virtual.strTipoUsuario=='1'?Virtual.strMatricula:Virtual.strClaveempleado), tipousuario:Virtual.strTipoUsuario, clavecurso:Virtual.strClaveCurso, claveescuela:Virtual.strClaveEscuela, eventotipo:evt.type, nombrearchivo:nameFile, tamanioarchivo:sizeFile, totaldescargado:totalBytesLoaded, titulo:(infoBitacora.titulo?infoBitacora.titulo:''), msg:(infoBitacora.msg?infoBitacora.msg:'')});
					}
					
					fileRef.cancel();
					cerrarventana(null);
					//trace(evt.type);	  	
				}
				
				if(evt.type=="progress"){
					checkTimeOut.stop();
					progBar.setProgress((evt as ProgressEvent).bytesLoaded, (evt as ProgressEvent).bytesTotal);
					progBar.labelPlacement = "center";
					progBar.label = (((evt as ProgressEvent).bytesLoaded/1024).toFixed(0))+'kb de '+(((evt as ProgressEvent).bytesTotal/1024).toFixed(0))+'kb';
					totalBytesLoaded=(evt as ProgressEvent).bytesLoaded;
					sizeFile=(evt as ProgressEvent).bytesTotal;
				}
			}
			
			function timeOutEvt(evt:TimerEvent):void{
				loader.msgReintentar("Ocurrió un error al descargar el archivo.",retryDownload);
				checkTimeOut.stop();
				fileRef.cancel();
				cerrarventana(null);
			}
			
			function retryDownload(evento:Object):void{
				if(evento.detail == Alert.YES) {
					fileRef.download(urlReq);
				}
			}
		}
		
		
		//Formateador de Archivos
		public static function formatFileSize(numSize:Number):String
		{
			var strReturn:String;
			numSize = Number(numSize / 1024);
			strReturn = String(numSize.toFixed(1) + " KB");
			if (numSize > 1000)
			{
				numSize = numSize / 1024;
				strReturn = String(numSize.toFixed(1) + " MB");
				if (numSize > 1000) 
				{
					numSize = numSize / 1024;
					strReturn = String(numSize.toFixed(1) + " GB");
				}
			}				
			return strReturn;
		}
		
		public static function getIconoByExtension(ext:String):Class
		{
			switch (ext.toString().toLocaleLowerCase())
			{
				case ".jpg":
				case ".jpeg":
				case ".gif":
				case ".png":
				case ".bmp": return Iconos.ImgsDL;					
				case ".xls":
				case ".xlsx": return Iconos.ExcelDL;						
				case ".rtf":
				case ".doc":
				case ".docx": return Iconos.WordDL;						
				case ".ppt":
				case ".pps":
				case ".ppsx":
				case ".pptx": return Iconos.PowerPntDL;			
				case ".zip":
				case ".rar": return Iconos.RarDL;	
				case ".txt": return Iconos.TxtDL;
				case ".pdf": return Iconos.PdfDL;			
				default: return Iconos.DesconocidoDL;
			}
		}
		
		
		public static function getImagenByTipoAsignacion(tipo:String):*{
			switch(tipo.toLowerCase()){
				case "usuario": return Iconos.iconUsuario;
				case "tipo de usuario": return Iconos.iconUsuario;     
				case "permiso": return Iconos.KEYS16;
			}	
			return Iconos.MESSAGEBOX_QUESTION16;
		}
		
		
		public static function getImagenByTipoArchivo(item:Object):*{
			switch(String(item.tipoarchivo)){
				case "imagen":     
				case "swf":		   
					return item.rutaThumb;
					
				case "documento":   
				case "multimedia":  
				case "link":        
				case "comprimido": 
					return Utils.getImagenByExtension(item.extension);
					
				default: loader.msgAviso("Utils->getImagenByTipoArchivo: tipo de archivo desconocido ["+item.tipoarchivo+"]");
			}	
			return Iconos.MESSAGEBOX_QUESTION;
		}
		
		
		public static function getImagenByExtension(ext:String):Class
		{
			switch (ext.toString().replace(".","").toLocaleLowerCase())
			{
				case "pdf": return Iconos.PDF64;					
				case "xls":
				case "xlsx": return Iconos.XLS64;						
				case "doc":
				case "docx": return Iconos.WORD64;
				case "link": return Iconos.LINK64;	
				case "mp3":  return Iconos.AUDIO64;
				case "flv":  return Iconos.VIDEO64;					
				case "ppt":
				case "pptx": return Iconos.PPT64;					
				case "rar": return Iconos.RAR64;	
				case "youtube": return Iconos.YOUTUBE64;				
				case "zip": return Iconos.ZIP64;						
				default: return Iconos.MESSAGEBOX_QUESTION;
			}
			return Iconos.MESSAGEBOX_QUESTION;
		}
		
		public static function getDownloadIconByExtension(ext:String):Class
		{
			switch (ext.toString().replace(".","").toLocaleLowerCase())
			{
				case "jpg":
				case "jpeg":
				case "gif":
				case "png":
				case "bmp": return Iconos.DLIMG;				
				case "pdf": return Iconos.DLPDF;
				case "xls":
				case "xlsx": return Iconos.DLXLS;						
				case "rtf":
				case "doc":
				case "docx": return Iconos.DLWRD;
				case "ppt":
				case "pps":
				case "ppsx":
				case "pptx": return Iconos.DLPPT;
				case "rar": return Iconos.DLRAR;
				case "zip": return Iconos.DLZIP;
				case "txt": return Iconos.DLTXT;
				default: return Iconos.DLNKNWN;
			}
			return Iconos.DLNKNWN;
		}
		
		
		public static function inbtwn(strUrl:String, startcut:String, finishcut:String):String{
			var output:String = "";
			var arr1:Array=new Array();
			var arr2:Array=new Array();
			try
			{
				arr1 = strUrl.split(startcut);
				arr2 = arr1[1].split(finishcut);
				output = arr2[0];
			}
			catch(e:*)
			{
				return '';
			}
			
			return output;
		}
		
		
		
		public static function getArchivosFromEvent(event:ResultEvent, isForEdit:Boolean=false):ArrayCollection{
			var arrArchivos:ArrayCollection = null;
			
			try{				
				arrArchivos = Utils.getRowsEvent(event);
				
				if(arrArchivos){
					// Revisamos que loader.archivosUploadUrl no esté vacío o undefined
					if(!loader.archivosUploadUrl || loader.archivosUploadUrl=="" || String(loader.archivosUploadUrl).toLowerCase().search( Utils.trim("undefined").toLowerCase() ) != -1){
						arrArchivos = null;
						loader.msgAviso("Error->getArchivosFromEvent->archivosUploadUrl ["+loader.archivosUploadUrl+"]",3);
					}else{
						if(arrArchivos.length>0){
							var error:Boolean = false;
							for(var i:int=0; i<arrArchivos.length; i++){
								if(!error){
									// Si es interno concatenar loader.archivosUploadUrl
									if(int(arrArchivos[i].interno) == 1){
										arrArchivos[i].rutaThumb = loader.archivosUploadUrl + String(String(arrArchivos[i].ruta).replace("swf","thumbnails")).replace("swf","png");
										
										// true = Es para editar en algun St     false = Es para visualizar en el SuperPlayer
										if(!isForEdit){
											arrArchivos[i].ruta      = loader.archivosUploadUrl + arrArchivos[i].ruta;	
										}
										
										if(!arrArchivos[i].ruta || arrArchivos[i].ruta=="" || String(arrArchivos[i].ruta).toLowerCase().search( Utils.trim("undefined").toLowerCase() ) != -1){
											loader.msgAviso("Error->getArchivosFromEvent->ruta ["+arrArchivos[i].ruta+"]",3);
											error = true;
										}			
									}
								}							
							}
							if(error) arrArchivos = null;
						}
					}
				}	
				
			}catch(ex:Error){
				arrArchivos = null;
				loader.dp.reintentarHttpService("Utils->getArchivosFromEvent->Ocurrió un problema al recibir los archivos", event.currentTarget as HTTPService,ex);
			}	
			
			loader.dp.removeLoader();
			return arrArchivos;
		}
		
	
		
	}
}