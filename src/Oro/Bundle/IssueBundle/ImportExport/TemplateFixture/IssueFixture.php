<?php

namespace Oro\Bundle\IssueBundle\ImportExport\TemplateFixture;

use Oro\Bundle\ImportExportBundle\TemplateFixture\AbstractTemplateRepository;
use Oro\Bundle\ImportExportBundle\TemplateFixture\TemplateFixtureInterface;

use Oro\Bundle\IssueBundle\Entity\Issue;
use Oro\Bundle\IssueBundle\Entity\IssuePriority;
use Oro\Bundle\IssueBundle\Entity\IssueResolution;

class IssueFixture extends AbstractTemplateRepository implements TemplateFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    protected function createEntity($key)
    {
        return new Issue();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityClass()
    {
        return 'Oro\Bundle\IssueBundle\Entity\Issue';
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->getEntityData('Issue');
    }

    /**
     * @param string $key
     * @param Issue $entity
     */
    public function fillEntityData($key, $entity)
    {
        $user = $this->templateManager
            ->getEntityRepository('Oro\Bundle\UserBundle\Entity\User')
            ->getEntity('John Dow');
        $organization = $this->templateManager
            ->getEntityRepository('Oro\Bundle\OrganizationBundle\Entity\Organization')
            ->getEntity('default');
        $priority = new IssuePriority();
        $priority->setName('Major');
        $resolution = new IssueResolution();
        $resolution->setName('Fixed');

        switch ($key) {
            case 'Issue':
                $entity->setCode('new code')
                    ->setSummary('Summary example')
                    ->setDescription('Description example')
                    ->setOwner($user)
                    ->setAssignee($user)
                    ->setReporter($user)
                    ->setOrganization($organization)
                    ->setPriority($priority)
                    ->setResolution($resolution)
                    ->setType('task');
                return;
        }

        parent::fillEntityData($key, $entity);
    }
}
