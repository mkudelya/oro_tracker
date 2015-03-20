<?php

namespace Oro\Bundle\IssueBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class IssueRepository extends EntityRepository
{
    /**
     * Load issues grouped by status
     *
     * @return  array
     */
    public function loadGroupByStatus()
    {
        $q = $this
            ->createQueryBuilder('issue')
            ->select('COUNT(issue.id) AS ct, step.label AS name')
            ->leftJoin('issue.workflowStep', 'step')
            ->groupBy('step.id')
            ->getQuery();
        return $q->getResult();
    }
}
