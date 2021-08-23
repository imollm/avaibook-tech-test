<?php

namespace App\Tests\Repository;

use App\DataFixtures\FakeData;
use App\Entity\Picture;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PictureRepositoryTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    private EntityManager $entityManager;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testGetPicturesByAdId()
    {
        $ads = FakeData::getAds();
        $randomAd = $ads[rand(0, count($ads) - 1)];
        $randomAdId = $randomAd['id'] + 1;

        $result = $this->entityManager
            ->getRepository(Picture::class)
            ->getPicturesByAdId($randomAdId);

        $this->assertJson(json_encode($randomAd), json_encode($result));
    }
}
