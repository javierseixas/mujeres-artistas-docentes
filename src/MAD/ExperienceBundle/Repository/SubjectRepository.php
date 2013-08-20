<?php

namespace MAD\ExperienceBundle\Repository;

use Doctrine\ORM\EntityRepository;

class SubjectRepository extends EntityRepository
{
    public function findSubjectQuestionsAndAnswersByUser($userId)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('s, q, e, u')
            ->from('MADExperienceBundle:Subject','s')
            ->innerJoin('s.questions','q')
            ->leftJoin('q.experiences','e')
            ->leftJoin('e.user', 'u', 'WITH', 'u.id = :userId')
            ->setParameter('userId', $userId);
        ;

        $query = $qb->getQuery();

        return $query->getResult();

    }
}