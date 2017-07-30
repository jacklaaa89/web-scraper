<?php

namespace Example\Tests\Controller;

use Example\Tests\BaseTestCase;
use Example\Tests\TestMockService;
use Psr\Container\ContainerInterface;

/**
 * Class ContainerAwareTraitTest
 *
 * We cannot directly initialise traits, however as the BaseTestCase class inherits from ContainerGet
 * We can tests the methods directly.
 *
 * vendor/bin/phpunit -c phpunit.xml --stderr --filter ContainerAwareTraitTest
 *
 * @package Controller
 */
class ContainerAwareTraitTest extends BaseTestCase
{
    /** @const string */
    const TEST_SERVICE_ID = 'testService';

    /**
     * @return TestMockService
     */
    private function getMockService(): TestMockService
    {
        return new TestMockService();
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter ContainerAwareTraitTest::testGetContainer
     */
    public function testGetContainer()
    {
        $this->assertInstanceOf(ContainerInterface::class, $this->getContainer());
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter ContainerAwareTraitTest::testGet
     */
    public function testGet()
    {
        $mockService = $this->getMockService();
        $container = $this->getContainer();
        $container[self::TEST_SERVICE_ID] = $mockService;

        $this->assertEquals($mockService, $this->get(self::TEST_SERVICE_ID));
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter ContainerAwareTraitTest::testGet
     */
    public function testSet()
    {
        $mockService = $this->getMockService();
        $this->set(self::TEST_SERVICE_ID, $mockService);

        $this->assertEquals($mockService, $this->get(self::TEST_SERVICE_ID));
    }
}