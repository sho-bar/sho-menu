<?php

declare(strict_types=1);

namespace ShoMenu\PostTypes;

use WP_Post;
use WP_Term;
use ShoMenu\Dish;

final class DishesPostType
{
    public const POST_TYPE = 'sho-menu-dishes';
    public const TAXONOMY = 'sho-menu-dish-category';

    /**
     * Meta boxes for the post type dishes
     *
     * @var array[]
     */
    private array $meta_boxes;

    public function __construct()
    {
        $this->meta_boxes = $this->setMetaBoxes();
    }

    public function register(): void
    {
        $this->savePostHook();
        $this->disableGutenberg();

        add_action('init', function () {
            $this->registerTaxonomy();
            $this->registerPostType();
        });

        add_action('add_meta_boxes', function () {
            foreach ($this->meta_boxes as $box) {
                add_meta_box(
                    $box['id'],
                    $box['title'],
                    $box['callback'],
                    self::POST_TYPE,
                    $box['context'],
                    $box['priority'] ?? 'default',
                );
            }
        });
    }

    private function registerPostType(): void
    {
        register_post_type(self::POST_TYPE, [
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
        register_taxonomy(self::TAXONOMY, self::POST_TYPE, [
            'labels' => [
                'name' => __('Категории', 'sho-menu'),
                'singular_name' => __('Категория', 'sho-menu'),
                'new_item_name' => __('Новая категория', 'sho-menu'),
                'edit_item' => __('Редактировать', 'sho-menu'),
                'update_item' => __('Обновить', 'sho-menu'),
                'add_new_item' => __('Добавить', 'sho-menu'),
            ],
            'public' => true,
            'show_in_rest' => true,
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
        ]);
    }

    private function disableGutenberg(): void
    {
        add_filter('use_block_editor_for_post_type', function ($current_status, $post_type) {
            $disabled_post_types = [self::POST_TYPE];

            if (in_array($post_type, $disabled_post_types, true)) {
                return false;
            }

            return $current_status;
        }, 10, 2);
    }

    /**
     * @return array[]
     */
    private function setMetaBoxes(): array
    {
        return [
            [
                'id' => 'sho-menu-price',
                'title' => __('Цена', 'sho-menu'),
                'slug' => 'price',
                'context' => 'side', // 'normal', 'advanced', 'side'
                'callback' => [$this, 'priceBoxMarkup'],
            ],
            [
                'id' => 'sho-menu-weight',
                'title' => __('Вес (г)', 'sho-menu'),
                'slug' => 'weight',
                'context' => 'side',
                'callback' => [$this, 'weightBoxMarkup'],
            ],
            [
                'id' => 'sho-menu-info',
                'title' => '<span>ℹ️ ' . __('Информация', 'sho-menu') . '</span>',
                'slug' => 'info',
                'context' => 'normal',
                'priority' => 'high',
                'callback' => [$this, 'infoBoxMarkup'],
            ],
        ];
    }

    public function priceBoxMarkup(WP_Post $post): void
    {
        wp_nonce_field('save_meta', 'sho_menu_nonce');

        $value = Dish::getMeta('price', $post->ID);
        $value = $value === '' ? 0 : $value;

        echo "<input type='number' name='sho-menu-price' value='{$value}'>";
    }

    public function weightBoxMarkup(WP_Post $post): void
    {
        wp_nonce_field('save_meta', 'sho_menu_nonce');

        $value = Dish::getMeta('weight', $post->ID);
        $value = $value === '' ? 0 : $value;

        echo "<input type='number' name='sho-menu-weight' value='{$value}'>";
    }

    public function infoBoxMarkup(WP_Post $post): void
    {
        echo <<<HTML
        <b>Категории:</b><br>
        <span>
            Когда вы выбираете категорию, то автоматически выбирается родительская категория.
            Это значит что у каждой позиции должно быть минимум две категории: основная и
            родительская. Например: "Супы" и "Первые блюда".
        </span>
        HTML;
    }

    public function savePostHook(): void
    {
        add_action('save_post', function (int $post_id): void {
            $nonce = $_POST['sho_menu_nonce'] ?? null;
            $verify_nonce = wp_verify_nonce($nonce, 'save_meta');
            $user_can_edit = current_user_can('edit_post', $post_id);

            if (!$nonce || !$verify_nonce || !$user_can_edit) {
                return;
            }

            $this->selectParentCategory($post_id);

            $price = sanitize_text_field($_POST['sho-menu-price'] ?? '');
            $weight = sanitize_text_field($_POST['sho-menu-weight'] ?? '');

            update_post_meta($post_id, '_sho_menu_price', $price);
            update_post_meta($post_id, '_sho_menu_weight', $weight);
        });
    }

    private function selectParentCategory(int $post_id): void
    {
        $ids = $this->getCategoryIdsFromRequest();

        foreach ($ids as $id) {
            /** @var WP_Term|null $term */
            $term = get_term_by('id', $id, self::TAXONOMY);

            $parent = $term->parent ?? 0;

            if ($parent === 0) {
                continue;
            }

            wp_set_post_terms($post_id, [$parent], self::TAXONOMY, true);
        }
    }

    /**
     * @return int[]
     */
    private function getCategoryIdsFromRequest(): array
    {
        $result = [];

        $categories = $_POST['tax_input']['sho-menu-dish-category'] ?? [];

        // First category doesn't count. We ignore it
        if (count($categories) <= 1) {
            return [];
        }

        // Skip the first category
        $categories = array_slice($categories, 1);

        foreach ($categories as $category) {
            $result[] = (int) $category;
        }

        return $result;
    }
}
