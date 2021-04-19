<?php

namespace app\core;

class Router
{
    public array $routes = [];

    public function __construct(
        public Request $request,
        public Response $response
    ) {
    }

    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve()
    {
        $path = $this->request->uri();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path];

        if (!$callback) {
            Application::$app->controller->setLayout('_404');
            $this->response->setStatusCode(404);
            return $this->renderView('_404');
        }

        if (is_string($callback))
            return $this->renderView($callback);

        if (is_array($callback)) {
            Application::$app->controller = new $callback[0]();
            $callback[0] = Application::$app->controller;
        }

        return call_user_func($callback, $this->request);
    }

    public function renderView($view, $params = [])
    {
        $layoutContent = $this->layoutContent();
        $viewContent = $this->viewContent($view, $params);

        return str_replace('{{contents}}', $viewContent, $layoutContent);
    }

    public function layoutContent()
    {
        $layout = Application::$app->controller->layout;

        ob_start();
        include_once Application::$ROOT_DIR . "/views/layouts/$layout.php";
        return ob_get_clean();
    }

    public function viewContent($view, $params)
    {
        foreach ($params as $key => $value)
            $$key = $value;

        ob_start();
        include_once Application::$ROOT_DIR . "/views/$view.php";
        return ob_get_clean();
    }
}
