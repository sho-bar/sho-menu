<?php

declare(strict_types=1);

namespace ShoMenu\PostTypes;

use WP_Post;
use WP_Term;
use ShoMenu\Dish;
use ShoMenu\Enums\Designation;

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
                'id' => 'sho-menu-designations',
                'title' => 'Обозначения',
                'slug' => 'designations',
                'callback' => [$this, 'designationsBoxMarkup'],
            ],
            [
                'id' => 'sho-menu-recommended',
                'title' => 'Реккомендовані блюда',
                'slug' => 'recommended',
                'callback' => [$this, 'recommendedBoxMarkup'],
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

        echo "<input type='text' name='sho-menu-weight' value='{$value}'>";
    }

    public function weightUnitBoxMarkup(WP_Post $post): void
    {
        wp_nonce_field('save_meta', 'sho_menu_nonce');

        $value = Dish::getMeta('weight_unit', $post->ID);
        $value = $value === '' ? ' г' : $value;

        echo "<input type='text' name='sho-menu-weight-unit' value='{$value}'>";
    }

    public function designationsBoxMarkup(WP_Post $post): void
    {
        wp_nonce_field('save_meta', 'sho_menu_nonce');

        $designations = Designation::all();
        $selected = Dish::getDesignations($post->ID);
        $checkboxes = '';

        foreach ($designations as $designation) {
            $checked = in_array($designation, $selected, true) ? 'checked' : '';

            $checkboxes .= <<<HTML
                <label>
                    <input
                        type="checkbox"
                        name="sho-menu-designation[]"
                        value="{$designation->value}"
                        {$checked}
                    />

                    <img
                        src="{$designation->icon()}"
                        width="11"
                        height="11"
                        style="margin-right: 1px;"
                    />

                    {$designation->description()}
                </label>
            HTML;
        }

        echo <<<HTML
            <div style="display: flex; flex-direction: column; gap: 3px;">
                {$checkboxes}
            </div>
        HTML;
    }

    public function recommendedBoxMarkup(WP_Post $post): void
    {
        wp_nonce_field('save_meta', 'sho_menu_nonce');

        $recommended_dishes = Dish::getRecommended($post->ID);
        $selected = '';

        foreach ($recommended_dishes as $dish) {
            $selected .= <<<HTML
                <li class="has-been-saved">
                    {$dish->title}
                    <input
                        type="hidden"
                        name="sho-recommended-dishes[]"
                        value="{$dish->id}"
                    />
                </li>
            HTML;
        }

        echo <<<HTML
            <div class="sho-recommended-dishes">
                <input
                    type="text"
                    name="sho-menu-weight-unit"
                    id="sho-recommended-dishes"
                    placeholder="Почни набирати назву"
                />

                <ul
                    id="sho-recommended-dishes-dropdown"
                    class="sho-recommended-dishes__dropdown sho-recommended-dishes__dropdown--hide"
                ></ul>

                <ul
                    id="sho-recommended-dishes-list"
                    class="sho-recommended-dishes__list"
                >{$selected}</ul>
            </div>
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

            $designations = $_POST['sho-menu-designation'] ?? [];

            if (is_array($designations)) {
                $save_value = implode(',', $designations);
                update_post_meta($post_id, '_sho_menu_designations', $save_value);
            }

            $recommended_dishes = $_POST['sho-recommended-dishes'] ?? [];

            if (is_array($recommended_dishes)) {
                $save_value = implode(',', $recommended_dishes);
                update_post_meta($post_id, '_sho_menu_recommended_dishes', $save_value);
            }
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
                    $price = Dish::getMeta('price', $post['id']);
                    return $price === false ? null : (int) $price;
                },
            ]);

            register_rest_field('sho-menu-dishes', 'weight', [
                'get_callback' => function ($post) {
                    $weight = Dish::getMeta('weight', $post['id']);
                    return $weight === false ? null : $weight;
                },
            ]);

            register_rest_field('sho-menu-dishes', 'weight_unit', [
                'get_callback' => function ($post) {
                    $unit = Dish::getMeta('weight_unit', $post['id']);
                    return $unit === false ? null : $unit;
                },
            ]);

            register_rest_field('sho-menu-dishes', 'image_url', [
                'get_callback' => function ($post) {
                    $image = get_the_post_thumbnail_url($post['id']);
                    return $image === false ? null : $image;
                },
            ]);

            register_rest_field('sho-menu-dishes', 'designations', [
                'get_callback' => function ($post) {
                    $selected = Dish::getDesignations($post['id']);
                    $result = [];

                    foreach ($selected as $item) {
                        $result[] = $item->toArray();
                    }

                    return $result;
                },
            ]);

            register_rest_field('sho-menu-dishes', 'recommended_dishes', [
                'get_callback' => function ($post) {
                    return Dish::getRecommended($post['id']);
                },
            ]);
        });
    }
}
