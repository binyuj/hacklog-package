<?php
/**
 * @filename hacklog-package.class.php 
 * @encoding UTF-8 
 * @author 荒野无灯 <HuangYeWuDeng> 
 * @link http://ihacklog.com 
 * @copyright Copyright (C) 2012 荒野无灯 
 * @license http://www.gnu.org/licenses/
 * @version 1.0.5
 * @Description
  */

class hacklog_package
{
	const TEXTDOMAIN = 'hacklog_package';
	const PLUGIN_NAME = 'Hacklog Package';
	const PLUGIN_VERION = '1.0.5';
	const OPT = 'hacklog_package_options';
	private static $_inc_dir = '';
	
		public static function get_hacks()
	{
		$packages = require dirname(__FILE__). '/packages.php';
		return $packages;
	}
	
	public static function init()
	{
		self::$_inc_dir = dirname(HACKLOG_PACKAGE_LOADER).'/includes/';
		add_action('admin_menu',array(__CLASS__,'plugin_menu'));
		$hacks = get_option(self::OPT,self::get_hacks());
//		var_dump($hacks);
		foreach($hacks as $file => $opt)
		{
			$file_path = self::locate_file($file);
			if(is_file($file_path) && $opt['enable'])
			{
				require $file_path;
			}
		}
		
		is_admin() && add_filter( 'current_screen', array(__CLASS__,'contextual_help_tab') );
	}
	
	/**
	 *Contextual help
	 * @return type 
	 */
	public static function contextual_help_tab( $current_screen )
	{
	if ( $current_screen->id == 'settings_page_' . md5(HACKLOG_PACKAGE_LOADER) ) {
		// General contexual help
		$general  = '<p>这是一个<strong>“半插件”</strong>，之所以这么说，是因为这个插件设计的目的是用于方便实现那些经常要用到的功能。如<strong>评论回复邮件通知</strong>';
		$general .= '、<strong>中文片断截取</strong>、<strong>评论者网站URL重定向</strong>等,然而，此插件并不打算设计后台选项。</p>';
		$general .= '<p><strong>好处</strong> : 使用此插件相比于直接在你当前主题的functions.php文件中添加相应代码的好处是，每次当你更换主题后，你没有必要一遍又一遍地复制和粘贴代码到你所使用的主题的functions.php文件中。真正做到，<strong>一次添加，永久使用</strong>。</p>';
		$general .= '<p>你可以根据需要把用于实现功能的代码放置在本插件目录的includes目录下面，并编辑<code>packages.php</code>文件，添加相关信息。</p>';
		$general .= '<p>你可以通过FTP或者直接在WP后台编辑<code>packages.php</code>文件（点击<a href="'. admin_url('plugin-editor.php?file=hacklog-package%2Fpackages.php&plugin=hacklog-package%2Floader.php') .'"><strong>这里</strong></a>开始编辑）。</p>';
		
		$current_screen->add_help_tab( array(
			'id'      => self::TEXTDOMAIN . '-general',
			'title'   => '概述',
			'content' => $general,
		) );
		
		//how to
		$how_to  = '<p>首先，把你用于实现某功能的代码添加到一个新建文件中，如demo.php,这个文件要位于本插件目录下的<code>includes</code>目录下面。</p>';
		$how_to .= '<p>然后，编辑<code>packages.php</code>文件，按照文件中已有条目的格式，增加一条，';
		$how_to .= '如：<blockquote style="font-size:16px;font-family:monaco,Consolas;">&apos;demo.php&apos;=&gt;array(&apos;name&apos;=&gt;&apos;演示如何添加代码&apos;,&apos;enable&apos;=&gt;1),</blockquote></p>';
		$how_to .= '<p><strong>解释</strong> - 第一个参数demo.php是文件名（linux/BSD主机区分大小写）,name对应的值为功能描述，enable表示是否启用，启用此功能。启用则值为1，不启用设置其值为0即可。</p>';
		
		$current_screen->add_help_tab( array(
			'id'      => self::TEXTDOMAIN .'-how-to',
			'title'   => '如何添加新功能',
			'content' => $how_to,
		) );
	
		//standardize
		$comment_star = '*';
		$standardize = '<p>所有<strong>全局变量</strong>、<strong>函数名</strong>、<strong>类名</strong>，都要以<code>ihacklog_pkg_</code>开头，此举是为防止因冲突而导致程序运行出错。</p>';
		$standardize .= '如：<pre>';
		$standardize .= '$GLOBALS[\'<strong>ihacklog_pkg_</strong>foo\']';
		$standardize .= '</pre>';
		$standardize .= '<p><strong>增加配置支持</strong> - 在文件开头处按如下格式增加配置：</p>';
		$standardize .='
<pre>
/'. $comment_star . '========= START CONFIGURE ========'. $comment_star .'/
$GLOBALS[\'<strong>ihacklog_pkg_</strong>foo\'] = array(
	\'key\' => \'value\',
);
/'. $comment_star .'=========  END  CONFIGURE ========'. $comment_star .'/
</pre>
	然后在函数中声明 <code>global <strong>$ihacklog_pkg_</strong>foo;</code> 后引用配置即可。
		';
		$current_screen->add_help_tab( array(
			'id'      => self::TEXTDOMAIN .'-standardize',
			'title'   => 'package文件编码规范',
			'content' => $standardize,
		) );	

		// Contact sidebar
		$current_screen->set_help_sidebar(
			'<p><strong>意见、建议和问题？</strong></p>' .
			'<p><a href="http://ihacklog.com" target="_blank">联系荒野无灯</a></p>' .
			'<p><a href="http://wordpress.org.cn/forum-25-1.html" target="_blank">访问WP中文论坛插件区</a></p>' 
		);
	}
	
	return $current_screen;
	}
	public static function locate_file($file)
	{
		return self::$_inc_dir . $file;
	}



	
	public static function plugin_menu()
	{
		$identifier = md5(HACKLOG_PACKAGE_LOADER);
		$option_page = add_options_page(__('Hacklog Package', self::TEXTDOMAIN), __('Hacklog Package', self::TEXTDOMAIN), 'manage_options', $identifier, array(__CLASS__, 'plugin_options')
		);
	}
	
