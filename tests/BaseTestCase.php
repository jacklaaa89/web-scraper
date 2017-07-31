<?php

namespace Example\Tests;

use Example\Controller\Constants;
use Example\Controller\ContainerAwareInterface;
use Example\Controller\ContainerGet;
use Example\Core\Application;
use Example\Core\RouteCollection;
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
    /** @const string */
    const VIEW_WITH_TWO_LINKS_WITH_GOOGLE_ANALYTICS = 'test/two_links_with_ga.twig';

    /** @const array */
    const EXPECTED_JSON_SERIALIZE_RESULT = [
        'domains' => self::EXPECTED_DOMAIN_LIST_NO_LINKS,
        'isGoogleAnalyticsPresent' => true,
        'title' => 'Test Content',
        'linkCount' => 2,
        'wasSecure' => true
    ];

    /** @const array */
    const EXPECTED_DOMAIN_LIST_NO_LINKS = [
        self::EXPECTED_DOMAIN,
        'www.test-domain.com'
    ];

    /** @const array */
    const EMPTY_SET = [];

    /** @const string */
    const EXPECTED_DOMAIN = 'www.test-request.com';

    /** @const string */
    const EXPECTED_LINK = self::EXPECTED_DOMAIN . '/link';

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

        $routeCollection = new RouteCollection(require APP_PATH . '/app/routes.php');
        $application->registerRoutes($routeCollection);

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