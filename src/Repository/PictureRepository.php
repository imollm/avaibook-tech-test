<?php

namespace App\Repository;

use App\Entity\Picture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

class PictureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Picture::class);
    }

    public function getPicturesByAdId(int $adId)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.ad = :val')
            ->setParameter('val', $adId)
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
    }
}
