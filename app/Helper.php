<?php

declare(strict_types=1);

namespace ShoMenu;

use Exception;

final class Helper
{
    public static function fileVersion(string $file_path): int
    {
        try {
            return filemtime($file_path) ?? 0;
        } catch (Exception $e) {
            tiny_log("fileVersion() method exception. Message: {$e->getMessage()}");
            return 0;
        }
    }
}
