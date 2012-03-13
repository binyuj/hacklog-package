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

#lbOverlay{position:fixed;z-index:9999;left:0;top:0;width:100%;height:100%;background-color:#000;cursor:pointer;}#lbCenter,#lbBottomContainer{position:absolute;z-index:9999;overflow:hidden;background-color:#fff;-moz-border-radius:3px 3px 3px 3px;}.lbLoading{background:#fff url(loading.gif) no-repeat center;}#lbImage{position:absolute;left:0;top:0;border:10px solid #fff;background-repeat:no-repeat;}#lbPrevLink,#lbNextLink{display:block;position:absolute;top:0;width:50%;outline:none;}#lbPrevLink{left:0;}#lbPrevLink:hover{background:transparent url(prevlabel.gif) no-repeat 0 15%;}#lbNextLink{right:0;}#lbNextLink:hover{background:transparent url(nextlabel.gif) no-repeat 100% 15%;}#lbBottom{font-family:Verdana,Arial,Geneva,Helvetica,sans-serif;font-size:10px;color:#666;line-height:1.4em;text-align:left;border:10px solid #fff;border-top-style:none;}#lbCloseLink{display:block;float:right;width:66px;height:22px;background:transparent url(closelabel.gif) no-repeat center;margin:5px 0;outline:none;}#lbCaption,#lbNumber{margin-right:71px;}#lbCaption{font-weight:bold;}

<?php if(extension_loaded('zlib')) {ob_end_flush();} ?>