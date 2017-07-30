<?php

namespace Example\Util\Crawler;

use InvalidArgumentException;

/**
 * Class UrlValueObject
 *
 * @package Example\Util\Crawler
 */
final class UrlValueObject
{
    /** @const string */
    const DEFAULT_HOST = '/';

    /** @var string */
    private $_host;

    /**
     * UrlValueObject constructor.
     *
     * @param array $urlBreakdown
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $urlBreakdown)
    {
        if (!isset($urlBreakdown['host'])) {
            $urlBreakdown['host'] = self::DEFAULT_HOST;
        }

        $this->_host = $urlBreakdown['host'];
    }

    /**
     * Gets the host, this will be the default host if a host was not passed.
     *
     * @return string
     */
    public function getHost(): string
    {
        return $this->_host;
    }
}