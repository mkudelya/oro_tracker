<?php

namespace Oro\Bundle\IssueBundle\Tests\Functional\Controller\Api\Rest\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Oro\Bundle\IssueBundle\Entity\IssuePriority;

class LoadPriorityData extends AbstractFixture implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $priority = new IssuePriority();
        $priority->setLabel('test');
        $priority->setName('test');
        $priority->setOrder(1);

        $manager->persist($priority);
        $manager->flush();
    }
}
