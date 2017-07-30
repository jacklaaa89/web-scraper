<?php

namespace Example\Util;

use Closure;
use Example\Util\Crawler\Domain;
use Example\Util\Crawler\DomainCollection;
use Example\Util\Crawler\UrlValueObject;
use Goutte\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use JsonSerializable;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler as InternalCrawler;

/**
 * Class Crawler
 *
 * @package Example\Util
 */
class Crawler implements JsonSerializable
{
    /** @const array */
    const GA_TAGS = [
      'ga.js', //google-analytics
      'gtm.js' //google-tag-manager
    ];

    /** @var InternalCrawler */
    private $_internalCrawler;

    /** @var string */
    private $_url;

    /** @var Request */
    private $_request;

    /**
     * Loads a url.
     *
     * @param string $url
     */
    public function load(string $url)
    {
        $client = new Client();
        $client->setClient($this->getGuzzleClient());
        $this->_internalCrawler = $client->request('GET', $url);
        $this->_url = $url;
    }

    /**
     * initialises a guzzle client which captures the response.
     *
     * @return GuzzleClient
     *
     * @codeCoverageIgnore
     *
     * We cannot test this method as we are not making live requests to urls during tests.
     */
    protected function getGuzzleClient(): GuzzleClient
    {
        $stack = new HandlerStack();
        $stack->setHandler(new CurlHandler());
        $stack->push($this->_requestHandler());

        return new GuzzleClient(['handler' => $stack]);
    }

    /**
     * @param Request $request
     */
    protected function setRequest(Request $request)
    {
        $this->_request = $request;
    }

    /**
     * Gets the request handler which captures the request from a guzzle request.
     *
     * @return Closure
     *
     * @codeCoverageIgnore
     *
     * We cannot test this method as we are not making live requests to urls during tests.
     */
    private function _requestHandler()
    {
        $crawler = $this;
        return function (callable $handler) use ($crawler) {
            return function (
                RequestInterface $request,
                array $options
            ) use ($handler, $crawler) {
                $crawler->setRequest($request);

                return $handler($request, $options);
            };
        };
    }

    /**
     * Gets a breakdown of a URL.
     *
     * @param string $url
     *
     * @return UrlValueObject
     */
    private function _getUrlBreakdown(string $url): UrlValueObject
    {
        return new UrlValueObject(parse_url($url));
    }

    /**
     * Generate a `Domain` object from a href.
     *
     * @param string $href
     *
     * @return Domain
     */
    private function _generateDomain(string $href): Domain
    {
        $host = $this->_getUrlBreakdown($this->_url)->getHost();
        //is the url relative or a hash (#)
        if (preg_match('/^(\/|#)/', $href)) {
            //the domain is the one we provided.
            $domain = $host;
        } else {
            $domain = $this->_getUrlBreakdown($href)->getHost();
        }

        $domain = new Domain($domain);
        $domain->addLink($href);

        return $domain;
    }

    /**
     * Gets the title of the current webpage.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->_internalCrawler->filter('title')->first()->text();
    }

    /**
     * filters the internal crawler to `a` tags.
     *
     * @return InternalCrawler
     */
    private function _getLinks(): InternalCrawler
    {
        return $this->_internalCrawler->filter('a');
    }

    /**
     * Gets the amount of links on the page.
     *
     * @return int
     */
    public function getLinkCount(): int
    {
        return $this->_getLinks()->count();
    }

    /**
     * Determines if the request was made using HTTPS
     *
     * @return bool
     */
    public function wasSecureRequest(): bool
    {
        return $this->_request->getUri()->getScheme() === 'https';
    }

    /**
     * Is google analytics present on the page.
     *
     * @return bool
     */
    public function isGoogleAnalyticsPresent()
    {
        $scripts = $this->_internalCrawler->filter('script');

        $results = $scripts->each(function (InternalCrawler $script) {
            $scriptContent = $script->text();
            foreach (Crawler::GA_TAGS as $tag) {
                if (stristr($scriptContent, $tag) !== false) {
                    return true;
                }
            }

            return false;
        });

        return in_array(true, $results);
    }

    /**
     * Gets the list of unique domains of the current webpage.
     *
     * @param bool $includeLinks
     *
     * @return DomainCollection|array
     */
    public function getDomains(bool $includeLinks)
    {
        $domains = new DomainCollection();

        //for each of the links. determine the domain.
        $this->_getLinks()->each((function (InternalCrawler $link) use ($domains) {
            $domains->addDomain($this->_generateDomain($link->attr('href')));
        })->bindTo($this));

        if (!$includeLinks) {
            return $domains->getDomainList();
        }

        return $domains;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
            'domains' => $this->getDomains(false),
            'isGoogleAnalyticsPresent' => $this->isGoogleAnalyticsPresent(),
            'title' => $this->getTitle(),
            'linkCount' => $this->getLinkCount(),
            'wasSecure' => $this->wasSecureRequest()
        ];
    }
}