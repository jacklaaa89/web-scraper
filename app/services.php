<?php

use Example\Util\Config;
use Example\Util\Crawler;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\StreamHandler;
use Psr\Container\ContainerInterface;
use Slim\Views\Twig;
use Monolog\Logger;

/**
 * The service definition uses the ArrayServiceProvider to inject these services into
 * the container.
 *
 * That provider wraps the anonymous functions defined here with one that provides the
 * configuration.
 */
return [
    /**
     * Initialise the twig view component so we can render twig templates.
     */
    'view' => function (ContainerInterface $container, Config $configuration) {
        $twig = new Twig(
            $configuration->get('twig.template_directory'),
            $configuration->get('twig.environment')
        );

        return $twig;
    },
    /**
     * Sets up the monolog logger to log to our defined file location.
     */
    'logger' => function (ContainerInterface $container, Config $configuration) {
        $logger = new Logger('application_logger');
        $lineFormatter = new LineFormatter(null, null, false, true);
        $streamHandler = new StreamHandler($configuration->get('monolog.path'), Logger::DEBUG);
        $streamHandler->setFormatter($lineFormatter);
        $fingersCrossedHandler = new FingersCrossedHandler(
            $streamHandler,
            $configuration->get('monolog.level')
        );
        $logger->pushHandler($fingersCrossedHandler);

        return $logger;
    },
    'crawler' => function (ContainerInterface $container, Config $configuration) {
        return new Crawler();
    }
];