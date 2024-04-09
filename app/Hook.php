<?php

declare(strict_types=1);

namespace ShoMenu;

final class Hook
{
    public function init(): void
    {
        dd(get_class_methods($this));
        foreach (get_class_methods($this) as $method) {
            if ($method === __FUNCTION__) {
                continue;
            }

            $this->{$method}();
        }
    }

    private static function registerAssets(): void
    {
        add_action('wp_enqueue_scripts', function (): void {
            $css_url = SHO_MENU_URL . 'assets/main.css';
            $css_path = SHO_MENU_PATH . 'assets/main.css';
            $js_url = SHO_MENU_URL . 'assets/main.js';
            $js_path = SHO_MENU_PATH . 'assets/main.js';

            wp_register_script('sho-menu-js', $js_url, [], Helper::fileVersion($js_path), true);

            wp_enqueue_style('sho-menu-style', $css_url, [], Helper::fileVersion($css_path));

            wp_localize_script('sho-menu-js', 'sho_menu_globals', [
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('nalognl_pdf_offer'),
            ]);

            wp_enqueue_script('sho-menu-js');
        });
    }
}
