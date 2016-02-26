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
 * Test router class
 */
class RouteTest extends BaseTest
{
	/**
	 * Test InvalidArgumentException without arguments
	 */
	public function testInvalidArgumentExceptionWithoutArguments()
	{
		$this->setExpectedException('\Szenis\Exceptions\InvalidArgumentException');
		$route = new \Szenis\Route();
	}

	/**
	 * Test InvalidArgumentException without one argument
	 */
	public function testInvalidArgumentExceptionWith1Argument()
	{
		$this->setExpectedException('\Szenis\Exceptions\InvalidArgumentException');
		$route = new \Szenis\Route('/');
	}

	/**
	 * Test InvalidArgumentException with 2 arguments
	 */
	public function testInvalidArgumentExceptionWith2Arguments()
	{
		$this->setExpectedException('\Szenis\Exceptions\InvalidArgumentException');
		$route = new \Szenis\Route('/', 'GET');
	}

	/**
	 * Test InvalidArgumentException with wrong method
	 */
	public function testInvalidArgumentExceptionWithWrongMethod()
	{
		$this->setExpectedException('\Szenis\Exceptions\InvalidArgumentException');
		$route = new \Szenis\Route('/', 'GET', function(){});
	}

	/**
	 * Test InvalidArgumentException with wrong action
	 */
	public function testInvalidArgumentExceptionWithWrongAction()
	{
		$this->setExpectedException('\Szenis\Exceptions\InvalidArgumentException');
		$route = new \Szenis\Route('/', ['GET'], '');
	}

	/**
	 * Test the set and get methods of the route class
	 */
	public function testSetRouteMethods()
	{
		$route = new \Szenis\Route('/', ['GET'], function(){});

		$route->setMethod(['POST']);
		$route->setUrl('/test');
		$route->setAction('\App\Controllers\IndexController::index');

		$this->assertEquals($route->getMethod(), ['POST']);
		$this->assertEquals($route->getUrl(), '/test');
		$this->assertEquals($route->getAction(), '\App\Controllers\IndexController::index');
	}
}
