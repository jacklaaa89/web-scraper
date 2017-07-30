<?php

namespace Example\Core;

use Example\Util\Config;
use Psr\Container\ContainerInterface;

/**
 * Interface ServiceProviderInterface
 *
 * @package Example\Core;
 */
interface ServiceProviderInterface
{
    /**
     * Registers services in this provider into the container.
     *
     * @param ContainerInterface $container
     * @param Config $config
     */
    public function register(ContainerInterface $container, Config $config);
}
