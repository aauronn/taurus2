package actionscripts.footerDataGrid
{
	import actionscripts.RowColorDataGrid;
	
	import flash.events.Event;
	
	import mx.collections.ListCollectionView;
	import mx.controls.dataGridClasses.DataGridListData;
	import mx.controls.listClasses.BaseListData;
	import mx.core.UIComponent;
	
	//http://code.seanhess.net/?p=17
	
	public class FooterDataGrid extends RowColorDataGrid implements IFooterDataGrid
	{
		//include "_footerDataGrid.as";
				
		private var _footer:Array = null;
		public var footerHeight:int = 0;
		public var totalFooterHeight:int = 0;
		private var footerDirty:Boolean = false;
		
		override public function set dataProvider(value:Object):void
		{
			if (value is ListCollectionView)
				value.addEventListener('collectionChange', onCollectionChange);
		
			super.dataProvider = value;
		}
		
		private function onCollectionChange(event:Event):void
		{
			dispatchEvent(new Event('collectionChange'));
		}
		
		public function set footer(value:Array):void
		{
		    footerHeight = 22;
		    
		    // need to clear out any old footer children that might exist
		    if(_footer != null)
		    {
		        for each (var child:UIComponent in _footer)
		        {
		             if(this.contains(child))
		             {
		                 removeChild(child);
		             }
		        }
		    }
		    
		    _footer = value;
		    footerDirty = true;
		    invalidateProperties();
		    invalidateList();
		}
		
		public function get footer():Array
		{
			return _footer;
		}
		
		/*public function get widthDG():Number
		{
			return this.width;	
		}*/
		
		override protected function commitProperties():void
		{
		    super.commitProperties();
		
		    if (footerDirty)
		    {
		        footerDirty = false;
		        
		        for each (var child:UIComponent in footer)
		        {
		            if(!(child is SummaryFooter))
		            {
		                throw new Error("All Footers must be SummaryFooter");
		            }
		            
		            var childFooter:SummaryFooter = child as SummaryFooter;
		            childFooter.styleName = this;
		            childFooter.dataGrid = this;
		            addChild(childFooter);
		        }
		    }
		}
		
		
		override protected function adjustListContent(unscaledWidth:Number = -1, unscaledHeight:Number = -1):void
		{
		    
		    
		    if(footer != null && footer.length > 0)
		    {
		        if(!isNaN(rowHeight))
		        {
		            footerHeight = rowHeight;
		            totalFooterHeight = rowHeight * footer.length;
		        }
		    }
		    
		    super.adjustListContent(unscaledWidth, unscaledHeight);
		
			
		    if(footer != null && footer.length > 0)
		    {
		        
		        if(lockedColumnCount>0){
		        	//listContent.x = 4;
		        	//loader.msgAviso(listContent.x.toString());
		        	//loader.msgAviso("A");
		        	listContent.setActualSize(listContent.width, listContent.height - footerHeight);
		        	listContent.x = 1;
		        	 //listContent.setActualSize(unscaledWidth, unscaledHeight - totalFooterHeight - this.headerHeight);
		        	 //listContent.setActualSize(listContent.width, unscaledHeight - totalFooterHeight - this.headerHeight);
		        }else{
					//orig
					//loader.msgAviso("B");
					listContent.y -= 2;
					listContent.setActualSize(unscaledWidth, unscaledHeight - totalFooterHeight - this.headerHeight);		        	
		        }
	                //this deals w/ having locked columns - it's handled differently in
	                //the dg and the adg
	                //footer.setActualSize(listContent.width + listContent.x, footerHeight);
	                //footer.move(1, listContent.y + listContent.height + 1);

		        	//listContent.setActualSize(unscaledWidth, unscaledHeight - totalFooterHeight - this.headerHeight);	
		        	var offset:int = 1;
			        for each (var child:UIComponent in footer)
			        {
			            /*child.setActualSize(unscaledWidth - 2, footerHeight);		            
			            child.move(listContent.x, unscaledHeight - totalFooterHeight - 1 + offset);
			            offset += footerHeight;*/ 
			            
			            //ok
			            child.setActualSize(listContent.width, footerHeight);
			            
			            if(lockedColumnCount>0 || (horizontalScrollBar==null || !horizontalScrollBar || horizontalScrollBar.visible==false)){
			            	child.move(listContent.x, listContent.y + listContent.height + offset);	
			            }else{
			            	child.move(listContent.x, listContent.y + listContent.height + offset - 15);
			            }
						
						offset += footerHeight;
			        }
			        
			        
		        	/*
		        	listContent.setActualSize(unscaledWidth, unscaledHeight - totalFooterHeight - this.headerHeight);	
		        	var offset:int = 0;
			        for each (var child:UIComponent in footer)
			        {
			            //child.setActualSize(unscaledWidth - 2, footerHeight);		            
			            child.move(listContent.x, unscaledHeight - totalFooterHeight - 1 + offset);
			            offset += footerHeight;// 
			            
			            //ok
			            child.setActualSize(listContent.width, footerHeight);
						child.move(listContent.x, listContent.y + listContent.height + offset);
						offset += footerHeight;
			        }*/
		        		        
		    }
		}
		
		override public function invalidateDisplayList():void
		{
		    super.invalidateDisplayList();
		    
		    if(footer && footer.length >= 0)
		    {
		        for each (var child:UIComponent in footer)
		        {
		            child.invalidateDisplayList();
		        }
		    }
		}
		
		public function createListData(text:String, dataField:String, i:int):BaseListData
		{
			return new DataGridListData(text, dataField, i, null, this, -1);
		}
	}
}