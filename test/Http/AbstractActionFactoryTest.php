<?php

namespace AppTest\Http;

use App\Http\AbstractActionFactory;
use AppTest\TestAsset\TestAction;
use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Zend\Expressive\Template\TemplateRendererInterface;

class AbstractActionFactoryTest extends TestCase
{
    /**
     * @var ContainerInterface|ObjectProphecy
     */
    private $container;

    protected function setUp()
    {
        $this->container = $this->prophesize(ContainerInterface::class);
    }

    public function testCanCreateInstanceForAction()
    {
        $factory = new AbstractActionFactory();

        $this->assertTrue($factory->canCreate($this->container->reveal(), 'TestAction'));
    }

    public function testCanNotCreateInstanceForInvalidActionClass()
    {
        $factory = new AbstractActionFactory();

        $this->assertFalse($factory->canCreate($this->container->reveal(), 'SomeMiddleware'));
    }

    public function testCreateObjectForTestAction()
    {
        $templateRenderer = $this->prophesize(TemplateRendererInterface::class);
        $this->container->get(TemplateRendererInterface::class)->shouldBeCalled()->willReturn($templateRenderer->reveal());

        $factory = new AbstractActionFactory();
        $object = $factory($this->container->reveal(), TestAction::class);

        $this->assertInstanceOf(TestAction::class, $object);
    }
}
