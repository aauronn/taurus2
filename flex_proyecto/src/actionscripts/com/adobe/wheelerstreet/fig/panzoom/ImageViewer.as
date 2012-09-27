package actionscripts.com.adobe.wheelerstreet.fig.panzoom
{
	import actionscripts.com.adobe.wheelerstreet.fig.panzoom.modes.PanZoomCommandMode;
	import actionscripts.com.adobe.wheelerstreet.fig.panzoom.utils.ContentRectangle;
	
	import flash.display.Bitmap;
	import flash.display.BlendMode;
	import flash.display.Loader;
	import flash.events.Event;
	import flash.events.IOErrorEvent;
	import flash.events.ProgressEvent;
	import flash.geom.Matrix;
	import flash.geom.Point;
	import flash.geom.Rectangle;
	import flash.net.URLRequest;
	
	import mx.controls.Label;
	import mx.controls.SWFLoader;
	import mx.core.UIComponent;
	import mx.effects.AnimateProperty;
	import mx.events.FlexEvent;
	import mx.events.ResizeEvent;
	import mx.events.TweenEvent;
	
	
	public class ImageViewer extends UIComponent
	{
		[Bindable]
		public var loadingImage:Boolean = false;
		[Bindable]
		public var bitmapScaleFactorMin:Number = .125;		
		[Bindable]
		public var bitmapScaleFactorMax:Number = 5;			

		private var _panZoomCommandMode:PanZoomCommandMode;
				
		public var viewRect:Rectangle;

		private var _contentRectangle:ContentRectangle;
		private var bgcolor:uint=0xffffff;

		
		private var _bitmap:Bitmap;
		private var _bitmapScaleFactor:Number;
		
		private var _smoothBitmap:Boolean = true;
		
		private var _imageURL:String;
		private var _loader:Loader;
		private var _percentLoadedLabel:Label;
		
		// preloader assets
		[Embed(source="icons/iconography.swf", symbol="ProgressThrobber")] 
		private var _progressThrobber:Class;
		private var _progressSWF:SWFLoader;	
		

		/////////////////////////////////////////////////////////
		//
		// public getters/setters
		//
		/////////////////////////////////////////////////////////
		
		/**
		* 
		* Setting the imageURL triggers the loading of the image and extraction 
		* and assignment of it's bitmapData. 
		*
		*/ 		
		
		[Bindable]
		public function get imageURL():String
		{
			return _imageURL;
		}
		public function set imageURL(value:String):void
		{
			// setting imageURL triggers loading sequence		
			if (value == _imageURL)
				return;

			_loader = new Loader();
            _loader.load(new URLRequest(value));
			loadingImage = true;            
            
            formatUI();
            
			_percentLoadedLabel.text = "";		
            invalidateDisplayList();            
            
            // load events 
			_loader.contentLoaderInfo.addEventListener(Event.COMPLETE, handleLoadComplete);
			_loader.contentLoaderInfo.addEventListener(IOErrorEvent.IO_ERROR, handleLoadIOError);
			_loader.contentLoaderInfo.addEventListener(ProgressEvent.PROGRESS, handleLoadProgress);					
			

		}
		
		/**			
		* Setting the ImageViewer's bitmap triggers the activation of the PanZoomCommandMode.
		* 
		* <p>The PanZoomCommandMode acts as the 'invoker' element in the Command Pattern.
		* It's constructor requires that you assoiciate it with a 'client' and a 'reciever'. 
		* In this implementation the 'client' is the ImageView (this) and the 
		* reciever is the bitmapData transform matrix.</p> 
		*/
		
		public function get bitmap():Bitmap
		{
			return _bitmap;
		}
		public function set bitmap(value:Bitmap):void
		{
			try{
				if (value == _bitmap)
					return;
				
				_bitmap = value;
	
				_contentRectangle = new ContentRectangle(0, 0,_bitmap.width, _bitmap.height, viewRect);		
				
				//_contentRectangle.viewAll(viewRect);
	
				_panZoomCommandMode = new  PanZoomCommandMode(this, _contentRectangle)			
				_panZoomCommandMode.activate();
				
				invalidateDisplayList();	
			}catch(ex:Error){
				
			}
		}
		
		
		/**
		* Tracks the scale of the bitmap being displayed.
		* Setting the bitmapScale factor invalidates the displayList since any
		* change will requite an update.
		*/ 	
				
		[Bindable]
		public function get bitmapScaleFactor():Number
		{
			return _bitmapScaleFactor;

		}
		public function set bitmapScaleFactor(value:Number):void
		{
			if (value == bitmapScaleFactor )
				return;
				
			if (value < bitmapScaleFactorMin)
				return;

			if (value > bitmapScaleFactorMax)
				return;

			_bitmapScaleFactor = value;								
			invalidateDisplayList();				
		}
		
		/**
		 * setting smoothBitmap to true hurts performance slightly
		 */
		 
		[Bindable]
		public function get smoothBitmap():Boolean
		{
			return _smoothBitmap;
		}
		public function set smoothBitmap(value:Boolean):void
		{
			if (value == _smoothBitmap)
				return;
			
			_smoothBitmap = value;
			invalidateDisplayList();	
		}		
			
		
		/////////////////////////////////////////////////////////
		//
		// public functions
		//
		/////////////////////////////////////////////////////////
		
		/**
		 * The zoom function requires a direction to be assigned when the function 
		 * is triggerd.  "in" zooms in and conversly "out" zooms out.
		 */
		
		public function zoom(direction:String):void
		{
			var _animateProperty:AnimateProperty = new AnimateProperty(this);		
			_animateProperty.property = "bitmapScaleFactor";
			
			_animateProperty.addEventListener(TweenEvent.TWEEN_UPDATE, handleTween);
			_animateProperty.addEventListener(TweenEvent.TWEEN_END, handleTween);			
			
			switch (direction)
			{
				case "in":
					
					if (_bitmapScaleFactor * 2 > bitmapScaleFactorMax)
					{
						_animateProperty.toValue = bitmapScaleFactorMax;
						
					} else
					{
						_animateProperty.toValue = _bitmapScaleFactor * 2;				
					}				
					break;
				
				case "out":
					
					if (_bitmapScaleFactor / 2 > bitmapScaleFactorMax)
					{
						_animateProperty.toValue = bitmapScaleFactorMax;
						
					} else
					{
						_animateProperty.toValue = _bitmapScaleFactor / 2;				
					}				
					break;					
			}

			
			_animateProperty.play();
			
			function handleTween(e:TweenEvent):void
			{
				switch (e.type)
				{
					case "tweenUpdate":
					
						_contentRectangle.zoom = bitmapScaleFactor;						
						break;
				
					case "tweenEnd":
						
						_contentRectangle.zoom = bitmapScaleFactor;		
						_animateProperty.removeEventListener(TweenEvent.TWEEN_END, handleTween);	
						_animateProperty.removeEventListener(TweenEvent.TWEEN_UPDATE, handleTween);
						
						break;
				}
			}	 
			
												     	
		}
		/**
		 * The zoomByOrigin function zooms in on the users current mouse position.  
		 * This function requires a direction to be assigned when the function 
		 * is triggerd.  "in" zooms in and conversly "out" zooms out.
		 */
		 
		public function zoomByOrigin(direction:String):void
		{
			var _animateProperty:AnimateProperty = new AnimateProperty(_contentRectangle);		
			_animateProperty.property = "zoomByOrigin";			
			_animateProperty.addEventListener(TweenEvent.TWEEN_UPDATE, handleTween);
			_animateProperty.addEventListener(TweenEvent.TWEEN_END, handleTween);		
			
			_contentRectangle.zoomOrigin = new Point(
													 (-_contentRectangle.x + mouseX) *  1/_contentRectangle.scaleX,
													 (-_contentRectangle.y + mouseY) *  1/_contentRectangle.scaleY
												     );		
			
			switch (direction)
			{
				case "in":
					
					if (_bitmapScaleFactor * 2 > bitmapScaleFactorMax)
					{
						_animateProperty.toValue = bitmapScaleFactorMax;
						
					} else
					{
						_animateProperty.toValue = _bitmapScaleFactor * 2;				
					}				
					break;
				
				case "out":
					
					if (_bitmapScaleFactor / 2 > bitmapScaleFactorMax)
					{
						_animateProperty.toValue = bitmapScaleFactorMax;
						
					} else
					{
						_animateProperty.toValue = _bitmapScaleFactor / 2;				
					}				
					break;					
			}													     
													     
			
			_animateProperty.play();
			
			function handleTween(e:TweenEvent):void
			{
				switch (e.type)
				{
					case "tweenUpdate":
					
						bitmapScaleFactor = e.value	as Number;		
						
						break;
				
					case "tweenEnd":
								
						_animateProperty.removeEventListener(TweenEvent.TWEEN_END, handleTween);	
						_animateProperty.removeEventListener(TweenEvent.TWEEN_UPDATE, handleTween);
						
						break;
				}
			}				
		}
		
		
		public function setZoom(scale:Number):void
		{
			_contentRectangle.zoom = scale;
			bitmapScaleFactor = scale;
			
		}
		
		public function centerView():void
		{
			_contentRectangle.viewAll(viewRect);
			bitmapScaleFactor = _contentRectangle.scaleX;
		}


		/////////////////////////////////////////////////////////
		//
		// constructor
		//
		/////////////////////////////////////////////////////////
		
	    /**
	     *  Constructor.
	     */	
	     
		public function ImageViewer():void
		{
			viewRect = new Rectangle();			
			_contentRectangle = new ContentRectangle(0,0,0,0,viewRect);
			
			addEventListener(ResizeEvent.RESIZE, handleResize);
		
			addEventListener(FlexEvent.CREATION_COMPLETE, handleCreationComplete);
			function handleCreationComplete(e:FlexEvent):void
			{
				_contentRectangle.zoom = .5;	
				bitmapScaleFactor = _contentRectangle.zoom;
				invalidateDisplayList();
			}
		}
		
	    /**
	     *  @private
	     */
		
		private function handleResize(e:ResizeEvent):void
		{
			if (_contentRectangle == null)
				return;
				
			_contentRectangle.centerToPoint(new Point(this.width/2, this.height/2));	
		}
		
		/////////////////////////////////////////////////////////
		//
		// protected overrides
		//		
		/////////////////////////////////////////////////////////

		/**
		 * When the display list is updated the bitmap is drawn via a bitmapFill
		 * applied to the UIComponents graphics layer. The size and position of the bitmap 
		 * are determined by the bitmapData's transform matrix, which is derived by parsing
		 * the _contentRectangle's properties.   
		 * 
		 */
		 
		override protected function updateDisplayList(unscaledWidth:Number, unscaledHeight:Number):void
		{
			super.updateDisplayList(unscaledWidth, unscaledHeight);
						
			viewRect.width = width;
			viewRect.height = height;

			if (_bitmap == null)
			{
				// if there's no bitmapData fill the component with black
				graphics.beginFill(bgcolor,1)
				graphics.drawRect(0,0,unscaledWidth,unscaledHeight);								
			
			} else if (viewRect != null)
			{	
				/*if(_contentRectangle.topLeft.x<0) return;
				var n:Number=0;
				trace("contentRectangle)  X:"+ _contentRectangle.topLeft.x.toString()+"   Y:"+_contentRectangle.topLeft.y.toString());
				trace("_bitmap)  W:"+ _bitmap.width.toString()+"   H:"+ _bitmap.height.toString());
				*/
							   
				var __bitmapTransform:Matrix = new Matrix(_contentRectangle.width / _bitmap.width,
											  0,
											  0,
											  _contentRectangle.height / _bitmap.height,
											  _contentRectangle.topLeft.x,
											  _contentRectangle.topLeft.y
											  );

				// fill the component with the bitmap.
				graphics.clear()
				graphics.beginBitmapFill(_bitmap.bitmapData,  // bitmapData
										 __bitmapTransform,   // matrix
										 true,                // tile?
										 _smoothBitmap		  // smooth?
										 )					 
				
				graphics.drawRect(0,0,unscaledWidth, unscaledHeight);


				// if the edge of the bitmap transition into view 
				// we paint in the negative area.
		
				if (_contentRectangle.left > viewRect.topLeft.x)
				{
					//trace("B: "+_contentRectangle.left.toString()+" - "+viewRect.topLeft.x.toString());
					graphics.beginFill(bgcolor,1)
					graphics.drawRect(0,0, _contentRectangle.x, unscaledHeight);				
				}
				if (_contentRectangle.top > viewRect.topLeft.y)
				{
					//trace("C");
					graphics.beginFill(bgcolor,1)
					graphics.drawRect(0,0,unscaledWidth, _contentRectangle.y);				
				}
				if (_contentRectangle.right < viewRect.width)
				{
					//trace("D: "+_contentRectangle.right.toString()+" - "+viewRect.width.toString());
					graphics.beginFill(bgcolor,1)
					graphics.drawRect(_contentRectangle.right,0, viewRect.width - _contentRectangle.right , viewRect.height );				
				}			
				
				if (_contentRectangle.bottom < viewRect.height)
				{
					//trace("E");
					graphics.beginFill(bgcolor,1)
					graphics.drawRect(0,_contentRectangle.bottom, viewRect.width , viewRect.height - _contentRectangle.bottom  );				
				}			
			
			}
		}
		

		/////////////////////////////////////////////////////////
		//
		// ui
		//		
		/////////////////////////////////////////////////////////
		
		/**
	    *  @private
	    */
		
		private function formatUI():void
		{
			_progressSWF = new SWFLoader();
			_progressSWF.source = _progressThrobber;
			_progressSWF.width = 16;
			_progressSWF.height = 16;
			_progressSWF.x = 40;
			_progressSWF.y = 15;
			addChild(_progressSWF);

			_percentLoadedLabel = new Label();				
			_percentLoadedLabel.width = 300;
			_percentLoadedLabel.height = 32;
			_percentLoadedLabel.x = 55;
			_percentLoadedLabel.y = 15;
			_percentLoadedLabel.blendMode = BlendMode.INVERT;
			addChild(_percentLoadedLabel);	
		}

		/**
	    *  @private
	    */
				
		// load handlers
		private function handleLoadComplete(e:Event):void
		{
			try{
				bitmap = Bitmap(_loader.content);			
	            removeChild(_progressSWF);
	            removeChild(_percentLoadedLabel); 
	            
	            loadingImage = false;
				_percentLoadedLabel.text = "Complete";			
				trace(e.type)	
			}catch(ex:Error){
				
			}				
		}

		/**
	    *  @private
	    */
				
		private function handleLoadIOError(e:Event):void
		{
            removeChild(_progressSWF);
            loadingImage = false;
			_percentLoadedLabel.text = "failed to load image";
		}
		
		/**
	    *  @private
	    */
				
		private function handleLoadProgress(e:Event):void
		{
			_percentLoadedLabel.text = String(
											   Math.round(
														   (ProgressEvent(e).bytesLoaded / ProgressEvent(e).bytesTotal)
														    * 100
														    ) + "%"
						   		  			  );					
		}				
	}
}