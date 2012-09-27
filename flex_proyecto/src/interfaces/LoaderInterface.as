package interfaces
{
	import flash.events.TimerEvent;
	
	import mx.rpc.events.FaultEvent;

	public interface LoaderInterface
	{
		function init():void;
		function getParameter(parametro:String):String;
		function getParameterInt(parametro:String):int;
		//function initStage():void;
		function loadUnloadModule(modulo:String):void;
		function checkSession(evt:TimerEvent):void;
		//function msgAviso(mensaje:String,tipoIcono:Number=1,e:Error=null):void;
		//function msgReintentar(mensaje:String,fnReintentar:Function,msgReintentar:String="¿Desea volver a intentarlo?",tituloVentana:String="Aviso", yesLabel:String="SI", noLabel:String="NO"):void;
		//function logout():void;
		function faultHandler(evento:FaultEvent):void;
		
	}
}