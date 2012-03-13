<?php

#######  START   ##########     显示相关日志    #################    START   #########
###########################   www.ihacklog.com    ######################################
$wp_rp = array(
	'limit' => 6, //显示几条相关文章
	'wp_rp_rss' => true, //在rss feed 中显示相关文章
	'wp_no_rp' => 'random', //无相关文章时的选择：text 或random （random为显示随机文章)
	'wp_rp_date' => true, //显示日志发布日期
	'wp_rp_comments' => true, //显示日志评论数
	'wp_rp_title_tag' => 'h3', //相关日志标题标签(h2 ,h3 ,h4 ,p ,div)
);

function wp_get_random_posts($limitclause="")
{
	global $wpdb, $post;

	$q = "SELECT ID, post_title, post_content,post_excerpt, post_date, comment_count FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND ID != $post->ID ORDER BY RAND() $limitclause";
	return $wpdb->get_results($q);
}

function wp_get_related_posts()
{
	global $wpdb, $post, $wp_rp;
	$limit = $wp_rp["limit"];
	$wp_rp_title = '相关日志';
	if (!$post->ID)
	{
		return;
	}
	$now = current_time('mysql', 1);
	$tags = wp_get_post_tags($post->ID);

	$taglist = "'" . $tags[0]->term_id . "'";

	$tagcount = count($tags);
	if ($tagcount > 1)
	{
		for ($i = 1; $i < $tagcount; $i++)
		{
			$taglist = $taglist . ", '" . $tags[$i]->term_id . "'";
		}
	}

	if ($limit)
	{
		$limitclause = "LIMIT $limit";
	}
	else
	{
		$limitclause = "LIMIT 10";
	}

	$q = "SELECT p.ID, p.post_title, p.post_content,p.post_excerpt, p.post_date,  p.comment_count, count(t_r.object_id) as cnt FROM $wpdb->term_taxonomy t_t, $wpdb->term_relationships t_r, $wpdb->posts p WHERE t_t.taxonomy ='post_tag' AND t_t.term_taxonomy_id = t_r.term_taxonomy_id AND t_r.object_id  = p.ID AND (t_t.term_id IN ($taglist)) AND p.ID != $post->ID AND p.post_status = 'publish' AND p.post_date_gmt < '$now' GROUP BY t_r.object_id ORDER BY cnt DESC, p.post_date_gmt DESC $limitclause;";

	$related_posts = $wpdb->get_results($q);

	$output = "";
	//不存在相关日志则显示随机日志
	if (!$related_posts)
	{
		if ($wp_rp['wp_no_rp'] == "text")
		{
			$output .= '<li>无相关日志</li>';
		}
		else
		{
			if ($wp_rp['wp_no_rp'] == "random")
			{
				$wp_no_rp_text = '随机日志';
				$related_posts = wp_get_random_posts($limitclause);
			}

			$wp_rp_title = $wp_no_rp_text;
		}
	}

	foreach ($related_posts as $related_post)
	{
		$output .= '<li>';
		if ($wp_rp['wp_rp_date'])
		{
			$dateformat = get_option('date_format');
			$output .= mysql2date($dateformat, $related_post->post_date) . "  //  ";
		}
		$output .= '<a href="' . get_permalink($related_post->ID) . '" title="' . wptexturize($related_post->post_title) . '">' . wptexturize($related_post->post_title) . '</a>';
		if ($wp_rp["wp_rp_comments"])
		{
			$output .= " (" . $related_post->comment_count . ")";
		}
		$output .= '</li>';
	}
	$output = '<ul class="related_post">' . $output . '</ul>';
	$wp_rp_title_tag = $wp_rp["wp_rp_title_tag"];

	if (!$wp_rp_title_tag)
		$wp_rp_title_tag = 'h3';
	if ($wp_rp_title != '')
		$output = '<' . $wp_rp_title_tag . '  class="related_post_title">' . $wp_rp_title . '</' . $wp_rp_title_tag . '>' . $output;
	return $output;
}

function wp_related_posts_attach($content)
{
	global $wp_rp;
	if (is_single() || (is_feed() && $wp_rp["wp_rp_rss"]))
	{
		$output = wp_get_related_posts();
		$content = $content . $output;
	}

	return $content;
}

add_filter('the_content', 'wp_related_posts_attach', 100);
#######    END     ##########     显示相关日志    #################    END   #########
###########################   www.ihacklog.com    ######################################