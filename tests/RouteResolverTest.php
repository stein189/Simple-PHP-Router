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
	 * @var \Szenis\RouteResolver
	 */
	private $resolver;

	/**
	 * Set up the test case
	 */
	public function setUp()
	{
		// initialize router
		$router = new \Szenis\Router(new \Szenis\RouteFactory());

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

		$router->add('/call/controller', 'GET', 'RouterTests\TestController::index');

		// initialize resolver
		$this->resolver = new \Szenis\RouteResolver($router);
	}

	/**
	 * Test simple route
	 */
	public function testBasicResolve()
	{
		$request = array(
			'uri' => '/test',
			'method' => 'GET',
		);

		$response = $this->resolver->resolve($request);

		$this->assertEquals('well done', $response);
	}

	/**
	 * Test route with a argument
	 */
	public function testResolveWithNormalArgument()
	{
		$request = array(
			'uri' => '/hello/mike',
			'method' => 'POST',
		);

		$response = $this->resolver->resolve($request);

		$this->assertEquals('mike', $response);
	}

	/**
	 * Test route with special argument (numeric only)
	 */
	public function testResolveWithSpecialArgument()
	{
		$request = array(
			'uri' => '/bye/97722',
			'method' => 'GET',
		);

		$response = $this->resolver->resolve($request);

		$this->assertEquals('97722', $response);
	}
	
	/**
	 * Test route not found exception
	 */
	public function testRouteNotFoundException()
	{
		$request = array(
			'uri' => '/bye/mike',
			'method' => 'GET',
		);

		$this->setExpectedException('\Szenis\Exceptions\RouteNotFoundException');
		$response = $this->resolver->resolve($request);
	}

	/**
	 * Test route with callable path
	 */
	public function testResolveWithClassPath()
	{
		$request = array(
			'uri' => '/call/controller',
			'method' => 'GET',
		);

		$response = $this->resolver->resolve($request);

		$this->assertEquals('index called', $response);
	}
}
