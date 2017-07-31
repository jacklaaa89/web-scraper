<?php

namespace Example\Tests\Util;

use Example\Tests\BaseTestCase;
use Example\Util\Crawler;

/**
 * Class CrawlerTest
 *
 * vendor/bin/phpunit -c phpunit.xml --stderr --filter CrawlerTest
 *
 * @package Example\Tests\Util\Crawler
 */
class CrawlerTest extends BaseTestCase
{
    /** @const string */
    const EXPECTED_TITLE = 'Test Content';

    /** @const int */
    const EXPECTED_TWO_LINKS = 2;

    /** @const string */
    const VIEW_WITH_TWO_LINKS_NO_GOOGLE_ANALYTICS = 'test/two_links_no_ga.twig';

    /**
     * Gets the test crawler.
     *
     * @param string $responseBody
     * @param bool $useSecureProtocol
     *
     * @return TestCrawler
     */
    public function getTestCrawler(string $responseBody, bool $useSecureProtocol = false): TestCrawler
    {
        return new TestCrawler($useSecureProtocol, $responseBody);
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter CrawlerTest::testLoadWithNoGoogleAnalytics
     *
     * @return Crawler
     */
    public function testLoadWithNoGoogleAnalytics(): Crawler
    {
        $response = $this->render(
            self::VIEW_WITH_TWO_LINKS_NO_GOOGLE_ANALYTICS
        )->getBody()->__toString();

        $crawler = $this->getTestCrawler($response);
        $crawler->load($crawler->getRequestUri());

        $linkCount = $crawler->getLinkCount();
        $this->assertEquals(self::EXPECTED_TWO_LINKS, $linkCount);

        return $crawler;
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter CrawlerTest::testLoadWithGoogleAnalytics
     *
     * @return Crawler
     */
    public function testLoadWithGoogleAnalytics(): Crawler
    {
        $response = $this->render(
            self::VIEW_WITH_TWO_LINKS_WITH_GOOGLE_ANALYTICS
        )->getBody()->__toString();

        $crawler = $this->getTestCrawler($response);
        $crawler->load($crawler->getRequestUri());

        $linkCount = $crawler->getLinkCount();
        $this->assertEquals(self::EXPECTED_TWO_LINKS, $linkCount);

        return $crawler;
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter CrawlerTest::testLoadWithSecureRequest
     *
     * @return Crawler
     */
    public function testLoadWithSecureRequest(): Crawler
    {
        $response = $this->render(
            self::VIEW_WITH_TWO_LINKS_WITH_GOOGLE_ANALYTICS
        )->getBody()->__toString();

        $crawler = $this->getTestCrawler($response, true);
        $crawler->load($crawler->getRequestUri());

        $linkCount = $crawler->getLinkCount();
        $this->assertEquals(self::EXPECTED_TWO_LINKS, $linkCount);

        return $crawler;
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter CrawlerTest::testGetTitle
     */
    public function testGetTitle()
    {
        $crawler = $this->testLoadWithNoGoogleAnalytics();
        $this->assertEquals(self::EXPECTED_TITLE, $crawler->getTitle());
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter CrawlerTest::testIsGoogleAnalyticsPresentWithNonePresent
     */
    public function testIsGoogleAnalyticsPresentWithNonePresent()
    {
        $crawler = $this->testLoadWithNoGoogleAnalytics();
        $this->assertFalse($crawler->isGoogleAnalyticsPresent());
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter CrawlerTest::testIsGoogleAnalyticsPresentWhenPresent
     */
    public function testIsGoogleAnalyticsPresentWhenPresent()
    {
        $crawler = $this->testLoadWithGoogleAnalytics();
        $this->assertTrue($crawler->isGoogleAnalyticsPresent());
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter CrawlerTest::testWasSecureRequestOnSecureRequest
     */
    public function testWasSecureRequestOnSecureRequest()
    {
        $crawler = $this->testLoadWithSecureRequest();
        $this->assertTrue($crawler->wasSecureRequest());
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter CrawlerTest::testWasSecureRequestOnNonSecureRequest
     */
    public function testWasSecureRequestOnNonSecureRequest()
    {
        $crawler = $this->testLoadWithNoGoogleAnalytics();
        $this->assertFalse($crawler->wasSecureRequest());
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter CrawlerTest::testGetDomainsWithoutLinks
     */
    public function testGetDomainsWithoutLinks()
    {
        $crawler = $this->testLoadWithNoGoogleAnalytics();
        $domains = $crawler->getDomains(false);

        $this->assertInternalType('array', $domains);
        $this->assertEquals(self::EXPECTED_DOMAIN_LIST_NO_LINKS, $domains);
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter CrawlerTest::testGetDomainsWithLinks
     */
    public function testGetDomainsWithLinks()
    {
        $crawler = $this->testLoadWithNoGoogleAnalytics();
        $domains = $crawler->getDomains(true);

        $this->assertCount(self::EXPECTED_TWO_LINKS, $domains);
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter CrawlerTest::testJsonSerialize
     */
    public function testJsonSerialize()
    {
        $crawler = $this->testLoadWithSecureRequest();
        $result = $crawler->jsonSerialize();

        $this->assertEquals(self::EXPECTED_JSON_SERIALIZE_RESULT, $result);
    }
}