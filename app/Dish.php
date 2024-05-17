<?php

declare(strict_types=1);

namespace ShoMenu;

use ShoMenu\Enums\Designation;

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
     * @return array[]
     */
    public static function getRecommended(): array
    {
        return [];
    }
}
