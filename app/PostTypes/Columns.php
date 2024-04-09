<?php

declare(strict_types=1);

namespace ShoMenu\PostTypes;

use ShoMenu\Dish;

final class Columns
{
    public function register(): void
    {
        $post_type = DishesPostType::POST_TYPE;

        /** What columns should be shown in admin panel */
        add_filter("manage_{$post_type}_posts_columns", function ($columns) {
            $columns['price'] = __('Цена', 'sho-menu');
            $columns['weight'] = __('Вес', 'sho-menu');

            return $columns;
        });

        // What data should be shown to each column in admin panel
        add_action("manage_{$post_type}_posts_custom_column", function ($column, $post_id) {
            $result = Dish::getMeta($column, $post_id);
            echo $result ? $result : ' - ';
        }, 10, 2);
    }
}
