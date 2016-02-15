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
	 * @param  array           $arguments
	 *
	 * @return RouteInterface
	 */
	public function create(RouteInterface $route, $url, $arguments);
}