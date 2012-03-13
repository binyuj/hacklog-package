<?php
/**
 * @filename highslide.php 
 * @encoding UTF-8 
 * @author 荒野无灯 <HuangYeWuDeng> 
 * @link http://ihacklog.com 
 * @copyright Copyright (C) 2011 荒野无灯 
 * @license http://www.gnu.org/licenses/
 * @Description 添加highslide图片显示特效
 */

add_action('wp_print_styles','ihacklog_pkg_highslide_stylesheets');
add_action('wp_enqueue_scripts', 'ihacklog_pkg_highslide_scripts');
// javascript for main blog
//按需加载JS，只能在ihacklog_pkg_highslide_add_js函数里面判断，在这里判断不行
add_action('wp_footer', 'ihacklog_pkg_highslide_add_js');
//hook it
add_filter('the_content', 'ihacklog_pkg_highslide_replace', 1);
add_filter('comment_text', 'ihacklog_pkg_highslide_replace', 1);

//add onclick event 
function ihacklog_pkg_highslide_replace ($content)
{ 
$pattern = "/<a(.*?)href=('|\")([^>]*).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>(.*?)<\/a>/i";
$replacement = '<a$1href=$2$3.$4$5 class="highslide"  onclick="return hs.expand(this)"  title="Click to enlarge（点击查看大图）" $6>$7 </a>';
$content = preg_replace($pattern, $replacement, $content);
return $content;
}

//add js to header
function ihacklog_pkg_highslide_add_js()
{
	if(is_single()||is_page() )
	{	
	$tpl_dir=plugin_dir_url(HACKLOG_PACKAGE_LOADER );
	echo <<<EOT
	<!-- Added By Highslide Plugin. Version  2.0  (C) 2009 荒野无灯 -->
	<script type='text/javascript'>
//	alert('ok');
	hs.showCredits = true;
	hs.creditsHref = 'http://ihacklog.com/';
    hs.creditsTarget  = '_self';
	hs.graphicsDir = '{$tpl_dir}js/highslide/graphics/';
	hs.align = 'center';

    hs.transitions = ['expand', 'crossfade'];
        hs.outlineType = 'glossy-dark';
        hs.wrapperClassName = 'glossy-dark';
        hs.fadeInOut = true;
        hs.dimmingOpacity = 0.3;
        //hs.padToMinWidth = true;

        if (hs.addSlideshow) hs.addSlideshow({
            interval: 5000,
            repeat: false,
            useControls: true,
            fixedControls: 'fit',
            overlayOptions: {
                opacity: .6,
                position: 'bottom center',
                hideOnMouseOut: true
            }
        });

// Add the slideshow providing the controlbar and the thumbstrip
/*
hs.addSlideshow({
	//slideshowGroup: 'group1',
	interval: 5000,
	repeat: false,
	useControls: true,
	overlayOptions: {
		className: 'text-controls',
		position: 'bottom center',
		relativeTo: 'viewport',
		offsetY: -60
	},
	thumbstrip: {
		position: 'bottom center',
		mode: 'horizontal',
		relativeTo: 'viewport'
	}
}); 
*/ 
 
hs.lang={
   loadingText :     '图片加载中...',
   loadingTitle :    '正在加载图片',
   closeText :       '关闭',
   closeTitle :      '关闭 (Esc)',
   creditsText :     'Powered by <i>荒野无灯</i>',
   creditsTitle :    'http://ihacklog.com',
   moveTitle :       '移动图片',
   moveText :        '移动',
   previousText :    '后退',
   previousTitle :   '上一张图片 (左方向键)',
   nextText :        '前进',
   nextTitle :       '下一张图片 (右方向键)',
   restoreTitle :    '小提示：点击可关闭或拖动. 用左右方向键可查看上一张和下一张-_-',
   fullExpandTitle : '点击查看原图',
   fullExpandText :  '查看原图'
     };
   
</script>
<!--  Added By Highslide Plugin. Version  2.0  (C) 2009 荒野无灯 -->
EOT;
	} //end if
 }//end function

function ihacklog_pkg_highslide_stylesheets()
{
		wp_enqueue_style('highslide', plugin_dir_url(HACKLOG_PACKAGE_LOADER ) . 'js/highslide/highslide.min.css.php', array() , '4.1.12');

}

function ihacklog_pkg_highslide_scripts()
{
	wp_enqueue_script('highslide', plugin_dir_url(HACKLOG_PACKAGE_LOADER ) . 'js/highslide/highslide-full.min.js.php' , array() , '4.1.12');
}
