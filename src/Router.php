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
	 * Resolve the given url and call the function that belongs to the route
	 *
	 * @param  array $request
	 *
	 * @return mixed
	 */
	public function resolve($request)
	{
		foreach ($this->routes as $route) {
			// remove trailing and leading /
			$requestSegments = trim(strtolower($request['uri']), "/");
			// make an array of all segments
			$requestSegments = explode('/', $requestSegments, PHP_URL_PATH);
			// count segments
			$uriSegmentCount = count($requestSegments);

			// check if the route matches the requested method
			if (!in_array($request['method'], $route->getMethod())) continue;

			// check if the requested uri has the same amount of segments as the provided uri's
			if ($route->getSegmentCount() !== $uriSegmentCount) continue;

			// get all route segments
			$routeSegments = $route->getSegments();

			$count = 0;
			$found = true;
			$arguments = [];

			// loop over the segments and check if the given segments fit the route
			foreach ($routeSegments as $segment) {
				// if the segments are exact the same
				if ($segment === $requestSegments[$count]) {
					$count++;

					continue;
				}

				// if there is a placeholder the segment is an argument
				if ($this->isPlaceholder($segment)) {
					$arguments[] = $requestSegments[$count];
					$count++;	

					continue;
				}

				continue 2;
			}

			// get the class name of the current route
			$className = $route->getClass();
			$method = new \ReflectionMethod($className, $route->getFunction());

			if (count($arguments) !== $method->getNumberOfParameters()) {
				throw new \Exception('Function '.$route->getFunction().' expects '.$method->getNumberOfParameters().' arguments. '.count($arguments).' arguments given.');
			}

			// call the method
			return call_user_func_array(array((new $className), $route->getFunction()), $arguments);
		}

		// throw an Exception
		throw new \Exception('404 Route not found');
	}

	/**
	 * Check if an url segment is an placeholder
	 *
	 * @param  string $segment
	 *
	 * @return boolean
	 */
	private function isPlaceholder($segment) {
		return (substr($segment, 0, strlen('{')) === '{' && substr($segment, strlen($segment)-1) === '}');
	}
}
