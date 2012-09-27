package components.MultimediaPlayer.events{
	import flash.events.Event;

	public class completeEvent extends Event{
		static public const COMPLETO:String = "completo";
		
		public function completeEvent(data:Object, bubbles:Boolean=true, cancelable:Boolean=false){
			super(COMPLETO, bubbles, cancelable);
			this.data = data;
		}
		
		override public function clone():Event {
			return new completeEvent(data);
		}

		
		public var data:Object;
	}
}