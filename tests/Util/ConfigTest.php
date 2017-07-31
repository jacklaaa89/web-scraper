<?php

namespace Example\Tests\Util;

use Example\Tests\BaseTestCase;
use Example\Util\Config;
use Exception;

/**
 * Class ConfigTest
 *
 * vendor/bin/phpunit -c phpunit.xml --stderr --filter ConfigTest
 *
 * @package Example\Tests\Util;
 */
class ConfigTest extends BaseTestCase
{
    /** @const string */
    const KEY_TEST = 'test';

    /** @const string */
    const KEY_TEST_CONFIG = 'test.config';

    /** @const string */
    const INVALID_KEY = 'invalid';

    /** @const int */
    const EXPECTED_RESULT_FROM_KEY_TEST_CONFIG = 1;

    /** @const array */
    const EXPECTED_RESULT_FROM_KEY_TEST = [
        'config' => self::EXPECTED_RESULT_FROM_KEY_TEST_CONFIG
    ];

    /** @const string */
    const CONFIG_LOCATION = APP_PATH .  '/tests/Util/test-config.php';

    /** @const string */
    const INVALID_CONFIG_LOCATION = APP_PATH .  '/tests/Util/invalid-config.php';

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter ConfigTest::testConstruct
     *
     * @return Config
     */
    public function testConstruct(): Config
    {
        $config = new Config(self::CONFIG_LOCATION);
        $this->assertInstanceOf(Config::class, $config);

        return $config;
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter ConfigTest::testConstructWithInvalidPhpFile
     */
    public function testConstructWithInvalidPhpFile()
    {
        $this->expectException(Exception::class);
        new Config(self::INVALID_CONFIG_LOCATION);
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter ConfigTest::testGet
     */
    public function testGet()
    {
        $result = $this->testConstruct()->get(self::KEY_TEST);
        $this->assertEquals(self::EXPECTED_RESULT_FROM_KEY_TEST, $result);
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter ConfigTest::testGetWithNamespacedKey
     */
    public function testGetWithNamespacedKey()
    {
        $result = $this->testConstruct()->get(self::KEY_TEST_CONFIG);
        $this->assertEquals(self::EXPECTED_RESULT_FROM_KEY_TEST_CONFIG, $result);
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter ConfigTest::testGetWithInvalidKey
     */
    public function testGetWithInvalidKey()
    {
        $this->assertNull($this->testConstruct()->get(self::INVALID_KEY));
    }
}
