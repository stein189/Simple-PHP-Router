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
		$this->router = new \Szenis\Routing\Router();
	}

	/**
	 * Test the get method
	 */
	public function testGet()
	{
		$this->router->get('/get/route', function(){});

		$routes = $this->router->getRoutesByMethod('GET');

		$this->assertEquals(1, count($routes));
	}

	/**
	 * Test the post method
	 */
	public function testPost()
	{
		$this->router->post('/post/route', function(){});

		$routes = $this->router->getRoutesByMethod('POST');

		$this->assertEquals(1, count($routes));
	}

	/**
	 * Test the put method
	 */
	public function testPut()
	{
		$this->router->put('/put/route', function(){});

		$routes = $this->router->getRoutesByMethod('PUT');

		$this->assertEquals(1, count($routes));
	}

	/**
	 * Test the patch method
	 */
	public function testPatch()
	{
		$this->router->patch('/patch/route', function(){});

		$routes = $this->router->getRoutesByMethod('PATCH');

		$this->assertEquals(1, count($routes));
	}

	/**
	 * Test the delete method
	 */
	public function testDelete()
	{
		$this->router->delete('/delete/route', function(){});

		$routes = $this->router->getRoutesByMethod('DELETE');

		$this->assertEquals(1, count($routes));
	}

	/**
	 * Test the any method
	 */
	public function testAny()
	{
		$this->router->any('/any/route', function(){});

		$getRoutes = $this->router->getRoutesByMethod('GET');
		$putRoutes = $this->router->getRoutesByMethod('PUT');

		$this->assertEquals(1, count($getRoutes));
		$this->assertEquals(1, count($putRoutes));
	}

	/**
	 * Test getAll method and expects 2 results
	 */
	public function testGetAll()
	{
		$this->router->add('/', 'GET', function(){});
		$this->router->add('/test', 'POST', function(){});

		$routes = $this->router->getAllRoutes();

		$this->assertEquals(2, count($routes));
	}

	/**
	 * Test getByMethod action and except one result
	 */
	public function testGetByMethod()
	{
		$this->router->add('/', 'GET', function(){});
		$this->router->add('/test', 'POST', function(){});

		$routes = $this->router->getRoutesByMethod('GET');

		$this->assertEquals(1, count($routes));
	}

	/**
	 * Test the getByMethod action - expect 0 results
	 */
	public function testGetByMethodWithoutResults()
	{
		$this->router->add('/', 'GET', function(){});

		$routes = $this->router->getRoutesByMethod('POST');

		$this->assertEquals(0, count($routes));
	}
}
