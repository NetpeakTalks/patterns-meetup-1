<?php
/**
 * Created by PhpStorm.
 * User: doctor
 * Date: 21.05.19
 * Time: 12:18
 */


class Request
{

    /**
     * Request constructor.
     */
    public function __construct($get, $post)
    {
        // some logic
    }

    public function getQuery()
    {
        // some logic
    }

    /**
     * @param $address
     */
    public function getData()
    {
        // some logic
    }

    public function getHeaders()
    {
        // some logic
    }
}

class Router
{
    public function getRoute(Request $request)
    {
        // some logic
    }
}

class ViewHandler
{
    public function printView($name, $data)
    {
        // some logic
    }
}

class ControllerHandler
{
    public function getController($route)
    {
        // some logic
    }
}


$request = new Request($_POST, $_GET);
$router = new Router();
$viewHandler = new ViewHandler();
$controllerHandler = new ControllerHandler($viewHandler);





$route = $router->getRoute($request);
$controller = $controllerHandler->getController($route);


$controller->handle($request);




















// Facade
class Application
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var ControllerHandler
     */
    protected $controllerHandler;

    /**
     * Application constructor.
     * @param Request $request
     * @param Router $router
     * @param ControllerHandler $controllerHandler
     */
    public function __construct(Request $request, Router $router, ControllerHandler $controllerHandler)
    {
        $this->request = $request;
        $this->router = $router;
        $this->controllerHandler = $controllerHandler;
    }


    public function run()
    {
        $route = $this->router->getRoute($this->request);
        $controller = $this->controllerHandler->getController($route);


        $controller->handle($this->request);

    }
}

// Usage example
$app = new Application($request, $router, $controllerHandler);
$app->run();

