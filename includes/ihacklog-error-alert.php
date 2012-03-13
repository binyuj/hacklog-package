<?php
if (!defined('ABSPATH'))
{
	header('HTTP/1.1 403 Forbidden', true, 403);
	die('Please do not load this page directly. Thanks!');
} 	
 /*********START*************ALERT ERROR by 荒野无灯******************************/ 
add_action('shutdown', 'ihacklog_error_alert');
 
function ihacklog_error_alert()
 {
         if(is_null($e = error_get_last()) === false )
         {
             switch($e['type'])
             {
                 //@see http://www.php.net/manual/en/errorfunc.constants.php
                 case E_ERROR:
                 case E_PARSE:
                 case E_CORE_ERROR:
                 case E_USER_ERROR:
                 case E_RECOVERABLE_ERROR :
                     if( !defined('WP_DEBUG') || (defined('WP_DEBUG') && !WP_DEBUG ) )
                 {
					if(!defined('WP_ADMIN_ALERT_EMAIL'))
					{
						define('WP_ADMIN_ALERT_EMAIL',get_option('admin_email'));
					}
                 	 $message = 'REQUEST DATE: ' . gmdate('Y-m-d H:i:s',time()+8*3600 ) . "\nREQUEST URI: " ;
                 	 $message .= isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'N/A';
                 	 $message .= "\nUSER AGENT: ";
                 	 $message .= isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'N/A';
                 	 $message .= "\nUSER IP: ";
                 	 $message .= isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'N/A';
                     $message .= "\nERROR INFO: \n". print_r($e, true). "\n";
                     @mail( WP_ADMIN_ALERT_EMAIL, 'Error from WP: [type]=>'. $e['type'], $message );
                     //header('Content-type: text/html;charset=UTF-8');
                     die('Oops! An error has occurred...<br />the message has been sent to the site administrator.');
                 }
                 else
                 {
                     echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
                             <html xmlns="http://www.w3.org/1999/xhtml">
                             <head>
                             <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                             <title>Oops! An error has occurred...</title>
                         <style type="text/css">
                         .error-div{margin:50px auto;font-size:20px;font-family:Georgia;}
                         .info{ color:#f00;font-weight:bold;}
                         </style>
                         </head><body>';
                     //print_r($e);  
                    echo '<div class="error-div">Error: <span class="info">'. $e['message'] .'</span> on file: <span class="info">'. $e['file'] . '</span> line: <span class="info">'. $e['line'] .'</span>.</div>' ;
                     echo '</body></html>';
                     //@ob_end_flush();
                     die();
                 }
                     break;
             }
         }
 }
 /*********END****************ALERT ERROR by 荒野无灯******************************/