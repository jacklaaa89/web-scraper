<?php

namespace Example\Tests\Util;

use Example\Util\Crawler;
use Exception;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Mockery;

/**
 * Class TestCrawler
 *
 * @package Example\Tests\Util\Crawler
 */
class TestCrawler extends Crawler
{
    /** @const string */
    const PROTOCOL_HTTP = 'http';

    /** @const string */
    const PROTOCOL_HTTPS = 'https';

    /** @const string */
    const REQUEST_URI_SUFFIX = '://www.test-request.com';

    /** @var string */
    private $requestUri;

    /** @var Response */
    private $response;

    /** @var Exception */
    private $exceptionToThrow;

    /**
     * TestCrawler constructor.
     *
     * @param bool      $useSecureProtocol
     * @param string    $responseBody
     * @param Exception $exceptionToThrow
     */
    public function __construct(
        bool $useSecureProtocol,
        string $responseBody,
        Exception $exceptionToThrow = null)
    {
        $protocol = $useSecureProtocol ? self::PROTOCOL_HTTPS : self::PROTOCOL_HTTP;
        $this->requestUri = $protocol . self::REQUEST_URI_SUFFIX;
        $this->response = new Response(200, [], $responseBody);
        $this->exceptionToThrow = $exceptionToThrow;
    }

    /**
     * @return string
     */
    public function getRequestUri(): string
    {
        return $this->requestUri;
    }

    /**
     * {@inheritdoc}
     *
     * We don't want to be making proper requests during tests, so we mock the response
     * body, this gives us control over what to expect in the response etc.
     */
    protected function getGuzzleClient(): GuzzleClient
    {
        $guzzleClient = Mockery::mock(GuzzleClient::class)->makePartial();
        $this->setRequest(new Request('GET', $this->requestUri));

        $expectation = $guzzleClient->shouldReceive('request')
            ->andReturn($this->response);

        if ($this->exceptionToThrow != null) {
            $expectation->andThrow($this->exceptionToThrow);
        }

        return $expectation->getMock();
    }
}