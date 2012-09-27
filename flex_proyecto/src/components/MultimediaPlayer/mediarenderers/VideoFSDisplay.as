package components.MultimediaPlayer.mediarenderers{
    
    //CONT
    
    import components.MultimediaPlayer.controls.MediaProgressSlider;
    import mx.containers.HBox;
    import mx.containers.VBox;
    import mx.controls.VideoDisplay;
    import mx.core.UIComponent;
    import mx.core.mx_internal;
    import flash.display.DisplayObject;
    import flash.display.StageDisplayState;
    import flash.events.FullScreenEvent;
    [DefaultProperty("displayState")]

/**
 * Description:	Custom video display with fullscreen abilities. Optional a video control can be set and<br />
 * 						Flash Video mipmapping capabilities (aka smoothing & deblocking) can be enabled. <br />
 * 						Based on FSVideoDisplay by Manish Jethani (manish.jethani@gmail.com).
 * The MIT License
 * 
 * Copyright (c) 2008 Jonas Schulz (j.schulz@cromac-services.de)
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
*/
    public class VideoFSDisplay extends VideoDisplay {
        private var _videoControl:UIComponent;
        private var _vcParentChildPos:int = 1;
        private var _vcXPos:Number;
        private var _vcYPos:Number;        
        private var _vcWidth:Number;
        private var _vcHeight:Number;
        private var _displayState:String = StageDisplayState.NORMAL;
        private var _enableSmoothing:Boolean = false;
        private var _setDeblocking:int = 0;
        
        [Inspectable(category="Other", enumeration="normal,fullScreen", defaultValue="normal")]       
        public function get displayState():String { return _displayState; }
        
        public function set displayState(value:String):void {
            if (value != _displayState) {
                _displayState = value;
                
                if (_displayState == StageDisplayState.FULL_SCREEN) {
                        stage.addEventListener(FullScreenEvent.FULL_SCREEN, handleFullScreen);
                
                        // Move video player to stage
                        this.mx_internal::videoPlayer.visible = false;
                        stage.addChild(this.mx_internal::videoPlayer);
                }
                
                stage.displayState = _displayState;
            }
        }
        
        [Inspectable(category="Other", defaultValue="false")]
        public function get enableSmoothing():Boolean { return _enableSmoothing; }
        
        public function set enableSmoothing(value:Boolean):void {
        	if (value != _enableSmoothing) {
	        	_enableSmoothing = value;
	        	invalidateProperties();
	        	//trace("smoothing");
        	}
        }
        
        [Inspectable(category="Other", enumeration="0,1,2,3,4,5", defaultValue="0")]
        public function get setDeblocking():int { return _setDeblocking; }
        
        public function set setDeblocking(value:int):void {
        	if (value >=0 && value <=5) {
				_setDeblocking = value;
				invalidateProperties();
        	}
        }
                
        public function set videoControl(value:UIComponent):void {
            if (value != _videoControl) {
                _videoControl = value;
                _vcXPos = _videoControl.x;
                 _vcYPos = _videoControl.y;
                _vcWidth = _videoControl.width;
                _vcHeight = _videoControl.height;
                //trace("X:"+_videoControl.x+" Y:"+_videoControl.y+" WIDTH:"+_videoControl.width+" HEIGHT:"+_videoControl.height)
            }
        }
        
        [Inspectable(category="Other", defaultValue="1")]
        public function get vcParentChildPos():int { return _vcParentChildPos; }
        
        public function set vcParentChildPos(value:int):void {
        	if(_videoControl) _vcParentChildPos = value;
        }
         
         public function VideoFSDisplay() {
         	super();
         }         
                       
		override protected function commitProperties():void {
        	super.commitProperties();
        	this.mx_internal::videoPlayer.deblocking = _setDeblocking;
        	this.mx_internal::videoPlayer.smoothing = _enableSmoothing;
        }
            
        override protected function updateDisplayList(unscaledWidth:Number, unscaledHeight:Number):void {
            if (displayState == StageDisplayState.NORMAL) {
                if (_videoControl) {
                	_videoControl.x = _vcXPos;
                	_videoControl.y = _vcYPos;
                    _videoControl.width = _vcWidth;
                    _videoControl.height = _vcHeight;
                   //trace(MediaProgressSlider(VBox(HBox(_videoControl).getChildren()[0]).getChildren()[1]).name);
					//trace(MediaProgressSlider(VBox((HBox(_videoControl).getChildren()[0]).getChildren()[1])).name);                    //_videoControl.includeInLayout=false;
                    //_videoControl.visible=false;
                }
                super.updateDisplayList(unscaledWidth, unscaledHeight);
            } else {
                // In full-screen mode, run layout with stage co-ordinates
                if (_videoControl) {
                    _videoControl.width = stage.stageWidth;
                    _videoControl.x = 0;
                    _videoControl.y = stage.stageHeight - _vcHeight;
                    //trace(MediaProgressSlider(VBox((HBox(_videoControl).getChildren()[0]).getChildren()[1])).name);
                    //_videoControl.includeInLayout=true;
                    //_videoControl.visible=true;
                }
                super.updateDisplayList(stage.stageWidth, stage.stageHeight);
            }
        }
        
        private function handleFullScreen(event:FullScreenEvent):void {
            if (stage.displayState == StageDisplayState.NORMAL) {
                stage.removeEventListener(FullScreenEvent.FULL_SCREEN, handleFullScreen);
                if (border) addChild(DisplayObject(border));
                addChild(this.mx_internal::videoPlayer);
                if (_videoControl) parent.addChildAt(_videoControl, _vcParentChildPos);
                invalidateDisplayList();
            } else {
                if (border) {
                    // Move border to stage, just behind video player
                    stage.addChildAt(DisplayObject(border), stage.getChildIndex(this.mx_internal::videoPlayer));
                }
                if (_videoControl) {
                	if (parent.contains(_videoControl)) parent.removeChild(_videoControl);
                	stage.addChild(_videoControl);
                }         
                invalidateDisplayList();
                this.mx_internal::videoPlayer.visible = true;
            }
            _displayState = stage.displayState;
        }       
    }
}