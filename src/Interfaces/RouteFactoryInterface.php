<?php

/*
 * This file is part of the Simple-PHP-Router package.
 *
 * (c) Stein Janssen <birdmage@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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