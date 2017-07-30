<?php

namespace Example\Util\Crawler;

use JsonSerializable;

/**
 * Class Domain
 *
 * @package Example\Util\Crawler
 */
final class Domain implements JsonSerializable
{
    /** @var string */
    private $_domain;

    /** @var array */
    private $_links = [];

    /**
     * Domain constructor.
     *
     * @param string $domain
     */
    public function __construct(string $domain)
    {
        $this->_domain = $domain;
    }

    /**
     * Adds a link to the domain.
     *
     * @param string $link
     *
     * @return Domain
     */
    public function addLink(string $link): Domain
    {
        $this->_links[] = $link;

        return $this;
    }

    /**
     * Gets the domain.
     *
     * @return string
     */
    public function getDomain(): string
    {
        return $this->_domain;
    }

    /**
     * Gets the list of unique links.
     *
     * @return array
     */
    public function getLinks(): array
    {
        return array_unique($this->_links);
    }

    /**
     * Merges a list of links from another domain into this one.
     *
     * @param Domain $domain
     */
    public function merge(Domain $domain)
    {
        $this->_links = array_merge($this->_links, $domain->getLinks());
    }

    /**
     * {@inheritdoc}
     */
    function jsonSerialize()
    {
        return $this->getLinks();
    }
}