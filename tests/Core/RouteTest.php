<?php

namespace Example\Tests\Core;

use Example\Core\Route;
use Example\Tests\BaseTestCase;
use Example\Tests\TestMockService;
use InvalidArgumentException;
use Slim\Http\Response;

/**
 * Class RouteTest
 *
 * vendor/bin/phpunit -c phpunit.xml --stderr --filter RouteTest
 *
 * @package Example\Tests\Core;
 */
class RouteTest extends BaseTestCase
{
    /** @const string */
    const VALID_ACTION = 'test';

    /** @const string */
    const INVALID_ACTION = 'index';

    /** @const string */
    const VALID_METHOD = 'GET';

    /** @const string */
    const INVALID_METHOD = 'INVALID';

    /** @const string */
    const ROUTE_PATTERN = '/route';

    /** @const string */
    const VALID_CONTROLLER = TestController::class;

    /** @const string */
    const INVALID_CONTROLLER = TestMockService::class;

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter RouteTest::testConstruct
     *
     * @return Route
     */
    public function testConstruct(): Route
    {
        $route = new Route(
            self::ROUTE_PATTERN,
            self::VALID_CONTROLLER,
            self::VALID_ACTION,
            self::VALID_METHOD
        );

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals(self::ROUTE_PATTERN, $route->getPattern());

        return $route;
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter RouteTest::testConstructWithNonControllerClass
     */
    public function testConstructWithNonControllerClass()
    {
        $this->expectException(InvalidArgumentException::class);
        new Route(self::ROUTE_PATTERN, self::INVALID_CONTROLLER, self::VALID_ACTION);
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter RouteTest::testConstructWithMissingControllerAction
     */
    public function testConstructWithMissingControllerAction()
    {
        $this->expectException(InvalidArgumentException::class);
        new Route(self::ROUTE_PATTERN, self::VALID_CONTROLLER, self::INVALID_ACTION);
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter RouteTest::testConstructWithInvalidMethod
     */
    public function testConstructWithInvalidMethod()
    {
        $this->expectException(InvalidArgumentException::class);
        new Route(self::ROUTE_PATTERN, self::VALID_CONTROLLER, self::VALID_ACTION, self::INVALID_METHOD);
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter RouteTest::testGetPattern
     */
    public function testGetPattern()
    {
        $this->assertEquals(self::ROUTE_PATTERN, $this->testConstruct()->getPattern());
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter RouteTest::testGetMethods
     */
    public function testGetMethods()
    {
        $this->assertEquals([self::VALID_METHOD], $this->testConstruct()->getMethods());
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter RouteTest::testGetControllerClass
     */
    public function testGetControllerClass()
    {
        $this->assertEquals(self::VALID_CONTROLLER, $this->testConstruct()->getControllerClass());
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter RouteTest::testGetAction
     */
    public function testGetAction()
    {
        $this->assertEquals(
            self::VALID_ACTION . Route::ACTION_PREFIX,
            $this->testConstruct()->getAction()
        );
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter RouteTest::testToCallable
     */
    public function testToCallable()
    {
        $route = $this->testConstruct();
        $callable = $route->toCallable($this->getContainer());

        //Call the callable for coverage.
        $result = $callable();

        $this->assertInstanceOf(Response::class, $result);
    }
}
