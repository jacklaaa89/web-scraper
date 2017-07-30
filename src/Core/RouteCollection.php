<?php

namespace Example\Core;

use ArrayIterator;
use Countable;
use IteratorAggregate;

/**
 * Class RouteCollection
 *
 * @package Example\Core;
 */
class RouteCollection implements IteratorAggregate, Countable
{
    /** @var Route[] */
    private $routes = [];

    /**
     * RouteCollection constructor.
     *
     * @param array $routes
     */
    public function __construct(array $routes = [])
    {
        $this->routes = $routes;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->routes);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->routes);
    }

    /**
     * Adds a route.
     *
     * @param Route $route
     */
    public function addRoute(Route $route)
    {
        $this->routes[] = $route;
    }
}
