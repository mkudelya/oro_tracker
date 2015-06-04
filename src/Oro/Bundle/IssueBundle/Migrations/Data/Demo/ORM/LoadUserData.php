<?php
namespace Oro\Bundle\IssueBundle\Migrations\Data\Demo\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Oro\Bundle\DataAuditBundle\Tests\Unit\Fixture\Repository\UserRepository;
use Oro\Bundle\OrganizationBundle\Entity\BusinessUnit;

class LoadUserData extends AbstractFixture implements ContainerAwareInterface
{
    /** @var ContainerInterface */
    private $container;

    /**
     * Set container
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container  = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $organization = $manager->getRepository('OroOrganizationBundle:Organization')->getFirst();
        $adminRole = $manager
            ->getRepository('OroUserBundle:Role')
            ->findOneBy(array('role' => 'ROLE_ADMINISTRATOR'));
        $userManager = $this->container->get('oro_user.manager');
        $businessUnit = $manager->getRepository('OroOrganizationBundle:BusinessUnit')->findOneBy(['name' => 'Main']);
        $this->addReference('default_organization', $organization);
        $this->addReference(
            'user_admin',
            $manager->getRepository('OroUserBundle:User')->findOneBy(array('username' => 'admin'))
        );
        $manager = $userManager->createUser();
        $manager
            ->setUsername('manager')
            ->setPlainPassword('manager')
            ->setFirstName('Ellen')
            ->setLastName('Rowell')
            ->addRole($adminRole)
            ->setEmail('manager@oro-bts.dev')
            ->setOrganization($organization)
            ->addOrganization($organization)
            ->setBusinessUnits(
                new ArrayCollection(
                    array(
                        $businessUnit
                    )
                )
            );
        $userManager->updateUser($manager);
        $this->addReference('user_manager', $manager);
        $user = $userManager->createUser();
        $user
            ->setUsername('user')
            ->setPlainPassword('user')
            ->setFirstName('John')
            ->setLastName('Dow')
            ->addRole($adminRole)
            ->setEmail('user@oro-bts.dev')
            ->setOrganization($organization)
            ->addOrganization($organization)
            ->setBusinessUnits(
                new ArrayCollection(
                    array(
                        $businessUnit
                    )
                )
            );
        $userManager->updateUser($user);
        $this->addReference('user_user', $user);
    }
}
