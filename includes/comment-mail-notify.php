<?php
/*========= START CONFIGURE ========*/
$GLOBALS['ihacklog_pkg_comment_mail_notify'] = array(
'139_email' => '', //USER-NAME@139.com ,用于有留言时短信通知
'admin_notify'=> FALSE, //是否向admin发送回复通知 

);
/*=========  END  CONFIGURE ========*/

add_action('comment_post', 'ihacklog_pkg_comment_mail_notify');
add_action('comment_form', 'ihacklog_pkg_add_comment_mail_notify_checkbox');

/* comment_mail_notify v1.0 by willin kan. (有勾選欄, 由訪客決定) */
function ihacklog_pkg_comment_mail_notify($comment_id) 
{
global $ihacklog_pkg_comment_mail_notify;
/********************配置开始********************/	
$admin_notify = $ihacklog_pkg_comment_mail_notify['admin_notify']; // admin 要不要收回覆通知
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
$subject = '您在《' . get_the_title($comment->comment_post_ID) . '》-- [' . get_option("blogname") . '] 的评论有了回复';
$message = '
<div style="border: #666 1px solid; background-color: #fff; margin: 10px auto 0px; width: 702px; font-family: 微软雅黑, arial; color: #111; font-size: 12px;-moz-border-radius: 8px; -webkit-border-radius: 8px; -khtml-border-radius: 8px; border-radius: 8px;">
    <div style="width: 100%; background: #666666; height: 60px; color: white; -moz-border-radius: 6px 6px 0 0; -webkit-border-radius: 6px 6px 0 0; -khtml-border-radius: 6px 6px 0 0; border-radius: 6px 6px 0 0"><span style="line-height: 60px; height: 60px; margin-left: 30px; font-size: 12px">您在 <a style="color: #00bbff; text-decoration: none" href="' . get_option('home') . '"  target="_blank">' . get_option('blogname') . '</a> 上的留言有回复啦！</span> 
    </div>
    <div style="margin: 0px auto; width: 90%">
        <p><strong>' . trim(get_comment($parent_id)->comment_author) . '</strong>, 您好!</p>
        <p>您曾在 [' . get_option("blogname") . '] 的文章 《' . get_the_title($comment->comment_post_ID) . '》 上的发表评论:</p>
        <p style="border-radius: 4px; padding: 20px; border: #ddd 1px solid; background-color: #eee; margin: 15px 0px;">'. trim(get_comment($parent_id)->comment_content) . '</p>
        <p><strong>' . trim($comment->comment_author) . ' </strong>给您的回复如下:
            <br />
            <p style="border-radius: 4px; padding: 20px; border: #ddd 1px solid; background-color: #eee; margin: 15px 0px;">' . trim($comment->comment_content) . '
                <br />
            </p>
            <p>您可以点击 <a style="color: #00bbff; text-decoration: none" href="' . htmlspecialchars(get_comment_link($parent_id)) . '" target="_blank">查看回复完整內容</a>
            </p>
            <p>欢迎再度光临 <a style="color: #00bbff; text-decoration: none" href="' . get_option('home') . '" target="_blank">' . get_option('blogname') . '</a>
            </p>
            <p>(此邮件由系统自动发送，请勿回复.)</p>
    </div>
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

if($admin_email != $comment_author_email )
{
$to_139_email= $ihacklog_pkg_comment_mail_notify['139_email'];
if( !empty($to_139_email) )
{
$from = "From: \"" . get_option('blogname') . "\" <$admin_email>";
$subject = trim($comment->comment_author) .'在 [' . get_the_title($comment->comment_post_ID) . '] 留言:';
$message =trim($comment->comment_content);
$headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
@wp_mail( $to_139_email, $subject, $message, $headers );
}
}

##########################################################


}

/* 自動加勾選欄 */
function ihacklog_pkg_add_comment_mail_notify_checkbox() {
	echo '<input style="margin-left:20px;width:20px;" type="checkbox" name="comment_mail_notify" id="comment_mail_notify" value="comment_mail_notify" checked="checked" /><label for="comment_mail_notify">有人回复时邮件通知我</label>';
}

// -- END ---------------------------------------- 
