<?php

namespace Example\Tests\Controller;

use Example\Controller\Constants;
use Example\Tests\BaseTestCase;
use Mockery;
use Slim\Http\Response;
use Slim\Views\Twig;

/**
 * Class ContainerGetTest
 *
 * We cannot directly initialise traits, however as the BaseTestCase class inherits from ContainerGet
 * We can tests the methods directly.
 *
 * vendor/bin/phpunit -c phpunit.xml --stderr --filter ContainerGetTest
 *
 * @package Example\Tests\Controller
 */
class ContainerGetTest extends BaseTestCase
{
    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter ContainerGetTest::testGetView
     */
    public function testGetView()
    {
        $this->assertInstanceOf(Twig::class, $this->getView());
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter ContainerGetTest::testGetResponse
     */
    public function testGetResponse()
    {
        $this->assertInstanceOf(Response::class, $this->getResponse());
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter ContainerGetTest::testRender
     *
     * As we cannot assume that a view will always exist for testing, and a view getting moved or changed
     * should not fail this test, we mock the Twig objects render method.
     *
     * This asserts that the correct type is returned, and that the view object is correctly
     * retrieved from the container.
     */
    public function testRender()
    {
        $mockTwigEngine = Mockery::mock(Twig::class)->makePartial()
            ->shouldReceive('render')
            ->once()
            ->andReturn($this->getResponse())
            ->getMock();

        $this->set(Constants::VIEW, $mockTwigEngine);

        $this->assertInstanceOf(Response::class, $this->render('test/template'));
    }
}