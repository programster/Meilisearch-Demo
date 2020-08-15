<?php


abstract class AbstractSlimController
{
    protected Slim\Psr7\Request $m_request;
    protected Slim\Psr7\Response $m_response;
    protected $m_args;


    public function __construct(Slim\Psr7\Request $request, Slim\Psr7\Response $response, $args) {
        $this->m_request = $request;
        $this->m_response = $response;
        $this->m_args = $args;
    }

    // this one is optional - refer to Slim3 - Simplifying Routing At Scale
    // https://blog.programster.org/slim3-simplifying-routing-at-scale
    abstract public function registerRoutes($app);
}