<?php

namespace Oro\Bundle\IssueBundle\Migrations\Data\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use Oro\Bundle\DashboardBundle\Migrations\Data\ORM\AbstractDashboardFixture;

/**
 * @codeCoverageIgnore
 */
class LoadDashboardData extends AbstractDashboardFixture implements DependentFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return array(
            'Oro\Bundle\UserBundle\Migrations\Data\ORM\LoadAdminUserData',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $main = $this->findAdminDashboardModel($manager, 'main');

        if ($main) {
            $main->addWidget($this->createWidgetModel('issue_status_bar_chart'));
            $main->addWidget($this->createWidgetModel('issue_short_grid'));
            $manager->flush();
        }
    }
}
