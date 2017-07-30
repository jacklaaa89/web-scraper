<?php

namespace Example\Tests\Controller;

use Example\Controller\IndexController;
use Example\Tests\BaseTestCase;
use Slim\Http\Response;

/**
 * Class IndexControllerTest
 *
 * vendor/bin/phpunit -c phpunit.xml --stderr --filter IndexControllerTest
 *
 * @package Example\Tests\Controller
 */
class IndexControllerTest extends BaseTestCase
{
    /** @const string */
    const INDEX_ACTION_EXPECTED_TEMPLATE = 'index/index.twig';

    /** @const int */
    const INDEX_ACTION_EXPECTED_STATUS_CODE = 200;

    /** @const string */
    const MALFORMED_URL = 'ww,malformed.c';

    /** @const array */
    const EXPECTED_RESPONSE_NO_URL_DEFINED = [
        'success' => false,
        'message' => 'URL was not supplied'
    ];

    /** @const array */
    const EXPECTED_RESPONSE_MALFORMED_URL = [
        'success' => false,
        'message' => 'supplied URL was not valid.'
    ];

    /**
     * Initialises a new IndexController for tests.
     *
     * @return IndexController
     */
    private function getController(): IndexController
    {
        return new IndexController($this->getContainer());
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter IndexControllerTest::testIndexAction
     *
     * As this action does depend on a certain view template, we can assert that the result from us
     * rendering it is the same as the controller action.
     */
    public function testIndexAction()
    {
        $controller = $this->getController();
        $expectedResult = $this->render(self::INDEX_ACTION_EXPECTED_TEMPLATE);
        $result = $controller->indexAction($this->getRequest());

        $this->assertInstanceOf(Response::class, $result);
        $this->assertEquals(self::INDEX_ACTION_EXPECTED_STATUS_CODE, $result->getStatusCode());
        $this->assertEquals($expectedResult->getBody()->getContents(), $result->getBody()->getContents());
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter IndexControllerTest::testScrapeActionWithNoRequestBody
     */
    public function testScrapeActionWithNoRequestBody()
    {
        $controller = $this->getController();
        $result = $controller->scrapeAction($this->getRequest(), $this->getResponse());

        $this->assertEquals(
            self::EXPECTED_RESPONSE_NO_URL_DEFINED,
            json_decode($result->getBody()->__toString(), true)
        );
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter IndexControllerTest::testScrapeActionWithMalformedUrl
     */
    public function testScrapeActionWithMalformedUrl()
    {
        $controller = $this->getController();
        $this->mockRequest(
            [ 'REQUEST_METHOD' => 'POST' ],
            [ 'url' => self::MALFORMED_URL ]
        );

        $result = $controller->scrapeAction($this->getRequest(), $this->getResponse());

        $this->assertEquals(
            self::EXPECTED_RESPONSE_MALFORMED_URL,
            json_decode($result->getBody()->__toString(), true)
        );
    }
}