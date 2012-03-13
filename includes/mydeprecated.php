<?php
/**
 * 禁用一些wp中很少要用到的功能
 * code mofified from [phoetry] (http://phoetry.me/archives/disable-useless-functions.html)
 * 由荒野无灯修订
 */

//前台禁用l10n.js
  !is_admin() && wp_deregister_script('l10n');
  //移除管理员工具条(或:后台也有设置项)
  //add_filter( 'show_admin_bar', '__return_false' );
  //禁用自动保存草稿
  //wp_deregister_script('autosave');
  //禁用修改历史记录
  remove_action('pre_post_update','wp_save_post_revision');
  //禁止在head泄露wordpress版本号
  remove_action('wp_head','wp_generator');
  //移除head中的rel="EditURI"
  remove_action('wp_head','rsd_link');
  //移除head中的rel="wlwmanifest"
  remove_action('wp_head','wlwmanifest_link');
  //禁止半角符号自动变全角
  /*
  foreach(array('comment_text','the_content','the_excerpt','the_title') as $xx)
  remove_filter($xx,'wptexturize');
   */
  //禁止自动给文章段落添加<p>标签
  //remove_filter('the_content','wpautop');
  //remove_filter('the_excerpt','wpautop');
  //禁止自动把'Wordpress'之类的变成'WordPress'
  /*
  remove_filter('comment_text','capital_P_dangit',31);
  remove_filter('the_content','capital_P_dangit',11);
  remove_filter('the_title','capital_P_dangit',11);
   */
  //评论跳转链接添加nofollow
  add_filter('comments_popup_link_attributes',create_function('',' return \' rel="nofollow"\'; ') );

  /*回复某人链接添加nofollow
  这个理应是原生的, 可是在wp某次改版后被改动了,
  现在是仅当开启注册回复时才有nofollow,否则需要自己手动了*/ 
  get_option('comment_registration') || add_filter('comment_reply_link',create_function('$link', 
    'return str_replace(\'<a\',\'<a rel="nofollow"\',$link); ') );

add_filter('pings_open','ihacklog_pkg_disable_trackback_recv',999);
//禁用trackback 接收功能，切断spam来源
//By 荒野无灯 1:13 2011/9/29
function ihacklog_pkg_disable_trackback_recv( $ping_status )
{
//is_trackback() 不工作。。。
    if( $GLOBALS['wp_the_query']->query_vars['tb']  )
    {
        return FALSE;
    }
    else
    {
        return $ping_status;
    }
}

