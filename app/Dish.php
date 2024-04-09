<?php

declare(strict_types=1);

namespace ShoMenu;

final class Dish
{
    public static function getMeta(string $name, int $post_id)
    {
        return get_post_meta($post_id, "_sho_menu_{$name}", true);
    }
}
