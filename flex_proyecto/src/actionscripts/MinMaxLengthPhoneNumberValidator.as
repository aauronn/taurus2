package actionscripts
{
	import mx.validators.*;
	import mx.managers.ISystemManager;
	import mx.managers.SystemManager;
	import mx.resources.ResourceBundle;
	
	public class MinMaxLengthPhoneNumberValidator extends PhoneNumberValidator
	{
		public var _minLength:Number = 10;
		public var _maxLength:Number = 0;
		
		public function set minLength(len:Number): void
		{
			_minLength = len;
			//wrongLengthError = "Your telephone number must be at least " + len + " digits in length.";
		}
		
		public function get minLength():Number
		{
			return _minLength;
		}
		
		public function set maxLength(len:Number): void
		{
			_maxLength = len;
			//wrongLengthError = "Your telephone number must be max " + len + " digits in length.";
		}
		
		public function get maxLength():Number
		{
			return _maxLength;
		}
		   
		public static function validatePhoneNumber(validator:MinMaxLengthPhoneNumberValidator, value:Object, baseField:String):Array
		{
			var results:Array = [];
			
			var valid:String =  DECIMAL_DIGITS + validator.allowedFormatChars;
			var len:int = value.toString().length;
			var digitLen:int = 0;
			var n:int;
			var i:int;
			
			if(validator.required || (!validator.required && len>0))
			{
				n = validator.allowedFormatChars.length;
				for (i = 0; i < n; i++)
				{
					if (DECIMAL_DIGITS.indexOf(validator.allowedFormatChars.charAt(i)) != -1)
						throw new Error(validator.invalidCharError);
				}
				
				for (i = 0; i < len; i++)
				{
					var temp:String = "" + value.toString().substring(i, i + 1);
					if (valid.indexOf(temp) == -1)
					{
						results.push(new ValidationResult(true, baseField, "invalidChar", validator.invalidCharError));
						return results;
					}
					if (valid.indexOf(temp) <= 9)
						digitLen++;              
				}
				
				if (digitLen < validator.minLength)
				{
					results.push(new ValidationResult(true, baseField, "wrongLength", validator.wrongLengthError));
					return results;
				}
				else
				{
					if( validator.maxLength > 0 && digitLen != validator.maxLength )
					{
						results.push(new ValidationResult(true, baseField, "wrongLength", validator.wrongLengthError));
						return results;
					}
				}
			}
			return results;
		}
		
		override protected function doValidation(value:Object):Array
		{
			return MinMaxLengthPhoneNumberValidator.validatePhoneNumber(this, value, null);
		}  
	}
}

