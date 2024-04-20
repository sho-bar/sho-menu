<?php

declare(strict_types=1);

namespace ShoMenu\Enums;

enum Designation: string
{
    case GARLIC = 'garlic';
    case CHILI = 'chili';
    case FARMER = 'farmer';
    case NEW = 'new';
    case ORGANIC = 'organic';
    case UKRAINE = 'ukraine';

    public function description(): string
    {
        return match ($this) {
            self::GARLIC => 'У складі страви є часник',
            self::CHILI => 'Гостра страва',
            self::FARMER => 'Страва містить фермерський продукт',
            self::NEW => 'Новинка',
            self::ORGANIC => 'Органічний продукт',
            self::UKRAINE => 'Українського виробництва',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::GARLIC => SHO_MENU_URL . 'assets/img/designations/garlic.png',
            self::CHILI => SHO_MENU_URL . 'assets/img/designations/chili.png',
            self::FARMER => SHO_MENU_URL . 'assets/img/designations/farmer.png',
            self::NEW => SHO_MENU_URL . 'assets/img/designations/new.png',
            self::ORGANIC => SHO_MENU_URL . 'assets/img/designations/organic.png',
            self::UKRAINE => SHO_MENU_URL . 'assets/img/designations/ukraine.png',
        };
    }
}
