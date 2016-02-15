<?php

namespace Szenis\Interfaces;

/**
 * RouteCollectionInterface
 */
interface RouteCollectionInterface
{
	/**
	 * Add a route to the collection
	 *
	 * @param RouteInterface $route
	 */
	public function add(RouteInterface $route);

	/**
	 * Get all routes
	 *
	 * @return array
	 */
	public function getAll();

	/**
	 * Get routes by method
	 *
	 * @param  string $method
	 *
	 * @return array
	 */
	public function getByMethod($method);
}