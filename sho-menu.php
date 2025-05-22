<?php

declare(strict_types=1);

/*
Plugin Name: Sho Menu
Author: Serhii Cho
Author URI: https://serhii.io
Description: Custom plugin for shobar.com.ua that adds a nice menu page. Use the [sho_menu] shortcode to display the menu
Version: 1.43
License: no
Text Domain: sho-menu
Tags: custom-menu
*/

use ShoMenu\Hook;
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

defined('ABSPATH') || exit;
define('SHO_MENU_PATH', plugin_dir_path(__FILE__));
define('SHO_MENU_URL', plugin_dir_url(__FILE__));
define('SHO_MENU_ENTRY_FILE', __FILE__);

require_once __DIR__ . '/vendor/autoload.php';

$update_checker = PucFactory::buildUpdateChecker('https://github.com/sho-bar/sho-menu', __FILE__, 'sho-menu');
$update_checker->setBranch('master');
$update_checker->setAuthentication('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');

(new Hook())
    ->registerActivationHooks()
    ->registerAdminAssets()
    ->registerMenuAssets()
    ->registerShortcodes()
    ->registerCustomPostType();
