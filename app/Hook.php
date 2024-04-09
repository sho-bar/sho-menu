<?php

declare(strict_types=1);

namespace ShoMenu;

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
                'nonce' => wp_create_nonce('nalognl_pdf_offer'),
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
        add_action('init', function (): void {
            register_post_type('dishes', [
                'labels' => [
                    'name' => __('Блюда', 'sho-menu'),
                    'singular_name' => __('Блюдо', 'sho-menu'),
                    'new_item_name' => __('Новое блюдо', 'sho-menu'),
                    'edit_item' => __('Редактировать блюдо', 'sho-menu'),
                    'update_item' => __('Обновить блюдо', 'sho-menu'),
                    'add_new_item' => __('Добавить', 'sho-menu'),
                ],
                'public' => true,
                'has_archive' => true,
                'menu_icon' => 'dashicons-food',
                'supports' => ['title', 'editor'],
                'taxonomies' => ['dish-category'],
                'supports' => ['title', 'editor', 'thumbnail'],
            ]);
        });

        return $this;
    }

    public function registerDishCategoryTaxonomy(): self
    {
        add_action('init', function (): void {
            register_taxonomy('dish-category', 'dishes', [
                'labels' => [
                    'name' => __('Категории', 'sho-menu'),
                    'singular_name' => __('Категория', 'sho-menu'),
                    'new_item_name' => __('Новая категория', 'sho-menu'),
                    'edit_item' => __('Редактировать', 'sho-menu'),
                    'update_item' => __('Обновить', 'sho-menu'),
                    'add_new_item' => __('Добавить', 'sho-menu'),
                ],
                'public' => true,
                'hierarchical' => true,
                'show_ui' => true,
                'show_admin_column' => true,
                'query_var' => true,
            ]);
        });

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
