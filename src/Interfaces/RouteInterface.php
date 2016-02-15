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
	 * Get class name
	 *
	 * @return string
	 */
	public function getClass();

	/**
	 * Set class nane
	 *
	 * @param string $class
	 */
	public function setClass($class);

	/**
	 * Get function name
	 * 
	 * @return string
	 */
	public function getFunction();

	/**
	 * Set function
	 *
	 * @param string $function
	 */
	public function setFunction($function);

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