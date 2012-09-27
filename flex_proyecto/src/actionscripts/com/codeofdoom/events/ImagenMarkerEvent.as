package actionscripts.com.codeofdoom.events
{
	import com.google.maps.overlays.Marker;
	
	import flash.events.Event;

	public class ImagenMarkerEvent extends Event
	{
		public static const CHANGE_ICON:String = "com.codeofdoom.events.ImagenMarkerEvent";
		
		public var _icon:Class;
		
		public function ImagenMarkerEvent(icon:Class)
		{
			super(CHANGE_ICON);
			_icon = icon;			
		}

		public function set icon(icon:Class):void{
			_icon = icon;
		}
		public function get icon():Class{
			return _icon;
		}		
	}
}