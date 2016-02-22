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
	 * @param  string          $url
	 * @param  string          $method
	 * @param  string|function $action
	 *
	 * @return Route
	 */
	public function create($url, $method, $action);
}