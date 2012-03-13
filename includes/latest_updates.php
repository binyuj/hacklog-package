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
 */

if ( !defined( 'ABSPATH' ) ) 
{ 
	header( 'HTTP/1.1 403 Forbidden', true, 403 ); die ('Please do not load this page directly. Thanks!'); 
}
//604800 7days ,7天之内发布了，再修改的就不算。
function ihacklog_pkg_get_latest_updates($num=5,$interval = 604800 ) 
{
	GLOBAL $wpdb;
	$num = (int) $num;
	$num = ($num > 0 && $num < 20 ) ? $num : 5;
	$now = current_time('timestamp',1);
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
		echo '<div style="border-left: 0px dashed #D6C094; margin: 5px; padding: 3px; margin-bottom:0px; border: 1px dashed #00a0c6;-moz-border-radius: 10px;-khtml-border-radius: 10px;-webkit-border-radius: 10px;border-radius: 10px;">';
		foreach ($list as $obj) 
		{
			echo '<div class="post-modified">';
			$idlink = $obj->ID;
			$permalink = get_permalink($idlink);
?>
						<h2 class="post-title post-modified-<?php echo $obj->ID;?>">
						<a class='latest-updates' style="border-bottom-width:0;" href="<?php echo $permalink;?>" title='<?php echo esc_attr($obj->post_title);?>'><?php echo hacklog_random_title_color( $obj->post_title, $obj->ID);?></a>
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
