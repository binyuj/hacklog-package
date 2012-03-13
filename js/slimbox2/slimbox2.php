<?php
/*
Plugin Name: SlimBox2 for WordPress
Plugin URI: http://immmmm.com/slimbox2-for-wordpress.html
Description: Based on a section of jQuery picture light-box effects, small to outrageous! 基于jQuery的一款图片灯箱特效，小到令人发指！
Version: 1.0.1
Author: 林木木
Author URI: http://immmmm.com/
*/
add_action('admin_menu', 'sb2_page');
function sb2_page (){
	if ( count($_POST) > 0 && isset($_POST['sb2_settings']) ){
		$options = array ('1','2','3','4','5','6','7','8','9');
		foreach ( $options as $opt ){
			delete_option ( 'sb2_'.$opt, $_POST[$opt] );
			add_option ( 'sb2_'.$opt, $_POST[$opt] );	
		}
	}
	add_options_page('SlimBox2 设置', 'SlimBox2 设置', 8, basename(__FILE__), 'sb2_settings');
}

function sb2_settings() {?>
<style>
	.wrap,.wrap h2,textarea,em{font-family:'Century Gothic','Microsoft YaHei',Verdana;}
	.wrap{margin:0 auto;width:635px;}
	fieldset{border:1px solid #aaa;padding-bottom:20px;margin-top:10px;-webkit-box-shadow:rgba(0,0,0,.2) 0px 0px 5px;-moz-box-shadow:rgba(0,0,0,.2) 0px 0px 5px;box-shadow:rgba(0,0,0,.2) 0px 0px 5px;}
	legend{margin-left:5px;padding:0 5px;color:#2481C6;background:#F9F9F9;font-size:21px;}
	.form-table th{width:148px;}
	.form-table input.regular-text{width:188px;}
	textarea,input[type="text"]{font-size:11px;border:1px solid #aaa;background:none;-moz-box-shadow:rgba(0,0,0,.2) 1px 1px 2px inset;box-shadow:rgba(0,0,0,.2) 1px 1px 2px inset;-webkit-transition:all .4s ease-out;-moz-transition:all .4s ease-out;}
	input:focus{-moz-box-shadow:rgba(0,0,0,.2) 0px 0px 8px;box-shadow:rgba(0,0,0,.2) 0px 0px 8px;outline:none;}
</style>
<div class="wrap">
<h2>SlimBox2 for WordPress</h2>
<form method="post" action="">
	<fieldset>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="1">限制效果范围：</label></th>
				<td>
					<input name="1" type="text" id="1" value="<?php if(get_option('sb2_1')!=''){echo get_option('sb2_1');}else{echo '.content';}?>" />
					<br /><em>根据主题及需要输入加载此效果部分的Class或ID，默认".content"。</em>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="2">遮罩层透明度：</label></th>
				<td>
					<input name="2" type="text" id="2" value="<?php echo get_option('sb2_2'); ?>" /> <em>1不透明，0完全透明；默认0.8</em>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="3">遮罩层渐显渐隐速度：</label></th>
				<td>
					<input name="3" type="text" id="3" value="<?php echo get_option('sb2_3'); ?>" /> <em>毫秒；默认400毫秒，设置为 1，禁用此效果。</em>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="4">图片渐显速度：</label></th>
				<td>
					<input name="4" type="text" id="4" value="<?php echo get_option('sb2_4'); ?>" /> <em>毫秒；默认400毫秒，设置为 1，禁用此效果。</em>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="5">标题滑出速度：</label></th>
				<td>
					<input name="5" type="text" id="5" value="<?php echo get_option('sb2_5'); ?>" /> <em>毫秒；默认400毫秒，设置为 1，禁用此效果。</em>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="6">相册数量格式：</label></th>
				<td>
					<input name="6" type="text" id="6" value="<?php echo get_option('sb2_6'); ?>" />
					<br /><em>{x}为当前图片，{y}为总图片数；默认显示为 Image {x} of {y} 。</em>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="7">加载jQuery库：</label></th>
				<td>
					<input name="7" type="checkbox" id="7" value="1" <?php if (get_option('sb2_7')!='') echo 'checked="checked"'; ?>/>Google CDN
				</td>
			</tr>
		</table>
	</fieldset>

	<p class="submit">
		<input type="submit" name="Submit" class="button-primary" value="保存设置" />
		<input type="hidden" name="sb2_settings" value="save" style="display:none;" />
	</p>

</form>
</div>
<?php
}

function sb2_head(){
	echo '<link rel="stylesheet" href="' .WP_PLUGIN_URL.'/slimbox2-for-wordpress/slimbox2.css" type="text/css" />';
}
add_action('wp_head', 'sb2_head');

function sb2_footer(){
	if(get_option('sb2_1')!=''){$sb2_1= get_option('sb2_1');}else{$sb2_1= '.content';}
	if(get_option('sb2_2')!=''){$sb2_2= 'overlayOpacity:'.get_option('sb2_2').',';}
	if(get_option('sb2_3')!=''){$sb2_3= 'overlayFadeDuration:'.get_option('sb2_3').',';}
	if(get_option('sb2_4')!=''){$sb2_4= 'imageFadeDuration:'.get_option('sb2_4').',';}
	if(get_option('sb2_5')!=''){$sb2_5= 'captionAnimationDuration:'.get_option('sb2_5').',';}
	if(get_option('sb2_6')!=''){$sb2_6= 'counterText:"'.get_option('sb2_6').'",';}
	if(get_option('sb2_7')!=''){$sb2_7= '<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js"></script>';}
	$links= ''.$sb2_7.'<script type="text/javascript" src="'.WP_PLUGIN_URL.'/slimbox2-for-wordpress/slimbox2.js"></script>';
	$links.= '<script type="text/javascript">jQuery(function($){$("'.$sb2_1.' a[rel!=link]:has(img)").slimbox({'.$sb2_2.$sb2_3.$sb2_4.$sb2_5.$sb2_6.'});});</script>';
	echo $links;
}
add_action('wp_footer', 'sb2_footer');
?>