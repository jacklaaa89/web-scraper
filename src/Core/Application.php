<?php

namespace Example\Core;

use Example\Util\Config;
use Slim\App as SlimApplication;

/**
 * Class Application
 *
 * @package Example\Core;
 */
class Application extends SlimApplication
{
    /** @var Config */
    private $configuration;

    /**
     * Application constructor.
     *
     * @param Config $configuration
     */
    public function __construct(Config $configuration)
    {
        $this->configuration = $configuration;
        parent::__construct(['settings' => $configuration->toArray()]);
    }

    /**
     * Gets the configuration.
     *
     * @return Config
     */
    public function getConfiguration(): Config
    {
        return $this->configuration;
    }

    /**
     * Registers services into the container using the provider.
     *
     * @param ServiceProviderInterface $serviceProvider
     */
    public function registerServiceProvider(ServiceProviderInterface $serviceProvider)
    {
        $serviceProvider->register($this->getContainer(), $this->getConfiguration());
    }

    /**
     * Registers a collection of routes.
     *
     * @param RouteCollection $routeCollection
     */
    public function registerRoutes(RouteCollection $routeCollection)
    {
        foreach ($routeCollection as $route) {
            $this->map(
                $route->getMethods(),
                $route->getPattern(),
                $route->toCallable($this->getContainer())
            );
        }
    }
}
