<?php
/**
 * @filename fancybox.php 
 * @encoding UTF-8 
 * @author 荒野无灯 <HuangYeWuDeng> 
 * @link http://ihacklog.com 
 * @copyright Copyright (C) 2011 荒野无灯 
 * @license http://www.gnu.org/licenses/
 * @Description 添加fancybox图片显示特效
 */
add_action('wp_print_styles','ihacklog_pkg_fancybox_stylesheets');
add_action('wp_enqueue_scripts', 'ihacklog_pkg_fancybox_scripts');

function ihacklog_pkg_fancybox_stylesheets()
{
		$suffix_css = WP_DEBUG ? '.css' : '.min.css.php';
		wp_enqueue_style('fancybox', plugin_dir_url(HACKLOG_PACKAGE_LOADER ) . 'js/fancybox/jquery.fancybox-1.3.4' . $suffix_css, array() , '1.3.4');
}

function ihacklog_pkg_fancybox_scripts()
{
	$suffix_js = WP_DEBUG ? '.js' : '.min.js.php';
		wp_enqueue_script('easing', plugin_dir_url(HACKLOG_PACKAGE_LOADER ) .'js/fancybox/jquery.easing-1.3' . $suffix_js, array('jquery') , '1.3',true);
			//wp_enqueue_script('mousewheel', get_template_directory_uri() . '/js/fancybox/jquery.mousewheel-3.0.4.pack.js', array('jquery') , '3.0.4',true);
			wp_enqueue_script('fancybox', plugin_dir_url(HACKLOG_PACKAGE_LOADER ) . 'js/fancybox/jquery.fancybox-1.3.4' . $suffix_js, array('jquery','easing' ) , '1.3.4',true);
}