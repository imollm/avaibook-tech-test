<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Ad, App\Entity\Picture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $ads = FakeData::getAds();
        $pictures = FakeData::getPictures();

        foreach ($ads as $ad) {
            $newAd = new Ad();
            $newAd->setId($ad['id']);
            $newAd->setTypology($ad['typology']);
            $newAd->setDescription($ad['description']);
            $newAd->setHouseSize($ad['houseSize']);
            $newAd->setGardenSize($ad['gardenSize']);
            $newAd->setScore($ad['score']);
            $newAd->setIrrelevantSince($ad['irrelevantSince']);

            $manager->persist($newAd);
            $manager->flush();

            $adPictureIds = $ad['pictures'];
            if (count($adPictureIds) > 0) {
                foreach ($adPictureIds as $adPictureId) {
                    $picFromArray = $pictures[$adPictureId - 1];
                    $newPicture = $this->newPicture($picFromArray, $newAd);
                    $manager->persist($newPicture);
                    $manager->flush();
                }
            }
        }
    }

    private function newPicture($picFromArray, $ad): Picture
    {
        $newPicture = new Picture();
        $newPicture->setId($picFromArray['id']);
        $newPicture->setUrl($picFromArray['url']);
        $newPicture->setQuality($picFromArray['quality']);
        $newPicture->setAd($ad);

        return $newPicture;
    }
}