	public static function plugin_options()
	{
		$action_page = admin_url('options-general.php?page=' . md5(HACKLOG_PACKAGE_LOADER));
		$ajax_action_page = WP_PLUGIN_URL .'/hacklog-package/ajax.php?ihacklog=huangye';
		if( isset($_POST['submit']))
		{
				update_option(self::OPT, self::get_hacks());
		}
		
		$opts = get_option(self::OPT);
		if( empty( $opts))
		{
			add_option(self::OPT, self::get_hacks() );
		}
		$hacks = get_option(self::OPT);
		
		$i = 0;
			?>
		<div class="wrap">
		<?php screen_icon(); ?>
			<h2> <?php _e(self::PLUGIN_NAME .' Options', self::TEXTDOMAIN) ?></h2>
			<form name="form1" method="post"
				  action="<?php echo $action_page; ?>">
				<table width="100%" cellspacing="0" border ="1" class="widefat fixed">
					<caption style="font-size:16px;font-weight:700;font-family:Georgia;">Currently loaded hacks (under <?php echo self::$_inc_dir;?>) <span id="ajax-loading"></span></caption>
					<thead>
					<tr>
						<th>file</th>
						<th>name</th>
						<th>loaded?</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach($hacks as $file => $opt):?>
					<tr class="<?php echo $i%2== 0 ? 'alternate': 'even';?>">
						<td><?php echo $file;?></td>
						<td><?php echo $opt['name'];?></td>
						<td><?php echo $opt['enable']? '<a href="#" class="button" rel="disable"><span style="color:#0089de;">Yes</span></a>': '<a href="#" class="button" rel="enable"><span style="color:#d00;">No</span></a>';?></td>
					</tr>
					<?php ++$i;?>
					<?php endforeach;?>
					<tr>
						<td></td>
						<td><input type="submit" name="submit" value="恢复到默认设置" class="button-primary"/></td>
						<td></td>
					</tr>
					</tbody>
				</table>
			</form>
			<script type="text/javascript">
			jQuery(function($){
				var action_page = '<?php echo $ajax_action_page;?>';
				var do_action = function()
				{
					var obj = this;
					$('#ajax-loading').html('<img src="images/wpspin_dark.gif" style="vertical-align:middle;" alt=""/> ').show();
					$.ajax({
						'url': action_page + '&act='+ $(this).attr('rel') + '&package=' + $(this).parent().parent().find('td:first-child').text(),
						'type':'POST',
						'async':false,
						'data':'submit=do_ajax',
						'dataType':'json',
						'success':function(data){
							$('#ajax-loading').slideUp('slow');
							if( data.result == 'ok')
								{
									
//							alert('success!');
							if( $(obj).children().text() == 'No')
								{
									$(obj).children().text('Yes');
									$(obj).attr('rel','disable');
									$(obj).children().css('color','#0089de');
								}
								else
								{
									$(obj).children().text('No');
									$(obj).attr('rel','enable');
									$(obj).children().css('color','#dd0000');
								}
								}
							else
								{
									alert('failed!');
								}
						},
						'error': function(){alert('error!')}
					});
					return false;
				};
//				$('.button').click(function(){alert($(this).parent().parent().find('td:first-child').text());
//			alert($(this).attr('rel'));
$('.button').click(do_action);
			});
			</script>
<?php					
	}
	
	
}
