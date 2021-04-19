<?php

namespace app\core;

class Application
{
    public static Application $app;

    public static string $ROOT_DIR;
    public Router $router;
    public Request $request;
    public Database $db;
    public Response $response;
    public Session $session;
    public Controller $controller;

    public function __construct($rootPath, array $config = [])
    {
        self::$app = $this;
        self::$ROOT_DIR = $rootPath;

        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();

        $this->router = new Router(
            $this->request,
            $this->response
        );

        $this->controller = new Controller();

        $this->db = new Database($config['db']);
    }

    public function run()
    {
        echo $this->router->resolve();
    }
}
