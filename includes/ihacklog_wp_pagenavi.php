<?php
	//类似于CMS的分页显示

function ihacklog_wp_pagenavi($before = '', $after = '') 
{
	global $wpdb, $wp_query;
	if (!is_single()) 
	{
		$request = $wp_query->request;
		$posts_per_page = intval(get_query_var('posts_per_page'));
		$paged = intval(get_query_var('paged'));
		$pagenavi_options = array(
		'num_pages'=>5, //每个分页导航条显示的页面数
		'always_show'=>1, //是否总显示页面导航
		'pages_text'=>'%CURRENT_PAGE%/%TOTAL_PAGES%',
		'style'=>1, //1为平铺,2为drop down
		'first_text'=>'&laquo; 首页',
		'last_text'	=>'尾页 &raquo;',
		'dotleft_text'=>'...',
		'dotright_text'=>'...',
		//'prev_text'=>'&laquo;',
	//	'next_text'=>'&raquo;',
		'prev_text'=>'上一页',
		'next_text'=>'下一页',
		'current_text'=>'%PAGE_NUMBER%',
		'page_text'=>'%PAGE_NUMBER%',
		);
		$numposts = $wp_query->found_posts;
		$max_page = $wp_query->max_num_pages;
		/*
		$numposts = 0;
		if(strpos(get_query_var('tag'), " ")) {
			preg_match('#^(.*)\sLIMIT#siU', $request, $matches);
			$fromwhere = $matches[1];			
			$results = $wpdb->get_results($fromwhere);
			$numposts = count($results);
		} else {
			preg_match('#FROM\s*+(.+?)\s+(GROUP BY|ORDER BY)#si', $request, $matches);
			$fromwhere = $matches[1];
			$numposts = $wpdb->get_var("SELECT COUNT(DISTINCT ID) FROM $fromwhere");
		}
		$max_page = ceil($numposts/$posts_per_page);
		*/
		if(empty($paged) || $paged == 0) {
			$paged = 1;
		}
		$pages_to_show = intval($pagenavi_options['num_pages']);
		$pages_to_show_minus_1 = $pages_to_show-1;
		$half_page_start = floor($pages_to_show_minus_1/2);
		$half_page_end = ceil($pages_to_show_minus_1/2);
		$start_page = $paged - $half_page_start;
		if($start_page <= 0) {
			$start_page = 1;
		}
		$end_page = $paged + $half_page_end;
		if(($end_page - $start_page) != $pages_to_show_minus_1) {
			$end_page = $start_page + $pages_to_show_minus_1;
		}
		if($end_page > $max_page) {
			$start_page = $max_page - $pages_to_show_minus_1;
			$end_page = $max_page;
		}
		if($start_page <= 0) {
			$start_page = 1;
		}
		if($max_page > 1 || intval($pagenavi_options['always_show']) == 1) {
			$pages_text = str_replace("%CURRENT_PAGE%", number_format_i18n($paged), $pagenavi_options['pages_text']);
			$pages_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pages_text);
			echo $before.'<div class="wp-pagenavi">'."\n";
			switch(intval($pagenavi_options['style'])) {
				case 1:
					if(!empty($pages_text)) {
						echo '<span class="pages"><span class="inner">'.$pages_text.'</span></span>';
					}					
					if ($start_page >= 2 && $pages_to_show < $max_page) {
						$first_page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pagenavi_options['first_text']);
						echo '<a href="'.clean_url(get_pagenum_link()).'" title="'.$first_page_text.'"><span class="inner">'.$first_page_text.'</span></a>';
						if(!empty($pagenavi_options['dotleft_text'])) {
							echo '<span class="extend"><span class="inner">'.$pagenavi_options['dotleft_text'].'</span></span>';
						}
					}
					previous_posts_link('<span class="inner">'.$pagenavi_options['prev_text'].'</span>');
					for($i = $start_page; $i  <= $end_page; $i++) {						
						if($i == $paged) {
							$current_page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['current_text']);
							echo '<span class="current"><span class="inner">'.$current_page_text.'</span></span>';
						} else {
							$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
							echo '<a href="'.clean_url(get_pagenum_link($i)).'" title="'.$page_text.'"><span class="inner">'.$page_text.'</span></a>';
						}
					}
					next_posts_link('<span class="inner">'.$pagenavi_options['next_text'].'</span>', $max_page);
					if ($end_page < $max_page) {
						if(!empty($pagenavi_options['dotright_text'])) {
							echo '<span class="extend"><span class="inner">'.$pagenavi_options['dotright_text'].'</span></span>';
						}
						$last_page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pagenavi_options['last_text']);
						echo '<a href="'.clean_url(get_pagenum_link($max_page)).'" title="'.$last_page_text.'"><span class="inner">'.$last_page_text.'</span></a>';
					}
					break;
				case 2;
					echo '<form action="'.htmlspecialchars($_SERVER['PHP_SELF']).'" method="get">'."\n";
					echo '<select size="1" onchange="document.location.href = this.options[this.selectedIndex].value;">'."\n";
					for($i = 1; $i  <= $max_page; $i++) {
						$page_num = $i;
						if($page_num == 1) {
							$page_num = 0;
						}
						if($i == $paged) {
							$current_page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['current_text']);
							echo '<option value="'.clean_url(get_pagenum_link($page_num)).'" selected="selected" class="current">'.$current_page_text."</option>\n";
						} else {
							$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
							echo '<option value="'.clean_url(get_pagenum_link($page_num)).'">'.$page_text."</option>\n";
						}
					}
					echo "</select>\n";
					echo "</form>\n";
					break;
			}
			echo '</div>'.$after."\n";
		}
	}
} //end function ihacklog_wp_pagenavi
