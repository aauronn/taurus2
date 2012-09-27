package actionscripts
{
	import mx.collections.ArrayCollection;
	import mx.core.UIComponent;
	
	import spark.components.ComboBox;
	import spark.events.IndexChangeEvent;
	
	public class DataProvidersSparks extends UIComponent
	{
		public function DataProvidersSparks(parent:*=null){
			super();
			parentApp = parent;
		}
		
		[Bindable] public var parentApp:*;
		[Bindable] public var _padreLoader:loader;
		
		public static function selectedIndexCombo(combo:ComboBox,campo:String,valor:String):void{
			var flag:Boolean=false;
			var dp:ArrayCollection=(combo.dataProvider as ArrayCollection);
			for(var i:int; i<dp.length; i++){
				if(dp[i][campo]==valor){
					combo.selectedIndex=i;
					flag=true;		
				}
			}
			if(!flag){
				//combo.selectedIndex=-1;
			}
			combo.dispatchEvent(new IndexChangeEvent(IndexChangeEvent.CHANGE));
		}
		
	}
}