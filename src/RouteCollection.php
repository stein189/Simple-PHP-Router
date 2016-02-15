<?php

namespace Szenis;

use Szenis\Interfaces\RouteCollectionInterface;
use Szenis\Interfaces\RouteInterface;

/**
 * RouteCollection
 */
class RouteCollection implements RouteCollectionInterface
{
	/**
	 * All routes
	 *
	 * @var array
	 */
	private $routes = array();

	/**
	 * Routes orderd by method
	 *
	 * @var array
	 */
	private $routesByMethod = array();

	/**
	 * Add a route to the collection
	 *
	 * @param RouteInterface $route
	 */
	public function add(RouteInterface $route)
	{
		$this->routes[] = $route;

		foreach ($route->getMethod() as $method) {
			$this->routesByMethod[$method][] = $route;
		}
	}

	/**
	 * Get routes by method
	 *
	 * @param  string $method
	 *
	 * @return array
	 */
	public function getByMethod($method)
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
}
