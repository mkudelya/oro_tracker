<?php

namespace Oro\Bundle\IssueBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Oro\Bundle\IssueBundle\Entity\IssueResolution;

class LoadIssueResolution extends AbstractFixture
{
    /**
     * @var array
     */
    protected static $data = array(
        array(
            'name' => 'fixed',
            'label' => 'Fixed'
        ),
        array(
            'name' => 'wont_fix',
            'label' => 'Won\'t Fix'
        ),
        array(
            'name' => 'duplicate',
            'label' => 'Duplicate'
        ),
        array(
            'name' => 'incomplete',
            'label' => 'Incomplete'
        ),
        array(
            'name' => 'cannot_reproduce',
            'label' => 'Cannot Reproduce'
        ),
        array(
            'name' => 'done',
            'label' => 'Done'
        ),
        array(
            'name' => 'wont_do',
            'label' => 'Won\'t Do'
        ),
    );

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach (self::$data as $item) {
            $entity = new IssueResolution();
            $entity->setLabel($item['label']);
            $entity->setName($item['name']);
            $manager->persist($entity);
        }

        $manager->flush();
    }
}
