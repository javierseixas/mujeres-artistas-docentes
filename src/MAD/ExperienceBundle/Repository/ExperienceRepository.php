<?php

namespace MAD\ExperienceBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ExperienceRepository extends EntityRepository
{
    public function findUserFreeExperiences($userId)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('e')
            ->from('MADExperienceBundle:Experience','e')
            ->where($qb->expr()->eq('e.user', ':userId'))
            ->andWhere($qb->expr()->isNull('e.question'))
            ->setParameter('userId', $userId);
        ;

        $query = $qb->getQuery();

        return $query->getResult();

    }
}