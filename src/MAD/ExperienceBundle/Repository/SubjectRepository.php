<?php

namespace MAD\ExperienceBundle\Repository;

use Doctrine\ORM\EntityRepository;

class SubjectRepository extends EntityRepository
{
    public function findSubjectQuestionsAndAnswersByUser($userId)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('s, q, e')
            ->from('MADExperienceBundle:Subject','s')
            ->innerJoin('s.questions','q')
            ->leftJoin('q.experiences','e')
            ->where($qb->expr()->eq('e.user', ':userId'))
            ->orWhere($qb->expr()->isNull('e.user'))
            ->setParameter('userId', $userId);
        ;

        $query = $qb->getQuery();

        return $query->getResult();

    }
}