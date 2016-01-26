<?php

namespace Szenis;

use Szenis\Exceptions\RouteNotFoundException;
use Szenis\Router;

/**
 * Class resolves the given route or throws an exception
 */
class RouteResolver
{
	/**
	 * Collection with routes
	 *
	 * @var Router
	 */
	private $router;

	/**
	 * RouteResolver constructor
	 *
	 * @param Router $router
	 */
	public function __construct(Router $router)
	{
		$this->router = $router;
	}

	/**
	 * Resolve the given url and call the method that belongs to the route
	 *
	 * @param  array $request [contains the uri and the request method]
	 *
	 * @return mixed
	 */
	public function resolve($request)
	{
		// get all register routes
		$routes = $this->router->getAll();

		foreach ($routes as $route) {
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

		// throw an RouteNotFoundException
		throw new RouteNotFoundException($request['method'].' '.$request['uri'].' not found');
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