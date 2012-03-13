<?php
/**
 * 媒体文件播放支持
 * @TODO 清洁代码
 */

add_action('wp_enqueue_scripts', 'ihacklog_pkg_parsemedia_scripts');
add_shortcode('media','ihacklog_pkg_parsemedia');
add_shortcode('audio','ihacklog_pkg_parsemp3');

function ihacklog_pkg_parseaudio($url, $width = 400, $autostart = 0, $title = '') {
	$ext = strtolower(substr(strrchr($url, '.'), 1, 5));
	switch($ext) {
		case 'mp3':	
			$auto=$autostart?'yes':'no';		
		//return '<embed src="'.plugin_dir_url(HACKLOG_PACKAGE_LOADER ) . 'swf/player.swf?soundFile='. base64_encode($url) .'&autostart='.$auto.'&animation=yes&encode=yes&initialvolume=80&remaining=yes&noinfo=no&buffer=5&checkpolicy=no&rtl=no&bg=E5E5E5&text=333333&leftbg=CCCCCC&lefticon=333333&volslider=666666&voltrack=FFFFFF&rightbg=B4B4B4&rightbghover=999999&righticon=333333&righticonhover=FFFFFF&track=FFFFFF&loader=009900&border=CCCCCC&tracker=DDDDDD&skip=666666" type="application/x-shockwave-flash" wmode="transparent" allowscriptaccess="always" width="290" height="30">';
		if( empty($title) )
		{
			$filename = rawurldecode(basename($url));  
			$filename = substr($filename, strrpos($filename,'=' )+1 );
		}
		else
		{
			$filename = strip_tags($title);
		}
		return '<div class="ui360">
 					<a onclick="return false;" href="'. $url . '">play "'.  $filename . '"</a>
				</div>';
		case 'wma':
		case 'mid':
		case 'wav':
			return '<object classid="clsid:6BF52A52-394A-11d3-B153-00C04F79FAA6" width="'.$width.'" height="64"><param name="invokeURLs" value="0"><param name="autostart" value="'.$autostart.'" /><param name="url" value="'.$url.'" /><embed src="'.$url.'" autostart="'.$autostart.'" type="application/x-mplayer2" width="'.$width.'" height="64"></embed></object>';
		case 'ra':
		case 'rm':
		case 'ram':
			$mediaid = 'media_'.random(3);
			return '<object classid="clsid:CFCDAA03-8BE4-11CF-B84B-0020AFBBCCFA" width="'.$width.'" height="32"><param name="autostart" value="'.$autostart.'" /><param name="src" value="'.$url.'" /><param name="controls" value="controlpanel" /><param name="console" value="'.$mediaid.'_" /><embed src="'.$url.'" type="audio/x-pn-realaudio-plugin" controls="ControlPanel" console="'.$mediaid.'_" width="'.$width.'" height="32"></embed></object>';
	}
}



function ihacklog_pkg_parseflv($url, $width, $height) {
    $lowerurl = strtolower($url);
    $flv = '';
    if($lowerurl != str_replace(array('player.youku.com/player.php/sid/','tudou.com/v/','player.ku6.com/refer/'), '', $lowerurl)) {
        $flv = $url;
    } elseif(strpos($lowerurl, 'v.youku.com/v_show/') !== FALSE) {
        if(preg_match("/http:\/\/v.youku.com\/v_show\/id_([^\/]+)(.html|)/i", $url, $matches)) {
            $flv = 'http://player.youku.com/player.php/sid/'.$matches[1].'/v.swf';
        }
    } elseif(strpos($lowerurl, 'tudou.com/programs/view/') !== FALSE) {
        if(preg_match("/http:\/\/(www.)?tudou.com\/programs\/view\/([^\/]+)/i", $url, $matches)) {
            $flv = 'http://www.tudou.com/v/'.$matches[2];
        }
    } elseif(strpos($lowerurl, 'v.ku6.com/show/') !== FALSE) {
        if(preg_match("/http:\/\/v.ku6.com\/show\/([^\/]+).html/i", $url, $matches)) {
            $flv = 'http://player.ku6.com/refer/'.$matches[1].'/v.swf';
        }
    } elseif(strpos($lowerurl, 'v.ku6.com/special/show_') !== FALSE) {
        if(preg_match("/http:\/\/v.ku6.com\/special\/show_\d+\/([^\/]+).html/i", $url, $matches)) {
            $flv = 'http://player.ku6.com/refer/'.$matches[1].'/v.swf';
        }
    }
    if($flv) {
        return '<script type="text/javascript" reload="1">document.write(AC_FL_RunContent(\'width\', \''.$width.'\', \'height\', \''.$height.'\', \'allowNetworking\', \'internal\', \'allowScriptAccess\', \'never\', \'src\', \''.$flv.'\', \'quality\', \'high\', \'bgcolor\', \'#ffffff\', \'wmode\', \'transparent\', \'allowfullscreen\', \'true\'));</script>';
    } else {
        return FALSE;
    }
}



