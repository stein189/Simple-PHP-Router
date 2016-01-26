<?php

namespace Szenis;

use Szenis\Route;

/**
 * Simple routing collection class
 */
class Router
{
	/**
	 * An array with all the registerd routes
	 *
	 * @var array
	 */
	private $routes;

	/**
	 * Add new route to routes array
	 *
	 * @param  string $url
	 * @param  array  $arguments
	 */
	public function add($url, $arguments)
	{	
		// if the method is not set throw an exception
		if (!isset($arguments['method'])) {
			throw new \Exception('Request method argument is missing for route: '.$url);
		}

		// if the method arguments is not an array throw an exception
		if (!is_array($arguments['method'])) {
			throw new \Exception('Request method should be an array for route: '.$url);
		}
		
		// if no valid url is given throw an exception
		if ($url === null || $url === '') {
			throw new \Exception('No url provided, for root use /');
		}
		
		// if the class name is not provided throw exception
		if (!isset($arguments['class'])) {
			throw new \Exception('Class argument is missing');
		}

		// if the function argument is not provided throw an exception
		if (!isset($arguments['function'])) {
			throw new \Exception('Function argument is missing');
		}
		
		// if the class argument is not provided throw an exception
		if (!class_exists($arguments['class'])) {
			throw new \Exception('Class '.$arguments['class'].' not found!');
		}
		
		// get all the methods from the given class
		$classMethods = get_class_methods($arguments['class']);
		
		// check if the given method exists in class, if not throw exception
		if (!in_array($arguments['function'], $classMethods)) {
			throw new \Exception('Function '.$arguments['function'].' does not exists in class '.$arguments['class']);
		}

		// create new route object
		$route = new Route(strtolower($url));

		$route->setMethod($arguments['method']);
		$route->setClass($arguments['class']);
		$route->setFunction($arguments['function']);

		$this->routes[] = $route;
	}

	/**
	 * Get all routes
	 *
	 * @return array
	 */
	public function getAll()
	{
		return $this->routes;
	}

	/**
	 * Resolve the given url and call the function that belongs to the route
	 *
	 * @deprecated since v0.1.0 - function moved to RouterResolver class
	 * 
	 * @param  array $request
	 *
	 * @return mixed
	 */
	public function resolve($request)
	{
		trigger_error(__FUNCTION__.' is deprecated since version 0.1.0 and will be remove in version 1.0.0, use the function resolve in the RouteResolver class.', E_USER_DEPRECATED);

		$resolver = new \Szenis\RouteResolver($this);
		return $resolver->resolve($request);
	}
}
