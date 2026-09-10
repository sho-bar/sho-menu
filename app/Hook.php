<?php

declare(strict_types=1);

namespace ShoMenu;

use ShoMenu\PostTypes\Columns;
use ShoMenu\PostTypes\DishesPostType;
use ShoMenu\Ajax\Ajax;

final class Hook
{
    public function registerMenuAssets(): self
    {
        add_action('wp_enqueue_scripts', function (): void {
            $css_url = SHO_MENU_URL . 'assets/main.css';
            $css_path = SHO_MENU_PATH . 'assets/main.css';
            $js_url = SHO_MENU_URL . 'assets/main.js';
            $js_path = SHO_MENU_PATH . 'assets/main.js';

            wp_register_script('sho-menu-js', $js_url, [], Helper::fileVersion($js_path), true);
            wp_register_style('sho-menu-style', $css_url, [], Helper::fileVersion($css_path));

            wp_localize_script('sho-menu-js', 'shoMenuGlobals', [
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('sho_menu_nonce'),
                'mainUrl' => SHO_MENU_URL,
                'isAuth' => is_user_logged_in(),
            ]);
        });

        return $this;
    }

    public function registerAdminAssets(): self
    {
        add_action('admin_enqueue_scripts', function (): void {
            $css_url = SHO_MENU_URL . 'assets/admin.css';
            $css_path = SHO_MENU_PATH . 'assets/admin.css';
            $js_url = SHO_MENU_URL . 'assets/admin.js';
            $js_path = SHO_MENU_PATH . 'assets/admin.js';

            wp_enqueue_script('sho-menu-admin-js', $js_url, [], Helper::fileVersion($js_path), true);
            wp_enqueue_style('sho-menu-admin-style', $css_url, [], Helper::fileVersion($css_path));

            wp_localize_script('sho-menu-admin-js', 'shoMenuGlobals', [
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('sho_menu_nonce'),
            ]);
        });

        return $this;
    }

    public function registerShortcodes(): self
    {
        add_shortcode('sho_menu', function (): string {
            wp_enqueue_script('sho-menu-js');
            wp_enqueue_style('sho-menu-style');

            return '<div id="sho-menu"><main-menu /></div>';
        });

        return $this;
    }

    public function registerCustomPostType(): self
    {
        (new DishesPostType())->register();
        (new Columns())->register();

        return $this;
    }

    public function registerActivationHooks(): self
    {
        register_activation_hook(SHO_MENU_ENTRY_FILE, function (): void {
            flush_rewrite_rules();
        });

        register_deactivation_hook(SHO_MENU_ENTRY_FILE, function (): void {
            flush_rewrite_rules();
        });

        return $this;
    }

    public function registerAjax(): self
    {
        add_action('wp_ajax_sho_menu_toggle_dish_availability', function () {
            (new Ajax())->toggleDishAvailability();
        });

        return $this;
    }
}
