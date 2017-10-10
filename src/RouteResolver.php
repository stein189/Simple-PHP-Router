<?php

/*
 * This file is part of the Simple-PHP-Router package.
 *
 * (c) Stein Janssen <birdmage@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Szenis\Routing;

use Szenis\Routing\Router;

/**
 * Class resolves the given route or throws an exception
 */
class RouteResolver
{
	/**
	 * Resolve the given url and call the method that belongs to the route
	 *
	 * @param  Router $router
	 * @param  string $uri
	 * @param  string $method
	 *
	 * @return array
	 */
	public function resolve(Router $router, $uri, $method)
	{
		// get all register routes with the same request method
		$routes = $router->getRoutesByMethod($method);
		// remove trailing and leading slash
		$requestedUri = trim(preg_replace('/\?.*/', '', $uri), '/');
		// loop trough the posible routes
		foreach ($routes as $route) {
			$matches = array();

			// if the requested route matches one of the defined routes
			if ($route->getUrl() === $requestedUri || preg_match('~^'.$route->getUrl().'$~', $requestedUri, $matches)) {
				$argumentArray = array();
				$arguments = $this->getArguments($matches);

				// check if there route has arguments
				if (is_array($route->getArguments()) && count($route->getArguments()) > 0) {
					// loop trough all the arguments
					foreach ($route->getArguments() as $key => $argument) {
						// if the argument exists set the value
						if (isset($arguments[$key])) {
							$argumentArray[$argument] = $arguments[$key];
						} else {
							// setting the value to null should only occure when we use an optional parameter
							$argumentArray[$argument] = null;
						}
					}
				}

				return [
					'code' => Route::STATUS_FOUND,
					'handler' => $route->getAction(),
					'arguments' => $argumentArray
				];
			}
		}

		return [
			'code' => Route::STATUS_NOT_FOUND,
			'handler' => null,
			'arguments' => []
		];
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
