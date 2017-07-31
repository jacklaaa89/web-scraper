<?php

namespace Example\Tests\Core;

use ArrayIterator;
use Example\Core\Route;
use Example\Core\RouteCollection;
use Example\Tests\BaseTestCase;

/**
 * Class RouteCollectionTest
 *
 * vendor/bin/phpunit -c phpunit.xml --stderr --filter RouteCollectionTest
 *
 * @package Example\Tests\Core;
 */
class RouteCollectionTest extends BaseTestCase
{
    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter RouteCollectionTest::testConstruct
     *
     * @return RouteCollection
     */
    public function testConstruct(): RouteCollection
    {
        $routeCollection = new RouteCollection([
            new Route('/route', TestController::class, 'test')
        ]);

        $this->assertInstanceOf(RouteCollection::class, $routeCollection);

        return $routeCollection;
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter RouteCollectionTest::testCount
     */
    public function testCount()
    {
        $this->assertEquals(1, $this->testConstruct()->count());
        $this->assertCount(1, $this->testConstruct());
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter RouteCollectionTest::testGetIterator
     */
    public function testGetIterator()
    {
        $iterator = $this->testConstruct()->getIterator();
        $this->assertInstanceOf(ArrayIterator::class, $iterator);
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter RouteCollectionTest::testAddRoute
     */
    public function testAddRoute()
    {
        $routeCollection = $this->testConstruct();
        $routeCollection->addRoute(
            new Route('/another-route', TestController::class, 'test')
        );
        $this->assertCount(2, $routeCollection);
    }
}
