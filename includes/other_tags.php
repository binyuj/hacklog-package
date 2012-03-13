<?php
	if (strpos($_SERVER['REQUEST_URI'], 'post.php') || strpos($_SERVER['REQUEST_URI'], 'post-new.php') || strpos($_SERVER['REQUEST_URI'], 'page-new.php') || strpos($_SERVER['REQUEST_URI'], 'page.php')) 
{
		function add_smiley_css()
		{
		echo '
		<style type="text/css">
		#smiley img {
		padding-left:5px;
		}	
		</style>	
		';
			
		}
		
		add_action('admin_head','add_smiley_css');
		function ihacklog_add_other_tags()
		{
		echo <<<EOT
		<script type="text/javascript">
		<!--
			
		function grin(tag) 
		{
    	var myField;
    	tag = ' ' + tag + ' ';
        if (document.getElementById('content') && document.getElementById('content').style.display != 'none' && document.getElementById('content').type == 'textarea') 
        {
    		myField = document.getElementById('content');
    
    		if (document.selection) 
    		{
    		myField.focus();
    		sel = document.selection.createRange();
    		sel.text = tag;
    		myField.focus();
    		}
    		else 
    			if (myField.selectionStart || myField.selectionStart == '0') 
    			{
    			var startPos = myField.selectionStart;
    			var endPos = myField.selectionEnd;
    			var cursorPos = endPos;
    			myField.value = myField.value.substring(0, startPos) + tag + myField.value.substring(endPos, myField.value.length);
    			cursorPos += tag.length;
    			myField.focus();
    			myField.selectionStart = cursorPos;
    			myField.selectionEnd = cursorPos;
    			}
    			else 
    			{
    			myField.value += tag;
    			myField.focus();
    			}
    	} 
    	else 
    	{
    	tinyMCE.execCommand('mceInsertContent', false, tag);
    	}
    }
    var smiley='<div id="smiley"><a href="javascript:grin(\':?:\')" title="疑惑"<img src="../wp-includes/images/smilies/icon_question.gif" alt="疑惑" /></a><a href="javascript:grin(\':razz:\')" title="冷笑"><img src="../wp-includes/images/smilies/icon_razz.gif" alt="冷笑" /></a><a href="javascript:grin(\':sad:\')" title="伤心"><img src="../wp-includes/images/smilies/icon_sad.gif" alt="伤心" /></a><a href="javascript:grin(\':evil:\')" title="邪恶"><img src="../wp-includes/images/smilies/icon_evil.gif" alt="邪恶" /></a><a href="javascript:grin(\':!:\')" title="感叹"><img src="../wp-includes/images/smilies/icon_exclaim.gif" alt="感叹" /></a><a href="javascript:grin(\':smile:\')" title="微笑"><img src="../wp-includes/images/smilies/icon_smile.gif" alt="微笑" /></a><a href="javascript:grin(\':oops:\')" title="红脸"><img src="../wp-includes/images/smilies/icon_redface.gif" alt="红脸" /></a><a href="javascript:grin(\':grin:\')" title="咧嘴笑"><img src="../wp-includes/images/smilies/icon_biggrin.gif" alt="咧嘴笑" /></a><a href="javascript:grin(\':eek:\')" title="吃惊"><img src="../wp-includes/images/smilies/icon_surprised.gif" alt="吃惊" /></a><a href="javascript:grin(\':shock:\')" title="惊讶" ><img src="../wp-includes/images/smilies/icon_eek.gif" alt="惊讶" /></a><a href="javascript:grin(\':???:\')" title="困惑"><img src="../wp-includes/images/smilies/icon_confused.gif" alt="困惑" /></a><a href="javascript:grin(\':cool:\')" title="耍酷"><img src="../wp-includes/images/smilies/icon_cool.gif" alt="耍酷" /></a><a href="javascript:grin(\':lol:\')" title="大笑"><img src="../wp-includes/images/smilies/icon_lol.gif" alt="大笑" /></a><a href="javascript:grin(\':mad:\')" title="抓狂"><img src="../wp-includes/images/smilies/icon_mad.gif" alt="抓狂" /></a><a href="javascript:grin(\':twisted:\')" title="痛苦"><img src="../wp-includes/images/smilies/icon_twisted.gif" alt="痛苦" /></a><a href="javascript:grin(\':roll:\')" title="转眼珠"><img src="../wp-includes/images/smilies/icon_rolleyes.gif" alt="转眼珠" /></a><a href="javascript:grin(\':wink:\')" title="眨眼"><img src="../wp-includes/images/smilies/icon_wink.gif" alt="眨眼" /></a><a href="javascript:grin(\':idea:\')" title="好主意"><img src="../wp-includes/images/smilies/icon_idea.gif" alt="好主意" /></a><a href="javascript:grin(\':arrow:\')" title=""><img src="../wp-includes/images/smilies/icon_arrow.gif" alt="" /></a><a href="javascript:grin(\':neutral:\')" title="自然"><img src="../wp-includes/images/smilies/icon_neutral.gif" alt="自然" /></a><a href="javascript:grin(\':cry:\')" title="哭"><img src="../wp-includes/images/smilies/icon_cry.gif" alt="哭" /></a><a href="javascript:grin(\':mrgreen:\')" title="绿脸先生"><img src="../wp-includes/images/smilies/icon_mrgreen.gif" alt="绿脸先生" /></a></div>';
	jQuery('#wp-content-editor-container').before(smiley);
     		//override the b-quote,it's value was too long for me
    	edButtons[40] = new QTags.TagButton('block','bq','\\n\\n<blockquote>','</blockquote>\\n\\n','q');
    	jQuery('#qt_content_close').val('Close');
    	// id, display, arg1(callback), arg2, access_key, title, priority, instance
    	QTags.addButton('audio' ,'audio' ,hacklogInsertAudio ,'','a', 'Insert audio');
    	QTags.addButton('media' ,'media' ,hacklogInsertMedia ,'','m', 'Insert media');
    	function hacklogInsertAudio() 
		{
				var U=prompt('请输入mp3 URL','http://');
				if(!U)
					return false;
				var audio_url = jQuery.trim(U);
				if(audio_url == null || audio_url == "" || audio_url =='http://') 
				{
				alert('请输入正确的mp3 URL!');
				return false;
				} 
				else 
				{
					QTags.insertContent("[audio]" + audio_url + "[/audio]");
				}
		}
			
		function hacklogInsertMedia() 
		{
				var U = prompt('Enter URL' , 'http://');
				U = jQuery.trim(U);
				if(!U)
					return false;
				var T = prompt('Enter type' ,'mp3');
				var W = prompt('Enter width' , '500');
				var H = prompt('Enter height' , '400');
				var A = prompt('auto autostart?' , '0');
				var theTag = '[media type=' + T + ' width=' + W + ' height=' + H + ' autostart=' + A +']'+U+'[/media]';
				QTags.insertContent(theTag);
		}

</script>
EOT;
		}
		
	add_action('admin_footer','ihacklog_add_other_tags',99);
	
	//add custom edButton
		function my_custom_quicktags() 
	{
		$suffix_js = SIMPLEDARK_DEBUG ? '.js' : '.min.js.php';
		wp_enqueue_script(
			'my_custom_quicktags',
			plugin_dir_url(HACKLOG_PACKAGE_LOADER ) . 'includes/my-custom-quicktags' . $suffix_js,
			array('quicktags')
		);
	}
	add_action('admin_print_scripts', 'my_custom_quicktags');
	
}
		