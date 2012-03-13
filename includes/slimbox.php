<?php

function slimbox_stylesheets()
{
	$suffix_css = WP_DEBUG ? '.css' : '.min.css.php';
	wp_enqueue_style('slimbox2', plugin_dir_url(HACKLOG_PACKAGE_LOADER) . 'js/slimbox2/slimbox2' . $suffix_css, array(), '2.0');
}

add_action('wp_print_styles', 'slimbox_stylesheets');



add_action('wp_enqueue_scripts', 'slimbox_scripts');

function slimbox_scripts()
{
	$suffix_js = WP_DEBUG ? '.js' : '.min.js.php';
	wp_enqueue_script('slimbox2', plugin_dir_url(HACKLOG_PACKAGE_LOADER) . 'js/slimbox2/slimbox2' . $suffix_js, array('jquery'), '2.0', true);
}