<?php

namespace Example\Controller;

use Psr\Container\ContainerInterface;

/**
 * Trait ContainerAwareTrait
 *
 * @package Example\Controller
 */
trait ContainerAwareTrait
{
    /** @var ContainerInterface */
    protected $container;

    /**
     * Sets the container.
     *
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Gets the container.
     *
     * @return ContainerInterface
     */
    public final function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * Helper function to retrieve a value from the container.
     *
     * @param string $service
     *
     * @return object|null
     */
    public final function get(string $service)
    {
        return $this->getContainer()->get($service);
    }

    /**
     * Sets a service in the container.
     *
     * @param string $service
     * @param mixed $definition
     */
    public final function set(string $service, $definition)
    {
        $this->container[$service] = $definition;
    }
}