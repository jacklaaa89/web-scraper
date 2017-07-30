<?php

namespace Example\Provider;

use Example\Core\ServiceProviderInterface;
use Example\Util\Config;
use Psr\Container\ContainerInterface;

/**
 * Class ArrayServiceProvider
 *
 * @package Example\Provider;
 */
class ArrayServiceProvider implements ServiceProviderInterface
{
    /** @var array */
    private $serviceArray = [];

    /**
     * ArrayServiceProvider constructor.
     *
     * @param array $serviceArray
     */
    public function __construct(array $serviceArray)
    {
        $this->serviceArray = $serviceArray;
    }

    /**
     * {@inheritdoc}
     */
    public function register(ContainerInterface $container, Config $config)
    {
        foreach ($this->serviceArray as $serviceKey => $definition) {
            $container[$serviceKey] = function (ContainerInterface $container) use ($config, $definition) {
                return call_user_func_array($definition, [$container, $config]);
            };
        }
    }
}
