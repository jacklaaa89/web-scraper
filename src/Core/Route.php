<?php

namespace Example\Core;

use Example\Controller\Constants;
use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use ReflectionClass;

/**
 * Class Route
 *
 * A wrapper class to be able to add some structure to adding routes to slim.
 *
 * @package Example\Core;
 */
class Route
{
    /** @const string */
    const ACTION_PREFIX = 'Action';

    /** @const array */
    const METHODS = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];

    /**
     * The methods that this route accepts.
     *
     * Defaults to any.
     *
     * @var string[]
     */
    private $methods = self::METHODS;

    /**
     * The pattern for this route.
     *
     * @var string
     */
    private $pattern;

    /**
     * The name of the controller class.
     *
     * @var string
     */
    private $controllerClass;

    /**
     * The `action` or method to dispatch to.
     *
     * @var string
     */
    private $action;

    /**
     * Route constructor.
     *
     * @param string $pattern
     * @param string $controllerClass
     * @param string $action
     * @param string|array $methods
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        string $pattern,
        string $controllerClass,
        string $action,
        $methods = self::METHODS
    ) {
        $methods = !is_array($methods) ? [$methods] : $methods;
        $methods = array_map('strtoupper', $methods);
        foreach ($methods as $method) {
            if (!in_array($method, self::METHODS)) {
                throw new InvalidArgumentException("{$method} is not a valid HTTP method");
            }
        }

        $reflectionClass = new ReflectionClass($controllerClass);
        if (!$reflectionClass->isSubclassOf(Controller::class)) {
            throw new InvalidArgumentException(
                "{$controllerClass} is not an instance of a controller"
            );
        }

        $action = $action . self::ACTION_PREFIX;

        if (!$reflectionClass->hasMethod($action)) {
            throw new InvalidArgumentException(
                "{$action} method does not exist on {$controllerClass}"
            );
        }

        $this->pattern = $pattern;
        $this->controllerClass = $controllerClass;
        $this->action = $action;
        $this->methods = $methods;
    }

    /**
     * Gets the list of methods that this route accepts.
     *
     * @return string[]
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * Gets the route pattern.
     *
     * @return string
     */
    public function getPattern(): string
    {
        return $this->pattern;
    }

    /**
     * Gets the controller class name which handles the route.
     *
     * @return string
     */
    public function getControllerClass(): string
    {
        return $this->controllerClass;
    }

    /**
     * Gets the action name.
     *
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * Generates a callable from this Route.
     *
     * @param ContainerInterface $container
     *
     * @return callable
     */
    public function toCallable(ContainerInterface $container): callable
    {
        $route = $this;
        return function() use ($route, $container) {
             $controllerClass = $route->getControllerClass();
             $action = $route->getAction();
             $controller = new $controllerClass($container);

             return $controller->$action(
                 $container->get(Constants::REQUEST),
                 $container->get(Constants::RESPONSE)
             );
        };
    }
}
