<?php

namespace Example\Controller;

use Example\Core\Controller;
use Example\Util\Crawler;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Http\Response as SlimResponse;

/**
 * Class IndexController
 *
 * @package Example\Controller;
 */
class IndexController extends Controller
{
    /**
     * Handles `/`
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        return $this->render('index/index.twig');
    }

    /**
     * Handles `/scrape`
     *
     * @param Request      $request
     * @param SlimResponse $response
     *
     * @return Response
     */
    public function scrapeAction(Request $request, SlimResponse $response)
    {
        //get the request body.
        if (!($requestBody = $request->getParsedBody())) {
            $requestBody = [];
        }

        //check if a url was defined.
        if (!isset($requestBody['url'])) {
            return $this->generateErrorResponse('URL was not supplied');
        }

        //ensure the URL is in the correct format.
        $url = $requestBody['url'];
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return $this->generateErrorResponse('supplied URL was not valid.');
        }

        //Scrape the provided URL
        $crawler = $this->getCrawler();
        try {
            $crawler->load($url);
        } catch (Exception $exception) {
            return $this->generateErrorResponse($exception->getMessage());
        }

        //return JSON response.
        return $response->withJson($crawler, 200, JSON_PRETTY_PRINT);
    }

    /**
     * Generates an error JSON response.
     *
     * @param string $message
     *
     * @return ResponseInterface
     */
    private function generateErrorResponse(string $message): ResponseInterface
    {
        $error = [
            'success' => false,
            'message' => $message
        ];

        /** @var SlimResponse $response */
        $response = $this->getResponse();

        return $response->withJson($error, 500);
    }
}
