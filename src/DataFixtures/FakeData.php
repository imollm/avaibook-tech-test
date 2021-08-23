<?php

namespace App\DataFixtures;

final class FakeData
{
    public static function getAds(): array
    {
        return [
            [
                'id' => 1,
                'typology' => 'CHALET',
                'description' => 'Este piso es una ganga, compra, compra, COMPRA!!!!!',
                'pictures' => [],
                'houseSize' => 300,
                'gardenSize' => null,
                'score' => null,
                'irrelevantSince' => null
            ],
            [
                'id' => 2,
                'typology' => 'FLAT',
                'description' => 'Nuevo ático céntrico recién reformado. No deje pasar la oportunidad y adquiera este ático de lujo',
                'pictures' => [4],
                'houseSize' => 300,
                'gardenSize' => null,
                'score' => null,
                'irrelevantSince' => null
            ],
            [
                'id' => 3,
                'typology' => 'CHALET',
                'description' => '',
                'pictures' => [2],
                'houseSize' => 300,
                'gardenSize' => null,
                'score' => null,
                'irrelevantSince' => null
            ],
            [
                'id' => 4,
                'typology' => 'FLAT',
                'description' => 'Ático céntrico muy luminoso y recién reformado, parece nuevo',
                'pictures' => [5],
                'houseSize' => 300,
                'gardenSize' => null,
                'score' => null,
                'irrelevantSince' => null
            ],
            [
                'id' => 5,
                'typology' => 'FLAT',
                'description' => 'Pisazo,',
                'pictures' => [3, 8],
                'houseSize' => 300,
                'gardenSize' => null,
                'score' => null,
                'irrelevantSince' => null
            ],
            [
                'id' => 6,
                'typology' => 'GARAGE',
                'description' => '',
                'pictures' => [6],
                'houseSize' => 300,
                'gardenSize' => null,
                'score' => null,
                'irrelevantSince' => null
            ],
            [
                'id' => 7,
                'typology' => 'GARAGE',
                'description' => 'Garaje en el centro de Albacete',
                'pictures' => [],
                'houseSize' => 300,
                'gardenSize' => null,
                'score' => null,
                'irrelevantSince' => null
            ],
            [
                'id' => 8,
                'typology' => 'CHALET',
                'description' => 'Maravilloso chalet situado en lAs afueras de un pequeño pueblo rural. El entorno es espectacular, las vistas magníficas. ¡Cómprelo ahora!',
                'pictures' => [1, 7],
                'houseSize' => 300,
                'gardenSize' => null,
                'score' => null,
                'irrelevantSince' => null
            ]
        ];
    }

    public static function getPictures(): array
    {
        return [
            [
                'id' => 1,
                'url' => 'https://www.idealista.com/pictures/1',
                'quality' => 'SD'
            ],
            [
                'id' => 2,
                'url' => 'https://www.idealista.com/pictures/2',
                'quality' => 'HD'
            ],
            [
                'id' => 3,
                'url' => 'https://www.idealista.com/pictures/3',
                'quality' => 'SD'
            ],
            [
                'id' => 4,
                'url' => 'https://www.idealista.com/pictures/4',
                'quality' => 'HD'
            ],
            [
                'id' => 5,
                'url' => 'https://www.idealista.com/pictures/5',
                'quality' => 'SD'
            ],
            [
                'id' => 6,
                'url' => 'https://www.idealista.com/pictures/6',
                'quality' => 'SD'
            ],
            [
                'id' => 7,
                'url' => 'https://www.idealista.com/pictures/7',
                'quality' => 'SD'
            ],
            [
                'id' => 8,
                'url' => 'https://www.idealista.com/pictures/8',
                'quality' => 'HD'
            ]
        ];
    }
}
