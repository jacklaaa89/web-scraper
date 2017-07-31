<?php

namespace Example\Tests\Crawler;

use Example\Tests\BaseTestCase;
use Example\Util\Crawler\Domain;

/**
 * Class DomainTest
 *
 * vendor/bin/phpunit -c phpunit.xml --stderr --filter DomainTest
 *
 * @package Example\Tests\Crawler;
 */
class DomainTest extends BaseTestCase
{
    /** @const array */
    const EXPECTED_LINKS_ONE_LINK = [
        self::EXPECTED_LINK
    ];

    /** @const int */
    const EXPECTED_COUNT_TWO_LINKS = 2;

    /** @const array */
    const EXPECTED_LINKS = [
        self::EXPECTED_LINK,
        self::EXPECTED_LINK_2
    ];

    /** @const int */
    const EXPECTED_COUNT = 1;

    /** @const string */
    const EXPECTED_LINK_2 = self::EXPECTED_DOMAIN . '/link2';

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter DomainTest::testConstruct
     *
     * @return Domain
     */
    public function testConstruct(): Domain
    {
        $domain = new Domain(self::EXPECTED_DOMAIN);
        $this->assertInstanceOf(Domain::class, $domain);
        $this->assertEquals(self::EXPECTED_DOMAIN, $domain->getDomain());

        return $domain;
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter DomainTest::testGetDomain
     */
    public function testGetDomain()
    {
        $domain = $this->testConstruct();
        $this->assertEquals(self::EXPECTED_DOMAIN, $domain->getDomain());
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter DomainTest::testAddLink
     *
     * @return Domain
     */
    public function testAddLink(): Domain
    {
        $domain = $this->testConstruct();
        $domain->addLink(self::EXPECTED_LINK);
        $this->assertCount(self::EXPECTED_COUNT, $domain->getLinks());

        return $domain;
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter DomainTest::testGetLinks
     */
    public function testGetLinks()
    {
        $domain = $this->testAddLink();
        $links = $domain->getLinks();
        $this->assertCount(self::EXPECTED_COUNT, $links);
        $this->assertEquals(self::EXPECTED_LINKS_ONE_LINK, $links);
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter DomainTest::testMerge
     */
    public function testMerge()
    {
        $domain1 = $this->testAddLink();
        $domain2 = $this->testAddLink();
        $domain2->addLink(self::EXPECTED_LINK_2);

        $domain1->merge($domain2);
        $links = $domain1->getLinks();
        $this->assertCount(self::EXPECTED_COUNT_TWO_LINKS, $links);
        $this->assertEquals(self::EXPECTED_LINKS, $links);
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter DomainTest::testJsonSerialize
     */
    public function testJsonSerialize()
    {
        $domain = $this->testAddLink();
        $this->assertEquals(self::EXPECTED_LINKS_ONE_LINK, $domain->jsonSerialize());
    }
}
