<?php

namespace Example\Tests\Core;

use Example\Core\Controller;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class TestController
 *
 * Concrete implementation of a controller with an action used for testing.
 *
 * @package Example\Tests\Core;
 */
class TestController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function testAction(Request $request): Response
    {
        return $this->getResponse();
    }
}
