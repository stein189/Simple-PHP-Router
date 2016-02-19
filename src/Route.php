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

use Szenis\Interfaces\RouteInterface;

/**
 * Simple route object
 */
class Route implements RouteInterface
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
	 * Action that the route will use when called
	 *
	 * @var string|function
	 */
	private $action;

	/**
	 * Contains the index number of the url arguments
	 *
	 * @var array
	 */
	private $argumentIndexes;
	
	/**
	 * Get the request method of the current route
	 *
	 * @return array
	 */
	public function getMethod()
	{
		return $this->method;
	}

	/**
	 * Set the method of the current route
	 *
	 * @param array $method
	 */
	public function setMethod(array $method)
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
	 * Get the action of the current route
	 *
	 * @return string|function
	 */
	public function getAction()
	{
		return $this->action;
	}

	/**
	 * Set action 
	 * 
	 * @param string|function $action
	 */
	public function setAction($action)
	{
		$this->action = $action;
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
	public function setArgumentIndexes(array $indexes)
	{
		$this->argumentIndexes = $indexes;
	}
}
