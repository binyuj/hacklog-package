<?php
function fancybox_stylesheets()
{
		$suffix_css = WP_DEBUG ? '.css' : '.min.css.php';
		wp_enqueue_style('fancybox', plugin_dir_url(HACKLOG_PACKAGE_LOADER ) . 'js/fancybox/jquery.fancybox-1.3.4' . $suffix_css, array() , '1.3.4');
}
add_action('wp_print_styles','fancybox_stylesheets');



add_action('wp_enqueue_scripts', 'fancybox_scripts');

function fancybox_scripts()
{
	$suffix_js = WP_DEBUG ? '.js' : '.min.js.php';
		wp_enqueue_script('easing', plugin_dir_url(HACKLOG_PACKAGE_LOADER ) .'js/fancybox/jquery.easing-1.3' . $suffix_js, array('jquery') , '1.3',true);
			//wp_enqueue_script('mousewheel', get_template_directory_uri() . '/js/fancybox/jquery.mousewheel-3.0.4.pack.js', array('jquery') , '3.0.4',true);
			wp_enqueue_script('fancybox', plugin_dir_url(HACKLOG_PACKAGE_LOADER ) . 'js/fancybox/jquery.fancybox-1.3.4' . $suffix_js, array('jquery','easing' ) , '1.3.4',true);
}