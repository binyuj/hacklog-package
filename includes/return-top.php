<?php

/**
 * $Id$
 * $Revision$
 * $Date$
 * @filename return-top.php 
 * @encoding UTF-8 
 * @author 荒野无灯 <HuangYeWuDeng, admin@ihacklog.com> 
 * @link http://ihacklog.com 
 * @copyright Copyright (C) 2011 荒野无灯 
 * @license http://www.gnu.org/licenses/
 * @datetime Jan 24, 2012  8:50:02 PM
 * @version 1.0
 * @Description
 */
if (!defined('ABSPATH'))
{
	header('HTTP/1.1 403 Forbidden', true, 403);
	die('Please do not load this page directly. Thanks!');
} 
 	 
function return_top_html_and_js()
{
	echo <<<EOT
	<div id="return_top">
 <a href="#wrapper" title="Top">&nbsp;</a>
</div>
EOT;
	echo '	
<script type="text/javascript">
	jQuery(function($) {
	/*返回顶部*/
	$("#return_top").click(function(){$("html,body").animate({scrollTop: "0px"}, 500); return false;}); 
		});
	</script>';
}

function return_top_css()
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

add_action('wp_head', 'return_top_css');
add_action('wp_footer', 'return_top_html_and_js', 999);