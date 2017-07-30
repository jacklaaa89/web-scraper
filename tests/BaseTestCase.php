<?php

namespace Example\Tests;

use Example\Controller\Constants;
use Example\Controller\ContainerAwareInterface;
use Example\Controller\ContainerGet;
use Example\Core\Application;
use Example\Provider\ArrayServiceProvider;
use Example\Util\Config;
use Mockery;
use PHPUnit\Framework\TestCase;
use Slim\App;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class BaseTestCase
 *
 * @package Example\Tests
 */
abstract class BaseTestCase extends TestCase implements ContainerAwareInterface
{
    use ContainerGet;

    /** @var Application */
    private $application;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->application = $this->initialiseApplication();
        $this->setContainer($this->application->getContainer());
        $this->mockRequest([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/',
        ]);
        $this->set(Constants::RESPONSE, new Response());
    }

    /**
     * @return App
     */
    protected final function getApplication(): App
    {
        return $this->application;
    }

    /**
     * Mocks a request and sets it in the container.
     *
     * @param array $requestData
     * @param array $postData
     *
     * @return Request
     */
    protected function mockRequest(array $requestData, array $postData = []): Request
    {
        $environment = Environment::mock($requestData);
        $request = Request::createFromEnvironment($environment);

        if ($request->getMethod() == 'POST') {
            $request = $request->withParsedBody($postData);
        }

        $this->set(Constants::REQUEST, $request);

        return $request;
    }

    /**
     * Initialises the application.
     *
     * @return Application
     */
    private function initialiseApplication(): Application
    {
        $configFile = APP_PATH . '/config/config.php';
        $application = new Application(new Config($configFile));

        //register our services into the container.
        $provider = new ArrayServiceProvider(require APP_PATH . '/app/services.php');
        $application->registerServiceProvider($provider);

        return $application;
    }

    /**
     * Gets the request object from the container.
     *
     * @return Request
     */
    protected function getRequest(): Request
    {
        return $this->get(Constants::REQUEST);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        Mockery::close();
    }
}