<?php
	
/* comment_mail_notify v1.0 by willin kan. (有勾選欄, 由訪客決定) */
function comment_mail_notify($comment_id) 
{
/********************配置开始********************/	
$admin_notify = 0; // admin 要不要收回覆通知 ( '1'=要 ; '0'=不要 )
//$admin_email = get_bloginfo ('admin_email'); // $admin_email 可改為你指定的 e-mail.
$admin_email = get_option('admin_email'); //get_bloginfo('admin_email') 内部实际上调用的是get_option('admin_email')
/********************配置结束********************/	

$comment = get_comment($comment_id);
$comment_author_email = trim($comment->comment_author_email);
$parent_id = $comment->comment_parent ? $comment->comment_parent : '';
global $wpdb;
if ($wpdb->query("Describe {$wpdb->comments} comment_mail_notify") == '')
$wpdb->query("ALTER TABLE {$wpdb->comments} ADD COLUMN comment_mail_notify TINYINT NOT NULL DEFAULT 0;");
if (($comment_author_email != $admin_email && isset($_POST['comment_mail_notify'])) || ($comment_author_email == $admin_email && $admin_notify == 1 ))
$wpdb->query("UPDATE {$wpdb->comments} SET comment_mail_notify=1 WHERE comment_ID=$comment_id");
$notify = $parent_id ? get_comment($parent_id)->comment_mail_notify : 0;
$spam_confirmed = $comment->comment_approved;
if ($parent_id != '' && $spam_confirmed != 'spam' && $notify == 1 ) {
$wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['HTTP_HOST'])); // e-mail 發出點, no-reply 可改為可用的 e-mail.
$to = trim(get_comment($parent_id)->comment_author_email);
$subject = '您在 [' . get_option("blogname") . '] 的留言有了新回复';
$message = '
<div style="background-color:#eef2fa; border:1px solid #d8e3e8; color:#111; padding:0 15px; -moz-border-radius:5px; -webkit-border-radius:5px; -khtml-border-radius:5px;">
<p>' . trim(get_comment($parent_id)->comment_author) . ', 您好!</p>
<p>您曾在《' . get_the_title($comment->comment_post_ID) . '》的留言:<br />
' . trim(get_comment($parent_id)->comment_content) . '</p>
<p>' . trim($comment->comment_author) . ' 给您的回复:<br />
' . trim($comment->comment_content) . '<br /></p>
<p>您可以点击 <a href="' . htmlspecialchars(get_comment_link($parent_id)) . '"> 查看完整回复內容</a></p>
<p>欢迎再度光临 <a href="' . get_option('home') . '">' . get_option('blogname') . '</a></p>
<p>(此邮件由系统自动发出, 请勿回复.)</p>
</div>';
$from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
$headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
@wp_mail( $to, $subject, $message, $headers );
// $ret = wp_mail( $to, $subject, $message, $headers ); // for testing
//echo 'mail to ', $to, '<br/> ' , $subject, $message; 
//var_dump($ret);

}


//有评论被发表时，发信到139邮箱
##########################################################
/*
if($admin_email != $comment_author_email )
{
$to='USER-NAME@139.com';
$from = "From: \"" . get_option('blogname') . "\" <$admin_email>";
$subject = trim($comment->comment_author) .'在 [' . get_the_title($comment->comment_post_ID) . '] 留言:';
$message =trim($comment->comment_content);
$headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
@wp_mail( $to, $subject, $message, $headers );
}
*/
##########################################################


}
add_action('comment_post', 'comment_mail_notify');

/* 自動加勾選欄 */
function add_checkbox() {
	echo '<input style="margin-left:20px;width:20px;" type="checkbox" name="comment_mail_notify" id="comment_mail_notify" value="comment_mail_notify" checked="checked" /><label for="comment_mail_notify">有人回复时邮件通知我</label>';
}
add_action('comment_form', 'add_checkbox');
// -- END ---------------------------------------- 