package actionscripts
{
	import flash.display.DisplayObject;
	import flash.text.TextLineMetrics;
	
	import mx.controls.CheckBox;
	import mx.core.IFlexDisplayObject;
	import mx.core.mx_internal;
	use namespace mx_internal;
	
	public class MultilineCheckBox extends CheckBox
	{
		public var idrespuesta:String="";
		public var valor:String="";
		
		public function MultilineCheckBox()
		{
			super();
		}
		
		override protected function createChildren():void
		{
			if (!textField)
			{
				textField = new NoTruncationUITextField();
				textField.styleName = this;
				addChild(textField as DisplayObject);
			}
			
			super.createChildren();
			
			textField.multiline = true;
			textField.wordWrap = true;
		}
		
		override protected function measure():void
		{
			if (!isNaN(explicitWidth))
			{
				var tempIcon:IFlexDisplayObject = getCurrentIcon();
				var w:Number = explicitWidth;
				if (tempIcon)
					w -= tempIcon.width + getStyle("horizontalGap") + getStyle("paddingLeft") + getStyle("paddingRight");
				textField.width = w;
			}
			if (!isNaN(explicitHeight))
			{
				var tempIcon2:IFlexDisplayObject = getCurrentIcon();
				var h:Number = explicitHeight;
				if (tempIcon2)
					h -= tempIcon2.height + getStyle("verticalGap") + getStyle("paddingTop") + getStyle("paddingBottom");
				textField.height = h;
			}
			super.measure();
			
		}
		
		override public function measureText(s:String):TextLineMetrics
		{
			textField.text = s;
			var lineMetrics:TextLineMetrics = textField.getLineMetrics(0);
			lineMetrics.width = textField.textWidth + 4;
			lineMetrics.height = textField.textHeight + 4;
			return lineMetrics;
		}
		
	}
}