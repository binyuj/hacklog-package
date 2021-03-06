# Hacklog Package (WordPress 插件增强功能包)

## Plugin Info
* Contributors: **荒野无灯**
* Donate link: [http://ihacklog.com/donate](http://ihacklog.com/donate)
* Tags: **hacklog,package,misc**
* Requires at least: **3.3**
* Tested up to: **3.3.1**
* Stable tag: **v1.0.8**

## 下载
* [https://github.com/ihacklog/hacklog-package/downloads](https://github.com/ihacklog/hacklog-package/downloads)

## 概述

这是一个“半插件”，之所以这么说，是因为这个插件设计的目的是用于方便实现那些经常要用到的功能。如评论回复邮件通知、中文片断截取、评论者网站URL重定向等,然而，此插件并不打算设计后台配置选项。


**好处** : 使用此插件相比于直接在你当前主题的functions.php文件中添加相应代码的好处是，每次当你更换主题后，你**没有必要一遍又一遍地复制和粘贴代码到你所使用的主题的functions.php文件中**。真正做到，**一次添加，永久使用**。

你可以根据需要把用于实现功能的代码放置在本插件目录的**includes**目录下面，并编辑**packages.php**文件，添加相关信息。

你可以通过FTP或者直接在WP后台编辑**packages.php**文件.

## 如何添加新功能

首先，把你用于实现某功能的代码添加到一个新建文件中，如**demo.php**,这个文件要位于本插件目录下的**includes**目录下面。

然后，编辑**packages.php**文件，按照文件中已有条目的格式，增加一条，如：
<pre>
'demo.php'=>array('name'=>'演示如何添加代码','enable'=>1),
</pre>
。
**解释** - 第一个参数`demo.php`是文件名（linux/BSD主机区分大小写）,`name`对应的值为功能描述，`enable`表示是否启用，启用此功能。启用则值为1，不启用设置其值为0即可。

## package文件编码规范

* 所有放置于**includes**目录下面的文件中的全局变量、函数名、类名，都要以`ihacklog_pkg_`开头，此举是为防止因冲突而导致程序运行出错。
* js 文件均放入公共的 **js** 目录下面
* css 文件均放入公共的 **css** 目录下面
* 增加配置支持 - 如需要配置，可在文件开头处按如下格式增加配置,如：
<pre>
/*========= START CONFIGURE ========*/
$GLOBALS['ihacklog_pkg_foo'] = array(
	'key' => 'value',
);
/*=========  END  CONFIGURE ========*/
</pre>
然后在函数中声明 `global $ihacklog_pkg_foo;` 后引用配置即可。

## 如何在主题中手动引用css或js文件？

如这样：
<pre>
&lt;link rel="stylesheet" type="text/css" media="screen" href="&lt;?php echo plugins_url('hacklog-package');?&gt;/css/foo.css" /&gt;
</pre>

## 此插件自带的package中包含功能

有时间再列出来，可看下下面的截图。或者去includes目录下面看下，都有注释的。


更多信息请访问[插件主页](http://ihacklog.com/?p=5001 "plugin homepage") 获取关于插件的更多信息，使用技巧等.


## Installation

0x01. ensure that the plugin directory was named to `hacklog-package` .
0x01. Upload the whole fold `hacklog-remote-attachment` to the `/wp-content/plugins/` directory
0x02. Activate the plugin through the 'Plugins' menu in WordPress


## Screenshots

![后台截图](hacklog-package.png "Screenshot")



## Frequently Asked Questions
will be added here future


## Upgrade Notice




## Changelog

### 1.0.8
* rss latest udpates feauture seems have some problem. disable it.

### 1.0.4
* updated infinitescroll

### 1.0.2
* standardized the code,all gloabal var,class name,function prefixed with `ihacklog_pkg_`
* add configuration block to package files.
* updated the document.

### 1.0.1
* added atom publish and xmlrpc support in anti_spam.php

### 1.0.0
* released the first version.











