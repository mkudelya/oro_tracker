<?php

namespace Oro\Bundle\IssueBundle\Tests\Unit\Entity;

use Oro\Bundle\IssueBundle\Entity\IssuePriority;

class IssuePriorityTest extends AbstractEntityTestCase
{
    /** @var IssuePriority */
    protected $entity;

    /**
     * {@inheritDoc}
     */
    public function getEntityFQCN()
    {
        return 'Oro\Bundle\IssueBundle\Entity\IssuePriority';
    }

    /**
     * @return array
     */
    public function getSetDataProvider()
    {
        $label = 'label';
        $name = 'name';
        $order = 1;

        return [
            'id' => ['id', 1, 1],
            'label'     => ['label', $label, $label],
            'name'     => ['name', $name, $name],
            'order'     => ['order', $order, $order],
        ];
    }

    public function testToString()
    {
        $this->entity->setLabel('test');
        $this->assertEquals('test', $this->entity);
    }
}
