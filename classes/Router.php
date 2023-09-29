<?php

class Router
{
  private $routes = [
    "GET" => [],
    "POST" => [],
    "PATCH" => [],
    "PUT" => [],
    "DELETE" => [],
  ];

  private function error_handler(Request &$request, \Throwable $th) {
    http_response_code($th->getCode());
    header("content-type: text/plain");
    echo $th->getMessage();
  }

  public function get(string $path, callable $controller)
  {
    return $this->routes["GET"][$path] = $controller;
  }

  public function post(string $path, callable $controller)
  {
    return $this->routes["POST"][$path] = $controller;
  }

  public function patch(string $path, callable $controller)
  {
    return $this->routes["PATCH"][$path] = $controller;
  }

  public function put(string $path, callable $controller)
  {
    return $this->routes["PUT"][$path] = $controller;
  }

  public function delete(string $path, callable $controller)
  {
    return $this->routes["DELETE"][$path] = $controller;
  }

  private function match_request(Request &$request): callable
  {
    $controller = &$this->routes[$request->METHOD][$request->REQUEST_PATH];
    if (isset($controller) && is_callable($controller)) {
      return $controller;
    } else {
      throw new Exception("{$request->METHOD} {$request->REQUEST_PATH} 404 NOT FOUND", 404);
    }
  }
  function __destruct()
  {
    $request = new Request();
    try {
      $controller = $this->match_request($request);
      $controller($request);
    } catch (\Throwable $th) {
      $this->error_handler($request, $th);
    }
  }
}