<?php

namespace MAD\ExperienceBundle\Repository;

use Doctrine\ORM\EntityRepository;

class QuestionRepository extends EntityRepository
{
    public function findQuestionsAndAnswersByGroupAndSubject($groups, $subjectId, $userId)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('q, e, g')
            ->from('MADExperienceBundle:Question','q')
            ->leftJoin('q.experiences','e', 'WITH', 'e.user = :userId')
            ->leftJoin('q.groups', 'g')
            ->leftJoin('e.user', 'u')
//            ->leftJoin('e.user', 'u', 'WITH', 'u.id = :userId')
            ->where($qb->expr()->eq('q.subject', ':subjectId'))
            ->andWhere($qb->expr()->in('g.id', ':groupIds'))
            ->andWhere($qb->expr()->orX($qb->expr()->eq('u.id', ':userId'), $qb->expr()->isNull('u.id')))
            ->setParameter('groupIds', $this->getGroupsId($groups))
            ->setParameter('userId', $userId)
            ->setParameter('subjectId', $subjectId);
        ;

        $query = $qb->getQuery();
        $sql = $query->getSQL();
        return $query->getResult();
    }

    public function findQuestionsAndSharedAnswersByGroups($groups, $subjectId)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('q, e, g')
            ->from('MADExperienceBundle:Question','q')
            ->leftJoin('q.experiences','e')
            ->leftJoin('q.groups', 'g')
            ->where($qb->expr()->eq('q.subject', ':subjectId'))
            ->andWhere($qb->expr()->in('g.id', ':groupIds'))
            ->andwhere($qb->expr()->eq('e.sharedWithAll', ':sharedWithAll'))
            ->setParameter('groupIds', $this->getGroupsId($groups))
            ->setParameter('subjectId', $subjectId)
            ->setParameter('sharedWithAll', true)
        ;

        $query = $qb->getQuery();

        return $query->getResult();
    }

    public function findQuestionsAndAnswersByGroups($groups, $subjectId)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('q, e, g')
            ->from('MADExperienceBundle:Question','q')
            ->leftJoin('q.experiences','e')
            ->leftJoin('q.groups', 'g')
            ->where($qb->expr()->eq('q.subject', ':subjectId'))
            ->andWhere($qb->expr()->in('g.id', ':groupIds'))
            ->setParameter('groupIds', $this->getGroupsId($groups))
            ->setParameter('subjectId', $subjectId)
        ;

        $query = $qb->getQuery();

        return $query->getResult();
    }

    protected function getGroupsId($groups)
    {
        $group_ids = array();
        foreach($groups as $group) {
            $group_ids[] = $group->getId();
        }

        return implode(',', $group_ids);

    }
}