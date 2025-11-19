<?php

declare(strict_types=1);

namespace ShoMenu\Enums;

enum Designation: string
{
    case ENDED = 'ended';
    case GARLIC = 'garlic';
    case CHILI = 'chili';
    case FARMER = 'farmer';
    case NEW = 'new';
    case ORGANIC = 'organic';
    case UKRAINE = 'ukraine';
    case POPULAR = 'popular';
    case FOR_BEAR = 'for_bear';
    case FOR_SEASON = 'for_season';

    /**
     * @return array<int, self>
     */
    public static function all(): array
    {
        return [
            self::ENDED,
            self::GARLIC,
            self::CHILI,
            self::FARMER,
            self::NEW,
            self::ORGANIC,
            self::UKRAINE,
            self::POPULAR,
            self::FOR_BEAR,
            self::FOR_SEASON,
        ];
    }

    /**
     * @param array<int, self> $designations
     * @return array{slug: string, description: string, icon: string|null}
     */
    public function toArray(): array
    {
        return [
            'slug' => $this->value,
            'description' => $this->description(),
            'icon' => $this->icon(),
        ];
    }

    public function description(): string
    {
        return match ($this) {
            self::ENDED => 'Закінчилось',
            self::GARLIC => 'У складі страви є часник',
            self::CHILI => 'Гостра страва',
            self::FARMER => 'Містить фермерський продукт',
            self::NEW => 'Новинка',
            self::ORGANIC => 'Органічний продукт',
            self::UKRAINE => 'Українського виробництва',
            self::POPULAR => 'Популярне',
            self::FOR_BEAR => 'Ідеально до пива',
            self::FOR_SEASON => 'Сезонне',
        };
    }

    public function icon(): string|null
    {
        return match ($this) {
            self::ENDED => null,
            self::GARLIC => SHO_MENU_URL . 'assets/img/designations/garlic.png',
            self::CHILI => SHO_MENU_URL . 'assets/img/designations/chili.png',
            self::FARMER => SHO_MENU_URL . 'assets/img/designations/farmer.png',
            self::NEW => SHO_MENU_URL . 'assets/img/designations/new.png',
            self::ORGANIC => SHO_MENU_URL . 'assets/img/designations/organic.png',
            self::UKRAINE => SHO_MENU_URL . 'assets/img/designations/ukraine.png',
            self::POPULAR => SHO_MENU_URL . 'assets/img/designations/popular.png',
            self::FOR_BEAR => SHO_MENU_URL . 'assets/img/designations/for-bear.png',
            self::FOR_SEASON => SHO_MENU_URL . 'assets/img/designations/for-season.png',
        };
    }
}
