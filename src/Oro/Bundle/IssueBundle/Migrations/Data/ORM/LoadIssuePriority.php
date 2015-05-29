<?php

namespace Oro\Bundle\IssueBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Oro\Bundle\IssueBundle\Entity\IssuePriority;

class LoadIssuePriority extends AbstractFixture
{
    /**
     * @var array
     */
    protected $data = array(
        array(
            'name' => 'major',
            'label' => 'Major',
            'order' => 4,
        ),
        array(
            'name' => 'blocker',
            'label' => 'Blocker',
            'order' => 3,
        ),
        array(
            'name' => 'critical',
            'label' => 'Critical',
            'order' => 5,
        ),
        array(
            'name' => 'minor',
            'label' => 'Minor',
            'order' => 2,
        ),
        array(
            'name' => 'trivial',
            'label' => 'Trivial',
            'order' => 1,
        ),
    );

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->data as $item) {
            $entity = new IssuePriority();
            $entity->setLabel($item['label']);
            $entity->setName($item['name']);
            $entity->setOrder($item['order']);
            $manager->persist($entity);
        }

        $manager->flush();
    }
}
