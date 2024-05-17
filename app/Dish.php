<?php

declare(strict_types=1);

namespace ShoMenu;

use ShoMenu\Enums\Designation;
use ShoMenu\PostTypes\DishesPostType;
use WP_Post;

final class Dish
{
    public static function getMeta(string $name, int $post_id)
    {
        return get_post_meta($post_id, "_sho_menu_{$name}", true);
    }

    /**
     * @return array<int, Designation>
     */
    public static function getDesignations(int $post_id): array
    {
        $meta_value = self::getMeta('designations', $post_id);
        $meta_values = explode(',', $meta_value);

        $result = [];

        foreach ($meta_values as $value) {
            $designation = Designation::tryFrom($value);

            if ($designation === null) {
                continue;
            }

            $result[] = $designation;
        }

        return $result;
    }

    /**
     * @return WP_Post[]
     */
    public static function getRecommended(int $post_id): array
    {
        $meta_value = self::getMeta('recommended_dishes', $post_id);
        $ids = explode(',', $meta_value);

        $posts = get_posts([
            'post_type' => DishesPostType::POST_TYPE,
            'post__in' => $ids,
        ]);

        $result = [];

        foreach ($posts as $post) {
            $result[] = [
                'id' => $post->ID,
                'title' => $post->post_title,
                'slug' => $post->post_name,
            ];
        }

        unset($posts);

        return $result;
    }
}
