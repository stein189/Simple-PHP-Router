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
	 * Array with all segments of the url
	 *
	 * @var array
	 */
	private $segments;
	
	/**
	 * Route constructor
	 *
	 * @param string $url
	 */
	public function __construct($url)
	{
		$this->url = $url;
		
		// remove trailing and leading /
		$requestSegments = trim($url, "/");

		// make an array of all segments
		$this->segments = explode('/', $requestSegments, PHP_URL_PATH);
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
	 * Get the request method of the current route
	 *
	 * @return string
	 */
	public function getMethod()
	{
		return $this->method;
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
	 * Set the class name of the current route
	 *
	 * @param string $class
	 */
	public function setClass($class)
	{
		$this->class = $class;
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
	 * Set the function name of the route
	 *
	 * @param string $function
	 */
	public function setFunction($function)
	{
		$this->function = $function;
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
	 * Get segments of current url
	 *
	 * @return array
	 */
	public function getSegments()
	{
		return $this->segments;
	}
	
	/**
	 * Count the amount of segments in the url
	 *
	 * @return int
	 */
	public function getSegmentCount()
	{
		return count($this->segments);
	}
}
