<?php

namespace Oro\Bundle\IssueBundle\Datagrid;

use Oro\Bundle\IssueBundle\Form\Type\IssueType;
use Doctrine\ORM\EntityManager;

class GridHelper
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getTypeChoices()
    {
        return (new IssueType())->getIssueTypes();
    }

    public function getPriorityChoices()
    {
        $result = [];
        $objects  = $this->em->getRepository('IssueBundle:IssuePriority')->findAll();
        foreach ($objects as $obj) {
            $result[$obj->getName()] = $obj->getLabel();
        }

        return $result;
    }

    public function getResolutionChoices()
    {
        $result = [];
        $objects  = $this->em->getRepository('IssueBundle:IssueResolution')->findAll();
        foreach ($objects as $obj) {
            $result[$obj->getName()] = $obj->getLabel();
        }

        return $result;
    }
}
