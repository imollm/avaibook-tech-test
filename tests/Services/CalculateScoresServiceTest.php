<?php

namespace App\Tests\Services;

use App\DataFixtures\FakeData;
use App\Rules\DescriptionRules;
use App\Rules\PictureRules;
use App\Services\CalculateScoresService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use \Faker\Factory as Faker;

class CalculateScoresServiceTest extends KernelTestCase
{
    private CalculateScoresService $service;

    private $faker;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $this->service = $container->get(CalculateScoresService::class);

        $this->faker = Faker::create();
    }

    /** PHOTOS SCORE FUNCTION */

    public function testPicturesScoreWithAdWithOutPictures()
    {
        $ad['pictures'] = [];

        $expected = DescriptionRules::MIN_SCORE;

        $result = $this->service->picturesScore($ad['pictures']);

        $this->assertEquals($expected, $result);
    }

    public function testPicturesScoreWithAdWithOnePictureHD()
    {
        $ad['pictures'] = [
            [
                'quality' => 'HD'
            ]
        ];

        $expected = PictureRules::PHOTO_HD;

        $result = $this->service->picturesScore($ad['pictures']);

        $this->assertEquals($expected, $result);
    }

    public function testPicturesScoreWithAdWithOnePictureSD()
    {
        $ad['pictures'] = [
            [
                'quality' => 'SD'
            ]
        ];

        $expected = PictureRules::PHOTO_SD;

        $result = $this->service->picturesScore($ad['pictures']);

        $this->assertEquals($expected, $result);
    }

    public function testPicturesScoreWithAdWithTwoPicturesSDAndHD()
    {
        $ad['pictures'] = [
            [
                'quality' => 'SD'
            ],
            [
                'quality' => 'HD'
            ]
        ];

        $expected = PictureRules::PHOTO_SD + PictureRules::PHOTO_HD;

        $result = $this->service->picturesScore($ad['pictures']);

        $this->assertEquals($expected, $result);
    }

    public function testPicturesScoreWithAdWithTwoPicturesHD()
    {
        $ad['pictures'] = [
            [
                'quality' => 'HD'
            ],
            [
                'quality' => 'HD'
            ]
        ];

        $expected = PictureRules::PHOTO_HD + PictureRules::PHOTO_HD;

        $result = $this->service->picturesScore($ad['pictures']);

        $this->assertEquals($expected, $result);
    }

    public function testPicturesScoreWithAdWithTwoPicturesSD()
    {
        $ad['pictures'] = [
            [
                'quality' => 'SD'
            ],
            [
                'quality' => 'SD'
            ]
        ];

        $expected = PictureRules::PHOTO_SD + PictureRules::PHOTO_SD;

        $result = $this->service->picturesScore($ad['pictures']);

        $this->assertEquals($expected, $result);
    }

    /** COUNT WORDS FUNCTION */

    public function testCountWords()
    {
        $string = $this->faker->sentence(10, false);

        $expected = 10;

        $result = $this->service->countWords($string);

        $this->assertEquals($expected, $result);
    }

    /** DESCRIPTION SCORE FUNCTION */

    public function testDescriptionScoreNoDescription()
    {
        $ad = [
            'typology' => 'FLAT',
            'description' => '',
        ];

        $expected = DescriptionRules::MIN_SCORE;

        $result = $this->service->descriptionScore($ad);

        $this->assertEquals($expected, $result);
    }

    public function testDescriptionScoreHaveDescription()
    {
        $ad = [
            'typology' => 'FLAT',
            'description' => $this->faker->sentence(5, false),
        ];

        $expected = DescriptionRules::HAVE_DESCRIPTION;

        $result = $this->service->descriptionScore($ad);

        $this->assertEquals($expected, $result);
    }

    public function testDescriptionScoreHaveDescriptionIsFlatAndHave20Words()
    {
        $ad = [
            'typology' => 'FLAT',
            'description' => $this->faker->sentence(20, false)
        ];

        $expected = DescriptionRules::HAVE_DESCRIPTION + DescriptionRules::FLAT_BETWEEN_20_AND_49;

        $result = $this->service->descriptionScore($ad);

        $this->assertEquals($expected, $result);
    }

    public function testDescriptionScoreHaveDescriptionIsFlatAndHave30Words()
    {
        $ad = [
            'typology' => 'FLAT',
            'description' => $this->faker->sentence(30, false)
        ];

        $expected = DescriptionRules::HAVE_DESCRIPTION + DescriptionRules::FLAT_BETWEEN_20_AND_49;

        $result = $this->service->descriptionScore($ad);

        $this->assertEquals($expected, $result);
    }

    public function testDescriptionScoreHaveDescriptionIsFlatAndHave49Words()
    {
        $ad = [
            'typology' => 'FLAT',
            'description' => $this->faker->sentence(49, false)
        ];

        $expected = DescriptionRules::HAVE_DESCRIPTION + DescriptionRules::FLAT_BETWEEN_20_AND_49;

        $result = $this->service->descriptionScore($ad);

        $this->assertEquals($expected, $result);
    }

    public function testDescriptionScoreHaveDescriptionIsFlatAndHave50Words()
    {
        $ad = [
            'typology' => 'FLAT',
            'description' => $this->faker->sentence(50, false)
        ];

        $expected = DescriptionRules::HAVE_DESCRIPTION + DescriptionRules::FLAT_EQUAL_OR_MORE_THAN_50;

        $result = $this->service->descriptionScore($ad);

        $this->assertEquals($expected, $result);
    }

    public function testDescriptionScoreHaveDescriptionIsFlatAndHaveMoreThan50Words()
    {
        $ad = [
            'typology' => 'FLAT',
            'description' => $this->faker->sentence(100, false)
        ];

        $expected = DescriptionRules::HAVE_DESCRIPTION + DescriptionRules::FLAT_EQUAL_OR_MORE_THAN_50;

        $result = $this->service->descriptionScore($ad);

        $this->assertEquals($expected, $result);
    }

    public function testDescriptionScoreHaveDescriptionIsChaletAndHaveLessThan50Words()
    {
        $ad = [
            'typology' => 'CHALET',
            'description' => $this->faker->sentence(49, false)
        ];

        $expected = DescriptionRules::HAVE_DESCRIPTION;

        $result = $this->service->descriptionScore($ad);

        $this->assertEquals($expected, $result);
    }

    public function testDescriptionScoreHaveDescriptionIsChaletAndHave50Words()
    {
        $ad = [
            'typology' => 'CHALET',
            'description' => $this->faker->sentence(50, false)
        ];

        $expected = DescriptionRules::HAVE_DESCRIPTION;

        $result = $this->service->descriptionScore($ad);

        $this->assertEquals($expected, $result);
    }

    public function testDescriptionScoreHaveDescriptionIsChaletAndHaveMoreThan50Words()
    {
        $ad = [
            'typology' => 'CHALET',
            'description' => $this->faker->sentence(51, false)
        ];

        $expected = DescriptionRules::HAVE_DESCRIPTION + DescriptionRules::CHALET_EQUAL_OR_MORE_THAN_50;
        ;

        $result = $this->service->descriptionScore($ad);

        $this->assertEquals($expected, $result);
    }
    
    /** WORDS IN DESCRIPTION FUNCTION (Luminoso, Nuevo, Céntrico, Reformado, Ático) */

    public function testWordsInDescriptionOneLuminoso()
    {
        $description = 'Piso espectacular muy luminoso';

        $expected = DescriptionRules::FOR_EVERY_WORD;

        $result = $this->service->wordsInDescription($description);

        $this->assertEquals($expected, $result);
    }

    public function testWordsInDescriptionOneNuevo()
    {
        $description = 'Piso espectacular muy nuevo';

        $expected = DescriptionRules::FOR_EVERY_WORD;

        $result = $this->service->wordsInDescription($description);

        $this->assertEquals($expected, $result);
    }

    public function testWordsInDescriptionOneCentrico()
    {
        $descriptionWithAccent = 'Piso espectacular muy céntrico';
        $descriptionWithOutAccent = 'Piso espectacular muy centrico';
        $descriptionWithUpperCase = 'Piso espectacular muy Centrico';

        $expected = DescriptionRules::FOR_EVERY_WORD;

        $resultWithAccent = $this->service->wordsInDescription($descriptionWithAccent);
        $resultWithOutAccent = $this->service->wordsInDescription($descriptionWithOutAccent);
        $resultWithUpperCase = $this->service->wordsInDescription($descriptionWithUpperCase);

        $this->assertEquals($expected, $resultWithAccent);
        $this->assertEquals($expected, $resultWithOutAccent);
        $this->assertEquals($expected, $resultWithUpperCase);
    }

    public function testWordsInDescriptionOneReformado()
    {
        $description = 'Piso espectacular reformado';

        $expected = DescriptionRules::FOR_EVERY_WORD;

        $result = $this->service->wordsInDescription($description);

        $this->assertEquals($expected, $result);
    }

    public function testWordsInDescriptionOneAtico()
    {
        $descriptionWithAccent = 'Ático muy espacioso y con vistas al mar, ....';
        $descriptionWithOutAccent = 'Atico muy espacioso y con vistas al mar, ....';
        $descriptionWithLowerCase = 'atico muy espacioso y con vistas al mar, ....';

        $expected = DescriptionRules::FOR_EVERY_WORD;

        $resultWithAccent = $this->service->wordsInDescription($descriptionWithAccent);
        $resultWithOutAccent = $this->service->wordsInDescription($descriptionWithOutAccent);
        $resultWithLowerCase = $this->service->wordsInDescription($descriptionWithLowerCase);

        $this->assertEquals($expected, $resultWithAccent);
        $this->assertEquals($expected, $resultWithOutAccent);
        $this->assertEquals($expected, $resultWithLowerCase);
    }

    public function testWordsInDescriptionTwoWords()
    {
        $description = 'Piso nuevo espectacular muy luminoso';

        $expected = DescriptionRules::FOR_EVERY_WORD * 2;

        $result = $this->service->wordsInDescription($description);

        $this->assertEquals($expected, $result);
    }

    public function testWordsInDescriptionThreeWords()
    {
        $description = 'Piso nuevo espectacular muy luminoso, reformado y con vistas';

        $expected = DescriptionRules::FOR_EVERY_WORD * 3;

        $result = $this->service->wordsInDescription($description);

        $this->assertEquals($expected, $result);
    }

    public function testWordsInDescriptionFourWords()
    {
        $description = 'Piso nuevo espectacular muy luminoso, reformado y muy céntrico';

        $expected = DescriptionRules::FOR_EVERY_WORD * 4;

        $result = $this->service->wordsInDescription($description);

        $this->assertEquals($expected, $result);
    }

    public function testWordsInDescriptionFiveWords()
    {
        $description = 'Ático nuevo espectacular muy luminoso, reformado y muy céntrico';

        $expected = DescriptionRules::FOR_EVERY_WORD * 5;

        $result = $this->service->wordsInDescription($description);

        $this->assertEquals($expected, $result);
    }

    /** COMPLETE AD FUNCTION */

    public function testCompleteAdFlatOnePictureHouseSizeDescription()
    {
        $ad = [
            'typology' => 'FLAT',
            'pictures' => [1],
            'houseSize' => 100,
            'description' => $this->faker->sentence(10, false)
        ];

        $expected = DescriptionRules::COMPLETE_AD;

        $result = $this->service->completeAd($ad);

        $this->assertEquals($expected, $result);
    }

    public function testCompleteAdChaletOnePictureHouseSizeGardenSizeDescription()
    {
        $ad = [
            'typology' => 'CHALET',
            'pictures' => [1],
            'houseSize' => 100,
            'gardenSize' => 50,
            'description' => $this->faker->sentence(10, false)
        ];

        $expected = DescriptionRules::COMPLETE_AD;

        $result = $this->service->completeAd($ad);

        $this->assertEquals($expected, $result);
    }

    public function testCompleteAdChaletOnePictureHouseSizeGarage()
    {
        $ad = [
            'typology' => 'GARAGE',
            'pictures' => [1],
            'houseSize' => 100
        ];

        $expected = DescriptionRules::COMPLETE_AD;

        $result = $this->service->completeAd($ad);

        $this->assertEquals($expected, $result);
    }

    /** NORMALIZE SCORE FUNCTION */

    public function testNormalizeNegativeScore()
    {
        $expected = DescriptionRules::MIN_SCORE;

        $result = $this->service->normalizeScore(-5);

        $this->assertEquals($expected, $result);
    }

    public function testNormalizeScoreGreaterThan100()
    {
        $expected = DescriptionRules::MAX_SCORE;

        $result = $this->service->normalizeScore(101);

        $this->assertEquals($expected, $result);
    }

}
