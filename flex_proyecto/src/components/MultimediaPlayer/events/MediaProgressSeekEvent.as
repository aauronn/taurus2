package components.MultimediaPlayer.events{
	
	import flash.events.*;
	
	
	public class MediaProgressSeekEvent extends Event
	{
		
		
		static public const CHANGE:String="seekchange";
		
		public var position:Number;
		
		
		public function MediaProgressSeekEvent( type:String=CHANGE, bubbles:Boolean = true, cancelable : Boolean = false ){
			super( type, bubbles, cancelable );
		}
		
	}
}