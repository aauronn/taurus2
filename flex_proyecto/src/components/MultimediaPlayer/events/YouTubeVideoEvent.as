package components.MultimediaPlayer.events{
	import flash.events.Event;

	public class YouTubeVideoEvent extends Event{
		
		public static const COMPLETE:String = "complete";
		
		public var recurso:Object;
		
		public function YouTubeVideoEvent(type:String, rec:Object, bubbles:Boolean=false, cancelable:Boolean=false){
			recurso = rec;
			super(type, bubbles, cancelable);
		}
		
	}
}