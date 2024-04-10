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
                add_meta_box($box['id'], $box['title'], $box['callback'], self::POST_TYPE, 'side');
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
                'callback' => [$this, 'priceBoxMarkup'],
            ],
            [
                'id' => 'sho-menu-weight',
                'title' => __('Вес (г)', 'sho-menu'),
                'slug' => 'weight',
                'callback' => [$this, 'weightBoxMarkup'],
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

    public function savePostHook(): void
    {
        add_action('save_post', function ($post_id) {
            $nonce = $_POST['sho_menu_nonce'] ?? null;
            $verify_nonce = wp_verify_nonce($nonce, 'save_meta');
            $user_can_edit = current_user_can('edit_post', $post_id);

            if (!$nonce || !$verify_nonce || !$user_can_edit) {
                return;
            }

            if ($this->categoryHasParent() === false) {
                $this->showValidationError(
                    title: __('Неправильная категория', 'sho-menu'),
                    content: __('Вы выбрали главную категорию без родительской. Вместо
                        этого выберите подкатегорию, к которой относится блюдо.
                        Например, если блюдо относится к категории "Супы", то
                        выберите подкатегорию "Супы".', 'sho-menu')
                );

                return;
            }

            $price = sanitize_text_field($_POST['sho-menu-price'] ?? '');
            $weight = sanitize_text_field($_POST['sho-menu-weight'] ?? '');

            update_post_meta($post_id, '_sho_menu_price', $price);
            update_post_meta($post_id, '_sho_menu_weight', $weight);
        });
    }

    private function categoryHasParent(): bool|null
    {
        $category_id = $this->getCategoryIdFromRequest();

        if (!$category_id) {
            return null;
        }

        /** @var WP_Term|null $term */
        $term = get_term_by('id', $category_id, self::TAXONOMY);

        $parent = $term->parent ?? 0;

        return $parent !== 0;
    }

    private function getCategoryIdFromRequest(): int|null
    {
        $result = $_POST['tax_input']['sho-menu-dish-category'][1] ?? null;
        return $result ? (int) $result : null;
    }

    private function showValidationError(string $title, string $content): void
    {
        echo <<<HTML
            <div class="error">
                <h2>⚠️ Неправильная категория</h2>
                <h3>Вы выбрали главную категорию без родительской. Вместо
                    этого выберите подкатегорию, к которой относится блюдо.
                    Например, если блюдо относится к категории "Супы", то
                    выберите подкатегорию "Супы".
                </h3>

                <a
                    href="#"
                    style="background: lightgray; padding: 7px 15px; border-radius: 5px; text-decoration: none; color: black;"
                    onclick="history.back()"
                >Назад</a>
            </div>
        HTML;
    }
}
