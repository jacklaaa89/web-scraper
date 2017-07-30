<?php

namespace Example\Controller;

use Example\Util\Crawler;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\Twig;

/**
 * Trait ContainerGet
 *
 * @package Example\Controller
 */
trait ContainerGet
{
    use ContainerAwareTrait;

    /**
     * Gets the Twig view engine.
     *
     * @return Twig
     */
    public function getView(): Twig
    {
        return $this->get(Constants::VIEW);
    }

    /**
     * Gets the current response object.
     *
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->get(Constants::RESPONSE);
    }

    /**
     * @return Crawler
     */
    public function getCrawler(): Crawler
    {
        return $this->get(Constants::CRAWLER);
    }

    /**
     * Renders a template.
     *
     * @param string $template
     * @param array $data
     *
     * @return ResponseInterface
     */
    public function render(string $template, array $data = []): ResponseInterface
    {
        return $this->getView()->render($this->getResponse(), $template, $data);
    }
}