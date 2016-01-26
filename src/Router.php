<?php

namespace Szenis;

use Szenis\Parsers\UrlParser;
use Szenis\Validators\ArgumentsValidator;
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
	 * An array with routes, request method as key.
	 * 
	 * @var array
	 */
	private $routesByMethod;

	/**
	 * Add new route to routes array
	 *
	 * @param  string $url
	 * @param  array  $arguments
	 */
	public function add($url, $arguments)
	{	
		$validator = new ArgumentsValidator();
		$validator->validateUrl($url);
		$validator->validateArguments($arguments);

		// initialize the url parser
		$urlParser = new UrlParser();
		// parse the url to a regex url
		$regexUrl = $urlParser->getRegexUrl(strtolower($url));
		// get the index numbers of the arguments
		$argumentIndexes = $urlParser->getArgumentIndexes($url);

		// create new route object and fill it
		$route = new Route();
		$route->setUrl($regexUrl);
		$route->setArgumentIndexes($argumentIndexes);
		$route->setMethod($arguments['method']);
		$route->setClass($arguments['class']);
		$route->setFunction($arguments['function']);

		// add route to the routes array
		$this->routes[] = $route;

		// in case of multiple methods the route will be added multiple times
		foreach ($arguments['method'] as $method) {
			$this->routesByMethod[$method][] = $route;
		}
	}

	/**
	 * Get all routes with matching method
	 *
	 * @param  string $method
	 *
	 * @return array
	 */
	public function getAllByMethod($method)
	{
		if ($this->routesByMethod && isset($this->routesByMethod[$method])) {
			return $this->routesByMethod[$method];
		}

		return [];
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
