<?php 
/**
 * @filename widgets.php 
 * @encoding UTF-8 
 * @author 荒野无灯 <HuangYeWuDeng> 
 * @link http://ihacklog.com 
 * @copyright Copyright (C) 2011 荒野无灯 
 * @license http://www.gnu.org/licenses/
 * @Description
 */

if (!defined('ABSPATH'))
{
	header('HTTP/1.1 403 Forbidden', true, 403);
	die('Please do not load this page directly. Thanks!');
} 
?>
<?php

/**
 * Recent_Comments widget class
 * By 荒野无灯　5:07 2011/9/28
 * @see http://codex.wordpress.org/Widgets_API
 */
class ihacklog_pkg_Widget_Recent_Comments extends WP_Widget
{

	function __construct()
	{
		$widget_ops = array('classname' => 'widget_recent_comments', 'description' => __('The most recent comments'));
		parent::__construct('ihacklog-recent-comments', __('iHacklog Recent Comments'), $widget_ops);
		$this->alt_option_name = 'ihacklog_widget_recent_comments';

		if (is_active_widget(false, false, $this->id_base))
			add_action('wp_head', array(&$this, 'recent_comments_style'));

		add_action('comment_post', array(&$this, 'flush_widget_cache'));
		add_action('transition_comment_status', array(&$this, 'flush_widget_cache'));
	}

	function recent_comments_style()
	{
		if (!current_theme_supports('widgets') // Temp hack #14876
				|| !apply_filters('show_recent_comments_widget_style', true, $this->id_base))
			return;
		?>
		<style type="text/css">
			.recentcomments a{display:inline !important;padding: 0 !important;margin:0 !important;}
			.recentcomments img.avatar {position:relative;top:0;left:0; vertical-align:middle !important; margin-right: 6px !important;}
			li.recentcomments { margin:5px 0 !important;
								padding: 0 0 3px !important;
			}
			.recentcomments .object { color:#66747B;font-family:consolas,monaco;font-size:14px;} 		
		</style>
		<?php
	}

	function flush_widget_cache()
	{
		wp_cache_delete('widget_recent_comments', 'widget');
	}

	function widget($args, $instance)
	{
		global $comments, $comment;

		$cache = wp_cache_get('widget_recent_comments', 'widget');

		if (!is_array($cache))
			$cache = array();

		if (isset($cache[$args['widget_id']]))
		{
			echo $cache[$args['widget_id']];
			return;
		}

		extract($args, EXTR_SKIP);
		$output = '';
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Comments') : $instance['title']);

		if (!$number = absint($instance['number']))
			$number = 5;

		$admin_email = get_option('admin_email');
		global $wpdb;

		$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID,
		comment_post_ID, comment_author, comment_author_email, comment_date_gmt, comment_approved,
		comment_type,comment_author_url,
		comment_content
		FROM $wpdb->comments
		LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID =
		$wpdb->posts.ID)
		WHERE comment_approved = '1' AND comment_type = '' AND
		post_password = '' AND comment_author_email!='" . $admin_email . "'
		ORDER BY comment_date_gmt DESC LIMIT " . $number;

		$comments = $wpdb->get_results($sql);

		$output .= $before_widget;
		if ($title)
			$output .= $before_title . $title . $after_title;

		$output .= '<ul id="recentcomments">';
		if ($comments)
		{
			foreach ((array) $comments as $comment)
			{
				$output .= '<li class="recentcomments">' . get_avatar($comment, 24) .
						/* translators: comments widget: 1: comment author, 2: post link */
						get_comment_author_link() . '<span class="object">->says(\'</span><a href="' . esc_url(get_comment_link($comment->comment_ID)) . '" title="on《' . esc_attr(strip_tags(get_the_title($comment->comment_post_ID))) . '》">'
						. ihacklog_pkg_substr(strip_tags($comment->comment_content), 50) . '</a><span class="object">\');</span>' . '</li>';
			}
		}
		$output .= '</ul>';
		$output .= $after_widget;

		echo $output;
		$cache[$args['widget_id']] = $output;
		wp_cache_set('widget_recent_comments', $cache, 'widget');
	}

	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = absint($new_instance['number']);
		$this->flush_widget_cache();

