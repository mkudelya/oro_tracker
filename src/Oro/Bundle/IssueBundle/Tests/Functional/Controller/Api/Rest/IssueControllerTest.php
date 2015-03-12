<?php

namespace Oro\Bundle\IssueBundle\Tests\Functional\Controller\Api\Rest;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * @outputBuffering enabled
 * @dbIsolation
 */
class TaskControllerTest extends WebTestCase
{
    /** @var array */
    protected $issue = array(
        'code' => 'new code',
        'summary' => 'summary',
        'type' => 'task'
    );

    protected function setUp()
    {
        $this->initClient(array(), $this->generateWsseAuthHeader());

//        if (!isset($this->issue['owner'])) {
//            $this->issue['owner'] = $this->getContainer()
//                ->get('doctrine')
//                ->getRepository('OroUserBundle:User')
//                ->findOneBy(['username' => self::USER_NAME])->getId();
//        }
    }

    public function testCreate()
    {
        $this->client->request('POST', $this->getUrl('oro_api_post_issue'), $this->issue);

        $issue = $this->getJsonResponseContent($this->client->getResponse(), 201);

        return $issue['id'];
    }

    /**
     * depends testCreate
     */
//    public function testCget()
//    {
//        $this->client->request('GET', $this->getUrl('oro_api_get_issue'));
//        $tasks = $this->getJsonResponseContent($this->client->getResponse(), 200);
//
//        $this->assertCount(1, $tasks);
//    }
}