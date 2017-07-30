<?php

use Example\Controller\Constants;
use Example\Util\Config;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\StreamHandler;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;
use Monolog\Logger;
use Slim\Views\TwigExtension;

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

        /** @var ServerRequestInterface $request */
        $request = $container->get(Constants::REQUEST);

        // Instantiate and add Slim specific extension
        $basePath = rtrim(
            str_ireplace('index.php', '', $request->getUri()->getBasePath()),
            '/'
        );
        $twig->addExtension(new TwigExtension($container->get(Constants::ROUTER), $basePath));

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
    }
];