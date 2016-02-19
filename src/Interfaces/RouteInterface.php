<?php

namespace Szenis\Interfaces;

/**
 * Route interface
 */
interface RouteInterface
{
	/**
	 * Get the url of the route
	 *
	 * @return string
	 */
	public function getUrl();

	/**
	 * Set url
	 *
	 * @param string $url
	 */
	public function setUrl($url);

	/**
	 * Get methods
	 *
	 * @return array
	 */
	public function getMethod();

	/**
	 * Set method
	 *
	 * @param array $method
	 */
	public function setMethod(array $method);

	/**
	 * Get action
	 *
	 * @return string|function
	 */
	public function getAction();

	/**
	 * Set action
	 *
	 * @param string|function
	 */
	public function setAction($action);

	/**
	 * Get argument indexes
	 *
	 * @return array
	 */
	public function getArgumentIndexes();

	/**
	 * Set argument indexes
	 *
	 * @param array $indexes
	 */
	public function setArgumentIndexes(array $indexes);
}