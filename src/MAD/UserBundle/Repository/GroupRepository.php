<?php

namespace MAD\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;

class GroupRepository extends EntityRepository
{
    public function findGroupsByUser($userId)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('g')
            ->from('MADUserBundle:Group','g')
            ->leftJoin('g.users', 'u')
            ->where($qb->expr()->eq('u.id', ':userId'))
            ->setParameter('userId', $userId)
        ;

        $query = $qb->getQuery();
$sql = $query->getSQL();
        return $query->getResult();

    }
}