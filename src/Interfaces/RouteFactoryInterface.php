<?php

namespace Szenis\Interfaces;

/**
 * RouteFactoryInterface
 */
interface RouteFactoryInterface
{
	/**
	 * Create a route object
	 *
	 * @param  RouterInterface $route
	 * @param  string          $url
	 * @param  string          $method
	 * @param  string|function $action
	 *
	 * @return RouteInterface
	 */
	public function create(RouteInterface $route, $url, $method, $action);
}