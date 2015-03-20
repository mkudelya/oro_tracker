<?php

namespace Oro\Bundle\IssueBundle\Tests\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Oro\Bundle\IssueBundle\Entity\Issue;

class IssueData extends AbstractFixture implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $priorityEntity = $manager->getRepository('OroIssueBundle:IssuePriority')->find(1);
        $userEntity = $manager->getRepository('OroUserBundle:User')->find(1);

        $obj = new Issue();
        $obj->setSummary('summary');
        $obj->setCode('code');
        $obj->setType('task');
        $obj->setPriority($priorityEntity);
        $obj->setAssignee($userEntity);
        $obj->setReporter($userEntity);
        $obj->setOwner($userEntity);

        $manager->persist($obj);
        $manager->flush();
        $this->setReference('issue', $obj);
    }
}
