<?php
/*
author:  荒野无灯
URL: http://www.ihacklog.com
comment: 请勿去除以上两行。
*/
@error_reporting( 0 );
/////////////START ///////配置///////////////////

$user='xxxxxxxxxxxxxxxx@apps.messenger.live.com';

/////////////END//////////配置//////////////////
if( file_exists( '../../../../wp-load.php' ) )
require '../../../../wp-load.php';
else
	if( file_exists(  '../../../wp-load.php' ) )
		require '../../../wp-load.php';
	else 
		die('error to find file wp-load.php');

@header('Content-Type: text/html;Charset=utf-8');
$http=new WP_Http();

$msn = array(
	'result'=> array(
	'code' => 500,
	'response'=> '',
	),
	);
//exit('http://messenger.services.live.com/users/'.$user.'/presence/?cb=showpresence');
$r=$http->get('http://messenger.services.live.com/users/'.$user.'/presence/?cb=showpresence');
if( !is_wp_error($r) )
{
	$n=preg_match_all("@showpresence\((.*?)\);@i",$r['body'],$matches);
	//echo($matches[1][0]);
	$msn=json_decode($matches[1][0],true) ;
}
//var_dump($msn);
$src=get_stylesheet_directory_uri().'/msn/';
$status='此人目前处在离线中...';
if( '200' == $msn['result']['code'] && 'OK' == $msn['result']['response'])
{
	switch ( $msn['status'] )
	{
	case 'Offline':
		$src.='msn_offline.png';
		$status='此人目前处在离线中...';
	break;	
	case 'Busy':
		$src.='msn_busy.jpg';
		$status='此人现在很忙，不要找他...';

		break;	
	case 'Away':
		$src.='msn_away.gif';
		$status='他不在...';

		break;	
	case 'Online':
		$src.='msn_online.png';
		$status='嘿嘿，在线哦，点击和他聊天吧';

		break;	
	default:
		$src.='msn_offline.png';
		$status='此人目前处在离线中...';

	}

}
else
{
	$src.='msn_offline.png';
	$status='此人目前处在离线中...';

}

	switch ( $msn['status'] )
	{
	case 'Offline':
	case 'Busy':
	case 'Away':
	$html='<img style="border-style: none;" src="'.$src.'" width="48" height="40" alt="'.$status.'" title="'.$status.'"/>';
	break;
	case 'Online':
	$html='<a target="_blank" title="'.$status.'" href="javascript:;" onclick=\'open_msn_chat("http://settings.messenger.live.com/Conversation/IMMe.aspx?invitee='.$user.'&mkt=zh-CN");return false;\'><img style="border-style: none;" src="'.$src.'" width="48" height="40" alt="'.$status.'"/></a>';

		break;	
	default:
	$html='<img style="border-style: none;" src="'.$src.'" width="48" height="40" alt="'.$status.'" title="'.$status.'"/>';

	}
	
echo $html;