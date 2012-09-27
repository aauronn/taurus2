/**
 * All computational source code, intellectual property or other works 
 * contained herein are deemed Public Domain as per the Creative 
 * Commons Public Domain license.
 * 
 * http://creativecommons.org/licenses/publicdomain/
 * 
 * Author : Jason Hawyluk
 * Date: 26/02/2007
 * Reference : http://flexibleexperiments.wordpress.com
 * 
 * Jason Hawryluk disclaims all warranties with regard to this software, 
 * including all implied warranties of merchantability and fitness, in 
 * no event shall Jason Hawryluk be liable for any special, indirect or 
 * consequential damages or any damages whatsoever resulting from loss of 
 * use, data or profits, whether in an action of contract, negligence or 
 * other tortuous action, arising out of or in connection with the use or 
 * performance of this software.
 **/
package actionscripts
{
	import flash.display.DisplayObject;
	import flash.events.Event;
	import flash.events.KeyboardEvent;
	import flash.events.MouseEvent;
	import flash.events.TimerEvent;
	
	import mx.collections.ICollectionView;
	import mx.collections.XMLListCollection;
	import mx.controls.Tree;
	import mx.controls.treeClasses.TreeItemRenderer;
	import mx.core.ClassFactory;
	import mx.core.DragSource;
	
	import mx.core.mx_internal;
	import mx.effects.Fade;
	import mx.events.DragEvent;
	import mx.events.EffectEvent;
	import mx.events.ListEvent;
	import mx.events.TreeEvent;
	
	import mx.managers.DragManager;
	
	//Update:
	import mx.styles.CSSStyleDeclaration;
	import mx.styles.StyleManager;
	import mx.managers.CursorManager;
	import flash.geom.Point;
	import mx.managers.dragClasses.*
	import mx.core.IUIComponent;
	import mx.controls.Alert;
	//Update:
					
	use namespace mx_internal;
		
	public class SpringLoadedTree extends Tree{
	
		
				
		/**
		* Keep a list of folders that were open prior to the drag operation so that
		* we can know not to close them in the restore and close nodes methods.
		**/
		private var openedFolderHierarchy:Object;
		
		[Bindable]
		private var _inheritRestrictionsDrag:Boolean = false;
		        
	       
		public function SpringLoadedTree(){
			super();
						
			//Drag events
			//addEventListener(DragEvent.DRAG_COMPLETE,handleDragComplete);
			//addEventListener(DragEvent.DRAG_OVER,handleDragOver);
			//addEventListener(DragEvent.DRAG_EXIT,handleDragExit);
			addEventListener(DragEvent.DRAG_START,handleDragStart);
			
			//addEventListener(TreeEvent.ITEM_OPEN,handleItemOpened);
			//addEventListener(TreeEvent.ITEM_CLOSE,handleItemClosed);
			
			
			//Update:
			//addEventListener(DragEvent.DRAG_DROP,handleDragDrop);
			addEventListener("click", handleOpenClick);
			
			
			//key events
			//addEventListener(KeyboardEvent.KEY_UP, handleKeyEvents);
			
			//_delayedTimer.addEventListener(TimerEvent.TIMER_COMPLETE,handleTimerComplete);
			
			//setup for effect
		//	_treeItemRendererFadeEffect.alphaFrom = 1;
		//	_treeItemRendererFadeEffect.alphaTo = .2;
		//	_treeItemRendererFadeEffect.duration = 200;
		//	_treeItemRendererFadeEffect.startDelay = 400;
		//	_treeItemRendererFadeEffect.repeatDelay = 200;
								
			
		}
		

		
				
		
		
		/**
		* Update: Get a handle to the indernal drop data structure.
		**/
		public function get dropData():Object{
			return super.mx_internal::_dropData;
		}
				
		/**
		* Update: Open/close on click anywhere in the item render
		* see handleClick()
		**/
		private var _openOnClick:Boolean = true;
		[Bindable]
		public function set openOnClick(value:Boolean):void{
			_openOnClick=value;
		}
		public function get openOnClick():Boolean{
			return _openOnClick;	
		}
		
		/**
		* Update:When set to true children will inherit their parents restrictions
		* for a drag operation. For example the control will not allow dragging of 
		* children of a parent that has a acceptDrag attribute = false. 
		**/
		
		public function set inheritRestrictionsDrag(value:Boolean):void{
			_inheritRestrictionsDrag=value;
		}
		public function get inheritRestrictionsDrag():Boolean{
			return _inheritRestrictionsDrag;	
		}

		/**
		* Update:When set to true children will inherit their parents restrictions
		* for a drop operation. For example the control will not allow dropping into 
		* children of a parent that has a acceptDrop attribute = false. 
		**/
		private var _inheritRestrictionsDrop:Boolean = true;
		[Bindable]
		public function set inheritRestrictionsDrop(value:Boolean):void{
			_inheritRestrictionsDrop=value;
		}
		public function get inheritRestrictionsDrop():Boolean{
			return _inheritRestrictionsDrop;	
		}
					
		/**
		* The returned dispatched call if delay triggered.
		**/
		
		
		private function handleOpenClick(event:MouseEvent):void{
			
			try{
				//Exit as well if the selected item is null, if the tree
				//supports multi selection then this is the case when one 
				//or more nodes are selected.
				if (!openOnClick || !selectedItem){return;}
				
				//test that we are in fact over top of the item.
				//(see method description)
				if (!isMouseOverNode(selectedItem)){return;}
				
				
				//if control key is pressed then return as perhaps multi 
				//select in progress
				if (event.ctrlKey==true || event.altKey==true){
					event.preventDefault(); 
					return;
				}
										
				if (dataDescriptor.isBranch(selectedItem)){
					
					//on the below expanditem calls dispatch the event so that 
					//subsequent actions can take place
					if (isItemOpen(selectedItem)==true){
						expandItem(selectedItem,false,true,true);
					}
					else{
						expandItem(selectedItem,true,true,true);				
					}
								
					return;
				}
			}
			catch(e:*){
				
			}
		}
		
		/**
		* Update: Test whether or not the passed node is the node 
		* the mouse is over, by getting the itemrenderer 
		* and compareing x and y corrdinates. This is for the 
		* clickOpen. In Flex if an item is selected and you 
		* click in the parent white space it'll open. This test 
		* helps us correct that.
		**/
		private function isMouseOverNode(item:Object):Boolean{
			
			//convert the mouse x and y
			var currentPoint:Point = new Point(mouseX,mouseY);
			currentPoint = localToGlobal(currentPoint);
			
			//grab the selected item renderer
			var selectedItemrenderer:TreeItemRenderer = TreeItemRenderer(itemToItemRenderer(item));
			
			//get the point structure for comparision
			var selectedItemPoint:Point = new Point(selectedItemrenderer.x,selectedItemrenderer.y);
			selectedItemPoint = localToGlobal(selectedItemPoint);
			
			
			if (currentPoint.y > selectedItemPoint.y && 
			currentPoint.y < (selectedItemPoint.y + selectedItemrenderer.height)){
				return true;
			}
			
			return false;
			
		}
		
		
		/**
		* Init the start of the drag and grab a open folder stack so we can 
		* compare later when closing, opening, exiting etc..
		**/		
		private function handleDragStart(event:DragEvent):void{
			//if(autoCloseOpenNodes==true){
			//	stopAnimation();
			//	_delayedTimer.cancelDelayedTimer();
			//	openedFolderHierarchy = openItems;
			//}
			
			//Update: if any of the nodes being dragged have a canDrag=false
			//then don't allow it
			for (var i:int = 0;i<event.currentTarget.selectedItems.length;i++){
				if (!itemAcceptDrag(event.currentTarget.selectedItems[i])){
					event.currentTarget.hideDropFeedback(event);	
					event.preventDefault();
					return;
				}
			}
		
			
		}
		
		/**
		* Update:Tests for the acceptDrag value on the data source for 
		* both obejct and xml.
		**/
		private function itemAcceptDrag(item:Object):Boolean{
			
			if (item is XML){
                try{
                    if (item.@acceptDrag.length() != 0){
                    	if (item.@acceptDrag == "false"){
                    		return false;
                    	}
                    }
                }
                catch(e:Error){
                }
            }
            else if (item is Object){
                try{
                    if (item.acceptDrag){
                    	if (item.acceptDrag == "false"){
	                    	return false;	
                    	}
                    }
                }
                catch (e:Error){
                }
            }
            
            //recurse if required
            if (inheritRestrictionsDrag){
            	if (item.parent()){
            		if (!itemAcceptDrag(item.parent())){
            			return false
            		}
            	}
            }
            
            return true;
		}
	}
}		