<?php

namespace AmVal\SteamAPIBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class GameRepository
 * @package AmVal\SteamAPIBundle\Repository
 */
class GameRepository extends EntityRepository
{
    /**
     * Gets the name of the game corresponding to the Steam Game Id
     *
     * @param $appId
     * @return mixed
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getName($appId)
    {
        return $this->createQueryBuilder('g')
            ->select('g.name')
            ->where('g.appId = :appId')
            ->setParameter('appId', $appId)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
