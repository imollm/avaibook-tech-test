<?php

namespace App\Repository;

use App\Entity\Ad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

class AdRepository extends ServiceEntityRepository
{
    private PictureRepository $pictureRepository;

    public function __construct(ManagerRegistry $registry, PictureRepository $pictureRepository)
    {
        parent::__construct($registry, Ad::class);
        $this->pictureRepository = $pictureRepository;
    }

    public function getAll(): array
    {
        $adsWithPictures = [];

        $ads = $this->createQueryBuilder('ad')
                ->getQuery()
                ->getResult(Query::HYDRATE_ARRAY);

        foreach ($ads as $ad) {
            $pictures = $this->pictureRepository->getPicturesByAdId($ad['id']);
            $ad['pictures'] = $pictures;
            array_push($adsWithPictures, $ad);
        }

        return $adsWithPictures;
    }

    public function getById(int $adId): array
    {
        $ad = $this->createQueryBuilder('ad')
                ->andWhere('ad.id = :val')
                ->setParameter('val', $adId)
                ->getQuery()
                ->getResult(Query::HYDRATE_ARRAY);

        $pictures = $this->pictureRepository->getPicturesByAdId($adId);
        $ad[0]['pictures'] = $pictures;

        return $ad;
    }

    public function hasScoresCalculated(): bool
    {
        $scores = $this->createQueryBuilder('ad')
                    ->where('ad.score is null')
                    ->getQuery()
                    ->getResult();

        return count($scores) <= 0;
    }

    public function updateScore(int $adId, int $score): void
    {
        $this->createQueryBuilder('ad')
            ->update('App:Ad', 'ad')
            ->set('ad.score', $score)
            ->andWhere('ad.id = :val')
            ->setParameter('val', $adId)
            ->getQuery()
            ->getResult();
    }

    public function updateIrrelevantSince(int $adId)
    {
        $this->createQueryBuilder('ad')
            ->update('App:Ad', 'ad')
            ->set('ad.irrelevantSince', "'".date(('Y-m-d H:i:s'), time())."'")
            ->andWhere('ad.id = :val')
            ->setParameter('val', $adId)
            ->getQuery()
            ->getResult();
    }

    public function getQualityAds()
    {
        return $this->createQueryBuilder('ad')
                ->where('ad.irrelevantSince is null')
                ->where('ad.score > 40')
                ->orderBy('ad.score', 'DESC')
                ->getQuery()
                ->getResult(Query::HYDRATE_ARRAY);
    }

    public function getIrrelevantAds()
    {
        return $this->createQueryBuilder('ad')
            ->where('ad.irrelevantSince is not null')
            ->where('ad.score <= 40')
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
    }

    public function getIrrelevantAdsScoreAvg()
    {
        return $this->createQueryBuilder('ad')
                ->select('avg(ad.score)')
                ->where('ad.irrelevantSince is not null')
                ->getQuery()
                ->getResult(Query::HYDRATE_SINGLE_SCALAR);
    }

    public function getQualityAdsScoreAvg()
    {
        return $this->createQueryBuilder('ad')
                ->select('avg(ad.score)')
                ->where('ad.irrelevantSince is null')
                ->getQuery()
                ->getResult(Query::HYDRATE_SINGLE_SCALAR);
    }

    public function getAdsAvg()
    {
        return $this->createQueryBuilder('ad')
            ->select('avg(ad.score)')
            ->getQuery()
            ->getResult(Query::HYDRATE_SINGLE_SCALAR);
    }
}
