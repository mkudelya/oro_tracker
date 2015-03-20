<?php

namespace Oro\Bundle\IssueBundle\Tests\Unit\Form\Type;

use Oro\Bundle\IssueBundle\Form\Type\IssueApiType;

class IssueApiTypeTest extends \PHPUnit_Framework_TestCase
{
    /** @var IssueApiType */
    protected $type;

    protected function setUp()
    {
        $this->type = new IssueApiType();
    }

    protected function tearDown()
    {
        unset($this->type);
    }

    public function testFields()
    {
        $builder = $this->getMockBuilder('Symfony\Component\Form\FormBuilder')
            ->disableOriginalConstructor()
            ->getMock();

        $builder->expects($this->exactly(9))
        ->method('add')
        ->will($this->returnSelf());

        $this->type->buildForm($builder, []);
    }

    public function testSetDefaultOptions()
    {
        $resolver = $this->getMock('Symfony\Component\OptionsResolver\OptionsResolverInterface');
        $resolver->expects($this->once())
            ->method('setDefaults')
            ->with($this->isType('array'));
        $this->type->setDefaultOptions($resolver);
    }

    public function testHasName()
    {
        $this->assertEquals('oro_issue_api', $this->type->getName());
    }

    public function testIssueTypes()
    {
        $this->assertInternalType('array', $this->type->getIssueTypes());
    }
}
