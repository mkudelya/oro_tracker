<?php

namespace Oro\Bundle\IssueBundle\Tests\Functional\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Oro\Bundle\IssueBundle\Entity\Issue;

class LoadIssueData extends AbstractFixture implements ContainerAwareInterface, DependentFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return array('Oro\Bundle\IssueBundle\Tests\Functional\DataFixtures\LoadPriorityData');
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
