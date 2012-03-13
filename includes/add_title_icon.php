<?php
/**
 * @filename add_title_icon.php 
 * @encoding UTF-8 
 * @author 荒野无灯 <HuangYeWuDeng> 
 * @link http://ihacklog.com 
 * @copyright Copyright (C) 2012 荒野无灯 
 * @license http://www.gnu.org/licenses/
 * @Description 增加title icon主题支持函数( ihacklog_pkg_the_title_icon ) 和彩色标题支持 
 */
if (!defined('ABSPATH'))
{
	header('HTTP/1.1 403 Forbidden', true, 403);
	die('Please do not load this page directly. Thanks!');
} 

if (is_admin() && basename($_SERVER['SCRIPT_FILENAME']) != 'index.php')
{
	add_filter('the_title', 'ihacklog_pkg_random_title_color', 99, 2);

	//&lt;span style=&quot;color: rgb(121, 6, 25);&quot; &gt;最近文章title属性去除HTML标签&lt;/span&gt;
	function ihacklog_pkg_strip_all_attribute_tags($safe_text, $text)
	{
		$safe_text = str_replace(
				array('&lt;span style=&quot;', '&quot; &gt;', '&lt;/span&gt;'), array('<span style="', '" >', '</span>'), $safe_text);
		return strip_tags($safe_text);
	}

	add_filter('attribute_escape', 'ihacklog_pkg_strip_all_attribute_tags', 99, 2);
}

//////start//////////title icon主题支持函数////////by 荒野无灯///////////////
function ihacklog_pkg_the_title_icon()
{
	global $post;
	$icon = '';
	$post_date = $post->post_date;
	$current_time = current_time('timestamp');
	$diff = ($current_time - strtotime($post_date)) / 3600;
	$title_icon_new = plugin_dir_url(HACKLOG_PACKAGE_LOADER) . 'images/title_icon/new.gif';
	$title_icon_top = plugin_dir_url(HACKLOG_PACKAGE_LOADER) . 'images/title_icon/top.gif';
	$sticky = get_option('sticky_posts');
	if ($sticky)
	{
		$icon = in_array($post->ID, $sticky) ? '<img class="title-icon" src=\'' . $title_icon_top . '\' alt="sticky" />' : '';
	}

	if ($diff < 24)
	{
		$icon .='<img class="title-icon" src=\'' . $title_icon_new . '\' alt="new" />';
	}
	echo $icon;
}
//////end//////////title icon////////by 荒野无灯///////////////

//random title color 
function ihacklog_pkg_random_title_color($title, $id)
{
	$id = (int) $id;
	//16
	static $colors = array(
'rgb(153,153,0);',
 'rgb(68,153,102);',
 'rgb(85,119,221);',
 //'rgb(238,238,238);',	
'rgb(187,102,34);',
 'rgb(153,68,170);',
 'rgb(119,136,255);',
 'rgb(0, 104, 28);',
 'rgb(121, 6, 25);',
 'rgb(91, 16, 148);',
 'rgb(200, 137, 0);',
 'rgb(204, 0, 96);',
 'rgb(0, 148, 134);',
 'rgb(185, 0, 56);',
 'rgb(132, 102, 0);',
 'rgb(51, 0, 153);',
 'rgb(0, 131, 145);',
	);
	$index = $id % 16;
	$style_color = 'style="color: ' . $colors[$index] . '" ';
	//var_dump($title);
	//return str_replace('<a ', '<a '. $style_color , $title );
	return '<span ' . $style_color . '>' . $title . '</span>';
}

