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

use Szenis\Interfaces\RouterInterface;
use Szenis\RouteFactory;

/**
 * Simple routing collection class
 *
 * @implements RouterInterface
 */
class Router implements RouterInterface
{
	/**
	 * Route factory
	 *
	 * @var RouteFactory
	 */
	private $factory;

	/**
	 * An array with all the registerd routes
	 *
	 * @var array
	 */
	private $routes;

	/**
	 * an array with all routes by method
	 *
	 * @var array
	 */
	private $routesByMethod;

	/**
	 * Router constructor
	 */
	public function __construct()
	{
		$this->factory = new RouteFactory();
	}

	/**
	 * Add new route to routes array
	 *
	 * @param  string $url
	 * @param  string $method
	 * @param  string $action
	 */
	public function add($url, $method, $action)
	{	
		$route = $this->factory->create($url, $method, $action);
	
		$this->routes[] = $route;

		foreach ($route->getMethod() as $method) {
			$this->routesByMethod[$method][] = $route;
		}
	}

	/**
	 * Get RouteCollection
	 *
	 * @return RouteCollection
	 */
	public function getAll()
	{
		return $this->routes;
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
}
