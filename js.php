<?php 
function slideshowjs(){
global $left_distance,$right_distance,$duration_time,$wait_time;
$siturl = wp_upload_dir();
$siturl = $siturl['baseurl'];

$return = <<<END
<script type="text/javascript">
//XMLを読み込み、イメージタグにして表示
	jQuery('#slideshow').ready(function($){
		var a = "";
		
		$.ajax({
			url:"$siturl/wp_simple_slideshow/slides.xml",
			dataType:"xml",
			ellor: function(){
				$("#slideshow").html("接続エラー");
			},
			success: function(data){
				$(data).find("slide").each(function(){
					var item_url = $("jpegURL",this).text();
					var item_num = $("numb",this).text();
					if(a==""){
						a = a + '<img src="'+ item_url + '" class="active" alt="' + item_num +'">';
						$('#slideshow').css({ background: "url('"+item_url+"') left top no-repeat"} );
					}else{
						a = a + '<img src="'+ item_url + '" class="last-active" alt="' + item_num + '">';
					}
				});
				$("#slideshow").html(a);
			} //success
		})
	})
</script>

<script type="text/javascript">
function slideSwitch(swi){
    var active = jQuery('#slideshow IMG.active');

    if ( active.length == 0 ){
    	active = jQuery('#slideshow IMG:last');
    }
		// use this to pull the images in the order they appear in the markup
		var next =  active.next().length ? active.next():jQuery('#slideshow IMG:first');
	
		if(jQuery.easing.def){
			if(swi){
				next.css({opacity: 0.0,left: $left_distance }).addClass('active').animate({opacity: 1.0,left:0},{duration: $duration_time ,easing:'easeOutQuad'});
			}else{
				next.css({opacity: 0.0,left: $right_distance }).addClass('active').animate({opacity: 1.0,left:0},{duration: $duration_time ,easing:'easeOutQuad'});
			}
		}else{
			if(swi){
				next.css({opacity: 0.0,left: $left_distance }).addClass('active').animate({opacity: 1.0,left:0},{duration: $duration_time});
			}else{
				next.css({opacity: 0.0,left: $right_distance }).addClass('active').animate({opacity: 1.0,left:0},{duration: $duration_time});
			}
		}
		jQuery('#slideshow IMG.last-active').removeClass('last-active');
		jQuery('#slideshow IMG.active:not(:last)').addClass('last-active');
		active.removeClass('active');
}

function timer(i) {
	i++;
	if((i % 2) == 0){
		slideSwitch(true);
	}else{
		slideSwitch(false);
	}
    setTimeout( "timer("+i+")", $wait_time);
    //timer(i);
}

jQuery("#slideshow").ready(function(){
	timer(0);
});
</script>
END;
return $return;
}?>
