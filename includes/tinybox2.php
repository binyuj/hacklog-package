<?php
/**
 * @filename tinybox2.php 
 * @encoding UTF-8 
 * @author 荒野无灯 <HuangYeWuDeng, admin@ihacklog.com> 
 * @link http://ihacklog.com 
 * @copyright Copyright (C) 2011 荒野无灯 
 * @license http://www.gnu.org/licenses/
 * @Description 
 */

if (!defined('ABSPATH'))
{
	header('HTTP/1.1 403 Forbidden', true, 403);
	die('Please do not load this page directly. Thanks!');
} 

add_action('wp_print_styles','ihacklog_pkg_tinybox2_stylesheets');
add_action('wp_enqueue_scripts', 'ihacklog_pkg_tinybox2_scripts');
add_filter('the_content', 'ihacklog_pkg_tinybox_replace', 1);
add_filter('comment_text', 'ihacklog_pkg_tinybox_replace', 100);

//add onclick event 
function ihacklog_pkg_tinybox_replace ($content)
{ 
$pattern = "/<a(.*?)href=('|\")([^>]*).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>(.*?)<\/a>/i";
$replacement = '<a$1href=$2$3.$4$5 class="tinybox2"  onclick="TINY.box.show({image:this.href,boxid:\'frameless\',animate:true,fixed:false});return false;"  title="Click to enlarge（点击查看大图）" $6>$7 </a>';
$content = preg_replace($pattern, $replacement, $content);
return $content;
}

function ihacklog_pkg_tinybox2_stylesheets()
{
		wp_enqueue_style('tinybox2', plugin_dir_url(HACKLOG_PACKAGE_LOADER ) . 'js/tinybox2/tinybox2.min.css.php', array() , '2.0');
}

function ihacklog_pkg_tinybox2_scripts()
{
	wp_enqueue_script('tinybox2', plugin_dir_url(HACKLOG_PACKAGE_LOADER ) . 'js/tinybox2/tinybox2.min.js.php' , array() , '2.0');
}

