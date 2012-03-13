<?php
/**
 * @filename subscribe-notify.php 
 * @encoding UTF-8 
 * @author 荒野无灯 <HuangYeWuDeng> 
 * @link http://ihacklog.com 
 * @copyright Copyright (C) 2011 荒野无灯 
 * @license http://www.gnu.org/licenses/
 * @Description 向访问者显示订阅通知
 */

if (!defined('ABSPATH'))
{
	header('HTTP/1.1 403 Forbidden', true, 403);
	die('Please do not load this page directly. Thanks!');
} 
/*========= START CONFIGURE ========*/
//订阅地址
$ihacklog_pkg_subscribe_url = home_url('feed');
/*=========  END  CONFIGURE ========*/

add_action('init', 'ihacklog_pkg_set_notify_cookie');
add_action('wp_head', 'ihacklog_pkg_subscribe_notify_css');
add_action('wp_footer', 'ihacklog_pkg_show_notify');

function ihacklog_pkg_show_notify()
{
	global $ihacklog_pkg_subscribe_url;
	/*	 * ************配置开始************* */
	//订阅地址
	$subscribe_url = $ihacklog_pkg_subscribe_url;
	/*	 * ************配置结束************* */

	$show = 0;
	$extra_msg = '';
	if (isset($_SERVER['HTTP_REFERER']))
	{
		$url = parse_url($_SERVER['HTTP_REFERER']);
		if (isset($url['port']))
			$ref_host = $url['host'] . ':' . $url['port'];
		else
			$ref_host = $url['host'];
		if ($ref_host != $_SERVER['HTTP_HOST'])
		{
			$show = 1;
			$ref_url = $url['scheme'] . '://' . $ref_host;
			$extra_msg = sprintf('来自<a href="%1$s" target="_blank">%2$s</a>的朋友,', $ref_url , $ref_host);
		}
	}
	if (empty($_COOKIE['notify_cookie']) || $show)
	{
		echo '<div id="hellovisitor">' . 
				$extra_msg . '欢迎您 <a href="' . $subscribe_url . '" target="_blank">点击这里</a> 
				订阅我的博客 o(∩_∩)o ~~~ 
				<a class="closebox" title="关闭" href="#">x</a> 
			</div>';
		echo <<<EOT
   		<script type="text/javascript">
			jQuery(function($){
				$('#hellovisitor a.closebox').click(function(){ $('#hellovisitor').slideUp('slow');$('.closebox').css('display','none'); return false;}); 
			});// end jQuery
		</script>
EOT;
	}
}



function ihacklog_pkg_set_notify_cookie()
{
	if (is_admin())
	{
		return;
	}

	if (strpos($_SERVER['SCRIPT_NAME'], 'wp-login.php') !== FALSE)
	{
		return;
	}
	if (empty($_COOKIE['notify_cookie']))
		setcookie('notify_cookie', md5($_SERVER['REMOTE_ADDR'] . $_SERVER['USER_AGENT']), time() + 3600 * 24 * 30);
}



function ihacklog_pkg_subscribe_notify_css()
{
	echo <<<EOT
   <style type="text/css">
	#hellovisitor {
	-moz-border-radius:5px;
	-khtml-border-radius:5px;
	-webkit-border-radius:5px;
	border-radius:5px;
	position:fixed;
	bottom:180px;
	width:300px;
	height:50px;
	word-wrap:break-word;
	display:block;
	font-size:14px;
	right:0;
	background:#000;
	filter:alpha(opacity=70);
	border-top:#b3b3b3 1px solid;
	border-left:#b3b3b3 1px solid;
	border-right:#b3b3b3 1px solid;
	color:#fff;
	border-bottom:#b3b3b3 1px solid;
	opacity:.7;
	padding:10px 15px 0 10px;
	text-align:left;
}

#hellovisitor a:link {
	color:#649230;
	text-decoration:none;
}

#hellovisitor a:visited {
	color:#3F80E2;
}

#hellovisitor a:hover {
	color:#FFA011;
	text-decoration:underline;
}

#hellovisitor a:active {
	color:#8CB9FB;
	text-decoration:underline;
}

#hellovisitor .closebox {
	margin:5px;
	position:absolute;
	top:-5px;
	right:1px;
}
</style>
EOT;
}


