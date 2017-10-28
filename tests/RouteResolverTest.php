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

		$router->add('/normal/{person}', 'GET', function($person) {
			return $person;
		});

		$router->add('/nummeric/{n:number}', 'GET', function($number) {
			return $number;
		});

		$router->add('/alpha-numeric/{an:alphanumeric}', 'GET', function($alphaNumeric) {
			return $alphaNumeric;
		});

		$router->add('/alpha/{a:alpha}', 'GET', function($alpha) {
			return $alpha;
		});

		$router->add('/word/{w:word}', 'GET', function($word) {
			return $word;
		});

		$router->add('/all/{*:all}', 'GET', function($all) {
			return $all;
		});

		$router->add('/optional/{a:alfa}{?:optional}', 'GET', function($alfa, $optional = null) {
			return $alfa;
		});

		$router->add('/optional-slug/{?:optional}', 'GET', function($optional = null) {
			return $optional;
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
	 * Test simple route with wrong method
	 */
	public function testBasicResolveWithWrongMethodType()
	{
		$response = $this->router->resolve('/test', 'POST');

		$this->assertEquals(\Szenis\Routing\Route::STATUS_NOT_FOUND, $response['code']);
	}

	/**
	 * Test route with a argument
	 */
	public function testResolveWithNormalArgument()
	{
		$response = $this->router->resolve('/normal/test-123', 'GET');

		$this->assertEquals(\Szenis\Routing\Route::STATUS_FOUND, $response['code']);
		$this->assertEquals('test-123', $response['arguments']['person']);
	}

	/**
	 * Test route with special argument (numeric only)
	 *
	 * /nummeric/{n:number} should match /nummeric/12345 but not /nummeric/test
	 */
	public function testResolveWithNumericArgument()
	{
		$response = $this->router->resolve('/nummeric/12345', 'GET');

		$this->assertEquals(\Szenis\Routing\Route::STATUS_FOUND, $response['code']);
		$this->assertEquals('12345', $response['arguments']['number']);

		$response = $this->router->resolve('/nummeric/test', 'GET');

		$this->assertEquals(\Szenis\Routing\Route::STATUS_NOT_FOUND, $response['code']);
	}
	
	/**
	 * Test route with alpha numeric parameter
	 * 
	 * /alpha-numeric/{an:alphanumeric} should match /alpha-numeric/test12345 but not /alpha-numeric/test-12345
	 */
	public function testResolveWithAlphaNumericArgument()
	{
		$response = $this->router->resolve('/alpha-numeric/test12345', 'GET');

		$this->assertEquals(\Szenis\Routing\Route::STATUS_FOUND, $response['code']);
		$this->assertEquals('test12345', $response['arguments']['alphanumeric']);

		$response = $this->router->resolve('/alpha-numeric/test-12345', 'GET');

		$this->assertEquals(\Szenis\Routing\Route::STATUS_NOT_FOUND, $response['code']);
	}

	/**
	 * Test route with alpha parameter
	 * 
	 * /alpha/{a:alpha} should match /alpha/test but not /alpha/12345
	 */
	public function testResolveWithAlphaArgument()
	{
		$response = $this->router->resolve('/alpha/test', 'GET');

		$this->assertEquals(\Szenis\Routing\Route::STATUS_FOUND, $response['code']);
		$this->assertEquals('test', $response['arguments']['alpha']);

		$response = $this->router->resolve('/alpha/12345', 'GET');

		$this->assertEquals(\Szenis\Routing\Route::STATUS_NOT_FOUND, $response['code']);
	}

	/**
	 * Test route with word parameter
	 *
	 * /word/{w:word} should match /word/this-is-a-word-12345 but not /word/special:char
	 */
	public function testResolveWithWord()
	{
		$response = $this->router->resolve('/word/this-is-a-word-12345', 'GET');

		$this->assertEquals(\Szenis\Routing\Route::STATUS_FOUND, $response['code']);
		$this->assertEquals('this-is-a-word-12345', $response['arguments']['word']);

		$response = $this->router->resolve('/word/special:char', 'GET');

		$this->assertEquals(\Szenis\Routing\Route::STATUS_NOT_FOUND, $response['code']);
	}

	/**
	 * Test route with 'all' parameter
	 *
	 * /all/{*:all} should match anything after the /all so /all/test/123/slug-123/special:char should work
	 */
	public function testResolveWithAll()
	{
		$response = $this->router->resolve('/all/test/123/slug-123/special:char', 'GET');

		$this->assertEquals(\Szenis\Routing\Route::STATUS_FOUND, $response['code']);
		$this->assertEquals('test/123/slug-123/special:char', $response['arguments']['all']);
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

	/**
	 * Test if a route can have a normal and optional argument in the same part of the slug
	 *
	 * /{a:alfa}{?:optional} should match things like /hello123 where 123 would be the optional part.
	 */
	public function testResolveWithAlfaAndOptional()
	{
		$response = $this->router->resolve('/optional/hello123', 'GET');

		$this->assertEquals(\Szenis\Routing\Route::STATUS_FOUND, $response['code']);
		$this->assertEquals('hello', $response['arguments']['alfa']);
		$this->assertEquals(123, $response['arguments']['optional']);

		$response = $this->router->resolve('/optional/hello', 'GET');

		$this->assertEquals(\Szenis\Routing\Route::STATUS_FOUND, $response['code']);
		$this->assertEquals('hello', $response['arguments']['alfa']);
		$this->assertEquals(null, $response['arguments']['optional']);
	}

	/**
	 * Test if an optional parameter also works when divided by a slash.
	 *
	 * /optional-slug/{?:optional} should match both /optional-slug and /optional-slug/test
	 */
	public function testResolveWithOptional()
	{
		$response = $this->router->resolve('/optional-slug', 'GET');

		$this->assertEquals(\Szenis\Routing\Route::STATUS_FOUND, $response['code']);
		$this->assertEquals(null, $response['arguments']['optional']);

		$response = $this->router->resolve('/optional-slug/test', 'GET');

		$this->assertEquals(\Szenis\Routing\Route::STATUS_FOUND, $response['code']);
		$this->assertEquals('test', $response['arguments']['optional']);
	}
}
