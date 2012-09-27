package components.MultimediaPlayer.comps{

	import mx.validators.ValidationResult;
	import mx.validators.Validator;
	
	public class URLValidator extends Validator{

		public var mustBeMultimedia:Boolean = true;
		
		public function URLValidator(){
			super();
		}

		public function validateUrl(validator:URLValidator,value:Object,baseField:String):Array{
			var results:Array = [];
			if (results.length > 0){
				return results;
			}
			var theStringValue:String=String(value).toLowerCase();
			if (theStringValue.length == 0) {
				return results;
			}
			
			if(theStringValue.indexOf('http')!=0){
				results.push(new ValidationResult(true,null,"NaN","Necesita iniciar con un prefijo 'http' ó 'https' válido"));
				return results;
			}
			
			if(theStringValue.indexOf('http:')== -1 && theStringValue.indexOf('https:')== -1){
				results.push( new ValidationResult( true,null,"NaN","Necesita teclear ':' en su dirección despues de 'http' ó 'https'"));
				return results;
			}
			
			if(theStringValue.indexOf('http://')== -1 && theStringValue.indexOf('https://')== -1){
				results.push (new ValidationResult( true,null,"NaN","Necesita teclear '//' en su dirección despues de 'http:' ó 'https:'"));
				return results;
			}
					
			if (theStringValue.indexOf('.') == -1) {
				results.push( new ValidationResult( true,null,"NaN","Su dirección no es válida"));
				return results;
			}
			
			var wwwArr:Array= theStringValue. split('.');
			if (wwwArr.length <3  && theStringValue.indexOf('//www.') != -1){
				results.push( new ValidationResult( true,null,"NaN","No existe dirección válida despues de 'www'"));
				return results;
			}
			else{
				if(wwwArr[1]=="" && theStringValue.indexOf('//www.')!= -1 ){
					results.push( new ValidationResult( true,null,"NaN","Su dominio no es válido"));
					return results;
				}
				
				if((wwwArr[0]=="http://" || wwwArr[0]=="https://") && theStringValue.indexOf('//www.')== -1 ){
					results.push( new ValidationResult( true,null,"NaN","Su dominio no es válido"));
					return results;
				}
				
				if(wwwArr.length<2 && theStringValue.indexOf('//www.')== -1 ){
					results.push( new ValidationResult( true,null,"NaN","Su dirección no es válida"));
					return results;
				}
			}
			
			var suffixDotpos: Number=theStringValue.lastIndexOf(".");
			var suffixStr:String= theStringValue;
			var theSuffix:String= suffixStr.substring(suffixDotpos,suffixStr.length);
			
			if (theSuffix.length < 3 ) {
				results.push( new ValidationResult( true,null,"NaN","El sufijo del domínio es inválido."));
				return results;
			}
			
			if(mustBeMultimedia){
				var extension:String=theStringValue.substr(-3);
				//trace(theStringValue.substr(-3));
				if( (extension!="flv" && extension!="mp3") && (theStringValue.indexOf("youtube")==-1)){	
					results.push( new ValidationResult( true,null,"NaN","La dirección no contiene un archivo válido."));
					return results;
				}
			}
			
			return results;
			
			
		}
		
   /*private function validateIP(str: String):Boolean {
         var pattern:RegExp= /\b(25[0- 5]|2[0-4] [0-9]|[01] ?[0-9][0-
 9]?)\.(25[ 0-5]|2[0- 4][0-9]|[ 01]?[0-9] [0-9]?)\. (25[0-5]| 2[0-4][0-
 9]|[01]?[ 0-9][0-9] ?)\.(25[0- 5]|2[0-4] [0-9]|[01] ?[0-9][0- 9]?)\b/;
         var r:Array=pattern. exec(str) ;
         if (r == null) {
             return false;
         }
         return true;
     }*/


		 override protected function doValidation(value:Object):Array{
		     var results:Array = super.doValidation(value);
		
		     // Return if there are errors
		     // or if the required property is set to false and length is 0.
		     var val:String = value ? String(value) : "";
		     if (results.length > 0 || ((val.length == 0) && !required))
		         return results;
		     else
		         return validateUrl( this, value, null);
		 }
	}		 
}
