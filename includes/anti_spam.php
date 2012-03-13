<?php
/**
 * @filename ihacklog-error-alert.php 
 * @encoding UTF-8 
 * @author 荒野无灯 <HuangYeWuDeng> , Willin Kan (http://kan.willin.org)
 * @link http://ihacklog.com 
 * @copyright Copyright (C) 2012 荒野无灯 
 * @license http://www.gnu.org/licenses/
 * @Description 小墙 Anti-Spam v1.9 modified by 荒野无灯。
 * <<小牆>> Anti-Spam v1.9 by Willin Kan.
 */

if (! defined ( 'ABSPATH' )) {
	die ( 'What are you doing?' );
}

class ihacklog_pkg_anti_spam
{

/*========= START CONFIGURE ========*/
	private $anti_spam_field = '';
	private $no_gravatar_die = TRUE;
	private $no_chinese_die = TRUE;
	private $admin_comment_author = '荒野无灯';
/*=========  END  CONFIGURE ========*/

	public function __construct()
	{
		add_action('init', array($this, 'init'));
	}

	public function init()
	{
		// 非中文語系
		/*
		  if ( stripos($_SERVER['HTTP_ACCEPT_LANGUAGE'], 'zh') === false )
		  {
		  add_filter( 'comments_open', create_function('', "return false;") ); // 關閉評論
		  }
		 */

		//current_user_can('manage_options') 
		//remember that is_usr_logged_in is a pluggable function !
		if (!is_user_logged_in() && !defined('XMLRPC_REQUEST') && !defined('APP_REQUEST'))
		{
			$this->set_anti_spam_field();
			//add_filter('anti_spam_field',array($this, 'get_anti_spam_field'), 1,1);	   
			add_action('comment_form_field_comment', array($this, 'w_tb'), 1);
			add_action('pre_comment_on_post', array($this, 'gate'), 1);
			add_action('preprocess_comment', array($this, 'sink'), 1);
		}
	}

	function my_die($str)
	{
		if (defined('DOING_AJAX') && DOING_AJAX)
		{
			header('HTTP/1.0 500 Internal Server Error');
			header('Content-Type: text/plain');
			die($str);
		}
		else
		{
			wp_die(__($str));
		}
	}

	function check_gravatar($comment_author_email='', $die = FALSE)
	{
		//头像检测
		if (get_option('require_name_email'))
		{
			$headers = @get_headers('http://1.gravatar.com/avatar/' . md5(strtolower($comment_author_email)) . '?d=404');
			//var_dump($headers);
			if (strpos($headers[0], '404') !== FALSE)
			{
				if ($die)
				{
					$this->my_die(__('Error: gravatar does not exsists (check your email address).'));
				}
				else
				{
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	function check_lang($comment_content='')
	{
		//中文检测
		//$pattern = "/[\x7f-\xff]/";  
		$pattern = "/[\x{4e00}-\x{9fa5}]+/u";
		//$pattern = '/[一-龥]/u';
		if ( !preg_match($pattern, $comment_content) )
		{
			// 老外发spam?
			if ($this->no_chinese_die)
			{
				$this->my_die(__('Sorry,No spam comment!Are you a spam robot?If not,please say some Chinese words,OK?为了防止垃圾留言，本站检测留言内容是否包含中文。'));
			}
			else
			{
				return FALSE;
			}
		}
		return TRUE;
	}

	function add_black($comment)
	{
		if (empty($comment['comment_author_url']))
			return;
		$comment_author_url = $comment['comment_author_url'];
		if ($pos = strpos($comment_author_url, '//'))
		{
			$comment_author_url = substr($comment_author_url, $pos + 2);
		}
		if ($pos = strpos($comment_author_url, '/'))
		{
			$comment_author_url = substr($comment_author_url, 0, $pos);
		}
		$comment_author_url = strtr($comment_author_url, array('www.' => ''));
		if (!wp_blacklist_check('', '', $comment_author_url, '', '', ''))
		{
			update_option('blacklist_keys', $comment_author_url . "\n" . get_option('blacklist_keys'));
		}
	}

	function set_anti_spam_field($field='fuck_spam_name_0x')
	{
		//在启用chromeframe的情况下，第一次的ua与刷新页面后的ua不同。第一次的ua如：
		//Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; Tablet PC 2.0; .NET4.0C; .NET4.0E; chromeframe/16.0.912.75)
		//刷新页面后的ua如：
		//Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; Tablet PC 2.0; .NET4.0C; .NET4.0E)
		$ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		$ua = explode(' ', $ua);
		$ua = is_array($ua) ? $ua[0] : '';
		$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
		$the_key = $ua . $ip;
		$the_key = empty($the_key) ? gmdate('Ym', time()) : $the_key;
		$the_key .= __FILE__;
//			var_dump($the_key);
		$the_hash = md5($the_key);
		$this->anti_spam_field = $field . substr($the_hash, 0, 16);
		return $this->anti_spam_field;
	}

	function get_anti_spam_field()
	{
		return $this->anti_spam_field;
	}

	public function check_fakeadmin($comment)
	{
		$admin_email = get_option('admin_email');
		if (strtolower($comment['comment_author_email']) == strtolower($admin_email) || $comment['comment_author'] == $this->admin_comment_author)
		{
			$this->my_die('你想干嘛？');
		}
	}

	//設欄位
	function w_tb($input)
	{
		return preg_replace("#textarea(.*?)name=([\"\'])comment([\"\'])(.+)/textarea>#", "textarea$1name=$2" . $this->get_anti_spam_field() . "$3$4/textarea><textarea name=\"comment\" cols=\"60\" rows=\"4\" style=\"display:none\"></textarea>", $input);
	}

	//檢查
	function gate()
	{
		(!empty($_POST[$this->get_anti_spam_field()]) && empty($_POST['comment']) ) ? $_POST['comment'] = $_POST[$this->get_anti_spam_field()] : $_POST['spam_confirmed'] = 1;
	}

	//處理
	function sink($comment)
	{
		$comment['comment_author'] = str_replace(array('Your Name', 'YourName'), '', $comment['comment_author']);
		$comment['comment_author'] = str_replace(array('SEO', 'seo'), 's*o', $comment['comment_author']);
		$comment['comment_author_url'] = str_replace(array('Your URL', 'YourURL', 'http://YourURL', 'http://Your URL'), '', $comment['comment_author_url']);
		if (empty($comment['comment_author']))
		{
			$this->my_die('name required!');
		}

		$this->check_fakeadmin($comment);

		if (!empty($_POST['spam_confirmed']))
		{
			//机器提交，直接擋掉, 將 die(); 前面兩斜線刪除即可.
			$this->my_die('Hello World!');
		}
		//下面检测人肉提交的
		//无头像,方法1,直接拦截
		if ($this->no_gravatar_die)
		{
			$this->check_gravatar($comment['comment_author_email'], TRUE);
		}
		else
		{
			//无头像,方法2，列入待审,標記為spam, 留在資料庫檢查是否誤判.
			if ($this->check_gravatar($comment['comment_author_email'], FALSE))
			{
				add_filter('pre_comment_approved', create_function('', 'return "spam";'));
				$comment['comment_content'] = "[ 无头像，程序判断这可能是Spam! ]\n" . $comment['comment_content'];
			}
		}
		//纯英文？直接干掉
		$this->check_lang($comment['comment_content']);

		return $comment;
	}

}
//run
new ihacklog_pkg_anti_spam();
// -- END ----------------------------------------
