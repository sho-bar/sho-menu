<?php

declare(strict_types=1);

namespace ShoMenu\PostTypes;

final class DishesPostType
{
    public function registerAll(): void
    {
        $this->disableGutenberg();

        add_action('init', function () {
            $this->registerTaxonomy();
            $this->registerPostType();
        });
    }

    private function registerPostType(): void
    {
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
            'show_in_rest' => true,
            'show_in_nav_menus' => true,
            'show_ui' => true,
            'capability_type' => 'post',
            'has_archive' => true,
            'menu_position' => 7,
            'menu_icon' => 'dashicons-food',
            'supports' => ['title', 'editor'],
            'taxonomies' => ['dish-category'],
            'supports' => ['title', 'editor', 'thumbnail'],
        ]);
    }

    private function registerTaxonomy(): void
    {
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
    }

    private function disableGutenberg(): void
    {
        add_filter('use_block_editor_for_post_type', function ($current_status, $post_type) {
            $disabled_post_types = ['dishes'];

            if (in_array($post_type, $disabled_post_types, true)) {
                return false;
            }

            return $current_status;
        }, 10, 2);
    }
}
