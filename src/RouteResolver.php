<?php

/*
 * This file is part of the SimpleRouting package.
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
		$requestedUri = trim($request['uri'], '/');
		// get all segments of the requested uri in an array
		$requestedUriSegments = explode('/', $requestedUri, PHP_URL_PATH);
		// arguments that will be passed on to the method that will be called
		$arguments = [];

		// loop trough the posible routes
		foreach ($routes as $route) {
			// if the requested route matches one of the defined routes
			if ($route->getUrl() === $requestedUri || preg_match('~^'.$route->getUrl().'$~', $requestedUri, $matches)) {
				// get the class name of the defined route
				$className = $route->getClass();

				// get the indexes of the arguments - for more information check the private variable argumentIndexes in the route class
				foreach ($route->getArgumentIndexes() as $index) {
					// add all the arguments to the argument array
					$arguments[] = $requestedUriSegments[$index];
				}

				// call the requested method and give it the arguments (if any)
				return call_user_func_array(array((new $className), $route->getFunction()), $arguments);
			}
		}

		// if no route is found throw an RouteNotFoundException
		throw new RouteNotFoundException($request['method'].' '.$request['uri'].' not found');
	}
}
