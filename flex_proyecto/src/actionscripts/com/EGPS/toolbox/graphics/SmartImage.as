package com.EGPS.toolbox.graphics {
    import flash.display.BitmapData;
    import flash.events.Event;
    
    import mx.controls.Image;
    import mx.events.FlexEvent;

    [Event(name="averageColorUpdated", type="flash.events.Event")]
    public class SmartImage extends Image {
        public static const AVERAGE_COLOR_UPDATED:String = "averageColorUpdated";

        [Bindable]
        public var averageColor:uint;

        public function SmartImage() {
            this.addEventListener(FlexEvent.UPDATE_COMPLETE, updateAverageColor);
            this.maintainAspectRatio=false;
            //this.width=100;
            //this.height=100;
            //this.scaleContent=true;
            this.percentWidth=100;
            this.percentHeight=100;
            super();
        }

        public function get image():Image {
            return super;
        }

        private function updateAverageColor(event:*):void {
            if (this.width == 0 || this.height == 0)
                return;

            var bmpData:BitmapData = new BitmapData(this.width, this.height);
            bmpData.draw(this);

            var r:Number = 0;
            var g:Number = 0;
            var b:Number = 0;
 
            var count:Number = 0;
            var pixel:Number;
 
            for (var x:int = 0; x < bmpData.width; x++) {
                for (var y:int = 0; y < bmpData.height; y++) {
                    pixel = bmpData.getPixel(x, y);
 
                    r += pixel >> 16 & 0xFF;
                    g += pixel >> 8 & 0xFF;
                    b += pixel & 0xFF;
 
                    count++
                }
            }
 
            r /= count;
            g /= count;
            b /= count;

            this.averageColor = r << 16 | g << 8 | b;

            this.dispatchEvent(new Event(AVERAGE_COLOR_UPDATED));
        }

    }
}