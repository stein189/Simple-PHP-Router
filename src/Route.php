<?php

/*
 * This file is part of the Simple-PHP-Router package.
 *
 * (c) Stein Janssen <birdmage@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Szenis\Routing;

use Szenis\Routing\Exceptions\InvalidArgumentException;

/**
 * Simple route object
 */
class Route
{
	const STATUS_FOUND = 200;
	const STATUS_NOT_FOUND = 404;

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
	 * @var string|Closure
	 */
	private $action;

	/**
	 * Contains the arguments of the current route
	 *
	 * @var array
	 */
	private $arguments = [];

	/**
	 * Route constructor
	 *
	 * @param string         $url
	 * @param array          $method
	 * @param string|closure $action
	 */
	public function __construct($url = null, $arguments = null, $method = null, $action = null)
	{
		$this->setUrl($url);
		$this->setMethod($method);
		$this->setAction($action);
		$this->arguments = $arguments;
	}

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
	 *
	 * @return $this
	 */
	public function setMethod($method)
	{
		if ($method === null || !is_array($method) || empty($method)) {
			throw new InvalidArgumentException('No method provided');
		}

		foreach ($method as $m) {
			if (!in_array($m, array('GET','POST','PUT','PATCH','DELETE'))) {
				throw new InvalidArgumentException('Method not allowed. allowed methods: GET, POST, PUT, PATCH, DELETE');
			}
		}

		$this->method = $method;

		return $this;
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
	 *
	 * @return $this
	 */
	public function setUrl($url)
	{
		if ($url === null) {
			throw new InvalidArgumentException('No url provided, for root use /');
		}

		$this->url = $url;

		return $this;
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
	 *
	 * @return $this
	 */
	public function setAction($action)
	{
		if (!(is_object($action) && ($action instanceof \Closure)) && ($action === null || $action === '')) {
			throw new InvalidArgumentException('Action should be a Closure or a path to a function');
		}

		$this->action = $action;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getArguments()
	{
		return $this->arguments;
	}

	/**
	 * @param array $arguments
	 *
	 * @return $this
	 */
	public function setArguments(array $arguments)
	{
		$this->arguments = $arguments;

		return $this;
	}
}
