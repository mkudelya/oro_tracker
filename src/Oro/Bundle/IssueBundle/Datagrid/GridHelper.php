<?php

namespace Oro\Bundle\IssueBundle\Datagrid;

use Doctrine\ORM\EntityManager;

use Oro\Bundle\IssueBundle\Form\Type\IssueType;

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

    /**
     * @return array
     */
    public function getTypeChoices()
    {
        return (new IssueType())->getIssueTypes();
    }

    /**
     * @return array
     */
    public function getPriorityChoices()
    {
        $result = [];
        $objects  = $this->em->getRepository('OroIssueBundle:IssuePriority')->findAll();
        foreach ($objects as $obj) {
            $result[$obj->getName()] = $obj->getLabel();
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getResolutionChoices()
    {
        $result = [];
        $objects  = $this->em->getRepository('OroIssueBundle:IssueResolution')->findAll();
        foreach ($objects as $obj) {
            $result[$obj->getName()] = $obj->getLabel();
        }

        return $result;
    }
}
