<?php

namespace MAD\ExperienceBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ExperienceRepository extends EntityRepository
{
    public function findUserInvestigationExperiences($userId)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('e')
            ->from('MADExperienceBundle:Experience','e')
            ->where($qb->expr()->eq('e.user', ':userId'))
            ->andWhere($qb->expr()->isNotNull('e.question'))
            ->setParameter('userId', $userId);
        ;

        $query = $qb->getQuery();

        return $query->getResult();

    }

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

    public function findSharedFreeExperiences()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('e')
            ->from('MADExperienceBundle:Experience','e')
            ->where($qb->expr()->isNull('e.question'))
            ->andWhere($qb->expr()->eq('e.sharedWithAll', ':sharedWithAll'))
            ->setParameter('sharedWithAll', true);
        ;

        $query = $qb->getQuery();

        return $query->getResult();

    }
}