package actionscripts.footerDataGrid {
    import mx.collections.ArrayCollection;
    import mx.controls.listClasses.IListItemRenderer;
    import mx.core.IFactory;
    import mx.formatters.CurrencyFormatter;

    public class SummaryColumn implements IFooterDataGridColumn {
        public static const SUM:String = 'sum';
        public static const MIN:String = 'min';
        public static const MAX:String = 'max';
        public static const AVG:String = 'average';
        public static const COUNT:String = 'count';
        
        private var _dataColumn:Object;
        
        [Bindable] public var footer:SummaryFooter;
        [Bindable] public var labelFunction:Function;
        [Bindable] public var label:String="";
        [Bindable] public var checkedDG:Boolean=false;
        [Bindable] public var itemRenderer:IFactory;
        [Bindable] public var column:Object=null;
        [Bindable] public var renderer:IListItemRenderer;
        [Bindable] public var useColumnItemRenderer:Boolean = true;
        [Bindable] public var useCurrencyFormatter:Boolean = false;
        [Bindable] public var useCustomDataProvider:Boolean = false;
        [Bindable] public var customDataProvider:ArrayCollection = new ArrayCollection();
        
        public var precision:int = -1;
        
        private var _operation:String;

        // dataColumn is the one that feeds the data
        public function set dataColumn(value:Object):void {
            _dataColumn = value;
            
            if(labelFunction != null){
            	label = labelFunction(column);
            }
        }

        public function get dataColumn():Object {
            return (_dataColumn) ? _dataColumn : column;
        }

        private function average(col:Object):Number {
            if (dataProvider) {
                return (sum(col) / dataProvider.length);
            } else {
                return 0;
            }
        }
        
        private function sum(col:Object):Number {
            var total:Number = 0;
            for each (var row:Object in dataProvider) {
                total += ExpressionUtils.resolveExpression(row, col.dataField);
            }
                
            return total;
        }
        
        private function min(col:Object):Number {
            var min:Number = 0;
            for each (var row:Object in dataProvider) {      
                var value:Object = ExpressionUtils.resolveExpression(row, col.dataField);
                if (value != null) {
                    if(Number(value) < min || !min) {
                        min = Number(value);
                    }
                }
            }
                    
            return min;
        }
        
        private function max(col:Object):Number {
            var max:Number = 0;
            for each (var row:Object in dataProvider) {
                var value:Object = ExpressionUtils.resolveExpression(row, col.dataField);
                if(value != null) {
                    if (Number(value) > max || !max) {
                        max = Number(value);
                    }
                }
            }
                    
            return max;
        }
        
        private function count(col:Object):Number {
            var count:Number = 0;
            for each (var row:Object in dataProvider) {
                if (ExpressionUtils.resolveExpression(row, col.dataField)) {
                    count++;
                }
            }
                    
            return count;
        }
        
        public function set operation(value:String):void {
            _operation = value;
            labelFunction = defaultLabelFunction;
        }
        
        private function defaultLabelFunction(col:Object):String {
            var value:Number = 0;
            
            switch (_operation) {
                case SUM:
                    value = sum(col);
                    break;
                case AVG:
                    value = average(col);
                    break;
                case MIN:
                    value = min(col);
                    break;
                case MAX:
                    value = max(col);
                    break;
                case COUNT:
                    value = count(col);
                    break;
            }
            
            //var label:String = (value) ? value.toFixed(precision) : ((!isNaN(value))? value.toString() : "");
            label = "";
            if(value){
            	if(precision==-1){
            		label = value.toString();
            	}else{
            		label = value.toFixed(precision);
            	}
            }else{
            	if(!isNaN(value)){
            		label = value.toString();
            	}
            }
            //label = (value) ? (precision==-1 ? value :value.toFixed(precision)) : ((!isNaN(value))? value.toString() : "");
            
            
            
            if(useCurrencyFormatter){
            	var cf:CurrencyFormatter = new CurrencyFormatter();
            	cf.precision=2;
            	cf.useThousandsSeparator = true;
            	cf.decimalSeparatorFrom=".";
            	cf.thousandsSeparatorFrom = ",";
            	cf.useNegativeSign = true;
            	cf.rounding = "none";
            	label = cf.format(label);
            }else{
            	label = label.replace(/\.00/,'');
            }
            return label;
        }
        
        public function get dataProvider():Object {
        	if(useCustomDataProvider) return customDataProvider;
            return footer.dataProvider;
        }
    }
}