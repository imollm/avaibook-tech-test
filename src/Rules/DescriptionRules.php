<?php

namespace App\Rules;

final class DescriptionRules
{
    const HAVE_DESCRIPTION = 5;

    const FLAT_BETWEEN_20_AND_49 = 10;
    const FLAT_EQUAL_OR_MORE_THAN_50 = 30;
    const CHALET_EQUAL_OR_MORE_THAN_50 = 20;

    const HAVE_THIS_WORDS = 'Luminoso|Nuevo|Céntrico|Reformado|Ático';
    const FOR_EVERY_WORD = 5;

    const COMPLETE_AD = 40;

    const MIN_SCORE = 0;
    const MAX_SCORE = 100;

    const LETTERS_WITH_ACCENT = 'ÁÉÍÓÚáéíóúñ';
}
