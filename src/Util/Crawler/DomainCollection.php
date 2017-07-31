<?php

namespace Example\Util\Crawler;

use Countable;
use JsonSerializable;

/**
 * Class DomainCollection
 *
 * @package Example\Util\Crawler
 */
final class DomainCollection implements JsonSerializable, Countable
{
    /** @var Domain[] */
    private $_domains = [];

    /**
     * Adds a domain to the collection.
     *
     * @param Domain $domain
     *
     * @return DomainCollection
     */
    public function addDomain(Domain $domain): DomainCollection
    {
        $domainName = $domain->getDomain();
        if ($this->hasDomain($domainName)) {
            $domain->merge($this->getDomain($domainName));
        }

        $this->_domains[$domainName] = $domain;

        return $this;
    }

    /**
     * gets a domain from the collection from its name.
     *
     * @param string $domain
     *
     * @return Domain|null
     */
    public function getDomain(string $domain)
    {
        if (!array_key_exists($domain, $this->_domains)) {
            return null;
        }

        return $this->_domains[$domain];
    }

    /**
     * Whether the domain exists.
     *
     * @param string $domain
     *
     * @return bool
     */
    public function hasDomain(string $domain): bool
    {
        return array_key_exists($domain, $this->_domains);
    }

    /**
     * Gets a list of the found domains, without the links.
     *
     * @return array
     */
    public function getDomainList(): array
    {
        return array_keys($this->_domains);
    }

    /**
     * {@inheritdoc}
     */
    function jsonSerialize()
    {
        return $this->_domains;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->_domains);
    }
}