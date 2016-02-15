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

use Szenis\Interfaces\RouterInterface;
use Szenis\Validators\ArgumentsValidator;
use Szenis\Parsers\UrlParser;
use Szenis\RouteCollection;
use Szenis\RouteFactory;
use Szenis\Route;

/**
 * Simple routing collection class
 */
class Router implements RouterInterface
{
	/**
	 * An array with all the registerd routes
	 *
	 * @var RouteCollection
	 */
	private $routes;

	/**
	 * Router constructor
	 */
	public function __construct()
	{
		$this->routes = new RouteCollection();
	}

	/**
	 * Add new route to routes array
	 *
	 * @param  string $url
	 * @param  array  $arguments
	 */
	public function add($url, $arguments)
	{	
		$factory = new RouteFactory((new ArgumentsValidator()), (new UrlParser()));

		$this->routes->add($factory->create((new Route()), $url, $arguments));
	}

	/**
	 * Get RouteCollection
	 *
	 * @return RouteCollection
	 */
	public function get()
	{
		return $this->routes;
	}
}
