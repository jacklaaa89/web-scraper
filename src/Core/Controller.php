<?php

namespace Example\Core;

use Example\Controller\ContainerAwareInterface;
use Example\Controller\ContainerGet;
use Psr\Container\ContainerInterface;

/**
 * Class Controller
 *
 * @package Example\Core;
 */
abstract class Controller implements ContainerAwareInterface
{
    use ContainerGet;

    /**
     * Controller constructor.
     *
     * @param ContainerInterface $container
     */
    public final function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
    }
}
