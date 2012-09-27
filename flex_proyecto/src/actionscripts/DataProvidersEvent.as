package actionscripts
{
	import flash.events.Event;
	
	public class DataProvidersEvent extends Event{
		
		public var data:Object;
		
		static public const COMPLETEDP:String = "completedp";
		
		public function DataProvidersEvent(data:Object, bubbles:Boolean=true, cancelable:Boolean=false){
			super(COMPLETEDP, bubbles, cancelable);
			this.data = data;
		}
		
		override public function clone():Event {
			return new DataProvidersEvent(data);
		}
	}
}