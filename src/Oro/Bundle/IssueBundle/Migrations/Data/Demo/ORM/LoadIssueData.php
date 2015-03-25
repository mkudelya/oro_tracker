<?php
namespace Oro\Bundle\IssueBundle\Migrations\Data\Demo\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Oro\Bundle\IssueBundle\Entity\Issue;

class LoadIssueData extends AbstractFixture implements ContainerAwareInterface, DependentFixtureInterface
{
    /** @var ContainerInterface */
    private $container;

    /** @var array $issues */
    protected $issues = array(
        array(
            'summary'  => 'My new story',
            'code'  => 'new story',
            'type'     => 'story',
            'reporter' => 'user_manager',
            'assignee' => 'user_admin',
            'owner' => 'user_user',
            'priority' => 'major',
        ),
        array(
            'summary'  => 'Create database',
            'code'  => 'database',
            'type'     => 'task',
            'reporter' => 'user_manager',
            'assignee' => 'user_user',
            'owner' => 'user_user',
            'priority' => 'major',
            'subtask' => true,
        ),
        array(
            'summary'  => 'Issue CRUD',
            'code'  => 'crud',
            'type'     => 'task',
            'reporter' => 'user_manager',
            'assignee' => 'user_user',
            'owner' => 'user_user',
            'priority' => 'major',
            'subtask' => true,
        ),
        array(
            'summary'  => 'Management',
            'code'  => 'management',
            'type'     => 'task',
            'reporter' => 'user_manager',
            'assignee' => 'user_manager',
            'owner' => 'user_user',
            'priority' => 'minor',
        ),
        array(
            'summary'  => 'Estimates',
            'code'  => 'estimates',
            'type'     => 'task',
            'reporter' => 'user_manager',
            'assignee' => 'user_user',
            'owner' => 'user_user',
            'priority' => 'major',
        ),
    );

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            'Oro\Bundle\IssueBundle\Migrations\Data\Demo\ORM\LoadUserData',
        ];
    }

    /**
     * Set container
     *
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $organization = $this->getReference('default_organization');
        $users = array(
            'user_admin' => $this->getReference('user_admin'),
            'user_manager' => $this->getReference('user_manager'),
            'user_user' => $this->getReference('user_user'),
        );

        $priorityRepository = $manager->getRepository('OroIssueBundle:IssuePriority');

        $priorities = array(
            'major' => $priorityRepository->findOneBy(['name' => 'major']),
            'minor' => $priorityRepository->findOneBy(['name' => 'minor'])
        );

        $story = null;

        foreach ($this->issues as $data) {
            $issue = new Issue();
            $issue->setOrganization($organization);
            $issue->setSummary($data['summary']);
            $issue->setCode($data['code']);
            $issue->setDescription($data['summary']);
            $issue->setAssignee($users[$data['assignee']]);
            $issue->setOwner($users[$data['assignee']]);
            $issue->setReporter($users[$data['reporter']]);
            $issue->setType($data['type']);
            $issue->setPriority($priorities[$data['priority']]);

            if ($data['type'] == 'story') {
                $story = $issue;
            }

            if (isset($data['subtask'])) {

                if (!$issue) {
                    continue;
                }

                $issue->setParent($story);
            }

            $manager->persist($issue);
        }

        $manager->flush();
    }
}
