<?php

namespace Oro\Bundle\IssueBundle\Tests\Functional\Controller\Api\Rest;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * @outputBuffering enabled
 * @dbIsolation
 */
class IssueControllerTest extends WebTestCase
{
    /** @var array */
    protected $issue = array(
        'oro_issue_api' => array(
            'code' => 'new code',
            'summary' => 'summary',
            'type' => 'task',
            'owner' => '1',
            'assignee' => '1',
            'reporter' => '1',
            'priority' => '1'
        )
    );

    protected function setUp()
    {
        $this->initClient(array(), $this->generateWsseAuthHeader());

        $this->loadFixtures(
            array(
                'Oro\Bundle\IssueBundle\Tests\Functional\DataFixtures\LoadPriorityData',
            )
        );
    }

    public function testCreate()
    {
        $this->client->request('POST', $this->getUrl('oro_api_post_issue'), $this->issue);

        $issue = $this->getJsonResponseContent($this->client->getResponse(), 201);

        return $issue['id'];
    }

    /**
     * @depends testCreate
     */
    public function testCget()
    {
        $this->client->request('GET', $this->getUrl('oro_api_get_issues'));
        $issues = $this->getJsonResponseContent($this->client->getResponse(), 200);

        $this->assertCount(1, $issues);
    }

    /**
     * @depends testCreate
     *
     * @param integer $id
     */
    public function testGet($id)
    {
        $this->client->request('GET', $this->getUrl('oro_api_get_issue', ['id' => $id]));
        $issue = $this->getJsonResponseContent($this->client->getResponse(), 200);

        $this->assertEquals($this->issue['oro_issue_api']['summary'], $issue['summary']);
    }

    /**
     * @depends testCreate
     *
     * @param integer $id
     */
    public function testPut($id)
    {
        $updatedIssue = $this->issue;
        $updatedIssue['oro_issue_api']['summary'] = 'Updated summary';
        $this->client->request('PUT', $this->getUrl('oro_api_put_issue', ['id' => $id]), $updatedIssue);
        $result = $this->client->getResponse();
        $this->assertEmptyResponseStatusCodeEquals($result, 204);

        $this->client->request('GET', $this->getUrl('oro_api_get_issue', ['id' => $id]));

        $issue = $this->getJsonResponseContent($this->client->getResponse(), 200);

        $this->assertEquals('Updated summary', $issue['summary']);
        $this->assertEquals($updatedIssue['oro_issue_api']['summary'], $issue['summary']);
    }

    /**
     * @depends testCreate
     *
     * @param integer $id
     */
    public function testDelete($id)
    {
        $this->client->request('DELETE', $this->getUrl('oro_api_delete_issue', ['id' => $id]));
        $result = $this->client->getResponse();
        $this->assertEmptyResponseStatusCodeEquals($result, 204);

        $this->client->request('GET', $this->getUrl('oro_api_get_issue', ['id' => $id]));
        $result = $this->client->getResponse();
        $this->assertJsonResponseStatusCodeEquals($result, 404);
    }
}
