<?php

declare(strict_types=1);

namespace ShoMenu\Ajax;

use ShoMenu\Dish;
use ShoMenu\Enums\Designation;

final class Ajax
{
    public function __construct()
    {
        check_ajax_referer('sho_menu_nonce');
    }

    public function __destruct()
    {
        wp_reset_postdata();
    }

    public static function createEntry(string $ajax_method_name, callable $callback): void
    {
        add_action("wp_ajax_$ajax_method_name", $callback);
        add_action("wp_ajax_nopriv_$ajax_method_name", $callback);
    }

    final public function toggleDishAvailability(): int
    {
        $dish_id = (int) $_POST['dish_id'];

        $designations = Dish::getDesignations($dish_id);
        $is_missing = in_array(Designation::ENDED, $designations, true);

        var_dump($is_missing);

        return 433;
    }
}
