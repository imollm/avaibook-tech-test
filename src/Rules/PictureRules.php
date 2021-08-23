<?php

namespace App\Rules;

final class PictureRules
{
    const NO_PHOTO = -10;
    const PHOTO_HD = 20;
    const PHOTO_SD = 10;

    public static function score(string $quality): float
    {
        return match ($quality) {
            'HD' => PictureRules::PHOTO_HD,
            'SD' => PictureRules::PHOTO_SD,
            default => 0
        };
    }
}
