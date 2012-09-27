/*
   Licensed under the Creative Commons License, Version 3.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

   http://creativecommons.org/licenses/by-sa/3.0/us/

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.

   Created By Russell Brown : EmpireGP Servces
   http://www.EmpireGPServices.com
 */

package com.EGPS.toolbox.graphics {
    import flash.events.Event;
    import flash.events.MouseEvent;
    
    import mx.containers.Canvas;
    import mx.containers.VBox;
    import mx.core.UIComponent;
    import mx.events.FlexEvent;

    public class RoundedImage extends Canvas {
        protected var currentImage:SmartImage;
        protected var vb:VBox;
        protected var _id:String;

        /**
         * Overall corner radius, used for setting all to a single number 
         */        
        [Bindable]
        protected var _cornerRadius:Number=0;

        /**
         * Top Left Corner Radius 
         */        
        [Bindable]
        protected var cornerRadiusTL:int=0;

        /**
         * Top Right Corner Radius 
         */        
        [Bindable]
        protected var cornerRadiusTR:int=0;

        /**
         * Bottom Left Corner Radius 
         */        
        [Bindable]
        protected var cornerRadiusBL:int=0;

        /**
         * Bottom Right Corner Radius 
         */        
        [Bindable]
        protected var cornerRadiusBR:int=0;

        private var _roundedMask:UIComponent;
        private var _border:UIComponent;
        private var _borderAlpha:int;
        private var _autoBorderColor:uint;
        private var _hasDynamicBorder:Boolean;

        public function RoundedImage() {
            this._borderAlpha=1;
            this._hasDynamicBorder=false;

            super();
			
			this.width=100;
			this.height=100;
			
			vb=new VBox();
			vb.percentHeight=100;
			vb.percentWidth=100;
			
			//vb.width=100;
			//vb.height=100;
			vb.horizontalScrollPolicy="off";
			vb.verticalScrollPolicy="off";
					
            this.currentImage=new SmartImage();
            this.currentImage.x=0;
            this.currentImage.y=0;

            this.setStyle("horizontalScrollPolicy", "off");
            this.setStyle("verticalScrollPolicy", "off");

			this.setStyle("horizontalAlign","center");
			this.setStyle("verticalAlign","middle");
			
			vb.addChild(currentImage);
            this.addChildAt(vb, 0);

            this.currentImage.addEventListener(FlexEvent.CREATION_COMPLETE, drawMask);
            this.currentImage.addEventListener(SmartImage.AVERAGE_COLOR_UPDATED, drawBorder);
            this.addEventListener(Event.REMOVED_FROM_STAGE, onRemove);
        }

        protected function onRemove(event:Event):void {
            if (this.currentImage != null) {
                this.currentImage.source=null;
            }
        }

        private function reThrowMouseEvent(event:MouseEvent):void {
            this.dispatchEvent(new MouseEvent(event.type));
        }

        private function drawMask(event:Event=null):void {
            if (cornerRadiusTL + cornerRadiusTR + cornerRadiusBL + cornerRadiusBR > 0) {
                this._roundedMask=new UIComponent();

                this._roundedMask.graphics.clear();
                this._roundedMask.graphics.beginFill(0x000000);
                this._roundedMask.graphics.drawRoundRectComplex(0, 0, this.width, this.height, this.cornerRadiusTL, this.cornerRadiusTR, this.cornerRadiusBL, this.cornerRadiusBR);
                this._roundedMask.graphics.endFill();
                this.addChild(_roundedMask);
                this.mask=_roundedMask;
            }
        }

        private function drawBorder(event:Event=null):void {
            var border:UIComponent;
            var color:uint=this.currentImage.averageColor;
            var width:int=super.getStyle("borderWidth");

            if (width > 0) {
                var c1:int=Math.floor(width * .50);
                var c2:int=Math.floor(width * .80);

                border=new UIComponent();
                border.graphics.clear();
                border.graphics.lineStyle(width, color, this._borderAlpha);

                if (cornerRadiusTL + cornerRadiusTR + cornerRadiusBL + cornerRadiusBR > 0)
                    border.graphics.drawRoundRectComplex(1, 1, this.width - 2, this.height - 2, this.cornerRadiusTL, this.cornerRadiusTR, this.cornerRadiusBL, this.cornerRadiusBR);
                else
                    border.graphics.drawRect(c1, c1, this.width - c2, this.height - c2);

                if (this._border == null)
                    this.addChild(border);

                this._border=border;
                this._autoBorderColor=color;
            }
        }

        public function set source(value:Object):void {
            this.currentImage.source=value;
        }

        public function get source():Object {
            return currentImage.source;
        }

        override public function set width(value:Number):void {
            //super.width=value;
            //currentImage.width=value;
            //super.height=value;
            //currentImage.height=value;
        }

        override public function get width():Number {
            return super.width;
        }

        public function set borderStyle(value:String):void {
            if (value == "dynamic") {
                this._hasDynamicBorder=true;
            } else {
                this._hasDynamicBorder=false;
                super.setStyle("borderStyle", value);
            }
        }

        public function get borderStyle():String {
            if (this._hasDynamicBorder)
                return "dynamic";
            else
                return super.getStyle("borderStyle");
        }

        public function set borderWidth(value:int):void {
            super.setStyle("borderWidth", value);
        }

        public function get borderWidth():int {
            return super.getStyle("borderWidth");
        }

        /* CORNER RADIUS CONSTRUCTION */
        public function set cornerRadius(radius:Number):void {
            this._cornerRadius=radius;
            this.cornerRadiusTL=radius;
            this.cornerRadiusTR=radius;
            this.cornerRadiusBL=radius;
            this.cornerRadiusBR=radius;
        }

        public function set topLeftCornerRadius(radius:int):void {
            this.cornerRadiusTL=radius;
        }

        public function set topRightCornerRadius(radius:int):void {
            this.cornerRadiusTR=radius;
        }

        public function set bottomLeftCornerRadius(radius:int):void {
            this.cornerRadiusBL=radius;
        }

        public function set bottomRightCornerRadius(radius:int):void {
            this.cornerRadiusBR=radius;
        }

        public function get cornerRadius():Number {
            return this._cornerRadius;
        }

        public function get topLeftCornerRadius():int {
            return this.cornerRadiusTL;
        }

        public function get topRightCornerRadius():int {
            return this.cornerRadiusTR;
        }

        public function get bottomLeftCornerRadius():int {
            return this.cornerRadiusBL;
        }

        public function get bottomRightCornerRadius():int {
            return this.cornerRadiusBR;
        }

    }

}