// ActionScript file
///////////////////////////////
// Author: Guillaume Nachury
//
//         Advanced Textarea
//
// -> Auoresize feature with
//             -> MaxChar limiter
//            -> MaxHeight limiter
//  http://proofofconcepts.wordpress.com/2009/06/09/flex-autoresizable-textarea/
///////////////////////////////

package actionscripts{
	 import mx.controls.TextArea;
	
	 public class ResizableTextArea extends TextArea{
		
		 private var _autoResize:Boolean =  false;
		 private var _lineOffset:int = 5;
		 public var fullText:String="";
		
		 public function ResizableTextArea(isAutoResize:Boolean=false){
			 super();
			 _autoResize = isAutoResize;
		 }
		
		 //overrides        
		 override public function set maxChars(i:int):void{
			 super.maxChars = i;
			 doValidations();
		 }
		
		 override public function set maxHeight(n:Number):void{
			 super.maxHeight = n;
			 doValidations();
		 }
		
		 override public function set text(s:String):void{
			 //limit the number of chars if there's a limit
			 fullText = s;
			 if(super.maxChars>0 && s.length > super.maxChars){
			 s = s.substring(0, super.maxChars)+"...";    
			 super.text = s;
			 }
			 super.text = s;
			
			 validateNow();
			 doValidations();
		 }
		
		 private function doValidations():void{
			 if(super.text != null && super.text.length >0){	
				 //limit the height if there's a limit
				 if(!isNaN(super.maxHeight)){
					 var textH:int = this.textField.measuredHeight+_lineOffset;
					 if(textH > super.maxHeight && _autoResize == true){
					 	this.height = super.maxHeight;
					 }else{
					 	if(_autoResize == true)    this.height = this.textField.measuredHeight+_lineOffset;
					 }
				 }else{
					 if(_autoResize == true){
					 	this.height = this.textField.measuredHeight+_lineOffset;
					 }
				 }
			 }
		 }
		
		 public function set autoResize(b:Boolean):void{
			 _autoResize = b;
			 doValidations();
		 }
	
	 }
}
