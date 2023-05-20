<?php

namespace thecodeholic\phpmvc;

use thecodeholic\phpmvc\middlewares\BaseMiddleware;

class Controller
{
    public $middleware;
    public string $layout = 'main';
    public string $action = '';


    protected array $middlewares = [];

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function render($view, $params = [])
    {
        return Application::$app->view->renderView($view, $params);        
    }

    public function registerMiddleware($middleware)
    {
        $this->middlewares[] = $middleware;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}
