<?php

namespace Oro\Bundle\IssueBundle\Tests\Functional\Controller;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * @outputBuffering enabled
 * @dbIsolation
 */
class NoteTest extends WebTestCase
{
    protected function setUp()
    {
        $this->initClient([], $this->generateBasicAuthHeader());

        $this->loadFixtures(
            [
                'Oro\Bundle\IssueBundle\Tests\Functional\DataFixtures\LoadIssueData'
            ]
        );
    }

    public function testNote()
    {
        $crawler = $this->client->request(
            'GET',
            $this->getUrl('oro_issue_view', array('id' => $this->getReference('issue')->getId()))
        );

        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertEquals(1, $crawler->filter('a[title="Add note"]')->count());
    }
}
