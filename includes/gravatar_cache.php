<?php
######## START ############头像缓存#########BY 荒野无灯###############
//2010-05-08更新
function cache_avatar($avatar, $id_or_email, $size)
{
//$host=get_bloginfo('wpurl');
//cookie free domain 10:02 2012/1/2
$host='http://static.'.$_SERVER['HTTP_HOST'];
	$email = '';
	$url='';
	if ( is_numeric($id_or_email) ) {
		$id = (int) $id_or_email;
		$user = get_userdata($id);
		if ( $user )
			$email = $user->user_email;
	} elseif ( is_object($id_or_email) ) {
		if ( isset($id_or_email->comment_type) && '' != $id_or_email->comment_type && 'comment' != $id_or_email->comment_type )
			return false; // No avatar for pingbacks or trackbacks

		if ( !empty($id_or_email->user_id) ) {
			$id = (int) $id_or_email->user_id;
			$user = get_userdata($id);
			if ( $user)
				$email = $user->user_email;
		} elseif ( !empty($id_or_email->comment_author_email) ) 
		{
			$email = $id_or_email->comment_author_email;
			$url=$id_or_email->comment_author_url;
		}
	} else {
		$email = $id_or_email;
	}

 //$out = "$host/wp-content/gravatar_cache/cache/avatar/";
 $out = "$host/gravatar_cache/cache/avatar/";
 $out .= md5( strtolower( $email ) );
 $avatar = "<img  src='{$out}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
/*   } */
  return $avatar;
}

add_filter('get_avatar', 'cache_avatar', 50,3);
########  END  ############头像缓存###########BY 荒野无灯#############