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
        $this->registerCustomRestApiFields();

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
                    'side',
                    $box['priority'] ?? 'default',
                );
            }
        });
    }

    private function registerPostType(): void
    {
        register_post_type(self::POST_TYPE, [
            'labels' => [
                'name' => 'Страви',
                'singular_name' => 'Страва',
                'new_item_name' => 'Нова страва',
                'edit_item' => 'Редагувати страву',
                'update_item' => 'Оновити страву',
                'add_new_item' => 'Додати',
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
                'name' => 'Категорії',
                'singular_name' => 'Категорія',
                'new_item_name' => 'Нова категорія',
                'edit_item' => 'Редагувати',
                'update_item' => 'Оновити',
                'add_new_item' => 'Додати',
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
                'title' => 'Ціна',
                'slug' => 'price',
                'callback' => [$this, 'priceBoxMarkup'],
            ],
            [
                'id' => 'sho-menu-weight',
                'title' => 'Вага',
                'slug' => 'weight',
                'callback' => [$this, 'weightBoxMarkup'],
            ],
            [
                'id' => 'sho-menu-weight-unit',
                'title' => 'Одиниця ваги (мл, г...)',
                'slug' => 'weight-unit',
                'callback' => [$this, 'weightUnitBoxMarkup'],
            ],
            [
                'id' => 'sho-menu-info',
                'title' => '<span>ℹ️ Інформація</span>',
                'slug' => 'info',
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

    public function weightUnitBoxMarkup(WP_Post $post): void
    {
        wp_nonce_field('save_meta', 'sho_menu_nonce');

        $value = Dish::getMeta('weight_unit', $post->ID);
        $value = $value === '' ? ' г' : $value;

        echo "<input type='text' name='sho-menu-weight-unit' value='{$value}'>";
    }

    public function infoBoxMarkup(WP_Post $post): void
    {
        echo <<<HTML
        <b>Категорії:</b><br>
        <span>
            Коли ви вибираєте категорію, то автоматично вибирається батьківська категорія.
                Це означає, що у кожної позиції має бути мінімум дві категорії: основна і
                батьківська. Наприклад: "Супи" і "Перші страви".
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
            $weight_unit = sanitize_text_field($_POST['sho-menu-weight-unit'] ?? '');

            update_post_meta($post_id, '_sho_menu_price', $price);
            update_post_meta($post_id, '_sho_menu_weight', $weight);
            update_post_meta($post_id, '_sho_menu_weight_unit', $weight_unit);
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

    private function registerCustomRestApiFields(): void
    {
        add_action('rest_api_init', function () {
            register_rest_field('sho-menu-dishes', 'price', [
                'get_callback' => function ($post) {
                    $price = get_post_meta($post['id'], '_sho_menu_price', true);
                    return $price === false ? null : (int) $price;
                },
            ]);

            register_rest_field('sho-menu-dishes', 'weight', [
                'get_callback' => function ($post) {
                    $weight = get_post_meta($post['id'], '_sho_menu_weight', true);
                    return $weight === false ? null : (int) $weight;
                },
            ]);

            register_rest_field('sho-menu-dishes', 'weight_unit', [
                'get_callback' => function ($post) {
                    $unit = get_post_meta($post['id'], '_sho_menu_weight_unit', true);
                    return $unit === false ? null : $unit;
                },
            ]);
        });
    }
}
