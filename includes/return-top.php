<?php
/**
 * @filename return-top.php 
 * @encoding UTF-8 
 * @author 荒野无灯 <HuangYeWuDeng> 
 * @link http://ihacklog.com 
 * @copyright Copyright (C) 2011 荒野无灯 
 * @license http://www.gnu.org/licenses/
 * @Description
 */

/**
 * notice: the return top image was taken from WordPress theme by mono-lab .
 */
if (!defined('ABSPATH'))
{
	header('HTTP/1.1 403 Forbidden', true, 403);
	die('Please do not load this page directly. Thanks!');
} 

add_action('wp_head', 'ihacklog_pkg_return_top_css');
add_action('wp_footer', 'ihacklog_pkg_return_top_html_and_js', 999);

function ihacklog_pkg_return_top_html_and_js()
{
	echo <<<EOT
	<div id="return_top">
 		<a href="#wrapper" title="Top">&nbsp;</a>
	</div>
EOT;
	echo '	
	<script type="text/javascript">
		jQuery(function($) 
		{
			/*返回顶部*/
			$("#return_top").click(function(){$("html,body").animate({scrollTop: "0px"}, 500); return false;}); 
		});
		</script>';
}

function ihacklog_pkg_return_top_css()
{
	$image_url = plugin_dir_url(HACKLOG_PACKAGE_LOADER) . 'images/return_top.png';
	echo '
<style type="text/css">
/* return top*/
#return_top a {
	position:fixed;
	right:15px;
	bottom:40px;
	width:18px;
	height:99px;
	display:block;
	background:url(' . $image_url . ') no-repeat left top;
}

#return_top a:hover {
	background:url(' . $image_url . ') no-repeat right top;
	text-decoration:none;
}
</style>		
';
}
