<?php

/**
 * $Id$
 * $Revision$
 * $Date$
 * @filename ajax.php 
 * @encoding UTF-8 
 * @author 荒野无灯 <HuangYeWuDeng, admin@ihacklog.com> 
 * @link http://ihacklog.com 
 * @copyright Copyright (C) 2011 荒野无灯 
 * @license http://www.gnu.org/licenses/
 * @datetime Feb 4, 2012  10:48:33 PM
 * @version 1.0
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
