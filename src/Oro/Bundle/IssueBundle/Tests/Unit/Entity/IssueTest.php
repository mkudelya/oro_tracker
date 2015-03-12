<?php

namespace Oro\Bundle\IssueBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\ArrayCollection;

//use Oro\Bundle\IntegrationBundle\Entity\Channel;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowItem;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowStep;

use Oro\Bundle\IssueBundle\Entity\Issue;

class IssueTest extends AbstractEntityTestCase
{
    /** @var Issue */
    protected $entity;

    /**
     * {@inheritDoc}
     */
    public function getEntityFQCN()
    {
        return 'Oro\Bundle\IssueBundle\Entity\Issue';
    }

    /**
     * @return array
     */
    public function getSetDataProvider()
    {
        $summary = 'summary';
        $code = 'code _ 5 !';
        $expectedCode = 'CODE_5';
        $description = 'description';
        $type = 'type';
        $priority = $this->getMock('Oro\Bundle\IssueBundle\Entity\IssuePriority');
        $resolution = $this->getMock('Oro\Bundle\IssueBundle\Entity\IssueResolution');
        $owner = $this->getMock('Oro\Bundle\UserBundle\Entity\User');
        $parent = $this->getMock('Oro\Bundle\IssueBundle\Entity\Issue');
        $reporter = $this->getMock('Oro\Bundle\UserBundle\Entity\User');
        $assignee = $this->getMock('Oro\Bundle\UserBundle\Entity\User');
        $tag = $this->getMock('Oro\Bundle\TagBundle\Entity\Tag');
        $flowItem = $this->getMock('Oro\Bundle\WorkflowBundle\Entity\WorkflowItem');
        $flowStep = $this->getMock('Oro\Bundle\WorkflowBundle\Entity\WorkflowStep');
        $organization = $this->getMock('Oro\Bundle\OrganizationBundle\Entity\Organization');
        $createdAt = '2015-01-01';
        $updatedAt = '2015-01-01';

        return [
            'id' => ['id', 1, 1],
            'summary'     => ['summary', $summary, $summary],
            'code' => ['code', $code, $expectedCode],
            'description' => ['description', $description, $description],
            'type' => ['type', $type, $type],
            'priority' => ['priority', $priority, $priority],
            'resolution' => ['resolution', $resolution, $resolution],
            'created' => ['createdAt', $createdAt, $createdAt],
            'updated' => ['updatedAt', $updatedAt, $updatedAt],
            'parent' => ['parent', $parent, $parent],
            'reporter' => ['reporter', $reporter, $reporter],
            'assignee' => ['assignee', $assignee, $assignee],
            'owner' => ['owner', $owner, $owner],
            'tag' => ['tags', $tag, $tag],
            'flowItem' => ['workflowItem', $flowItem, $flowItem],
            'flowStep' => ['workflowStep', $flowStep, $flowStep],
            'organization' => ['organization', $organization, $organization],
        ];
    }

    public function testCollaborators()
    {
        $mock = $this->getMockBuilder('Oro\Bundle\UserBundle\Entity\User')->getMock();
        $mock
            ->expects($this->exactly(3))
            ->method('getId')
            ->will($this->returnValue(self::TEST_ID));

        $this->assertCount(0, $this->entity->getCollaborators());
        $this->assertEquals($this->entity, $this->entity->addCollaborator($mock));
        $this->assertEquals(self::TEST_ID, $this->entity->getCollaborators()->get(0)->getId());
        $this->assertEquals(true, $this->entity->hasCollaborator($mock));
        $this->entity->removeCollaborator($mock);
        $this->assertCount(0, $this->entity->getCollaborators());
        $this->assertEquals(false, $this->entity->hasCollaborator($mock));
    }

    public function testRelated()
    {
        $mock = $this->getMockBuilder('Oro\Bundle\IssueBundle\Entity\Issue')->getMock();
        $mock
            ->expects($this->exactly(1))
            ->method('getId')
            ->will($this->returnValue(self::TEST_ID));

        $this->assertCount(0, $this->entity->getRelated());
        $this->assertEquals($this->entity, $this->entity->addRelated($mock));
        $this->assertEquals(self::TEST_ID, $this->entity->getRelated()->get(0)->getId());
        $this->entity->removeRelated($mock);
        $this->assertCount(0, $this->entity->getRelated());
    }

    public function testChildren()
    {
        $mock = $this->getMockBuilder('Oro\Bundle\IssueBundle\Entity\Issue')->getMock();
        $mock
            ->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(self::TEST_ID));

        $this->assertCount(0, $this->entity->getChildren());
        $this->assertEquals($this->entity, $this->entity->addChild($mock));
        $this->assertEquals(self::TEST_ID, $this->entity->getChildren()->get(0)->getId());
        $this->entity->removeChild($mock);
        $this->assertCount(0, $this->entity->getChildren());
    }

    public function testToString()
    {
        $this->entity->setSummary('test');
        $this->assertEquals('test', $this->entity);
    }

    public function testUpdatedTimestamp()
    {
        $this->entity->updatedTimestamps();
        $this->assertInstanceOf('DateTime', $this->entity->getCreatedAt());
    }
}
