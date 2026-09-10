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

    final public function toggleDishAvailability(): void
    {
        $dish_id = (int) ($_POST['dish_id'] ?? 0);

        if ($dish_id <= 0 || !current_user_can('edit_post', $dish_id)) {
            wp_send_json_error(['message' => 'Forbidden'], 403);
        }

        $designations = Dish::getDesignations($dish_id);
        $is_missing = in_array(Designation::ENDED, $designations, true);

        if ($is_missing) {
            $designations = array_values(array_filter($designations, function (Designation $des) {
                return $des !== Designation::ENDED;
            }));
        } else {
            $designations[] = Designation::ENDED;
        }

        Dish::setDesignations($dish_id, $designations);

        wp_send_json_success(['dish_id' => $dish_id, 'is_missing' => !$is_missing]);
    }
}
