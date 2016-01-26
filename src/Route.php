<?php

namespace Szenis;

/**
 * Simple route object
 */
class Route
{
	/**
	 * Method of route
	 *
	 * @var string
	 */
	private $method;

	/**
	 * Url of route
	 *
	 * @var string
	 */
	private $url;

	/**
	 * Classname that the route will use
	 *
	 * @var string
	 */
	private $class;

	/**
	 * Function that the route will execute when triggerd
	 *
	 * @var string
	 */
	private $function;

	/**
	 * Contains the index number of the url arguments
	 *
	 * @var array
	 */
	private $argumentIndexes;
	
	/**
	 * Get the request method of the current route
	 *
	 * @return string
	 */
	public function getMethod()
	{
		return $this->method;
	}

	/**
	 * Set the method of the current route
	 *
	 * @param string $method
	 */
	public function setMethod($method)
	{
		$this->method = $method;
	}
	
	/**
	 * Get the url of the current route
	 *
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * Set url
	 *
	 * @param string $url
	 */
	public function setUrl($url)
	{
		$this->url = $url;
	}
	
	/**
	 * Get the class name of the current route
	 *
	 * @return string
	 */
	public function getClass()
	{
		return $this->class;
	}
	
	/**
	 * Set the class name of the current route
	 *
	 * @param string $class
	 */
	public function setClass($class)
	{
		$this->class = $class;
	}

	/**
	 * Get the function name of the route
	 *
	 * @return string
	 */
	public function getFunction()
	{
		return $this->function;
	}

	/**
	 * Set the function name of the route
	 *
	 * @param string $function
	 */
	public function setFunction($function)
	{
		$this->function = $function;
	}

	/**
	 * Get argument indexes
	 *
	 * @return array
	 */
	public function getArgumentIndexes()
	{
		return $this->argumentIndexes;
	}

	/**
	 * Set argument indexes
	 *
	 * @param array $indexes
	 */
	public function setArgumentIndexes($indexes)
	{
		$this->argumentIndexes = $indexes;
	}
}
