// Author : 荒野无灯
// URL: http://www.ihacklog.com

/*
<script type="text/javascript" charset="utf-8">
var useronline={'timeout':1000*30,'ajax_url':'./include/msn.php'};
</script>
*/
// Variables
useronline.timeout = parseInt(useronline.timeout);
//alert(useronline.timeout );
// UserOnline JavaScript Init
function useronline_init() {
	if(jQuery('#msnonlineinfo').length) {
		get_useronline_count();
		setInterval("get_useronline_count()", useronline.timeout);
	}
}

// Get UserOnline Count
function get_useronline_count() {
	jQuery.ajax({type: 'GET', url: useronline.ajax_url, data: 'act=getonlinestatus', cache: false, success: function (data) { jQuery('#msnonlineinfo').html(data);}});
}

// Init UserOnline
jQuery(document).ready(function($) 
{
	stylesheet_uri = $("link[rel='stylesheet']:eq(0)").attr("href").split("/");
	stylesheet_uri.length--;
	img = "<img src='"+stylesheet_uri.join("/") + "/msn/ajax-loader.gif"+"' alt='Loading' />";

jQuery('#msnonlineinfo').html(img);
useronline_init();
 });


function open_msn_chat(url)
{
	stylesheet_uri = jQuery("link[rel='stylesheet']:eq(0)").attr("href").split("/");
	stylesheet_uri.length--;
	
   if ( self.name == '' ) {
       var title = '在线交流 &raquo; Hacklog &raquo; 荒野无灯weblog';
      }
    else {
       var title = '在线交流 ' + self.name;
      }
    _hacklog_msn = window.open("",title.value,"width=640,height=540,toolbar=no,menubar=no,location=no,scrollbars=yes");
 /*   var iframe = document.createElement("iframe");
    iframe.src = url;
    iframe.height="100%";
    iframe.width="100%";
    iframe.scrolling="no";
    iframe.border="0";
    iframe.frameborder="0";
   */
    //<iframe name="FileListWindow" id='videoList' height="410" width="100%" scrolling="no" border="0" frameborder="0" src="livetv_chlist.php?num=10"></iframe>

 /*   if (iframe.attachEvent)
    {    
    iframe.attachEvent("onload", function(){ });} 
    else 
    {    
    	iframe.onload = function(){  _hacklog_msn.document.write("<img src='"+stylesheet_uri.join("/")+"/msn/ajax-loader.gif' alt='Loading' />");  };
    }
  */
 	//_hacklog_msn.document.write("<img src='"+stylesheet_uri.join("/")+"/msn/ajax-loader.gif' alt='Loading' />");
 	_hacklog_msn.document.write('<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>'+title+'</title></head><body><iframe name="_msn" id="_msn" height="100%" width="100%" scrolling="no" border="0" frameborder="0" src="'+url+'"></iframe></body></html>');
   // 	_hacklog_msn.document.title=title;
    //_hacklog_msn.document.body.appendChild(iframe);
   _hacklog_msn.document.body.style.margin=0;
    _hacklog_msn.document.body.style.padding=0;
    _hacklog_msn.document.close();
}