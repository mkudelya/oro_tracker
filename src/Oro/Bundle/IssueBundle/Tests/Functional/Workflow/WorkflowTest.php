<?php

namespace Oro\Bundle\IssueBundle\Tests\Functional\Controller;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;
use Oro\Bundle\WorkflowBundle\Model\WorkflowManager;

/**
 * @outputBuffering enabled
 * @dbIsolation
 */
class WorkflowTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var WorkflowManager
     */
    protected $workflowManager;

    protected function setUp()
    {
        $this->initClient(array(), $this->generateBasicAuthHeader());
        $this->em = static::$kernel->getContainer()->get('doctrine')->getManager();
        $this->workflowManager = static::$kernel->getContainer()->get('oro_workflow.manager');
    }

    public function testWorkflow()
    {
        $crawler = $this->client->request('GET', $this->getUrl('oro_issue_create'));
        $form = $crawler->selectButton('Save and Close')->form();
        $form['oro_issue[code]'] = 'new code';
        $form['oro_issue[summary]'] = 'summary';
        $form['oro_issue[type]'] = 'task';

        $this->client->followRedirects(true);
        $this->client->submit($form);

        $response = $this->client->requestGrid(
            'issue-grid',
            array('issue-grid[_filter][code][value]' => 'NEWCODE')
        );

        $result = $this->getJsonResponseContent($response, 200);
        $result = reset($result['data']);

        $issue = $this->em
            ->getRepository('OroIssueBundle:Issue')
            ->find($result['id']);

        $workflowItem = $this->workflowManager->getWorkflowItemByEntity($issue);
        $this->assertEquals('open', $workflowItem->getCurrentStep()->getName());

        $this->workflowManager->transit($workflowItem, 'start_progress');
        $this->assertEquals('in_progress', $workflowItem->getCurrentStep()->getName());

        $this->workflowManager->transit($workflowItem, 'resolve');
        $this->assertEquals('resolved', $workflowItem->getCurrentStep()->getName());

        $this->workflowManager->transit($workflowItem, 'close');
        $this->assertEquals('closed', $workflowItem->getCurrentStep()->getName());

        $this->workflowManager->transit($workflowItem, 'reopen');
        $this->assertEquals('reopen', $workflowItem->getCurrentStep()->getName());

        $this->workflowManager->transit($workflowItem, 'close');
        $this->assertEquals('closed', $workflowItem->getCurrentStep()->getName());

        $this->workflowManager->transit($workflowItem, 'reopen');
        $this->assertEquals('reopen', $workflowItem->getCurrentStep()->getName());

        $this->workflowManager->transit($workflowItem, 'resolve');
        $this->assertEquals('resolved', $workflowItem->getCurrentStep()->getName());

        $this->workflowManager->transit($workflowItem, 'reopen');
        $this->assertEquals('reopen', $workflowItem->getCurrentStep()->getName());
    }
}
