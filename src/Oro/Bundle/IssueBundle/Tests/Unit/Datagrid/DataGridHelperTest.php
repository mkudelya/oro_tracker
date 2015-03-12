<?php

namespace Oro\Bundle\IssueBundle\Tests\Unit\Datagrid;

use Oro\Bundle\IssueBundle\Datagrid\GridHelper;

class DataGridHelperTest extends \PHPUnit_Framework_TestCase
{
    /** @var \stdClass */
    protected $mock;

    protected function setUp()
    {
        $this->mock=$this->getMockBuilder('\stdClass')->setMethods(array('getName', 'getLabel', 'findAll'))->getMock();
        $this->mock->expects($this->any())
            ->method('getName')
            ->will($this->returnValue(true));
        $this->mock->expects($this->any())
            ->method('getLabel')
            ->will($this->returnValue(false));

        $array = array($this->mock);

        $this->mock->expects($this->any())
            ->method('findAll')
            ->will($this->returnValue($array));
    }

    protected function tearDown()
    {
        unset($this->helper);
    }

    public function testGetPriorityChoices()
    {
        $mockEntityManager = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockEntityManager
            ->expects($this->any())
            ->method('getRepository')
            ->with($this->equalTo('OroIssueBundle:IssuePriority'))
            ->will($this->returnValue($this->mock));

        $helper = new GridHelper($mockEntityManager);

        $this->assertCount(1, $helper->getPriorityChoices());
    }

    public function testGetResolutionChoices()
    {
        $mockEntityManager = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockEntityManager
            ->expects($this->any())
            ->method('getRepository')
            ->with($this->equalTo('OroIssueBundle:IssueResolution'))
            ->will($this->returnValue($this->mock));

        $helper = new GridHelper($mockEntityManager);

        $this->assertCount(1, $helper->getResolutionChoices());
    }
}
