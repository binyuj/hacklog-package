<?php
/*
 Plugin Name: Hacklog Package
 Plugin URI: http://ihacklog.com/?p=5001
 Description: WordPress 插件增强功能包
 Version: 1.0.1
 Author: <a href="http://ihacklog.com/">荒野无灯</a>
 Author URI: http://ihacklog.com/
 */

/**
 * $Id: loader.php 482701 2011-12-31 05:54:57Z ihacklog $
 * $Revision: 482701 $
 * $Date: 2011-12-31 13:54:57 +0800 (周六, 31 十二月 2011) $
 * @package Hacklog Remote Attachment
 * @encoding UTF-8
 * @author 荒野无灯 <HuangYeWuDeng>
 * @link http://ihacklog.com
 * @copyright Copyright (C) 2011 荒野无灯
 * @license http://www.gnu.org/licenses/
 */

/*
 Copyright 2011  荒野无灯

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

define('HACKLOG_PACKAGE_LOADER', __FILE__);
require plugin_dir_path(__FILE__) . '/hacklog_package.class.php';
hacklog_package::init();
