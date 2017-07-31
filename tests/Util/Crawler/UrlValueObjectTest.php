<?php

namespace Example\Tests\Crawler;

use Example\Tests\BaseTestCase;
use Example\Util\Crawler\UrlValueObject;

/**
 * Class UrlValueObjectTest
 *
 * vendor/bin/phpunit -c phpunit.xml --stderr --filter UrlValueObjectTest
 *
 * @package Example\Tests\Crawler;
 */
class UrlValueObjectTest extends BaseTestCase
{
    /** @const array */
    const URL_PARAMS = [
        'protocol' => 'http',
        'host' => self::HOST,
        'path' => '/link'
    ];

    /** @const string */
    const HOST = 'www.test-domain.com';

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter UrlValueObjectTest::testConstruct
     *
     * @return UrlValueObject
     */
    public function testConstruct(): UrlValueObject
    {
        $urlValueObject = new UrlValueObject(self::URL_PARAMS);
        $this->assertEquals(self::HOST, $urlValueObject->getHost());

        return $urlValueObject;
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter UrlValueObjectTest::testConstructWithoutProvidingHost
     */
    public function testConstructWithoutProvidingHost()
    {
        $urlValueObject = new UrlValueObject(self::EMPTY_SET);
        $this->assertEquals(UrlValueObject::DEFAULT_HOST, $urlValueObject->getHost());
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter UrlValueObjectTest::testGetHost
     */
    public function testGetHost()
    {
        $urlValueObject = $this->testConstruct();
        $this->assertEquals(self::HOST, $urlValueObject->getHost());
    }
}
