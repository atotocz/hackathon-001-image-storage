<?php
namespace Hackaton\ImageStorage\Resolvers;

use Symfony\Component\HttpFoundation\Request;

class MvcResolver implements ICallableResolver {
  protected static $method_to_action = [
    'GET'    => 'readAction',
    'POST'   => 'createAction',
    'DELETE' => 'deleteAction'
  ];

  public function resolve(Request $request) {
    $method = $request->getMethod();

    if (!isset(self::$method_to_action[$method])) {
      return;
    }

    $path = trim($request->getPathInfo(), '/');
    $path_parts = explode('/', $path) + [null];
    $controller_name = ucfirst(strtolower(array_shift($path_parts)));

    $controller_class = "\\Hackaton\\ImageStorage\\Controllers\\{$controller_name}Controller";

    if (!class_exists($controller_class)) {
      return;
    }

    $action = self::$method_to_action[$method];

    if (!method_exists($controller_class, $action)) {
      return;
    }

    return [$controller_class, $action, $path_parts];
  }
}
