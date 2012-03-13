<?php
if (! defined ( 'ABSPATH' )) {
	die ( 'What are you doing?' );
}
########START########### 链接重定向处理 by 荒野无灯  http://ihacklog.com    ############START#############

function match_links($content)
{
	$match = array();
	preg_match_all("'<\s*a\s.*?href\s*=\s*([\"\'])?(?(1)(.*?)\\1|([^\s\>]+))[^>]*>?(.*?)</a>'isx", $content, $links);
	while (list($key, $val) = each($links[2])) {
		if (!empty($val) && !preg_match("@\.(jpg|gif|png|rar|zip|gz|tgz|swf|js|txt)$@i", $val))
			$match['link'][] = $val;
	}
	while (list($key, $val) = each($links[3])) {
		if (!empty($val))
			$match['link'][] = $val;
	}
	while (list($key, $val) = each($links[4])) {
		if (!empty($val))
			$match['content'][] = $val;
	}
	while (list($key, $val) = each($links[0])) {
		if (!empty($val))
			$match['all'][] = $val;
	}
	return $match;
}

function add_comment_link_redirect($content='')
{
	$l = match_links($content);
	if (!isset($l['link']))
		return $content;
	$cnt = count($l['link']);
	for ($i = 0; $i < $cnt; $i++)
	{
		if (0 === strpos($l['link'][$i], 'http://ihacklog.com/l.php'))
		{
			$rep[] = get_option('home') . '/l.php?url=' . rawurlencode(str_replace('http://ihacklog.com/l.php?url=', '', str_replace('&#038;', '&', $l['link'][$i])));
			continue;
		}
		if (0 === strpos($l['link'][$i], 'http://ihacklog.com') || 0 === strpos($l['link'][$i], '#'))
		{
			$rep[] = $l['link'][$i];
			continue;
		}
		else
			$rep[] = get_option('home') . '/external_link_redirect/' . base64_encode(str_replace('&#038;', '&', $l['link'][$i]));
	}
	return str_replace($l['link'], $rep, $content);
}

add_filter('get_comment_author_link', 'add_comment_link_redirect', 5);
add_filter('comment_text', 'add_comment_link_redirect', 99);
//add_filter('the_content', 'add_comment_link_redirect', 9);


add_filter('query_vars', 'hacklog_comment_redirect_go_query_vars');

function hacklog_comment_redirect_go_query_vars($public_query_vars)
{
	$public_query_vars[] = "hacklog_go_url";
	return $public_query_vars;
}

add_filter('generate_rewrite_rules', 'hacklog_comment_redirect_rewrite');

function hacklog_comment_redirect_rewrite($wp_rewrite)
{
	$wp_rewrite->rules = array_merge(array('external_link_redirect/(.*)$' => 'index.php?hacklog_go_url=$matches[1]'), $wp_rewrite->rules);
}

add_action('template_redirect', 'hacklog_comment_redirect_go', 6);

function hacklog_comment_redirect_go()
{
	$url = get_query_var('hacklog_go_url');
	$errorPage = 'http://ihacklog.com/error.html';
//$home='http://ihacklog.com';
	$home = $_SERVER['HTTP_HOST'];
	$refer = empty($_SERVER['HTTP_REFERER']) ? 'http://ihacklog.com' : $_SERVER['HTTP_REFERER'];

	if (!empty($url))
	{
		if (false !== strpos($url, 'aHR0c'))
			$url = base64_decode($url);

		$url = strip_tags($url);
		//fix error url like http: //mydomain.com
		$url = str_replace('http: //', 'http://', $url);
		$url = str_replace('http ://', 'http://', $url);
		//下面的冒號為全角
		$url = str_replace('：', ':', $url);
		$url = str_replace('http://http://', 'http://', $url);

		$url = (!preg_match("/^http(s)?\:\/\//i", $url)) ? "http://" . $url : $url;

		$validReferer = array('google.com', $_SERVER ['HTTP_HOST'], substr($_SERVER ['HTTP_HOST'],4), 'www.' . substr($_SERVER ['HTTP_HOST'],4) );

		$refererhost = parse_url($refer);

		if ( !in_array($refererhost ['host'], $validReferer) )  //非本站引用
		{
			header('Location:' . $errorPage);
			exit();
		}

		header('Location:' . $url); //直接跳转
		exit();
	}
}

######## END ########### 链接重定向处理 by 荒野无灯  http://ihacklog.com    ############ END #############


