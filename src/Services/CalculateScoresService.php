<?php

namespace App\Services;

use App\Repository\AdRepository;
use App\Rules\PictureRules, App\Rules\DescriptionRules;

class CalculateScoresService
{
    public AdRepository $adRepository;

    public function __construct(AdRepository $adRepository)
    {
        $this->adRepository = $adRepository;
    }

    public function exec(): array
    {
        if ($this->adRepository->hasScoresCalculated())
            return ['message' => 'scores are calculated'];

        $ads = $this->adRepository->getAll();

        foreach ($ads as $ad) {
            $score = $this->calculateScore($ad);
            $this->adRepository->updateScore($ad['id'], $score);
            if ($this->isIrrelevant($score))
                $this->adRepository->updateIrrelevantSince($ad['id']);
        }

        return $this->adRepository->getAll();
    }

    public function calculateScore(array $ad): int
    {
        $score = 0;

        $score += count($ad['pictures']) > 0 ?
            $this->picturesScore($ad['pictures']) : PictureRules::NO_PHOTO;
        $score  += $this->descriptionScore($ad)
                + $this->wordsInDescription($ad['description'])
                + $this->completeAd($ad);

        return $this->normalizeScore($score);
    }

    public function isIrrelevant(int $score)
    {
        return $score < 40;
    }

    public function picturesScore(array $pictures): int
    {
        $score = 0;

        foreach($pictures as $picture) {
            $score += PictureRules::score($picture['quality']);
        }
        return $score;
    }

    public function descriptionScore(array $ad): int
    {
        $score = 0;
        $typology = $ad['typology'];
        $description = $ad['description'];
        $descriptionCountWords = $this->countWords($description);

        if ($descriptionCountWords <= 0)
            return 0;

        $score += DescriptionRules::HAVE_DESCRIPTION;

        if ($typology === 'FLAT') {
            if (20 <= $descriptionCountWords && $descriptionCountWords <= 49) {
                $score += DescriptionRules::FLAT_BETWEEN_20_AND_49;
            }
            else if ($descriptionCountWords >= 50) {
                $score += DescriptionRules::FLAT_EQUAL_OR_MORE_THAN_50;
            }
        }
        else if ($typology === 'CHALET') {
            if ($descriptionCountWords > 50) {
                $score += DescriptionRules::CHALET_EQUAL_OR_MORE_THAN_50;
            }
        }
        return $score;
    }

    public function countWords(string $string): int
    {
        return str_word_count($string, 0, DescriptionRules::LETTERS_WITH_ACCENT);
    }

    public function wordsInDescription(string $description): int
    {
        $regex = '/'.$this->withOutAccents(DescriptionRules::HAVE_THIS_WORDS).'/i';
        $count = preg_match_all($regex, $this->withOutAccents($description));

        return $count * DescriptionRules::FOR_EVERY_WORD;
    }

    private function withOutAccents(string $str): string
    {
        $unwanted_array = array('Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
        return strtr( $str, $unwanted_array );
    }

    public function completeAd(array $ad): int
    {
        $type = ['FLAT', 'GARAGE', 'CHALET'];
        $typology = $ad['typology'];

        if (
        is_int(array_search($typology, $type)) &&
        count($ad['pictures']) > 0 &&
        is_int($ad['houseSize'])
        ) {
            if (!empty($ad['description'])) {
                if ($typology === 'CHALET' && is_int($ad['gardenSize']))
                    return DescriptionRules::COMPLETE_AD;
                else if ($typology === 'FLAT')
                    return DescriptionRules::COMPLETE_AD;
            }
            if ($typology === 'GARAGE')
                return DescriptionRules::COMPLETE_AD;
        }
        return 0;
    }

    public function normalizeScore(int $score): int
    {
        if($score <= 0)
            return DescriptionRules::MIN_SCORE;
        else if($score >= 100)
            return DescriptionRules::MAX_SCORE;
        else
            return $score;
    }


}
