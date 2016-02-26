<?php

/*
 * This file is part of the Simple-PHP-Router package.
 *
 * (c) Stein Janssen <birdmage@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RouterTests;

/**
 * RouterTest
 */
class RouterTest extends BaseTest
{
	/**
	 * Router
	 *
	 * @var \Szenis\Router
	 */
	private $router;

	/**
	 * SetUp the router
	 */
	public function setUp()
	{
		$this->router = new \Szenis\Router(new \Szenis\RouteFactory());
	}

	/**
	 * Test getAll method and expects 2 results
	 */
	public function testGetAll()
	{
		$this->router->add('/', 'GET', function(){});
		$this->router->add('/test', 'POST', function(){});

		$routes = $this->router->getAll();

		$this->assertEquals(2, count($routes));
	}

	/**
	 * Test getByMethod action and except one result
	 */
	public function testGetByMethod()
	{
		$this->router->add('/', 'GET', function(){});
		$this->router->add('/test', 'POST', function(){});

		$routes = $this->router->getByMethod('GET');

		$this->assertEquals(1, count($routes));
	}

	/**
	 * Test the getByMethod action - expect 0 results
	 */
	public function testGetByMethodWithoutResults()
	{
		$this->router->add('/', 'GET', function(){});

		$routes = $this->router->getByMethod('POST');

		$this->assertEquals(0, count($routes));
	}
}
