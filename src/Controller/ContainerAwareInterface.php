<?php

namespace Example\Controller;

use Psr\Container\ContainerInterface;

/**
 * Interface ContainerAwareInterface
 *
 * @package Example\Controller
 */
interface ContainerAwareInterface
{
    /**
     * Sets the container.
     *
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container);

    /**
     * Gets the container.
     *
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface;

    /**
     * Helper function to retrieve a value from the container.
     *
     * @param string $service
     *
     * @return object|null
     */
    public function get(string $service);
}