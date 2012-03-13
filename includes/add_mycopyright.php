<?php
if (!defined('ABSPATH'))
{
	header('HTTP/1.1 403 Forbidden', true, 403);
	die('Please do not load this page directly. Thanks!');
} 
	
########################################################以下由荒野无灯添加#################################################
function add_mycopyright($content) {
        if( is_single() || is_feed() ) 
        {
//                $content='<span style="display:none;"> 本文转自<a target="_blank"  href="'.get_option('home').'"><strong>'.get_bloginfo('name').'</strong>【荒野无灯weblog】</a>本文链接:'.post_permalink().'</span>'.$content;
			/***********以下为配置项*********/
			//邮件订阅地址
			$email_subscribe_url = 'http://list.qq.com/cgi-bin/qf_invite?id=5899d1ae341c4fb741adf6648000fbaf3ef47b98e2a163da';
			/***********以上为配置项*********/
			$content.='
<script type="text/javascript"> 
var cur_host=top.location.hostname;
var huangye_host="'.$_SERVER['HTTP_HOST']. '";
if ( huangye_host != cur_host) 
{
	var cur_url=top.location.href;
	//top.location.href = cur_url.replace(cur_host,huangye_host);
	top.location.href = "'. wp_get_shortlink() . '";
}
</script> 			
';             
                $content.= "<div class='sub'>";
                $content.= "<h4>喜欢这篇文章吗?</h4>";
                $content.= '<p>请订阅本站 <a class="feed" style="font-family:Consolas,\'DejaVu Sans Mono\',monospace,\'Comic Sans MS\',Monaco;font-size:14px;" href="' .get_option('home'). '/feed" onclick="prompt(&#39;URL:&#39;, this.href); return false;">RSS feed</a>';
                $content.= ' 或<a rel="link" style="display:inline-block;width:90px;" target="_blank" href="http://list.qq.com/cgi-bin/qf_invite?id=5899d1ae341c4fb741adf6648000fbaf3ef47b98e2a163da"><img style="vertical-align:middle;" border="0" alt="填写您的邮件地址，订阅我们的精彩内容：" src="'. plugin_dir_url(HACKLOG_PACKAGE_LOADER ) . '/images/picMode_dark_s.png" /></a>';
                $content .= ',欢迎点击<a href="http://ihacklog.com/donate" target="_blank">这里</a>捐赠以支持荒野无灯';
                $content .= '<img style="display:inline-block;margin-bottom:-4px;" src="'. plugin_dir_url(HACKLOG_PACKAGE_LOADER ) . '/images/weiboicon16.png" border="0" alt="转播到腾讯微博"> <a href="javascript:;" class="tmblog" id="share_btn_1329282856369" style="height:16px;font-size:12px;line-height:16px;">转播到腾讯微博</a>';
                $content.= '</p></div>';
                $content.= '
                <!-- 版权声明开始 -->
   		<div id="permissions">
		作者：<a href="'.home_url().'">'.get_the_author().'</a><br/>
		出处：<span style="color: #333300;"><a target="_blank"  href="'.home_url().'"><strong>'.get_bloginfo('name').'</strong>【'.get_bloginfo('blogdescription') .'】</a></span><br/>
                <!-- 版权声明结束 -->
                <!-- 协议声明开始 -->
            <p>
            <strong>声明:</strong> 本站遵循 <span style="color: #ff0000;"><a href="http://creativecommons.org/licenses/by-nc-sa/2.5/cn/" target="_blank"> 署名-非商业性使用-相同方式共享 2.5</a> </span>共享协议. 转载请注明转自<span style="color: #333300;"><a target="_blank" href="'.get_option('home').'"><strong>'.get_bloginfo('name').'</strong>【荒野无灯weblog】</a></span>
            </p>
                <!-- 协议声明结束 -->
                
             <p>
             本文链接:  <a  target="_blank"  href="'.wp_get_shortlink().'" title="Permanent Link to '.the_title_attribute('echo=0').'" onclick="prompt(&#39;URL:&#39;, this.href); return false;">'.wp_get_shortlink().'</a>
            </p>
             </div>';
         
         
                        

        }
        return $content;
}

function copyright_css()
{
	$image_pre_url = plugin_dir_url(HACKLOG_PACKAGE_LOADER ) . 'images/alarm_icons.png';
	echo <<<EOT
   <style type="text/css">
	#permissions
{
background:transparent url($image_pre_url) no-repeat scroll 0 50%;
border:1px solid #E5E5E5;
font-family:"Comic Sans MS","Microsoft Yahei",monaco, serif, sans-serif,verdana,"ms song",Arial,Helvetica,sans-serif;
font-size:12px;
margin-top:10px;
padding:10px 10px 10px 80px;
}
</style>
EOT;
}
add_filter ('the_content', 'add_mycopyright');
add_action('wp_head','copyright_css');
//////////////////////////////////////////////////////////////////////////
