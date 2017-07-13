<?php 
function photgallery_css(){
global $style_height,$style_width;
$height = $style_height.'px';
$width = $style_width.'px';
	$return = <<<END
	<style>
		#slideshow {
		    height: $height;
		    width: $width;
		    overflow: hidden;
		    position: relative;
		}
		#slideshow IMG {
		    left: 0;
		    opacity: 0;
		    position: absolute;
		    top: 0;
		    z-index: 8;
		}
		#slideshow IMG.active {
		    opacity: 1;
		    z-index: 10;
		}
		#slideshow IMG.last-active {
		    z-index: 9;
		}
	</style>
END;
	return $return;
}?>