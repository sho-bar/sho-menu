<?php

declare(strict_types=1);

namespace ShoMenu;

use ShoMenu\PostTypes\Columns;
use ShoMenu\PostTypes\DishesPostType;

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
            wp_register_style('sho-menu-style', $css_url, [], Helper::fileVersion($css_path), true);

            wp_localize_script('sho-menu-js', 'sho_menu_globals', [
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
}
