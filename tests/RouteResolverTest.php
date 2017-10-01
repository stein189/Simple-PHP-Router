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
 * RouteResolverTest
 */
class RouteResolverTest extends BaseTest
{
	/**
	 * Route resolver
	 *
	 * @var \Szenis\Routing\Rou
	 */
	private $router;

	/**
	 * Set up the test case
	 */
	public function setUp()
	{
		// initialize router
		$router = new \Szenis\Routing\Router();

		// add some routes
		$router->add('/test', 'GET', function(){
			return 'well done';
		});

		$router->add('/hello/{person}', 'POST', function($person){
			return $person;
		});

		$router->add('/bye/{n:number}', 'GET', function($number){
			return $number;
		});

		$router->add('/call/controller', 'GET', 'TestController::index');

		$this->router = $router;
	}

	/**
	 * Test simple route
	 */
	public function testBasicResolve()
	{
		$response = $this->router->resolve('/test', 'GET');

		$this->assertEquals(\Szenis\Routing\Route::STATUS_FOUND, $response['code']);
		$this->assertInstanceOf('Closure', $response['handler']);
	}

	/**
	 * Test route with a argument
	 */
	public function testResolveWithNormalArgument()
	{
		$response = $this->router->resolve('/hello/mike', 'POST');

		$this->assertEquals(\Szenis\Routing\Route::STATUS_FOUND, $response['code']);
		$this->assertEquals('mike', $response['arguments']['person']);
	}

	/**
	 * Test route with special argument (numeric only)
	 */
	public function testResolveWithSpecialArgument()
	{
		$response = $this->router->resolve('/bye/97722', 'GET');

		$this->assertEquals(\Szenis\Routing\Route::STATUS_FOUND, $response['code']);
		$this->assertEquals('97722', $response['arguments']['number']);
	}
	
	/**
	 * Test route not found exception
	 */
	public function testRouteNotFoundException()
	{
		$response = $this->router->resolve('/bye/mike', 'GET');

		$this->assertEquals(\Szenis\Routing\Route::STATUS_NOT_FOUND, $response['code']);
	}

	/**
	 * Test route with callable path
	 */
	public function testResolveWithClassPath()
	{
		$response = $this->router->resolve('/call/controller', 'GET');

		$this->assertEquals(\Szenis\Routing\Route::STATUS_FOUND, $response['code']);
		$this->assertEquals('TestController::index', $response['handler']);
	}
}
