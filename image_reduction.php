<?php
function image_reduction($filename){
	global $style_height,$style_width;
	
	$make_path = $filename;
  list($width,$height) = @getimagesize($filename);
  $newwidth = $style_width;
  $newheight = $style_height;


  if($width > $height){//横長
    $newheight = $newwidth * $height / $width;
	$y = ($height /2)-($newheight /2);
  }else{//縦長
  	$newheight = $newwidth * $height / $width;
    $y = ($height /2)-150;
  }
  if($y<=250){
	$y=0;  
	}

  //
  $thumb = @imagecreatetruecolor($style_width,$style_height);
  $source = @imagecreatefromjpeg($filename);

  if($thumb){
    imageCopyResampled($thumb, $source, 0,0,0,$y, $newwidth,$newheight, $width,$height);
    imagejpeg($thumb,$make_path);
    imagedestroy($thumb);
  }else{
	  echo "<br>";
	  echo "画像を縮小できませんでした";
	  return;
	}
  
  return true;
}
?>