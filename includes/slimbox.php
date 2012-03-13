<?php
/**
 * @filename slimbox.php 
 * @encoding UTF-8 
 * @author 荒野无灯 <HuangYeWuDeng> 
 * @link http://ihacklog.com 
 * @copyright Copyright (C) 2011 荒野无灯 
 * @license http://www.gnu.org/licenses/
 * @Description 添加slimbox图片特效所需css和js
 */

add_action('wp_print_styles', 'ihacklog_pkg_slimbox_stylesheets');
add_action('wp_enqueue_scripts', 'ihacklog_pkg_slimbox_scripts');


function ihacklog_pkg_slimbox_stylesheets()
{
	$suffix_css = WP_DEBUG ? '.css' : '.min.css.php';
	wp_enqueue_style('slimbox2', plugin_dir_url(HACKLOG_PACKAGE_LOADER) . 'js/slimbox2/slimbox2' . $suffix_css, array(), '2.0');
}

function ihacklog_pkg_slimbox_scripts()
{
	$suffix_js = WP_DEBUG ? '.js' : '.min.js.php';
	wp_enqueue_script('slimbox2', plugin_dir_url(HACKLOG_PACKAGE_LOADER) . 'js/slimbox2/slimbox2' . $suffix_js, array('jquery'), '2.0', true);
}