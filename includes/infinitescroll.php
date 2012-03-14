<?php
/**
 * @filename infinitescroll.plugin.php 
 * @encoding UTF-8 
 * @author 荒野无灯 <HuangYeWuDeng> 
 * @link http://ihacklog.com 
 * @copyright Copyright (C) 2011 荒野无灯 
 * @license http://creativecommons.org/licenses/by-nc/2.5/
 * @TODO 增加后台配置页面
 * @Description 无限分页支持。需要根据各自的主题配置。
 * JQuery plugin from http://www.infinite-scroll.com/
  */
if (! defined ( 'ABSPATH' )) {
	die ( 'What are you doing?' );
}
  	  
 class ihacklog_pkg_infinitescroll
{
	private static $defaultOpts =array(
	/*========= START CONFIGURE ========*/	
		'content_id'=>'content',
		'nextSelector'=>'div.alignleft a:first', 
		'navSelector'=>'div.navigation',
		'itemSelector'=>'div.post,div.entries',
		'loadingText'=>'正在努力加载中...',
		'donetext'=>'后面没有啦Orz',
		'debug'=>'false',
	/*=========  END  CONFIGURE ========*/		
		);
	
	public static function init()
	{
		add_action('wp_enqueue_scripts', array(__CLASS__,'register_js'));
		add_action('wp_head',array(__CLASS__,'print_css'));
		add_action('wp_footer',array(__CLASS__,'print_footer_js'),20);		
//		self::action_plugin_activation();
//		update_option(__CLASS__,self::$defaultOpts);
	}

	public static function help()
	{
		return ' <p>为Habari添加无限分页效果.<br />如不能正常工作，请修改配置参数.</p>';
	}
	
		public static function action_plugin_activation()
	{
			$options = get_option(__CLASS__);
			if (empty($options))
			{
				add_option(__CLASS__,self::$defaultOpts);
			}
	}
	
	/*
		public function configure()
	{
		$ui = new FormUI(__CLASS__);
		$ifs_content_id = $ui->append('text', 'content_id', __CLASS__. '__content_id', '<dl><dt>日志列表DIV id：</dt><dd>后续分页内容会自动加载到此DIV之尾部.</dd></dl>');
		
		$ifs_nextSelector = $ui->append('text', 'pic_path', __CLASS__. '__nextSelector', '<dl><dt>nextSelector：</dt><dd>下一页的数字所在元素的选择器.</dd></dl>');

		$ifs_navSelector = $ui->append('text', 'navSelector', __CLASS__. '__navSelector', '<dl><dt>navSelector： </dt><dd>分页导航的选择器.</dd></dl>');

		$ifs_itemSelector = $ui->append('text', 'itemSelector', __CLASS__. '__itemSelector', '<dl><dt>itemSelector：</dt><dd>内容DIV中每个元素的选择器(如每篇日志的选择器).</dd></dl>');

		$ifs_loadingText = $ui->append('text', 'loadingText', __CLASS__. '__loadingText', '<dl><dt>加载中提示：</dt><dd>正在加载时的提示文字.</dd></dl>');
		
		$ui->append('text', 'donetext', __CLASS__. '__donetext', '<dl><dt>最后一页提示：</dt><dd>到最后一页时的提示文字.</dd></dl>');
		
		$ui->append('checkbox', 'debug', __CLASS__. '__debug', '<dl><dt>调试：</dt><dd>如不能正常工作，可开启调试，在Firebug　Console查看调试信息.</dd></dl>');
		$ui->append('submit', 'save', _t('Save'));
		$ui->set_option( 'success_message', _t( 'Options saved' ) );
		$ui->out();
	}
	*/
	 static function print_footer_js()
	 {
		 $options = get_option(__CLASS__, self::$defaultOpts);
		 $options['debug'] = 1 == $options['debug'] ? 'true': 'false';
		 $plg_url = plugin_dir_url(HACKLOG_PACKAGE_LOADER )  ;
		 $js = <<<EOT
		 <script type="text/javascript">
jQuery(function($) {
	$("#{$options['content_id']}").infinitescroll(
	{
	  loading: {
            finished: undefined,
            finishedMsg: "<em>{$options['donetext']}</em>",
            img: "{$plg_url}images/ajax-loader.gif",
            msg: null,
            msgText: "<em>{$options['loadingText']}</em>",
            selector: null,
            speed: 'fast',
            start: undefined
        },
                          debug           : {$options['debug']},
                          preload         : true,
                          nextSelector    : "{$options['nextSelector']}",
                          navSelector     : "{$options['navSelector']}",
                          contentSelector : null,           // not really a selector. :) it's whatever the method was called on..
                          extraScrollPx   : 350,
                          itemSelector    : "{$options['itemSelector']}",
                          animate         : false,
                          localMode      : false,
                          bufferPx        : 40,
                          errorCallback   : function(){}
});
	

});
</script>
EOT;
			echo $js;
	 }
	 
	 public static function register_js()
	 {
			wp_enqueue_script('jquery.infinitescroll',  plugin_dir_url(HACKLOG_PACKAGE_LOADER).'js/jquery.infinitescroll.min.js.php', array('jquery'), '1.0', TRUE);
	 }
	 public static function print_css()
	 {
			echo '
				<style type="text/css">
		 #infscr-loading
		 {
			width:220px;
			margin:10px auto;
		 }
		 #infscr-loading em {
			font-size:14px;
		 }
		 </style>
		';	 
	 }

}
//run
ihacklog_pkg_infinitescroll::init();