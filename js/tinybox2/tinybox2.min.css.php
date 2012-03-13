<?php
//$etag = md5_file(__FILE__);	
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

if ( !( ( ini_get( 'zlib.output_compression' ) == 'On' || ini_get( 'zlib.output_compression_level' ) > 0 ) || ini_get( 'output_handler' ) == 'ob_gzhandler' )  && extension_loaded( 'zlib' ) )
{	
	ob_start( 'ob_gzhandler' );	
} 
header("Content-type: text/css"); 
header('Cache-Control: must-revalidate');
$offset = 3600 *24  * 7;
$expire_str = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
header($expire_str);
header('Last-Modified: ' . $last_modified);
header("Etag: $etag"); 
?>


.tbox{position:absolute;display:none;z-index:900;padding:14px 17px}.tinner{-moz-border-radius:5px;border-radius:5px;background:#fff url(images/preload.gif) no-repeat 50% 50%;border-right:1px solid #333;border-bottom:1px solid #333;padding:15px}.tmask{position:absolute;display:none;top:0;left:0;height:100%;width:100%;background:#000;z-index:800}.tclose{position:absolute;top:0;right:0;width:30px;height:30px;cursor:pointer;background:url(images/close.png) no-repeat}.tclose:hover{background-position:0 -30px}#error{background:#ff6969;color:#fff;text-shadow:1px 1px #cf5454;border-right:1px solid #000;border-bottom:1px solid #000;padding:0}#error .tcontent{border:1px solid #ffb8b8;-moz-border-radius:5px;border-radius:5px;padding:10px 14px 11px}#success{background:#2ea125;color:#fff;text-shadow:1px 1px #1b6116;border-right:1px solid #000;border-bottom:1px solid #000;-moz-border-radius:0;border-radius:0;padding:10px}#bluemask{background:#4195aa}#frameless{padding:0}#frameless .tclose{right:6px}

<?php if(extension_loaded('zlib')) {ob_end_flush();} ?>