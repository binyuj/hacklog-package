<?php
if(!isset($_GET['font']) || empty($_GET['font']) )
{
	die('err');
}

$font = basename($_GET['font']);

if(strpos($font,'.') !== FALSE )
{
	die('What are you doing?');
}

$font = dirname(__FILE__). '/google_fonts_cache/'. $font;
$filesize = filesize($font);
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
	
	header("Content-Type: application/force-download");
	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download");
	header('Content-Disposition: attachment; '. basename($font) );
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: " . $filesize);
						
echo file_get_contents($font);
