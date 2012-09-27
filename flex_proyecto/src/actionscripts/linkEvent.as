package actionscripts
{
	import flash.events.Event;

	public class linkEvent extends Event
	{
		static public const GO_TO:String = "goto";
		
		public function linkEvent(data:Object, bubbles:Boolean=true, cancelable:Boolean=false)
		{
			super(GO_TO, bubbles, cancelable);
			this.data = data;
		}
		
		override public function clone():Event {
			return new linkEvent(data);
		}
		
		
		public var data:Object;
	}
}