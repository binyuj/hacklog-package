<?php
/**
 * @filename soundmanager.php 
 * @encoding UTF-8 
 * @author 荒野无灯 <HuangYeWuDeng, admin@ihacklog.com> 
 * @link http://ihacklog.com 
 * @copyright Copyright (C) 2011 荒野无灯 
 * @license http://creativecommons.org/licenses/by-nc/2.5/
 * @datetime Aug 9, 2011  1:31:03 PM
 * @version 1.0
 * @Description
 * player from http://www.schillmania.com/projects/soundmanager2/
  */
	  
function soundmanager2_stylesheets()
{
		wp_enqueue_style('soundmanager2', plugin_dir_url(HACKLOG_PACKAGE_LOADER ) . 'js/soundmanager2/360player.min.css.php', array() , 'v297a-20111220');
}
add_action('wp_print_styles','soundmanager2_stylesheets');



add_action('wp_footer','soundmanager2_print_footer_js',20);
	
function soundmanager2_print_footer_js()
{
	$js_url = plugin_dir_url(HACKLOG_PACKAGE_LOADER ) . 'js/soundmanager2/' ;
	$swf_url = plugin_dir_url(HACKLOG_PACKAGE_LOADER ) . 'swf/soundmanager2';
	echo '
<!-- special IE-only canvas fix -->
<!--[if IE]><script type="text/javascript" src="' . $js_url . 'excanvas.min.js.php?ver=v297a-20111220"></script><![endif]-->

<!-- Apache-licensed animation library -->
<script type="text/javascript" src="' . $js_url . 'berniecode-animator.min.js.php?ver=v297a-20111220"></script>

<!-- the core stuff -->
<script type="text/javascript" src="' . $js_url . 'soundmanager2.min.js.php?ver=v297a-20111220"></script>
<script type="text/javascript" src="' . $js_url . '360player.min.js.php?ver=v297a-20111220"></script>

<script type="text/javascript">
soundManager.url = "' . $swf_url .'";
soundManager.flashVersion = 9;
soundManager.useFlashBlock = false;

soundManager.useFastPolling = true; // increased JS callback frequency, combined with useHighPerformance = true

threeSixtyPlayer.config.scaleFont = (navigator.userAgent.match(/msie/i)?false:true);
threeSixtyPlayer.config.showHMSTime = true;

// enable some spectrum stuffs

threeSixtyPlayer.config.useWaveformData = true;
threeSixtyPlayer.config.useEQData = true;

// enable this in SM2 as well, as needed

if (threeSixtyPlayer.config.useWaveformData) {
  soundManager.flash9Options.useWaveformData = true;
}
if (threeSixtyPlayer.config.useEQData) {
  soundManager.flash9Options.useEQData = true;
}
if (threeSixtyPlayer.config.usePeakData) {
  soundManager.flash9Options.usePeakData = true;
}

if (threeSixtyPlayer.config.useWaveformData || threeSixtyPlayer.flash9Options.useEQData || threeSixtyPlayer.flash9Options.usePeakData) {
  // even if HTML5 supports MP3, prefer flash so the visualization features can be used.
  soundManager.preferFlash = true;
}

// favicon is expensive CPU-wise, but can be used.
if (window.location.href.match(/hifi/i)) {
  threeSixtyPlayer.config.useFavIcon = true;
}

if (window.location.href.match(/html5/i)) {
  // for testing IE 9, etc.
  soundManager.useHTML5Audio = true;
}

</script>
';			
}

