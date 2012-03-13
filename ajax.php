<?php
/**
 * @filename ajax.php 
 * @encoding UTF-8 
 * @author 荒野无灯 <HuangYeWuDeng> 
 * @link http://ihacklog.com 
 * @copyright Copyright (C) 2012 荒野无灯 
 * @license http://www.gnu.org/licenses/
 * @Description
 */
require dirname(__FILE__) . '/../../../wp-load.php';

if ( !current_user_can('manage_options') )
	wp_die(__('Cheatin&#8217; uh?'));

if ($_POST['submit'] == 'do_ajax')
{
	$opts = get_option(hacklog_package::OPT);
	$act = $_GET['act'];
	$package = $_GET['package'];
	if (!isset($opts[$package]))
	{
		$data = array('result'=>'err');
		die(json_encode($data));
	}
	switch ($act)
	{
		case 'disable':
			$opts[$package]['enable'] = 0;
			break;
		case 'enable':
			$opts[$package]['enable'] = 1;
			break;
	}
	if( update_option(hacklog_package::OPT, $opts) )
	{
		$data = array('result'=>'ok');
		die(json_encode($data));
	}
	else
	{
		$data = array('result'=>'fail');
		die(json_encode($data));
	}
	
}