function ihacklog_pkg_parsemedia($atts, $content=null) {
	global $post;
    extract(shortcode_atts(array('type'=>'swf',"width"=>640,'height'=>400,'autostart'=>0,'audio'=>'','volume'=>80,'size'=>'medium','title'=>'',),$atts)); 
    $width = intval($width) > 800 ? 800 : intval($width);
    $height = intval($height) > 600 ? 600 : intval($height);
    if($flv = ihacklog_pkg_parseflv($content, $width, $height)) {
        return $flv;
    }
        $url = str_replace(array('<', '>'), '', str_replace('\\"', '\"', $content));
        $audio=isset($audio)?'&audio='.$audio.'&volume='.$volume:'';
        
		switch($type) {
			case 'mp3':
			case 'wma':
			case 'ra':
			case 'ram':
			case 'wav':
			case 'mid':
				return ihacklog_pkg_parseaudio($url, $width, $autostart, $title);
			case 'rm':
			case 'rmvb':
			case 'rtsp':
				$mediaid = 'media_'.random(3);
				return '<object classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" width="'.$width.'" height="'.$height.'"><param name="autostart" value="'.$autostart.'" /><param name="src" value="'.$url.'" /><param name="controls" value="imagewindow" /><param name="console" value="'.$mediaid.'_" /><embed src="'.$url.'" type="audio/x-pn-realaudio-plugin" controls="imagewindow" console="'.$mediaid.'_" width="'.$width.'" height="'.$height.'"></embed></object><br /><object classid="clsid:CFCDAA03-8BE4-11CF-B84B-0020AFBBCCFA" width="'.$width.'" height="32"><param name="src" value="'.$url.'" /><param name="controls" value="controlpanel" /><param name="console" value="'.$mediaid.'_" /><embed src="'.$url.'" type="audio/x-pn-realaudio-plugin" controls="controlpanel" console="'.$mediaid.'_" width="'.$width.'" height="32"'.($autostart ? ' autostart="true"' : '').'></embed></object>';
			case 'flv':
				return '<script type="text/javascript" reload="1">document.write(AC_FL_RunContent(\'width\', \''.$width.'\', \'height\', \''.$height.'\', \'allowNetworking\', \'internal\', \'allowScriptAccess\', \'never\', \'src\', \''.get_bloginfo('template_directory').'/swf/flvplayer.swf\', \'flashvars\', \'file='.rawurlencode($url).'\', \'quality\', \'high\', \'wmode\', \'transparent\', \'allowfullscreen\', \'true\'));</script>';
			case 'swf':
				return '<script type="text/javascript" reload="1">document.write(AC_FL_RunContent(\'width\', \''.$width.'\', \'height\', \''.$height.'\', \'allowNetworking\', \'internal\', \'allowScriptAccess\', \'never\', \'src\', \''.$url.'\', \'quality\', \'high\', \'bgcolor\', \'#ffffff\', \'wmode\', \'transparent\', \'allowfullscreen\', \'true\'));</script>';
			case 'asf':
			case 'asx':
			case 'wmv':
			case 'mms':
			case 'avi':
			case 'mpg':
			case 'mpeg':
				return '<object classid="clsid:6BF52A52-394A-11d3-B153-00C04F79FAA6" width="'.$width.'" height="'.$height.'"><param name="invokeURLs" value="0"><param name="autostart" value="'.$autostart.'" /><param name="url" value="'.$url.'" /><embed src="'.$url.'" autostart="'.$autostart.'" type="application/x-mplayer2" width="'.$width.'" height="'.$height.'"></embed></object>';
			case 'mov':
				return '<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" width="'.$width.'" height="'.$height.'"><param name="autostart" value="'.($autostart ? '' : 'false').'" /><param name="src" value="'.$url.'" /><embed src="'.$url.'" autostart="'.($autostart ? 'true' : 'false').'" type="video/quicktime" controller="true" width="'.$width.'" height="'.$height.'"></embed></object>';
			case 'gallery':
				$url=$post->ID;
				return '<script type="text/javascript" reload="1">document.write(AC_FL_RunContent(\'width\', \''.$width.'\', \'height\', \''.$height.'\', \'allowNetworking\', \'internal\', \'allowScriptAccess\', \'never\', \'src\', \''.get_bloginfo('template_directory').'/imagerotator/imagerotator.swf\', \'flashvars\', \'file='.rawurlencode(get_bloginfo('template_directory').'/imagerotator/playlist.php?id='.$url.'&width='.$width.'&height='.$height. '&size=' .$size ).'\', \'quality\', \'high\', \'wmode\', \'transparent\', \'allowfullscreen\', \'true\'));</script>';
			default:
				return '<a href="'.$url.'" target="_blank">'.$url.'</a>';
	
	}
	return;
}

function ihacklog_pkg_parsemp3($atts, $content=null)
{
    extract(shortcode_atts(array('title'=>'',),$atts)); 
	return ihacklog_pkg_parsemedia(array('type'=>'mp3',"width"=>640,'height'=>400,'autostart'=>0,'title'=>$title),$content);
}

function ihacklog_pkg_parsemedia_scripts()
{
	wp_enqueue_script('parsemedia', plugin_dir_url(HACKLOG_PACKAGE_LOADER ) . 'js/swf.min.js.php' , array() , '2.0');
}