<?php

namespace Oro\Bundle\IssueBundle\Tests\Unit\Entity;

use Oro\Bundle\IssueBundle\Entity\IssueResolution;

class IssueResolutionTest extends AbstractEntityTestCase
{
    /** @var IssueResolution */
    protected $entity;

    /**
     * {@inheritDoc}
     */
    public function getEntityFQCN()
    {
        return 'Oro\Bundle\IssueBundle\Entity\IssueResolution';
    }

    /**
     * @return array
     */
    public function getSetDataProvider()
    {
        $label = 'label';
        $name = 'name';

        return [
            'id' => ['id', 1, 1],
            'label'     => ['label', $label, $label],
            'name'     => ['name', $name, $name]
        ];
    }
}
