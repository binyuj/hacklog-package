<?php
/*
*wp image rotator 
*author:荒野无灯
*URL: http://www.ihacklog.com
*声明: 本站遵循  署名-非商业性使用-相同方式共享 2.5 共享协议 http://creativecommons.org/licenses/by-nc-sa/2.5/cn/
*Hacklog 荒野无灯weblog http://www.ihacklog.com All Rights Reserved.
*/

require( dirname(__FILE__) . '/../../../../wp-load.php' );

$id=(int) $_GET['id'];
if(!($id>0)  || isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST'])===false )
	wp_die('Access denied!<br />By <a href="http://www.ihacklog.com">荒野无灯weblog</a>');

$attr=array();
if(isset($_GET['size'] ) && in_array($_GET['size'],array('thumbnail', 'medium' , 'large' ,'full' ) ) )
{
	$attr['size']=$_GET['size'];
}
	if ( isset( $attr['orderby'] ) ) 
	{
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}
	
		
		extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'size'       => 'medium',
		'include'    => '',
		'exclude'    => ''
	), $attr));
		
		
		
$thepictures = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
//wp_get_attachment_link($id, $size, false, false)
//	wptexturize($attachment->post_excerpt)
//var_export($attachments );

		
		
// Create XML output
header("content-type:text/xml;charset=utf-8");
header('Cache-Control: must-revalidate');
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 3600*24) . ' GMT'); /* 1 day */ 
	
echo "<playlist version='1' xmlns='http://xspf.org/ns/0/'>\n";
echo "	<title>".stripslashes('荒野无灯相册')."</title>\n";
echo "	<trackList>\n";

if (is_array ($thepictures))
{
	foreach ($thepictures as $picture) 
	{
		$full_image=wp_get_attachment_image_src($picture->ID, $size);
		echo "		<track>\n";
		if (!empty($picture->post_title))	
		echo "			<title>".wptexturize(strip_tags(stripslashes(html_entity_decode($picture->post_title))))."</title>\n";
		else if (!empty($picture->post_excerpt))	
		echo "			<title>".stripslashes($picture->post_excerpt)."</title>\n";
		echo "			<location>".$full_image[0]."</location>\n";
		echo "		</track>\n";
	}
}
 
echo "	</trackList>\n";
echo "</playlist>\n";
