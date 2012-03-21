<?php
/**
 * @filename couponcode.php 
 * @encoding UTF-8 
 * @author 荒野无灯 <HuangYeWuDeng> 
 * @link http://ihacklog.com 
 * @copyright Copyright (C) 2011 荒野无灯 
 * @license http://www.gnu.org/licenses/
 * @Description add couponcode shortcode support
 * @usage [couponcode code='xxoo' caption='xxoo couponcode' url='http://ihacklog.com/' title='Copy Code & Shop Now']
 */


class ihacklog_pkg_couponcode
{
	public static function run()
	{
		add_action('wp_print_styles',array(__CLASS__,'ihacklog_pkg_couponcode_stylesheets'),999 );
		add_action('wp_enqueue_scripts', array(__CLASS__,'ihacklog_pkg_couponcode_scripts') );
		add_action('wp_footer', array(__CLASS__,'ihacklog_pkg_couponcode_js') );
		add_shortcode( "couponcode", array(__CLASS__,'couponcode_shortcode') );
	}

/**
 * usage [couponcode code='' caption='' url='' title='']
 * add shortcode support
 */
static function couponcode_shortcode( $atts ,$content)
{
    extract( shortcode_atts( 
    	array(
    		'caption' => '', 
    		'code' =>'not needed', 
    		'url' => '#' ,
    		'title' => 'Copy Code & Shop Now ►',
    		),
    	 $atts ) );
    $html = '
    <div class="couponcode-container">
    	<div class="couponcode-caption">%1$s</div>
    	<div class="couponcode">
    		<a title="%4$s" href="%2$s" >%3$s</a>
    		<span class="styledButton">%4$s</span>
    	</div>
    </div>';
    return sprintf($html,$caption, $url, $code, $title);
}



static function couponcode_install( )
{
    global $wpdb;
    $couponcode_db_version = "0.1";
    $installed_ver = get_option( "ihacklog_couponcode_db_version" );
    if ( $installed_ver != $couponcode_db_version )
    {
        require_once( ABSPATH."wp-admin/upgrade-functions.php" );
        $table_name = $wpdb->prefix."ihacklog_pkg_couponcodes";
        $sql = <<<EOT
        CREATE TABLE IF NOT EXISTS `zb_couponcodes` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `date_start` int(11) NOT NULL,
  `date_end` int(11) NOT NULL,
  `name` tinytext NOT NULL,
  `couponcode` text NOT NULL,
  `url` tinytext,
  `caption` text,
  `clicked` mediumint(9) DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;
EOT;
        dbDelta( $sql );
        update_option( "ihacklog_couponcode_db_version", $couponcode_db_version );
    }
}

static function ihacklog_pkg_couponcode_stylesheets()
{
	echo <<<EOT
	<style>
.couponcode-container {
    font-family: Arial,Helvetica,sans-serif;
    font-size: 14px;
    font-weight: bold;
    margin: 20px 0px;
    /*border-radius:3px;*/
    /*border: 1px solid #5577dd;*/
}

.couponcode-caption {
	display: block;
	margin: 4px 6px;
}


.styledButton{background:#2e7694;background:-moz-linear-gradient(top,#2e7694 0,#60b3d4 3%,#60b3d4 15%,#3c99c0 26%,#2e7694 91%,#3c99c0 99%);background:-webkit-gradient(linear,left top,left bottom,color-stop(0%,#2e7694),color-stop(3%,#60b3d4),color-stop(15%,#60b3d4),color-stop(26%,#3c99c0),color-stop(91%,#2e7694),color-stop(99%,#3c99c0));background:-webkit-linear-gradient(top,#2e7694 0,#60b3d4 3%,#60b3d4 15%,#3c99c0 26%,#2e7694 91%,#3c99c0 99%);background:-o-linear-gradient(top,#2e7694 0,#60b3d4 3%,#60b3d4 15%,#3c99c0 26%,#2e7694 91%,#3c99c0 99%);background:-ms-linear-gradient(top,#2e7694 0,#60b3d4 3%,#60b3d4 15%,#3c99c0 26%,#2e7694 91%,#3c99c0 99%);background:linear-gradient(top,#2e7694 0,#60b3d4 3%,#60b3d4 15%,#3c99c0 26%,#2e7694 91%,#3c99c0 99%);*filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#2e7694',endColorstr='#3c99c0',GradientType=0);zoom:100%;font-size:13px;line-height:11px;font-weight:bold;display:inline-block;*display:inline;color:white!important;padding:4px 8px;border-radius:6px;-moz-border-radius:6px;-webkit-border-radius:6px;cursor:pointer;text-decoration:none;border:1px solid #0e67aa;text-shadow:1px 1px 1px rgba(0,0,0,0.35);-webkit-text-shadow:1px 1px 1px rgba(0,0,0,0.35);-moz-text-shadow:1px 1px 1px rgba(0,0,0,0.35);-o-text-shadow:1px 1px 1px rgba(0,0,0,0.35);-ms-text-shadow:1px 1px 1px rgba(0,0,0,0.35);
  position:absolute;
    right:5px;
    top:3px;
}

.couponcode { 
		display: block;
	border-radius:3px;
	background-color: #FDFED2;
    border: 1px dashed #FEBF02;
    color: #325982;
    padding: 1px 0px 1px 10px;
    margin: 5px;
    position: relative;
    width: 360px;
    height:25px;
	  text-decoration: none; 
	  text-shadow: #0193c3 0 -1px;
}
.couponcode a {
	text-decoration:none !important;
}
.couponcode:hover{
	border-color:#000;
    background-color: #F5ECC1;
}
	
</style>
EOT;

}

/**
 * @see Zeroclipboard multiple elements ( http://stackoverflow.com/questions/2153246/zeroclipboard-multiple-elements )
 * @see http://code.google.com/p/zeroclipboard/wiki/Instructions#Setup
 */
static function ihacklog_pkg_couponcode_js()
{
	$plugin_dir_url =  plugin_dir_url(HACKLOG_PACKAGE_LOADER ) . 'js/zeroclipboard';
	echo <<<EOT
	<script type="text/javascript">
		// where are we directing to
        var coupon_direct = "";
        jQuery(function($) {
		// ZeroClipboard Setup
          ZeroClipboard.setMoviePath( "{$plugin_dir_url}/ZeroClipboard.swf" );
          $('.couponcode').each(function() {
						var clip = new ZeroClipboard.Client();
						clip.setHandCursor(true);
						//clip.toolTip = 'click to copy & see the deal';
						clip.glue(this, this);
						var element = $(this);
						//alert(element);
						var code = element.find('a').text();
						//alert( code );
						clip.toolTip = element.find('a').attr('title');

						clip.addEventListener('mouseOver', function(client) {
							code = element.find('a').text();
							coupon_direct = element.find('a').attr("href");	
							//alert( coupon_direct );
						});	

 						clip.addEventListener( 'mouseDown', function(client) {
                                // set text to copy here
 								//alert(code);
                                //alert("mouse down"); 
 							client.setText( code );
                        } );
						clip.addEventListener('complete', function(client, text) {
							//alert("Copied text to clipboard: " + text );
							window.location = coupon_direct;
						});		
	
					});//end each
        });     		
</script>
EOT;
}

static function ihacklog_pkg_couponcode_scripts()
{
	wp_enqueue_script('highslide', plugin_dir_url(HACKLOG_PACKAGE_LOADER ) . 'js/zeroclipboard/ZeroClipboard.js' , array('jquery') , '1.0');	
}
}//end class
ihacklog_pkg_couponcode::run();