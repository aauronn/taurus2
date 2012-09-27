package actionscripts.com.codeofdoom.events
{
	import com.google.maps.overlays.Marker;
	
	import flash.events.Event;

	public class InfoWindowDeleteMarkerEvent extends Event
	{
		public static const NAME:String = "com.codeofdoom.events.InfoWindowDeleteMarkerEvent";
		private var _inputName:String;
		private var _marker:Marker;
		
		public function InfoWindowDeleteMarkerEvent(inputName:String)
		{
			_inputName = inputName;
			super(NAME);
		}
		
		public function get inputName():String{
			return _inputName;
		}
		
		public function set marker(marker:Marker):void{
			_marker = marker;
		}
		
		public function get marker():Marker{
			return _marker;
		}
		
		
	}
}