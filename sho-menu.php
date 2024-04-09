<?php
/*
Plugin Name: Sho Menu
Author: Serhii Cho
Author URI: https://serhii.io
Description: Custom plugin for shobar.com.ua that adds a nice menu page
Version: 0.1
License: no
Text Domain: sho-menu
Tags: custom-menu
*/

defined('ABSPATH') || exit;
define('SHO_MENU_PATH', plugin_dir_path(__FILE__));
define('SHO_MENU_URL', plugin_dir_url(__FILE__));

require_once 'vendor/autoload.php';

(new \ShoMenu\Hook)->init();
