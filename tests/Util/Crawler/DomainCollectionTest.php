<?php

namespace Example\Tests\Crawler;

use Example\Tests\BaseTestCase;
use Example\Util\Crawler\Domain;
use Example\Util\Crawler\DomainCollection;

/**
 * Class DomainCollectionTest
 *
 * vendor/bin/phpunit -c phpunit.xml --stderr --filter DomainCollectionTest
 *
 * @package Example\Tests\Crawler;
 */
class DomainCollectionTest extends BaseTestCase
{
    /** @const string */
    const MISSING_DOMAIN = 'www.missing.domain.com';

    /** @const int */
    const EXPECTED_LINK_COUNT = 1;

    /** @const string */
    const EXPECTED_DOMAIN_JSON = '{"www.test-request.com":["www.test-request.com\\/link"]}';

    /**
     * Creates a domain collection with one domain.
     *
     * @return DomainCollection
     */
    private function getDomainCollection(): DomainCollection
    {
        $domainCollection = new DomainCollection();
        $domain = new Domain(self::EXPECTED_DOMAIN);
        $domain->addLink(self::EXPECTED_LINK);
        $domainCollection->addDomain($domain);

        return $domainCollection;
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter DomainCollectionTest::testGetDomain
     */
    public function testGetDomain()
    {
        $domainCollection = $this->getDomainCollection();
        $domain = $domainCollection->getDomain(self::EXPECTED_DOMAIN);
        $this->assertInstanceOf(Domain::class, $domain);
        $this->assertEquals(self::EXPECTED_DOMAIN, $domain->getDomain());
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter DomainCollectionTest::testGetDomainWithMissingDomain
     */
    public function testGetDomainWithMissingDomain()
    {
        $domainCollection = $this->getDomainCollection();
        $domain = $domainCollection->getDomain(self::MISSING_DOMAIN);
        $this->assertNull($domain);
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter DomainCollectionTest::testAddExistingDomain
     */
    public function testAddExistingDomain()
    {
        $domainCollection = $this->getDomainCollection();
        $domain = new Domain(self::EXPECTED_DOMAIN);
        $domain->addLink(self::EXPECTED_LINK);

        //This should attempt to merge the domain together with the one that already
        //exists in the collection.
        $domainCollection->addDomain($domain);

        $domain = $domainCollection->getDomain(self::EXPECTED_DOMAIN);
        $links = $domain->getLinks();
        $this->assertCount(self::EXPECTED_LINK_COUNT, $links);
        $this->assertEquals([self::EXPECTED_LINK], $links);
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter DomainCollectionTest::testCount
     */
    public function testCount()
    {
        $domainCollection = $this->getDomainCollection();
        $this->assertCount(self::EXPECTED_LINK_COUNT, $domainCollection);
    }

    /**
     * vendor/bin/phpunit -c phpunit.xml --stderr --filter DomainCollectionTest::testJsonSerialize
     */
    public function testJsonSerialize()
    {
        $domainCollection = $this->getDomainCollection();
        $output = json_encode($domainCollection);
        $this->assertEquals(self::EXPECTED_DOMAIN_JSON, $output);
    }
}