		$alloptions = wp_cache_get('alloptions', 'options');
		if (isset($alloptions['widget_recent_comments']))
			delete_option('widget_recent_comments');

		return $instance;
	}

	function form($instance)
	{
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$number = isset($instance['number']) ? absint($instance['number']) : 5;
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of comments to show:'); ?></label>
			<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
		<?php
	}

}

// end class ihacklog_Widget_Recent_Comments

/**
 * Popular_Posts widget class
 * By 荒野无灯　5:49 2011/9/28
 *
 */
class ihacklog_pkg_Widget_Popular_Posts extends WP_Widget
{

	function __construct()
	{
		$widget_ops = array('classname' => 'widget_popular_entries', 'description' => __("The most popular posts on your site"));
		parent::__construct('ihacklog-popular-posts', __('iHacklog Popular Posts'), $widget_ops);
		$this->alt_option_name = 'widget_popular_entries';

		add_action('comment_post', array(&$this, 'flush_widget_cache'));
		add_action('deleted_post', array(&$this, 'flush_widget_cache'));
		add_action('switch_theme', array(&$this, 'flush_widget_cache'));
	}

	function widget($args, $instance)
	{
		$cache = wp_cache_get('widget_popular_posts', 'widget');

		if (!is_array($cache))
			$cache = array();

		if (isset($cache[$args['widget_id']]))
		{
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('Popular Posts') : $instance['title'], $instance, $this->id_base);
		if (!$number = absint($instance['number']))
			$number = 10;

		//$r = new WP_Query(array('posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true));
		global $wpdb;
		$now = gmdate("Y-m-d H:i:s", time());
		$lastmonth = gmdate("Y-m-d H:i:s", gmmktime(date("H"), date("i"), date("s"), date("m") - 24, date("d"), date("Y")));
		$sql = "SELECT ID, post_title, COUNT($wpdb->comments.comment_post_ID) AS 'stammy' FROM $wpdb->posts, $wpdb->comments WHERE comment_approved = 1 AND $wpdb->posts.ID=$wpdb->comments.comment_post_ID AND post_status = 'publish' AND post_date < '$now' AND post_date > '$lastmonth' AND comment_status = 'open' GROUP BY $wpdb->comments.comment_post_ID ORDER BY stammy DESC LIMIT " . $number;
		$r = $wpdb->get_results($sql);

		//if ($r->have_posts()) :
		if ($r) :
			?>
			<?php echo $before_widget; ?>
			<?php if ($title)
				echo $before_title . $title . $after_title; ?>
			<ul>
			<?php foreach ($r as $p): ?>
					<li><a href="<?php echo get_permalink($p->ID); ?>" title="<?php echo (get_the_title($p) ? esc_attr(strip_tags(get_the_title($p))) : get_the_ID()); ?>"><?php if (get_the_title($p))
					echo stripslashes($p->post_title); else
					echo get_the_ID(); ?></a><span style="color:#66747B;"> [<?php echo $p->stammy; ?>]</span></li>
			<?php endforeach; ?>
			</ul>
			<?php echo $after_widget; ?>
			<?php
		// Reset the global $the_post as this query will have stomped on it
		//wp_reset_postdata();

		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		//var_dump( $r );
		wp_cache_set('widget_popular_posts', $cache, 'widget');
	}

	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$this->flush_widget_cache();

		$alloptions = wp_cache_get('alloptions', 'options');
		if (isset($alloptions['widget_popular_entries']))
			delete_option('widget_popular_entries');

		return $instance;
	}

	function flush_widget_cache()
	{
		wp_cache_delete('widget_popular_posts', 'widget');
	}

	function form($instance)
	{
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$number = isset($instance['number']) ? absint($instance['number']) : 5;
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:'); ?></label>
			<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
		<?php
	}

}

// end class ihacklog_Widget_Popular_Posts

function ihacklog_pkg_load_widgets()
{
	register_widget('ihacklog_pkg_Widget_Recent_Comments');
	register_widget('ihacklog_pkg_Widget_Popular_Posts');
}

add_action('widgets_init', 'ihacklog_pkg_load_widgets');
?>
