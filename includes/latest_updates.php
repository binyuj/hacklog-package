<?php
/**
 * @filename latest_updates.php 
 * @encoding UTF-8 
 * @author 荒野无灯 <HuangYeWuDeng> 
 * @link http://ihacklog.com 
 * @copyright Copyright (C) 2011 荒野无灯 
 * @license http://www.gnu.org/licenses/
 * @Description 增加显示近期修改的旧文章之调用函数 ihacklog_pkg_display_latest_updates
 * 用法：在index.php或其它列表页面调用 
 * ihacklog_pkg_display_latest_updates($num=5,$interval_days = 7,$displayDate=1,$timeformat = "jS F'Y");
 * depend on function ihacklog_pkg_random_title_color()
 * support both rss2 feed and xhtml
 */

if ( !defined( 'ABSPATH' ) ) 
{ 
	header( 'HTTP/1.1 403 Forbidden', true, 403 ); die ('Please do not load this page directly. Thanks!'); 
}

add_action('rss2_head','ihacklog_pkg_rss2_latest_updates',-99);


function ihacklog_pkg_get_latest_updates_for_feed($num=5,$interval = 604800 ) 
{
	GLOBAL $wpdb;
	$num = (int) $num;
	$num = ($num > 0 && $num < 20 ) ? $num : 5;
	$now = current_time('timestamp',1);
	$time_point_gmt = $now - $interval;
	$time_point_gmt = gmdate( 'Y-m-d H:i:s', $time_point_gmt);
	
	$sql            = "SELECT * FROM
	{$wpdb->posts} WHERE post_date_gmt < '$time_point_gmt' AND 
	post_status = 'publish' AND
	post_type = 'post' AND post_modified_gmt > '$time_point_gmt'
	ORDER BY post_modified DESC 
	LIMIT {$num} ";
	$list           = $wpdb->get_results($sql,OBJECT);

return $list;
}

/**
 * 用于附加在rss2 feed
 */
function ihacklog_pkg_rss2_latest_updates()
{
	global $post;
	$num           =5;
	$interval_days = 7;
	$displayDate   =1;
	$timeformat    = "jS F'Y";
	$interval      = 3600 * 24 * $interval_days ;
	$list          =	ihacklog_pkg_get_latest_updates_for_feed($num,$interval );
	foreach( $list as $post):
	setup_postdata($post);
	$update_time = mysql2date('D, d M Y H:i:s +0000', get_post_modified_time('Y-m-d H:i:s', true), false) ;
	$update_notify = '<p>本文内容已于GMT时间<strong>' . $update_time . '</strong>更新。</p>';
	?>
		<item>
		<title><?php the_title_rss();echo '(于GMT时间' . $update_time . '更新)'; ?></title>
		<link><?php the_permalink_rss() ?></link>
		<comments><?php comments_link_feed(); ?></comments>
		<pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_post_modified_time('Y-m-d H:i:s', true), false); ?></pubDate>
		<dc:creator><?php the_author() ?></dc:creator>
		<?php the_category_rss('rss2') ?>
		<guid isPermaLink="false"><?php the_guid(); ?></guid>
		<description><![CDATA[<?php echo $update_notify;?>]]></description>
	<?php if ( strlen( $post->post_content ) > 0 ) : ?>
		<content:encoded><![CDATA[<?php echo $update_notify;?><?php the_content_feed('rss2'); ?>]]></content:encoded>
	<?php else : ?>
		<content:encoded><![CDATA[<?php echo $update_notify;?><?php the_excerpt_rss() ?>]]></content:encoded>
	<?php endif; ?>
		<wfw:commentRss><?php echo esc_url( get_post_comments_feed_link(null, 'rss2') ); ?></wfw:commentRss>
		<slash:comments><?php echo get_comments_number(); ?></slash:comments>
<?php rss_enclosure(); ?>
	<?php do_action('rss2_item'); ?>
	</item>
<?php endforeach;
	wp_reset_postdata();
}

/**
 * 用于首页调用
 * 604800 7days ,7天之内发布了，再修改的就不算。
 */
function ihacklog_pkg_get_latest_updates($num=5,$interval = 604800 ) 
{
	GLOBAL $wpdb;
	$num            = (int) $num;
	$num            = ($num > 0 && $num < 20 ) ? $num : 5;
	$now            = current_time('timestamp',1);
	$time_point_gmt = $now - $interval;
	$time_point_gmt = gmdate( 'Y-m-d H:i:s', $time_point_gmt);

	$sql            = "SELECT ID, post_title, post_modified FROM
{$wpdb->posts} WHERE post_date_gmt < '$time_point_gmt' AND 
 post_status = 'publish' AND
 post_type = 'post' AND post_modified_gmt > '$time_point_gmt'
ORDER BY post_modified DESC 
LIMIT {$num} ";
$list           = $wpdb->get_results($sql,OBJECT);

return $list;
}

function ihacklog_pkg_display_latest_updates($num=5,$interval_days = 7,$displayDate=1,$timeformat = "jS F'Y")
{ 
	$interval = 3600 * 24 * $interval_days ;
	$list =	ihacklog_pkg_get_latest_updates($num,$interval );
	if (!empty($list)) {
		echo '<div style="border-left: 0px dashed #D6C094; margin: 5px; padding: 3px; border: 1px dashed #00a0c6;-moz-border-radius: 10px;-khtml-border-radius: 10px;-webkit-border-radius: 10px;border-radius: 10px;font-size:13px;">';
		echo '<h2>最近更新</h2>';
		foreach ($list as $obj) 
		{
			echo '<div class="post-modified">';
			$idlink = $obj->ID;
			$permalink = get_permalink($idlink);
?>
						<h2 class="post-title post-modified-<?php echo $obj->ID;?>">
						<a class='latest-updates' style="border-bottom-width:0;" href="<?php echo $permalink;?>" title='<?php echo esc_attr($obj->post_title);?>'><?php echo ihacklog_pkg_random_title_color( $obj->post_title, $obj->ID);?></a>
<?php				
			if ($displayDate == 1) 
			{
				echo ' -  <span style="font-weight:normal;font-size:12px;font-family:Tahoma, Consolas,Helvetica,sans-serif;">于' . date($timeformat, strtotime($obj->post_modified)) . ' 更新</span>';
			}
			echo '</h2></div>';
		}
		echo '</div>';
	}
}
