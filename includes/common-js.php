<?php
/**
 * @filename common-js.php 
 * @encoding UTF-8 
 * @author 荒野无灯 <HuangYeWuDeng> 
 * @link http://ihacklog.com 
 * @copyright Copyright (C) 2011 荒野无灯 
 * @license http://www.gnu.org/licenses/
 * @Description 向footer添加适用于各个主题的js
  */
add_action('wp_footer', 'ihacklog_pkg_print_common_js');

function ihacklog_pkg_print_common_js()
{
	echo <<<EOT
   <script type="text/javascript">
	
jQuery(function($){

/************open external nofollow link in new windows ************/
//to wordpress is not so good
//$("a[href^='http://']:not([href$='ihacklog.com/']),[href^='http://']:not([href$='ihacklog.com'])").attr("target", "_blank");
$('.fn a').attr({ target: "_blank" });
$("a[rel='external nofollow']").attr( { target: "_blank" } );
$('#wgNotice li a').attr({ target: "_blank" });
/************open external nofollow link in new windows ************/

});// end jQuery

</script>
EOT;
}

