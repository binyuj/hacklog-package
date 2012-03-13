<?php
/**
 * usage: <link rel="stylesheet" type="text/css" media="screen" href="<?php echo plugins_url('hacklog-package');?>/google_fonts_css.php?family=Nova+Square|Nosifer+Caps" />
 */
//$_GET['family'] = 'Electrolize|Nova+Square|Nosifer+Caps|Marmelad|Spinnaker';
		
if(!isset($_GET['family']) || empty($_GET['family']) )
{
	die('err');
}

require dirname(__FILE__). '/../../../wp-load.php';

$family = trim($_GET['family']);
if( empty( $family ) )
{
	die('err');
}


/////////////////////////////////////////////////////////////////////////////////
$fp = fopen(__FILE__, "r");
$fstat = fstat($fp);
$fstat['atime']='';
$etag = md5(serialize($fstat)); 
fclose($fp);
	
$last_modified_time = filemtime(__FILE__);
$last_modified = gmdate('D, d M Y H:i:s', $last_modified_time) . ' GMT';
// did the browser send an if-modified-since request?
if ( isset($_SERVER['HTTP_IF_NONE_MATCH']) || isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ) 
{
  // parse header
  $if_modified_since = preg_replace('/;.*$/', '', $_SERVER['HTTP_IF_MODIFIED_SINCE']);

  if (
  	  $if_modified_since == $last_modified ||  
  	  (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag )
   ) 
   {
    // the browser's cache is still up to date
    header('HTTP/1.0 304 Not Modified');
    header('Cache-Control: must-revalidate');
    exit;
 	}
}



header('Cache-Control: must-revalidate');
$offset = 3600 *24  * 7;
$expire_str = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
header($expire_str);
header('Last-Modified: ' . $last_modified);
header("Etag: $etag"); 
/////////////////////////////////////////////////////////////////////////////////////////////

//3600*24*7 7天，0为永不过期
	
$gfc = new google_fonts_cache($family, 'google_fonts_cache', 0);
$gfc->print_css();

class google_fonts_cache
{

	private $fonts;
	private $cache_path;
	private $cache_expire;

	public function __construct($family,$path, $expire = 3600)
	{
		$this->cache_dir = trim($path,'/');
		$this->cache_path = dirname(__FILE__). '/'.$this->cache_dir ;
		if(!is_dir($this->cache_path) )
		{
			mkdir($this->cache_path, 0777 , TRUE);
		}
		$this->cache_expire = $expire;

		$fonts = explode('|',$family);
		foreach($fonts as $f)
		{
			$this->fonts[]=
				array(
					'name' => $f,
					'localname' => str_replace(' ','-', $f),
				);

		}
		//var_export($this->fonts);exit;
	}

	public function expired($file)
	{
		$expired = FALSE;
		$font_file = $this->cache_path. '/'. $file; // .woff or .ttf 
		$css_file = $this->cache_path. '/'. $file. '.css';
		if( !file_exists($font_file) || !file_exists($css_file ) )
		{
			$expired = TRUE;
		}

		if( $this->cache_expire != 0 && file_exists($font_file) && ( time() - filemtime($font_file) > $this->cache_expire) )
		{
			$expired = TRUE;
		}
		if( $expired)
		{
			file_exists($font_file) && unlink($font_file);
			file_exists($css_file) && unlink($css_file);
		}
		return $expired;
	}

	public function send_header()
	{
		header('Content-Type: text/css;Charset=UTF-8');
	}

	public function print_css()
	{
		$this->send_header();
		ini_set('max_execution_time',300);
		foreach($this->fonts as $f)
		{

			if( $this->expired($f['localname']) )
			{
				$google_css_url = sprintf('http://fonts.googleapis.com/css?family=%s',str_replace(' ','%20',$f['name']) );
				$http = wp_remote_get($google_css_url,array('timeout'=>120));
				//var_dump($http);exit;
				//ignore  WP_Error
				if(is_wp_error($http))
				{
					//echo "WP_Error!\n";
					continue;
				}
				if ( 200 == $http['response']['code']) 
				{
					$file_content = $http['body'];
					//var_dump(preg_match("/url\('(http:\/\/themes\.googleusercontent\.com\/[a-zA-Z0-9_\-\/]+\.(woff|ttf)'\))/i"," src: local('Electrolize'), local('Electrolize-Regular'), url('http://themes.googleusercontent.com/static/fonts/electrolize/v1/DDy9sgU2U7S4xAwH5thnJ4bN6UDyHWBl620a-IRfuBk.woff') format('woff');",$matches));
					if(preg_match("/url\('(http:\/\/themes\.googleusercontent\.com\/[a-zA-Z0-9_\-\/]+\.(woff|ttf))'\)/i",$file_content,$matches) )
					{
						//var_dump('found');
						$font_url = $matches[1];
						//$font_url = str_replace('http','https', $font_url);
						//var_export($matches);
						//echo "$font_url\n";
						$font = wp_remote_get($font_url,array('timeout'=>300));
						if(is_wp_error($font))
						{
							continue;
						}						
						if ( 200 == $font['response']['code']) 
						{
							file_put_contents($this->cache_path. '/'. $f['localname'], $font['body']);
							$file_content = str_replace($font_url, plugins_url('hacklog-package').'/get_font.php?font='. $f['localname'] ,$file_content);
							file_put_contents($this->cache_path. '/'. $f['localname']. '.css',$file_content);
							echo $file_content;
						}

					}
				}

			}
			else
			{
				echo file_get_contents($this->cache_path. '/'. $f['localname']. '.css');
			}

		}
	}


}

