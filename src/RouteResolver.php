<?php

/*
 * This file is part of the Simple-PHP-Router package.
 *
 * (c) Stein Janssen <birdmage@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Szenis;

use Szenis\Exceptions\RouteNotFoundException;
use Szenis\Interfaces\RouterInterface;

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
	public function __construct(RouterInterface $router)
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
		// get all register routes with the same request method
		$routes = $this->router->getByMethod($request['method']);
		// remove trailing and leading slash
		$requestedUri = trim(preg_replace('/\?.*/', '', $request['uri']), '/');
		// get all segments of the requested uri in an array
		$requestedUriSegments = explode('/', $requestedUri, PHP_URL_PATH);

		// loop trough the posible routes
		foreach ($routes as $route) {
			$matches = array();

			// if the requested route matches one of the defined routes
			if ($route->getUrl() === $requestedUri || preg_match('~^'.$route->getUrl().'$~', $requestedUri, $matches)) {
				$arguments = $this->getArguments($matches);

				if (is_object($route->getAction()) && ($route->getAction() instanceof \Closure)) {
					return call_user_func_array($route->getAction(), $arguments);
				}

				$className = substr($route->getAction(), 0, strpos($route->getAction(), '::'));
				$functionName = substr($route->getAction(), strpos($route->getAction(), '::') + 2);

				return call_user_func_array(array((new $className), $functionName), $arguments);
			}
		}

		// if no route is found throw an RouteNotFoundException
		throw new RouteNotFoundException($request['method'].' '.$request['uri'].' not found');
	}

	/**
	 * Get arguments
	 *
	 * @param  array $matches
	 *
	 * @return array
	 */
	private function getArguments($matches)
	{
		$arguments = array();

		foreach ($matches as $key => $match) {
			if ($key === 0) continue;

			if (strlen($match) > 0) {
				$arguments[] = $match;
			}
		}

		return $arguments;
	}
}
