<?php

namespace Core;

use FastRoute\Dispatcher;
use Exceptions\MethodNotAllowedHttpException;
use Exceptions\NotFoundHttpException;

class Router
{
    use Singletonable;

    /**
     * @var array
     */
    public $routes = [];

    /**
     * Register a GET route.
     *
     * @param  string $uri
     * @param  mixed $action
     * @return $this
     */
    public function get($uri, $action)
    {
        $this->addRoute('GET', $uri, $action);

        return $this;
    }

    /**
     * Register a POST route.
     *
     * @param  string $uri
     * @param  mixed $action
     * @return $this
     */
    public function post($uri, $action)
    {
        $this->addRoute('POST', $uri, $action);

        return $this;
    }

    /**
     * Register a PUT route.
     *
     * @param  string $uri
     * @param  mixed $action
     * @return $this
     */
    public function put($uri, $action)
    {
        $this->addRoute('PUT', $uri, $action);

        return $this;
    }

    /**
     * Register a PATCH route.
     *
     * @param  string $uri
     * @param  mixed $action
     * @return $this
     */
    public function patch($uri, $action)
    {
        $this->addRoute('PATCH', $uri, $action);

        return $this;
    }

    /**
     * Register a DELETE route.
     *
     * @param  string $uri
     * @param  mixed $action
     * @return $this
     */
    public function delete($uri, $action)
    {
        $this->addRoute('DELETE', $uri, $action);

        return $this;
    }

    /**
     * Register a OPTIONS route.
     *
     * @param  string $uri
     * @param  mixed $action
     * @return $this
     */
    public function options($uri, $action)
    {
        $this->addRoute('OPTIONS', $uri, $action);

        return $this;
    }

    /**
     * Add a route to the collection.
     *
     * @param  string $method
     * @param  string $uri
     * @param  mixed $action
     */
    public function addRoute($method, $uri, $action)
    {
        $uri = '/' . trim($uri, '/');

        $this->routes[$method . $uri] = ['method' => $method, 'uri' => $uri, 'action' => $action];
    }

    /**
     * @return mixed
     * @throws MethodNotAllowedHttpException
     * @throws NotFoundHttpException
     */
    public function dispatch()
    {
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        if (isset($this->routes[$httpMethod . $uri])) {
            return $this->callControllerFunction($this->routes[$httpMethod . $uri]['action']);
        }

        return $this->handleDispatcherResponse($this->createDispatcher()->dispatch($httpMethod, $uri));
    }

    /**
     * @param $routeInfo
     * @return mixed
     * @throws MethodNotAllowedHttpException
     * @throws NotFoundHttpException
     */
    protected function handleDispatcherResponse($routeInfo)
    {
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                throw new NotFoundHttpException($routeInfo[0]);

            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new MethodNotAllowedHttpException($routeInfo[1]);

            case Dispatcher::FOUND:
                return $this->callControllerFunction($routeInfo[1], $routeInfo[2]);
        }
    }

    /**
     * @return Dispatcher
     */
    protected function createDispatcher()
    {
        return \FastRoute\simpleDispatcher(function ($r) {
            foreach ($this->routes as $route) {
                $r->addRoute($route['method'], $route['uri'], $route['action']);
            }
        });
    }

    /**
     * @param $routeAction
     * @param null $args
     * @return mixed
     * @throws \Exception
     */
    protected function callControllerFunction($routeAction, $args = null)
    {
        list($controller, $function) = explode('@', $routeAction);
        $instance = DI::getInstanceOf($controller);

        if (!empty($args)) {
            return call_user_func_array(array($instance, $function), $args);
        }

        return $instance->$function();
    }
}
